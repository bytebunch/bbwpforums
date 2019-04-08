<?php get_header();
?>
<main role="main" class="content_wrapper">
	<div class="container bbf content">
		<?php /*<div class="row"> */ ?>
    <?php //live_chat_box(); ?>
        <?php /* <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 page_left_content"> */ ?>
        	<div id="content" class="content">
						<h2>List of All Topics</h2>
			      <?php
						//wp_reset_postdata();
						//wp_reset_query();
						//global $post, $wp_query;
						//$wp_query;
						//db($wp_query);

						topics_list('page_topic_archive');
						 ?>
        	</div>
        <?php /*</div><!-- left content div end here --> */ ?>

      	<?php /*<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4"><?php get_sidebar(); ?></div>*/ ?>
        <div class="clearboth"></div>

			<?php include_once(FORUMS_ABS.'templates/forum-footer.php'); ?>

		<?php /*</div><!-- row div end here-->*/ ?>
	</div><!-- container div end here-->
</main>
<?php get_footer(); ?>
