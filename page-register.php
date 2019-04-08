<?php
session_start();
/**
 * Template Name: Register
 */
redirect_logged_in_users();
global $BBFThemeOptions;
$errorMessage = false;
$updateMessage = false;
if(isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['cpassword']) && isset($_POST['fname']))
{
	if(!(isset($_POST['captcha']) && isset($_SESSION['secure_contact']) && $_SESSION['secure_contact'] == (int)$_POST['captcha']))
		$errorMessage = 'Captcha code was incorrect, please try again.';

		$username = BBWPSanitization::Username($_POST['username']);
		$email = BBWPSanitization::Email($_POST['email']);
		$password = $_POST['password'];
		$cpassword = $_POST['cpassword'];
		$lname = '';
		$fname = BBWPSanitization::Textfield($_POST['fname']);
		if(isset($_POST['lname']))
			$lname = BBWPSanitization::Textfield($_POST['lname']);
		$ip = BBWPSanitization::Textfield($_SERVER['REMOTE_ADDR']);

		if(strlen($password) < 6 || $password != $cpassword)
			$errorMessage = "Your password must be between 6 characters and 100 characters. Type same password in both fields.";
		if(!$email)
	  		$errorMessage = "Please type your correct email address.";
		if(!$username)
	  		$errorMessage = "Only alphanumeric characters plus these: _, space, ., -, *, and @ are allowed in your username.";
		if(isset($_POST['lname']) && $_POST['lname'] != "" && (strlen($lname) > 20 || strlen($lname) < 3)){
			$errorMessage = "Your last name must be between 3 characters and 20 characters."; }
		if (!ctype_alnum ($fname) || strlen($fname) < 3 || strlen($fname) > 20)
			$errorMessage = "Your first name must be between 3 characters and 20 characters. You can use only alphabet and numeric characters.";
		if ( email_exists($email) || username_exists($email) )
				$errorMessage = "That email is already registered to a user.";
		if ( email_exists($username) || username_exists($username) )
				$errorMessage = "That username is already registered to a user.";

		if($errorMessage == false)
		{
			$rdate = date('Y-m-d H:i:s', current_time( 'timestamp'));
			$userdata = array("ID" => '', "first_name" => $fname, "last_name" => $lname, "user_login" => $username, "user_email" => $email, "user_pass" => $password, "display_name" => $username, "user_registered" => $rdate, "nickname" => $fname, 'user_url' => $ip);
			$userID = wp_insert_user( $userdata );

			/* add new user to wow datbase */
			if(class_exists("BBWoW")){
				$bbwow = new BBWoW();
				$bbwow->UpdateUser($username, $password, $email);
				unset($bbwow);
				$bbwow = new BBWoW("mop_auth");
				$bbwow->UpdateUser($username, $password, $email);
				unset($bbwow);
			}

			if($BBFThemeOptions->get_option('verify_email') == 'yes'){
				$verify_key = generate_random_int(12);
				$verify_url = get_permalink($BBFThemeOptions->get_option('page_login_id')).'?id='.$userID.'&vkey='.md5($verify_key);
				update_user_meta($userID, 'bb_verify_email', $verify_key);

				$message = 'Hi, <br />Recently you created your new account on <a href="'.HOME_URL.'">'.get_bloginfo('name').'</a>, and before you can login to your account you need to verify your email address. Please click on below link to verify your eamil address.';
				$message .= '<br /><br /><a href="'.$verify_url.'">'.$verify_url.'</a><br /><br />';
				$message .= 'If you could not click on this link you can also copy and paste the below url in your web browser. <br />'.$verify_url;

				send_email($email, 'Verify Email', $message);
				$errorMessage = "Your account has been created successfully, And a verification email has been sent to your email address. Before you can login to your account you need to verify your email address. Visit your inbox and follow the instructions to verify your email address. If you won't be able to find this email in your inbox try your Spam/Junk folders.";
				$updateMessage = true;
				//$errorMessage .= $verify_url;
				/*wp_redirect(get_permalink($BBFThemeOptions->get_option('page_login_id')).'?mode=new');
				exit();*/
			}else{
				wp_set_auth_cookie($userID);
				wp_redirect(HOME_URL);
				exit();
			}
			unset($email); unset($password); unset($cpassword); unset($fname); unset($fname); unset($username);
		}
}
get_header(); ?>

<main role="main" class="content_wrapper">
	<div class="container page_register_container">

			<!-- alert messaaaaage start from here -->
			<?php ErrorUpdateMessage($errorMessage, $updateMessage); ?>
			<!-- alert messaaaaage end here -->
			<?php if (have_posts()): while (have_posts()) : the_post(); ?>

				<div class="web_boxp headding_title">
					<h2 class="center"><?php the_title(); ?></h2>
				</div>
				<div class="web_boxp">
					<form action="<?php the_permalink(); ?>" class="jquery_validated_form" method="post">
					<div class="row">
						<div class="col-xs-12 col-sm-12 col-md-4">
							<label for="fname"><strong>First Name:</strong><spam class="forum_star">*</spam></label>
							<br />
							<small>Length must be between 3 characters and 20 characters.</small>
						</div>
						<div class="col-xs-12 col-sm-12 col-md-8">
							<input type="text" name="fname" id="fname" class="required" required="required" pattern=".{3,20}" title="3 characters minimum" <?php if(isset($fname)){ echo 'value="'.$fname.'"';} ?> />
						</div>
					</div>
					<div class="row">
						<div class="col-xs-12 col-sm-12 col-md-4">
							<label for="lname"><strong>Last Name:</strong></label>
							<br />
							<small>Length must be between 3 characters and 20 characters.</small>
						</div>
						<div class="col-xs-12 col-sm-12 col-md-8">
							<input type="text" name="lname" id="lname" <?php if(isset($fname)){ echo 'value="'.$lname.'"';} ?> />
						</div>
					</div>
					<div class="row">
						<div class="col-xs-12 col-sm-12 col-md-4">
							<label for="email"><strong>Email address:</strong><spam class="forum_star">*</spam></label>
							<br />
							<small>Use your actual Email address we will send a confirmation message on this email address to activate your account.</small>
						</div>
						<div class="col-xs-12 col-sm-12 col-md-8">
							<input type="email" name="email" id="email" class="required" required="required" <?php if(isset($email)){ echo 'value="'.$email.'"';} ?> />
						</div>
					</div>
					<div class="row">
						<div class="col-xs-12 col-sm-12 col-md-4">
							<label for="username"><strong>Username:</strong><spam class="forum_star">*</spam></label>
							<br />
							<small>Length must be between 3 characters and 20 characters.</small>
						</div>
						<div class="col-xs-12 col-sm-12 col-md-8">
							<input type="text" name="username" id="username" class="required" required="required" <?php if(isset($username)){ echo 'value="'.$username.'"';} ?> />
						</div>
					</div>
					<div class="row">
						<div class="col-xs-12 col-sm-12 col-md-4">
							<label for="password"><strong>Password:</strong><spam class="forum_star">*</spam></label>
							<br />
							<small>Must be between 6 characters and 100 characters.</small>
						</div>
						<div class="col-xs-12 col-sm-12 col-md-8">
							<input type="password" name="password" id="password" class="required" required="required" title="6 characters minimum" pattern=".{6,100}" />
						</div>
					</div>
					<div class="row">
						<div class="col-xs-12 col-sm-12 col-md-4">
							<label for="cpassword"><strong>Confirm password:</strong><spam class="forum_star">*</spam></label>
						</div>
						<div class="col-xs-12 col-sm-12 col-md-8">
							<input type="password" name="cpassword" id="cpassword" class="required" required="required" />
						</div>
					</div>
					<div class="row">
						<div class="col-xs-12 col-sm-12 col-md-4">
							<img src="<?php echo THEME_URI."lib/captcha/contact.php"; ?>" alt="captcha code" style="margin-bottom:5px;border:1px solid #ccc; display:block;">
							<small>Type the digits shown in above image into input field.</small>
						</div>
						<div class="col-xs-12 col-sm-12 col-md-8">
							<input type="text" name="captcha" id="captcha" autocomplete="off" required="required">
						</div>
					</div>
					<p>By creating an account, you confirm that you are agree to our <a href="<?php echo get_permalink($BBFThemeOptions->get_option('page_terms_of_use_id')); ?>">Terms of Use</a>.</p>

				 <p><input type="submit" value="Register" class="small-btn-Ube" /></p>
					</form>
				</div>

			<?php endwhile; ?>
			<?php else: ?>
				<article>
					<h2><?php _e( 'Sorry, nothing to display.', 'bbblank' ); ?></h2>
				</article>
			<?php endif; ?>

	</div><!-- container div end here-->
</main>


<?php get_footer(); ?>
