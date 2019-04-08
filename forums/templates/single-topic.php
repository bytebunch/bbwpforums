<?php

global $post, $wp_query, $authordata, $wpdb, $BBFThemeOptions;


if(isset($wp_query->query_vars['new']) && !is_user_logged_in())
{
	header("Location: ".get_bloginfo('url'));
}

$current_date = date("Y-m-d h:i:s", current_time( 'timestamp'));
$parent_forum_id = $post->post_parent;
$current_topic_id = $post->ID;
$current_post_id = $post->ID;
$current_topic_title = $post->post_title;
$closed_topic = false;
if($post->topic_status == 1)
{
	$closed_topic = true;
}
$sticky_topic = false;
if($post->topic_sticky == 1)
{
	$sticky_topic = true;
}
include_once(THEME_ABS.'forums/templates/topic-forms-handling.php');
get_header();

?>

<main role="main" class="content_wrapper">
	<div class="container bbf content">
        <div id="breadcrumbs" class="breadcrumbs hidden-xs hidden-sm">
            <?php echo bbf_breadcrumb(); ?>
        </div>

        <?php
				//live_chat_box();

	if (have_posts()) : while (have_posts()) : the_post(); ?>
	<?php



		if(isset($wp_query->query_vars['edit']) && $wp_query->query_vars['edit'] == 1 && isset($wp_query->query_vars['reply']))
		{
			include_once(THEME_ABS.'forums/templates/topic-reply-edit.php');
		}
		else if(isset($wp_query->query_vars['edit']) && $wp_query->query_vars['edit'] == 1)
		{
			include_once(THEME_ABS.'forums/templates/topic-edit.php');
		}
		else if(isset($wp_query->query_vars['new']) && is_numeric($wp_query->query_vars['new']) && $wp_query->query_vars['new'] >= 1)
		{
			$parent_forum_id = $wp_query->query_vars['new'];
			include_once(THEME_ABS.'forums/templates/topic-new.php');
		}
		else
		{
			update_page_counter($current_topic_id);
			include_once(THEME_ABS.'forums/templates/topic-content.php');
		}



	endwhile; else:
	endif; ?>

	</div><!-- container div end here-->
</main><!-- content_wrapper div end here-->

<?php get_footer(); ?>
