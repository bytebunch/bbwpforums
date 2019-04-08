<?php
session_start();
redirect_not_logged_in_users();
$errorMessage = false;
$updateMessage = false;
global $BBFThemeOptions, $wp_query, $user_profile_data, $current_user;
if(isset($wp_query->query_vars["bbf_user"]) && is_numeric($wp_query->query_vars["bbf_user"]) &&  $wp_query->query_vars["bbf_user"] >= 1){
	$url_user_id = BBWPSanitization::Int($wp_query->query_vars["bbf_user"]);
	if($url_user_id){
		if(isset($current_user->ID) && $url_user_id === $current_user->ID)
			$user_profile_data = $current_user;
		else{
			$user_profile_data = get_user_by("id",$url_user_id);
			if(!$user_profile_data)
				wp_redirect(HOME_URL);
		}
	}
	else
		wp_redirect(HOME_URL);
}
else
	wp_redirect(HOME_URL);

include_once(THEME_ABS.'forums/templates/users-forms-handling.php');
get_header();
?>
<main role="main" class="content_wrapper">
	<div class="container bbf content">

		<!-- alert messaaaaage start from here -->
		<?php ErrorUpdateMessage($errorMessage, $updateMessage); ?>
		<!-- alert messaaaaage end here -->

		<?php if (have_posts()): while (have_posts()) : the_post(); ?>

    <div class="users_dashboard">
  		<div class="dashboard_content_container">
				<div class="row">
      		<div class="col-xs-12 col-sm-3 col-md-3 col-lg-3 left_content">
          	<?php dashboard_profile_menu(); ?>
          </div>
          <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9" style="background-color:#fff;">
						<div class="right_content">
            <?php
						if(isset($wp_query->query_vars["topics"])){ echo '<h2>FORUM TOPICS STARTED</h2>'; topics_list($user_profile_data->ID); }
						else if(isset($wp_query->query_vars["replies"])){ echo '<div class="bbf_replies_container "><h2>FORUM REPLIES CREATED</h2>'; get_users_replies($user_profile_data->ID); echo '</div>'; }
						else if(isset($wp_query->query_vars["inbox"]) && is_user_logged_in() && $current_user->ID === $user_profile_data->ID)
						{
							if(isset($wp_query->query_vars["message"]))
								include_once(FORUMS_ABS.'templates/users-message-view.php');
							else
								include_once(FORUMS_ABS.'templates/users-inbox.php');
						}
						else if(isset($wp_query->query_vars["sent"]) && is_user_logged_in() && $current_user->ID === $user_profile_data->ID)
						{
							if(isset($wp_query->query_vars["message"]))
								include_once(FORUMS_ABS.'templates/users-message-view.php');
							else
								include_once(FORUMS_ABS.'templates/users-sent-messages.php');
						}
						else if(isset($wp_query->query_vars["compose"]) && is_user_logged_in() && $current_user->ID === $user_profile_data->ID)
							include_once(FORUMS_ABS.'templates/users-compose.php');
						else if(isset($wp_query->query_vars["email"]) && $wp_query->query_vars["email"] == 1 && is_user_logged_in() && $current_user->ID === $user_profile_data->ID)
							include_once(FORUMS_ABS.'templates/users-email.php');
						else if(isset($wp_query->query_vars["avatar"]) && is_user_logged_in() && $current_user->ID === $user_profile_data->ID)
							include_once(FORUMS_ABS.'templates/users-profile-image.php');
						else if(isset($wp_query->query_vars["settings"]) && is_user_logged_in() && $current_user->ID == $user_profile_data->ID)
							include_once(FORUMS_ABS.'templates/users-profile-settings.php');
						else if(isset($wp_query->query_vars["signature"]) && is_user_logged_in() && $current_user->ID === $user_profile_data->ID)
						{ ?>
            	<h2>Edit Signature</h2>
							<p>This is a block of text that can be added to posts you make. There is a 255 character limit.</p>
            	<form action="" method="post">
            		<?php //db(get_user_meta($url_user_id,'user_signature',true)); ?>
								<?php ckEditor('edit_signature', esc_textarea(get_user_meta($user_profile_data->ID,'user_signature',true))); ?>
            		<p style="margin:20px 0;"><input type="submit" value="Submit"><p>
							</form> <?php
						}
						else if(isset($wp_query->query_vars["edit"]) && is_user_logged_in() && $current_user->ID === $user_profile_data->ID)
							include_once(FORUMS_ABS.'templates/users-profile-edit.php');
						else{	?>
            	<div class="user_profile_view">
	              <h2>About</h2>
								<?php
									/*if(is_user_logged_in() && $current_user->ID === 1){
										echo '<p><span><a href="http://wow.bytebunch.com/wp-admin/users.php?action=delete&user='.$user_profile_data->ID.'&_wpnonce=f8cbee6a54">Delete this user</a></span></p>';
									}*/
										 ?>
	              <p><span>Display Name:</span> <?php echo $user_profile_data->data->user_login; ?></p>
	              <p><span>First Name:</span> <?php echo get_user_meta($user_profile_data->ID,'first_name',true); ?></p>
	              <p><span>Last Name:</span> <?php echo get_user_meta($user_profile_data->ID,'last_name',true); ?></p>
	              <p><span>Join Date: </span> <?php echo date("d F Y h:i A",strtotime($user_profile_data->data->user_registered)); ?><!--14th October 2014 03:26 AM--></p>
	              <p><span>Location:</span> <?php echo get_user_meta($user_profile_data->ID,'location',true); ?></p>
	              <?php if($fb_id = get_user_meta($user_profile_data->ID,'fb_id',true)){?>
	              	<p><span>Facebook ID:</span> <?php echo $fb_id; ?></p>
	              <?php } ?>
	              <?php if($skype_id = get_user_meta($user_profile_data->ID,'skype_id',true)){?>
	              	<p><span>Skype ID:</span> <?php echo $skype_id; ?></p>
	              <?php } ?>
	              <?php if($ingame_name = get_user_meta($user_profile_data->ID,'ingame_name',true)){?>
	              	<p><span>In-gane Name:</span> <?php echo $ingame_name; ?></p>
	              <?php } ?>
	              <p><span>Gender:</span> <?php echo get_user_meta($user_profile_data->ID,'gender',true); ?></p>

	              <p>&nbsp;</p>
	              <h2>Statistics</h2>
	              <p><span>Total Posts:</span> <?php echo get_user_posts($user_profile_data->ID);  ?> <small><a href="<?php echo USERS_URI.$user_profile_data->ID; ?>/replies/">(View all posts)</a></small></p>

	              <?php $user_topic_count = count_user_posts( $user_profile_data->ID , 'topic' ); ?>
	              <p><span>Total Topics:</span> <?php echo $user_topic_count; ?> <small><a href="<?php echo USERS_URI.$user_profile_data->ID; ?>/topics/">(View all topics)</a></small></p>
	              <!--<p><span>Last Activity:</span> 14th October 2014 03:26 AM</p>
	              <p><span>Most Active Topic:</span> <a href="#">Introduction of CM</a></p>-->

								<?php
								$profile_views = 1;
								if(get_user_meta($user_profile_data->ID,'profile_views',true))
								{
									$profile_views = get_user_meta($user_profile_data->ID,'profile_views',true)+1;
									update_user_meta($user_profile_data->ID,'profile_views',$profile_views);
								}else
								{
									update_user_meta($user_profile_data->ID,'profile_views',$profile_views);
								}
								?>
                <p><span>Profile Views:</span> <?php echo $profile_views; ?></p>

              </div><!-- user_profile_view div end here --><?php
						} ?>
						</div><!-- right content div end here-->
          </div> <!-- col div end here -->
          <div class="clearboth"></div>
				</div><!-- row div end here -->
      </div><!-- dashboard_content_container div end here-->
	</div><!-- users_dashboard div end here-->


		<?php endwhile; ?>
		<?php else: ?>
			<article>
				<h2><?php _e( 'Sorry, nothing to display.', 'bbblank' ); ?></h2>
			</article>
		<?php endif; ?>

		<?php include_once(FORUMS_ABS.'templates/forum-footer.php'); ?>

	</div><!-- container div end here-->
</main><!-- content_wrapper div end here-->

<?php get_footer(); ?>
