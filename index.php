<?php get_header(); ?>

<main role="main" class="content_wrapper">
	<div class="container bbf">
		<?php live_chat_box(); ?>
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-8 col-lg-8 page_left_content">
				<div class="content" id="content">

					<div class="post_entry web_box">
						<div class="dark_box_header">
							<h3>Welcome</h3>
						</div>
						<div class="web_boxp">
							<?php
							$home_page_content = get_option('bbf_home_page_content');
							if(!$home_page_content){
								$home_page_content = 'Welcome to the WoW ByteBunch community, we hope you enjoy your time here and we wish you a happy experience with us.
								<br /><br />
								-Clan ByteBunch community-';
							}
							$content = apply_filters( 'the_content', $home_page_content );
							$content = str_replace( ']]>', ']]&gt;', $content );
							echo $content;
							//$bbwow = new BBWoW("mop_auth");
							//$bbwow->displayAllUsers();
							//CreateUser();
							?>

						</div>
					</div><!-- post_entry div end here -->


					<div class="post_entry web_box" style="margin-bottom:20px;">
							<div class="dark_box_header">
									<h3>Most Recent Announcements and News</h3>
								</div>
								<?php topics_list('recent_announcements'); ?>
						</div><!-- post_entry div end here -->

						<div class="post_entry web_box" style=" margin-bottom:20px;">
							<div class="dark_box_header">
									<h3>Most Recent Topics</h3>
								</div>
								<?php topics_list('most_recent'); ?>
						</div><!-- post_entry div end here -->

						<div class="post_entry web_box" style="margin-bottom:20px;">
							<div class="dark_box_header">
									<h3>Most Popular Topics</h3>
								</div>
								<?php topics_list('most_popular'); ?>
						</div><!-- post_entry div end here -->



				</div><!-- content div end here-->
			</div><!-- col 8 div end here-->

			<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4"><?php get_sidebar(); ?></div>

		</div><!-- row div end here-->
	</div><!-- container div end here-->
</main>
<?php get_footer(); ?>
