<?php global $BBFThemeOptions; ?>
<footer id="footer" class="footer">
	<div class="copyright_text">
    <div class="container">
			<ul class="footer_menu">
	    	<li><a href="<?php echo get_post_type_archive_link( FORUM_PT ); ?>">Board Index</a></li>
	      <li><a href="<?php echo get_post_type_archive_link( TOPIC_PT ); ?>">All Topics</a></li>
	      <li><a href="<?php echo get_permalink($BBFThemeOptions->get_bbf_theme_option('page_terms_of_use_id')); ?>">Terms of Use</a></li>
	      <li><a href="<?php echo get_permalink($BBFThemeOptions->get_bbf_theme_option('page_privacy_policy_id')); ?>">Privacy Policy</a></li>
	      <li><a href="<?php echo get_permalink($BBFThemeOptions->get_bbf_theme_option('page_contact_us_id')); ?>">Contact us</a></li>
	      <?php if(is_user_logged_in()){ ?>
	        <li><a href="<?php echo wp_logout_url( home_url() ); ?>">Log Out</a></li>
	      <?php }else{?>
	        <li><a href="<?php echo get_permalink($BBFThemeOptions->get_bbf_theme_option('page_login_id')); ?>">Log in</a></li>
	        <li><a href="<?php echo get_permalink($BBFThemeOptions->get_bbf_theme_option('page_register_id')); ?>">Register</a></li>
		    <?php } ?>
	    <li><a href="https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=GR8NLRVEVE2P6&lc=US&item_name=Clan%20BvO&currency_code=USD&bn=PP%2dDonationsBF%3abtn_donateCC_LG%2egif%3aNonHosted" target="_blank">Donate</a></li>
    </ul>
		<?php
		$bbf_footer_copyright_text = get_option('bbf_footer_copyright_text');
		if($bbf_footer_copyright_text){
			$content = apply_filters( 'the_content', $bbf_footer_copyright_text );
			$content = str_replace( ']]>', ']]&gt;', $content );
			echo $content;
		}else{ ?>
			<p>© Copyright <?php echo date('Y'); ?>. Theme by <a href="https://bytebunch.com" target="_blank">Byte Bunch Team</a>.</p>
		<?php }
		?>

		</div>
	</div>
</footer><!-- footer div end here-->





<?php wp_footer(); ?>
</body>
</html>
