<?php
session_start();
/**
*Template Name: Contact Us
*/
$errorMessage = false;
$updateMessage = false;
$verify_key = false;

if(isset($_POST['user_email']) && isset($_POST['captcha']) )
{
	if(isset($_SESSION['secure_contact']) && $_SESSION['secure_contact'] == (int)$_POST['captcha']){
		$email = BBWPSanitization::Email($_POST['user_email']);
		if($email){
			$user_in = email_exists($email);
			if($user_in){
				$exptime =  strtotime("now");
				update_user_meta($user_in, "fpwd_time", $exptime);
				$losturl = get_permalink().'?id='.$user_in.'&key='.md5($exptime);
				send_email($email, get_bloginfo('name'), '<p>Please use the below link to recover your password. This link will expire after one hour.</p><br /><br /><a href="'.$losturl.'">'.$losturl.'</a>');
				$errorMessage = "An email has been sent on your email addrress. For further information visit your inbox.";
				$updateMessage = true;
				if(isset($_GET['id']))
					unset($_GET['id']);
			}
			else
				$errorMessage = "No user found with the given E-mail address";
		}
		else
			$errorMessage = "Type your email address correctly.";
	}
	else
		$errorMessage = 'Captcha code was incorrect, please try again.';
}


if(isset($_GET['key']) && isset($_GET['id']) && is_numeric($_GET['id']) && $_GET['id'] >= 1)
{
	global $BBFThemeOptions;
	$user_id = $_GET['id'];
	$key = $_GET['key'];
	$db_key = get_user_meta($_GET['id'], "fpwd_time", true);
	$exptime = $db_key + 13600;

	if(strtotime("now") < $exptime && md5($db_key) === $key)
	{
		$verify_key = true;

		if(isset($_POST['cpassword']) && isset($_POST['password']))
		{
			$password = $_POST['password'];
			$cpassword = $_POST['cpassword'];

			if(strlen($password) >= 6 && $password == $cpassword)
			{
				/* add new user to wow datbase */
				if(class_exists("BBWoW")){
					$user = get_user_by( 'ID', $user_id);
					$bbwow = new BBWoW();
					$bbwow->UpdateUser($user->user_login, $password, $user->user_email);
					unset($bbwow);
					$bbwow = new BBWoW("mop_auth");
					$bbwow->UpdateUser($user->user_login, $password, $user->user_email);
					unset($bbwow);
				}

				wp_set_password( $password, $user_id);
				update_user_meta($user_id, "fpwd_time", "");
				$verify_key = false;
				$errorMessage = 'Your password has been updated successfully. <a href="'.get_permalink($BBFThemeOptions->get_option('page_login_id')).'" style="color:#fff;">click here to login</a>';
				$updateMessage = true;
			}
			else
				$errorMessage = "Your password must be between 6 characters and 100 characters. Type same password in both fields.";
		}
	}
	else
		$errorMessage = "Your link has expired. Please try again.";
}


get_header(); ?>

<main role="main" class="content_wrapper">
	<div class="container">
		<div class="row">
			<!-- alert messaaaaage start from here -->
			<?php ErrorUpdateMessage($errorMessage, $updateMessage); ?>
			<!-- alert messaaaaage end here -->
			<div class="col-xs-12 col-sm-12 col-md-8 col-lg-8 page_left_content">
				<div class="content" id="content">
					<?php if (have_posts()): while (have_posts()) : the_post(); ?>
						<!-- article -->
						<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

							<div class="web_boxp headding_title">
									<h2 class="center"><?php the_title(); ?></h2>
							</div>

							<div class="web_boxp">
								<form action="" class="jquery_validated_form" method="post">
								<?php if($verify_key)
								{?>
									<p>
										<label for="password"><strong>New Password: </strong><spam class="forum_star">*</spam></label><br />
										<input type="password" name="password" id="password" class="required" required="required" title="6 characters minimum" pattern=".{6,100}" />
										<small>Must be between 6 characters and 100 characters.</small>
									</p>
									<p>
										<label for="cpassword"><strong>Confirm new password: </strong><spam class="forum_star">*</spam></label>
										<input type="password" name="cpassword" id="cpassword" class="required" required="required" />
									</p>

								<?php }else{ ?>
										<p>
										<label for="password"><strong>E-mail: </strong><span class="forum_star">*</span></label><br />
										<small>This must be the e-mail address associated with your account. If you have not changed this via your user control panel then it is the e-mail address you registered your account with.</small>
										<input type="email" name="user_email" id="email" class="required" required="required" />
										<p>
										<p>
											<!-- <label for="captcha"><strong>Captcha: <span class="forum_star">*</span></strong></label> -->
											<img src="<?php echo THEME_URI."lib/captcha/contact.php"; ?>" alt="captcha code" style="margin-bottom:10px;border:1px solid #ccc; display:block;">
											<input type="text" name="captcha" id="captcha" autocomplete="off" required="required">
											<!-- <small>Type the digits shown in above image.</small> -->
										</p>
									<?php } ?>

									<p><input type="submit" value="Submit" /></p>
								</form>
								<div class="clearboth"></div>
							</div><!-- web box div end here -->

						<!-- article -->
						<article>
					<?php endwhile; ?>
					<?php else: ?>
						<article>
							<h2><?php _e( 'Sorry, nothing to display.', 'bbblank' ); ?></h2>
						</article>
					<?php endif; ?>
				</div><!-- content div end here-->
			</div><!-- col 8 div end here-->
			<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4"><?php get_sidebar(); ?></div>
		</div><!-- row div end here-->
	</div><!-- container div end here-->
</main>

<?php get_footer(); ?>
