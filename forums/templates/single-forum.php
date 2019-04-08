<?php
get_header();
global $wp_query;
$forum_status = 0;
?>
<main role="main" class="content_wrapper">
	<div class="container bbf content">

    <?php live_chat_box(); ?>
        <div id="breadcrumbs" class="breadcrumbs">
            <?php echo bbf_breadcrumb(); ?>
        </div>

	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	<h2><?php echo get_the_title(); ?></h2>
	<?php
	/*global $wp_query;
	db($wp_query);*/
	 ?>
		<?php
		global $post;
		$parent_forum_id = $post->ID;
		$forum_meta = new ForumMeta($post->ID);
		if($forum_meta->getMeta('forum_status') == 1){
			$forum_status = 1;
		}

		add_filter( 'posts_clauses', 'single_forum_sub_forum_query');
		$args = array(
			'post_type'         => 'forum',
			'posts_per_page'    => -1,
			'post_parent' => $post->ID,
			'order' => 'ASC'
			//'cache_results' => false,
			//'update_post_meta_cache' => false,
			//'update_post_term_cache' => false
		);
		$my_subForums = new wp_query($args);

		remove_filter( 'posts_clauses', 'single_forum_sub_forum_query'/*, 20 */);
		if($my_subForums->have_posts())
		{?>
			<div class="clearboth"></div>
            <div class="bbf_head">
                <div class="col-xs-8 col-sm-8 col-md-6 col-lg-7 bbf_forum_info no_mp">Sub-Forums</div>
                <div class="col-xs-4 col-sm-2 col-md-1 bbf_forum_topic_count no_mp text-center">Topics</div>
                <div class="col-xs-2 col-sm-2 col-md-1 bbf_forum_reply_count hidden-xs no_mp text-center">Posts</div>
                <div class="col-md-4 col-lg-3 bbf_forum_freshness hidden-sm hidden-xs no_mp text-center">Last post</div>
                <div class="clearboth"></div>
            </div>
			<?php
                $i = 1;
				echo '<ul class="forum_subforums no_mp">';

				while($my_subForums->have_posts())
				{

					$my_subForums->the_post();
					//db($my_subForums);
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
					<span class="forum_title"><a href="<?php echo $forum_url; ?>"><?php echo get_the_title(); ?></a></span>
					<p><?php echo strip_tags(get_the_content()); ?></p>
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
				echo '<li class="bbp_footer"></li></ul>';
		}?>

		<?php

		if(is_user_logged_in() && $forum_status == 0 && $forum_meta->getMeta('forum_type') == 0 /*&& ( $parent_forum_id != 12 || get_user_role() == 'administrator')*/){
			?>
            <p style="margin-top:20px;"><a href="<?php echo get_bloginfo('url'); ?>/topic/new/<?php echo $parent_forum_id; ?>" class="orange_btn">Post New Topic</a></p>
			<?php
		}
		$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
		$args = array(
			'post_type'         => 'topic',
			'posts_per_page'    => 20,
			'post_parent' => $parent_forum_id,
			//'cache_results' => false,
			//'update_post_meta_cache' => false,
			//'update_post_term_cache' => false,
			'paged' => $paged,
			'order' => 'DESC'
		);
		add_filter( 'posts_clauses', 'get_topic_meta_with_query');
		$my_topics = new WP_Query($args);
		remove_filter( 'posts_clauses', 'get_topic_meta_with_query'/*, 20 */);
		//db($my_topics);
		if($my_topics->have_posts())
		{
			$big = 999999999; // need an unlikely integer
			echo '<div class="pagination">';
			echo paginate_links( array(
				'base' => str_replace( 999999999, '%#%', esc_url( get_pagenum_link( 999999999 ) ) ),
				'format' => '?paged=%#%',
				'current' => max( 1, get_query_var('paged') ),
				'total' => $my_topics->max_num_pages
			) );
			?>
            <div class="clearboth"></div>
		</div><!-- pagination div end here-->
        	<div class="clearboth"></div>
            <div class="bbf_head">
                <div class="col-xs-8 col-sm-8 col-md-6 col-lg-7 bbf_forum_info no_mp">Topics</div>
                <div class="col-xs-4 col-sm-2 col-md-1 bbf_forum_topic_count no_mp text-center">Views<?php //bbp_forum_topic_count($result['ID'])	; ?></div>
                <div class="col-xs-2 col-sm-2 col-md-1 bbf_forum_reply_count hidden-xs no_mp text-center">Posts<?php //bbp_forum_post_count($result['ID'])	; ?></div>
                <div class="col-md-4 col-lg-3 bbf_forum_freshness hidden-sm hidden-xs no_mp text-center">Last post</div>
                <div class="clearboth"></div>
            </div>
		<?php
			$i = 1;
				echo '<ul class="forum_subforums no_mp">';
				while($my_topics->have_posts())
				{
					global $post;
					//db($post);
					$my_topics->the_post();
					if($i%2 == 0)
						$sub_form_class = 'even';
					else
						$sub_form_class = 'odd';
					if($post->topic_sticky == 1)
						$sub_form_class .= ' sticky_topic';
					else
						$sub_form_class .= ' normal_topic';

					?>
				<li class="bbf_heads_childs <?php echo $sub_form_class; ?>">
					<?php
						$topic_url = get_the_permalink();

					?>
					<div class="col-xs-8 col-sm-8 col-md-6 col-lg-7 bbf_forum_info no_mp">
						<?php

						if(!($fImageURL = get_feature_image_url($post->ID))){
							$fImageURL = THEME_URI.'images/forum_read.png';
						}
						if($post->topic_sticky == 1){
							$fImageURL = THEME_URI.'images/forum_read_sticky.png';
						}
						?>
							<a href="<?php echo $topic_url; ?>" class="forums_thumbnails"><img src="<?php echo $fImageURL; ?>" alt="<?php echo get_the_title(); ?>" class="forums_thumbnails" /></a>

					<span class="forum_title"><a href="<?php echo $topic_url; ?>"><?php echo get_the_title(); ?></a></span>
					<p>Started by: <a href="<?php echo USERS_URI.get_the_author_meta('ID'); ?>"><?php echo get_the_author_meta('user_login'); ?></a></p>
					</div>
					<div class="col-xs-4 col-sm-2 col-md-1 bbf_forum_topic_count no_mp text-center"><?php echo get_views($post->ID); ?></div>
					<div class="col-xs-2 col-sm-2 col-md-1 bbf_forum_reply_count hidden-xs no_mp text-center"><?php echo $post->topic_posts; ?></div>

					<div class="col-md-4 col-lg-3 bbf_forum_freshness hidden-sm hidden-xs no_mp text-center">
						<span class="bbp-topic-freshness-author">
                            <!--introduction<br />-->
                             <?php if($post->last_poster_name){?>
                                By <a href="<?php echo USERS_URI.$post->topic_last_poster_id; ?>"><?php echo $post->last_poster_name; ?><!--Admin--></a><br />
                                <?php echo human_time_diff(strtotime($post->last_post_time), current_time( 'timestamp')); ?> ago

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
				echo '<li class="bbp_footer"></li></ul>';
				$big = 999999999; // need an unlikely integer
				echo '<div class="pagination">';
echo paginate_links( array(
	'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
	'format' => '?paged=%#%',
	'current' => max( 1, get_query_var('paged') ),
	'total' => $my_topics->max_num_pages
) ); ?>
<div class="clearboth"></div>
</div>
<?php
	if(is_user_logged_in() && $forum_meta->getMeta('forum_type') == 0 && $forum_status == 0){	?>
	<p style="margin-top:20px;"><a href="<?php echo get_bloginfo('url'); ?>/topic/new/<?php echo $parent_forum_id; ?>" class="orange_btn">Post New Topic</a></p>
<?php }
	else if($forum_meta->getMeta('forum_type') == 0 && $forum_status == 1){
				echo '<div class="web_boxp">This forum has been closed for new topics.</div>';
			}
		}
		else if($forum_meta->getMeta('forum_type') == 0 && $forum_status == 1)
		{
			echo '<div class="web_boxp">This forum has been closed for new topics.</div>';
		}
		?>


		<?php endwhile; else: ?>

<?php endif; ?>

			<?php include_once(FORUMS_ABS.'templates/forum-footer.php'); ?>

	</div><!-- container div end here-->
</main>
<?php get_footer(); ?>
