<?php
// Template Name: No Sidebar
get_header(); ?>
<main role="main" class="content_wrapper">
	<div class="container">
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<div class="content" id="content">
					<?php if (have_posts()): while (have_posts()) : the_post(); ?>
						<!-- article -->
						<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
							<div class="web_boxp headding_title">
									<h2 class="center"><?php the_title(); ?> <span class="pull-right"><?php edit_post_link(); ?></span></h2>

							</div>
							<div class="web_boxp">
								<?php the_content();  ?>
							</div>
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


		</div><!-- row div end here-->
	</div><!-- container div end here-->
</main>
<?php get_footer(); ?>
