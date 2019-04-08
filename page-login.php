<?php
session_start();
/**
*Template Name: Login
*/

redirect_logged_in_users();
$errorMessage = false;
$updateMessage = false;
global $BBFThemeOptions;


if(isset($_GET['mode']) && $_GET['mode'] != "")
{
	$mode_value = $_GET['mode'];
	if($mode_value == 1){
		$errorMessage = "You need to login in order to reply to topics within this forum.";
	}else if($mode_value == 2){
		$errorMessage = "You need to login in order to quote posts within this forum.";
	}else if ($mode_value == 'new') {
		$updateMessage = true;
		$errorMessage = "Your account has been created successfully, And a verification email has been sent to your email address. Before you can login to your account you need to verify your email address. Visit your inbox and follow the instructions to verify your email address. After that you can login to your account from this page.";
	}
}


if(isset($_GET['id']) && is_numeric($_GET['id']) && $_GET['id'] >= 1 && isset($_GET['vkey'])){
	$user = get_user_by("id", $_GET['id']);
	if($user){
		$verify_key = $_GET['vkey'];
		$db_key = get_user_meta($user->ID, 'bb_verify_email', true);
		if($db_key && $verify_key === md5($db_key)){
			update_user_meta($user->ID, 'bb_verify_email', "verified");
			$errorMessage = 'Thanks, Your email address has been verified. Now you can login to your account.';
			$updateMessage = true;
		}
		else
			$errorMessage = 'This url has been expired.';
	}
}

if(isset($_SESSION['resend_email']) && isset($_GET['verify']) && $_GET['verify'] === "resend"){
		$db_key = get_user_meta($_SESSION['resend_email'][0], 'bb_verify_email', true);
		if($db_key && $db_key != "verified"){
			$verify_key = generate_random_int(12);
			$verify_url = get_permalink().'?id='.$_SESSION['resend_email'][0].'&vkey='.md5($verify_key);
			update_user_meta($_SESSION['resend_email'][0], 'bb_verify_email', $verify_key);

			$message = 'Hi, <br />Recently you requested for account verification email on <a href="'.$verify_url.'">'.get_bloginfo('name').'</a>. Please click on below link to verify your eamil address.';
			$message .= '<br /><br /><a href="'.$verify_url.'">'.$verify_url.'</a><br /><br />';
			$message .= 'If you could not click on this link you can also copy and paste the below url in your web browser. <br />'.$verify_url;

			send_email($_SESSION['resend_email'][1], 'Verify Email', $message);
			$errorMessage = "New verification email has been sent to your email address. Visit your inbox and follow the instructions to verify your email address.";
			$updateMessage = true;
			unset($_SESSION['resend_email']);
		}
}

if(isset($_POST['username']))
{
	$username = $_POST['username'];
	$password = $_POST['password'];
	$rememberme = true;

	if(isset($_POST['rememberme'])){
		$rememberme = true;
	}
	$creds = array();
	$creds['user_login'] = $username;
	$creds['user_password'] = $password;
	$creds['remember'] = $rememberme;
	$user = wp_signon( $creds, false );
	if (is_wp_error($user))
	{
		if(isset($user->errors['verify_email'])){
			$user = $user->get_error_data();
			$_SESSION['resend_email'] = array($user->ID, $user->data->user_email);
			$verify_url = get_permalink()."?verify=resend";
			$errorMessage = "Your email address is not verified yet. When you created your account we sent a verification email to your registered email address. First you need to verify your email then you can login to your account. If you didn't received any email <a href='".$verify_url."'>click here </a>and we will send you new verification email.";
		}
		else
			$errorMessage = "There was some problem with your username or password, please try agian.";
	}
	else
	{
		header("Location: ".HOME_URL);
		exit();
	}

}
get_header(); ?>

<main role="main" class="content_wrapper">
	<div class="container">
		<div class="content">
			<!-- alert messaaaaage start from here -->
			<?php ErrorUpdateMessage($errorMessage, $updateMessage); ?>
			<!-- alert messaaaaage end here -->
					<?php if (have_posts()): while (have_posts()) : the_post(); ?>

							<div class="web_boxp headding_title">
									<h2 class="center"><?php the_title(); ?></h2>
							</div>
							<div class="web_box" style="padding:20px 0; margin-bottom:20px;">
							<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
								<form action="<?php the_permalink(); ?>" class="jquery_validated_form" method="post">
									<p>
											<label for="username">Username:</label>
											<input type="text" name="username" id="username" class="required" required="required" />
									</p>
									<p>
											<label for="password">Password:</label>
											<input type="password" name="password" id="password" class="required" required="required" />
									</p>
									<p><input type="checkbox" name="remember" id="remember_me" checked="checked" /> <label for="remember_me">Log me on automatically each visit</label></p>
									<p><a href="<?php echo get_permalink($BBFThemeOptions->get_option('page_forgot_password_id')); ?>">Forgot your password?</a></p>
									<p><input type="submit" value="Log in" class="small-btn-Ube <?php /*btn-Ube */ ?>" /></p>
								</form>
							</div>

								<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
										<h2>New User</h2>
										<p>In order to login you must be registered. Registering takes only a few moments but gives you increased capabilities. The board administrator may also grant additional permissions to registered users. Before you register please ensure you are familiar with our terms of use and related policies. Please ensure you read any forum rules as you navigate around the board.</p>
										<p><a href="<?php echo get_permalink($BBFThemeOptions->get_option('page_terms_of_use_id')); ?>">Terms of use</a> | <a href="<?php echo get_permalink($BBFThemeOptions->get_option('page_privacy_policy_id')); ?>">Privacy policy</a></p>
										<p><a class="orange_btn" href="<?php echo get_permalink($BBFThemeOptions->get_option('page_register_id')); ?>">Create My Account</a></p>
								</div>
								<div class="clearboth"></div>
							</div>
					<?php endwhile; ?>
					<?php else: ?>
						<article>
							<h2><?php _e( 'Sorry, nothing to display.', 'bbblank' ); ?></h2>
						</article>
					<?php endif; ?>

		</div><!-- row div end here-->
	</div><!-- container div end here-->
</main>

<?php get_footer(); ?>
