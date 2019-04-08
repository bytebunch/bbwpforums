<?php
	global $current_user, $BBFThemeOptions, $unread_messages;
?>
<!doctype html>
<html <?php language_attributes(); ?> class="no-js">
	<head>
		<meta charset="<?php bloginfo('charset'); ?>">

		<link href="<?php echo THEME_URI; ?>images/favicon/favicon.ico" rel="shortcut icon">
		<link href="<?php echo THEME_URI; ?>images/favicon/apple-icon-144x144.png" rel="apple-touch-icon-precomposed">

		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="<?php bloginfo('description'); ?>">

		<?php wp_head(); ?>
		<script>
        // conditionizr.com
        // configure environment tests
        /*conditionizr.config({
            assets: '<?php echo get_template_directory_uri(); ?>',
            tests: {}
        });*/
    </script>

		<script type="text/javascript">
		var THEME_URI = '<?php echo get_template_directory_uri(); ?>';
		var ajax_url = "<?php echo admin_url('admin-ajax.php'); ?>";
		var nodejs_live_chat = 0;
		<?php if($BBFThemeOptions->get_bbf_theme_option('nodejs_live_chat') == 1){ ?>
			nodejs_live_chat = 1;
		<?php } ?>
		<?php if($BBFThemeOptions->get_bbf_theme_option('page_users_id')){ ?>
			page_users_url = '<?php echo get_permalink($BBFThemeOptions->get_bbf_theme_option('page_users_id')); ?>';
		<?php } ?>
		</script>


	</head>
	<body <?php body_class(); ?>>

		<!-- header -->
		<header class="header clear" role="banner">
			<nav class="navbar navbar-inverse" role="navigation">
				<div class="container">
					<div class="row">
						<div class="col-xs-6 col-sm-6 col-md-6 col-lg-4 brand-header">
							<a href="<?php echo HOME_URL; ?>">
								<?php
								$logo_url = get_option('bbf_theme_logo');
								if(!$logo_url)
									$logo_url = THEME_URI.'images/logo.png';
								?>
								<img src="<?php echo $logo_url; ?>" alt="<?php echo get_bloginfo('site_title'); ?>" title="<?php echo get_bloginfo('site_title'); ?>">
							</a>
						</div><!-- col div end here-->
						<div class="col-xs-6 hidden-sm">
							<div class="navbar-header">
								<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".header_main_menu">
									<span class="sr-only">Toggle Navigation</span>
									<span class="icon-bar"></span>
									<span class="icon-bar"></span>
									<span class="icon-bar"></span>
								</button>
							</div><!-- navbar-header div end here-->
						</div>
						<div class="clearboth hidden-sm hidden-md hidden-lg"></div>
						<div class="pull-right main_menu">
							<div class="navbar-collapse collapse header_main_menu">
								<ul class="nav navbar-nav">

									<?php /* <li class="nav_home dropdown <?php if (is_home()) { echo 'active'; } ?>"><a href="#" data-toggle="dropdown">Dropdown <b style="margin-left:10px;" class="caret"></b></a>
										<ul class="dropdown-menu">
											<li><a href="#">home dropdown list</a></li>
											<li><a href="#">home dropdown list 2</a></li>
										</ul>
									</li> */ ?>

									<li class="nav_home <?php if (is_home()) { echo 'active'; } ?>"><a href="<?php echo HOME_URL; ?>">Home</a></li>
									<li class="nav_formms <?php if(is_bbf() && !is_page($BBFThemeOptions->get_bbf_theme_option('page_users_id'))){ echo 'active';} ?>"><a href="<?php echo get_post_type_archive_link( FORUM_PT ); ?>">Board Index</a></li>
									<?php /*<li class="nav_contact <?php if (is_page('contact-us')) {	echo 'active'; } ?>"><a href="<?php echo get_permalink($BBFThemeOptions->get_bbf_theme_option('page_contact_us_id')); ?>">Contact</a></li> */ ?>
									<?php  if (is_user_logged_in()) { ?>
										<li class="nav_login <?php if(is_page($BBFThemeOptions->get_bbf_theme_option('page_users_id'))){ echo 'active';} ?>"><a href="<?php if($unread_messages && $unread_messages > 0) { echo USERS_URI.$current_user->data->ID.'/inbox/'; }else { echo USERS_URI.$current_user->data->ID; } ?>">Hi! <?php echo substr($current_user->data->user_login,0,15); if($unread_messages && $unread_messages > 0){ echo ' ('.$unread_messages.')'; } ?></a></li>
				            <li class="nav_logout"><a href="<?php echo wp_logout_url(HOME_URL); ?>">Log Out</a></li>
									<?php } else { ?>
										<li class="nav_login"><a href="<?php echo get_permalink($BBFThemeOptions->get_bbf_theme_option('page_login_id')); ?>">Log in</a></li>
										<li class="nav_register"><a href="<?php echo get_permalink($BBFThemeOptions->get_bbf_theme_option('page_register_id')); ?>">Register</a></li>
									<?php }  ?>
								</ul>
							</div>
						</div><!-- col div end here-->
					</div><!-- row div end here-->
				</div><!--container div end here-->
			</nav>
		</header>
