<?php

get_header();
?>
<main role="main" class="content_wrapper">
	<div class="container bbf content">
		<?php /*<div class="row"> */ ?>
        <div id="breadcrumbs" class="breadcrumbs">
            <a class="icon_home" href="<?php echo get_post_type_archive_link( FORUM_PT ); ?>">Board index</a>
        </div>

	<?php
	global $wpdb;

	 $sql = "SELECT DISTINCT postst.* FROM ".$wpdb->posts. " as postst INNER JOIN ".$wpdb->bbf_forum_meta." as postmetat ON postst.ID=postmetat.forum_id WHERE postst.post_parent = 0 AND postst.post_type = 'forum' AND postst.post_status = 'publish' AND postmetat.forum_type = 1 order by postst.post_date ASC";
	$results = $wpdb->get_results($sql, ARRAY_A);
	if($results)
	{
		echo '<div class="clearboth"></div><ul class="no_mp bbf_main_categories">';
		foreach($results as $result)
		{
			$main_forum_id = $result['ID'];
		?>
			<li class="bbf_header">
				<div class="bbf_head">
					<div class="col-xs-8 col-sm-8 col-md-6 col-lg-7 bbf_forum_info no_mp">
        		<a href="<?php echo get_permalink($main_forum_id); ?>">
							<?php echo get_the_title($main_forum_id); ?>
            </a>
          </div>
					<div class="col-xs-4 col-sm-2 col-md-1 bbf_forum_topic_count no_mp text-center">Topics</div>
					<div class="col-xs-2 col-sm-2 col-md-1 bbf_forum_reply_count hidden-xs no_mp text-center">Posts</div>
					<div class="col-md-4 col-lg-3 bbf_forum_freshness hidden-sm hidden-xs no_mp text-center">Last post</div>
					<div class="clearboth"></div>
				</div>

					<?php
					wp_reset_query();
					$args = array('post_type' => 'forum', 'post_status' => 'publish', 'post_parent' => $main_forum_id, "posts_per_page" => -1,'order' => 'ASC');
					add_filter( 'posts_clauses', 'single_forum_sub_forum_query');
					query_posts($args);
					remove_filter( 'posts_clauses', 'single_forum_sub_forum_query'/*, 20 */);
					if(have_posts())
					{
						global $wp_query;

						$i = 1;
						echo '<ul class="forum_subforums">';
						while(have_posts())
						{
							the_post();
							//db($post);
							if($i%2 == 0)
							{
								$sub_form_class = 'even';
							}else
							{
								$sub_form_class = 'odd';
							}

							?>
						<li class="bbf_heads_childs <?php echo $sub_form_class; ?>">
							<?php
								$forum_url = get_permalink();
								if(isset($post->external_link) && $post->external_link && $post->external_link != '' && $post->external_link != " "){
									$forum_url = $post->external_link;
								}
							?>
							<div class="col-xs-8 col-sm-8 col-md-6 col-lg-7 bbf_forum_info no_mp">
								<?php
								if(!($fImageURL = get_feature_image_url($post->ID))){
									$fImageURL = THEME_URI.'images/forum_read.png';
								}
								?>
									<a href="<?php echo $forum_url; ?>" class="forums_thumbnails"><img src="<?php echo $fImageURL; ?>" alt="<?php echo get_the_title(); ?>" class="forums_thumbnails" /></a>

								<span class="forum_title">
	              	<a href="<?php echo $forum_url; ?>"><?php echo get_the_title(); ?></a>
	              </span>
								<p><?php echo strip_tags(get_the_content()); ?></p>
								<div class="clearboth"></div>
							</div>
							<div class="col-xs-4 col-sm-2 col-md-1 bbf_forum_topic_count no_mp text-center"><?php echo $post->forum_topics; ?></div>
							<div class="col-xs-2 col-sm-2 col-md-1 bbf_forum_reply_count hidden-xs no_mp text-center"><?php echo $post->forum_posts; ?></div>
							<div class="col-md-4 col-lg-3 bbf_forum_freshness hidden-sm hidden-xs no_mp text-center">
	            	<span class="bbp-topic-freshness-author">
	            		<!--introduction<br />-->
	                <?php if($post->last_poster_name){?>
	                	<a href="<?php echo get_permalink($post->forum_last_topic_id); ?>"><?php echo $post->last_topic_title; ?></a><br />
										<small>By <a href="<?php echo USERS_URI.$post->forum_last_poster_id; ?>"><?php echo $post->last_poster_name; ?><!--Admin--></a><br />
	                		<?php echo human_time_diff(strtotime($post->last_post_time), current_time( 'timestamp')); ?> ago
	                	</small>
									<?php }else{?>
									 	No post found.
									<?php } ?>
								</span>
              </div>
							<div class="clearboth"></div>

						</li>
						<?php
						$i++;
						}
						echo '</ul>';

						wp_reset_query();
					}
					 ?>
			</li>
		<?php }
		echo '</ul>';
	}?>

    <?php include_once(FORUMS_ABS.'templates/forum-footer.php'); ?>

	<?php /*</div><!-- row div end here--> */ ?>
</div><!-- container div end here-->
</main>
<?php get_footer(); ?>
