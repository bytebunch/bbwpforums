<?php
 global $wpdb, $BBFThemeOptions;
?>
<!-- sidebar -->
<aside class="sidebar" role="complementary">

	<div class="widget sidebar_widget subscribe web_boxp">
	<?php if(is_user_logged_in()){
		global $current_user;
	?>
	<h3 style="text-align:center;"><a href="<?php echo USERS_URI.$current_user->ID; ?>"><?php echo $current_user->data->user_login; ?></a></h3>
		<p><a href="<?php echo USERS_URI.$current_user->ID; ?>"><img src="<?php echo get_user_profile_image_url($current_user->ID); ?>" alt="" class="profile_image" style="max-width:100%; display:block; margin:0 auto;" /></a></p>

	    <div class="pull-left"><strong>Join Date:</strong></div><div class="pull-right"> <?php echo date('F d, Y',strtotime($current_user->data->user_registered)); ?></div>
	    <div class="clearboth"></div>

	   <?php if($user_location = get_user_meta($current_user->ID,'location',true)){ ?>
		 <div class="pull-left"><strong>Location: </strong></div><div class="pull-right"><?php echo $user_location; ?></div><div class="clearboth"></div>
	    <?php } ?>
	    <div class="pull-left"><strong>Posts:</strong></div><div class="pull-right"> <?php echo get_user_posts($current_user->ID);  ?></div>
	    <div class="clearboth"></div>



	<?php }else{ ?>
		<h3 class="widget_title">Login</h3>
	    <form action="<?php echo get_permalink($BBFThemeOptions->get_bbf_theme_option('page_login_id')); ?>" class="jquery_validated_form" method="post">
	        <p>
	            <label for="username">Username:</label><br />
	            <input type="text" name="username" id="username" class="required" required="required" />
	        </p>
	        <p>
	            <label for="password">Password:</label> <br />
	            <input type="password" name="password" id="password" class="required" required="required" />
	        </p>
	        <p><input type="checkbox" name="rememberme" id="remember_me" checked="checked" /> <label for="remember_me">Log me on automatically each visit.</label></p>
	        <p><a href="<?php echo get_permalink($BBFThemeOptions->get_bbf_theme_option('page_forgot_password_id')); ?>">Forgot your password?</a></p>
	        <p><input type="submit" value="Log in" class="small-btn-Ube" /></p>
	      </form>
	     <?php } ?>
	</div><!-- subscribe tags end here-->



  <div class="widget sidebar_widget web_boxp">
  	<h3 class="widget_title">Who is Online</h3>
      <div class="widget_content">
      <?php
  	global $visitor_counter;
  	$visitors = $visitor_counter->getAmountVisitors();
  	$total_visitors =  $visitors[0];
  	$total_members = count($visitors[1]);
  	$total_guests = $total_visitors - $total_members;
   ?>

      <div class="pull-left"><strong>Total: </strong></div><div class="pull-right"><?php echo $total_visitors; ?></div>
      <div class="clearboth"></div>
      <div class="pull-left"><strong>Registered: </strong></div><div class="pull-right"><?php echo $total_members; ?></div>
      <div class="clearboth"></div>
      <div class="pull-left"><strong>Guest: </strong></div><div class="pull-right"><?php echo $total_guests; ?></div>
      <div class="clearboth"></div>

          <p><br />Most users ever online was <strong><?php echo $visitors[2]; ?></strong> on <?php echo date('D M d, Y g:i A',get_option('most_users_online_date')); ?></p>
          <p>Registered users: <?php if($total_members >= 1)
    {
  	  $i = 1;
  	  foreach($visitors[1] as $member)
  	  {
  		  if($i == count($visitors[1]))
  		  {
  			  echo '<a href="'.USERS_URI.$member['memberid'].'">'.$member['membername'].'</a> ';
  		  }else
  		  {
  			  echo '<a href="'.USERS_URI.$member['memberid'].'">'.$member['membername'].'</a>, ';
  		}
  		$i++;

  		}
    }else
    {
  	  echo 'No registered users';
    } ?></p>
      </div>
  </div>



  <div class="widget sidebar_widget web_boxp">
  	<h3 class="widget_title">Statistics</h3>
      <div class="widget_content">
      	<?php
  	$topic_counts = wp_count_posts('topic');
  	$topic_counts = $topic_counts->publish;
  	$user_counts = count_users();
  	$user_counts = $user_counts['total_users'];

  	$sql = "select count(ID) as count from ".$wpdb->bbf_reply;
  	$results = $wpdb->get_results($sql, ARRAY_A);
  	$post_counts = $results[0]['count'];

   ?>
      <div class="pull-left"><strong>Total posts: </strong></div><div class="pull-right"><?php echo $post_counts; ?></div>
      <div class="clearboth"></div>
      <div class="pull-left"><strong>Total topics: </strong></div><div class="pull-right"><?php echo $topic_counts; ?></div>
      <div class="clearboth"></div>
      <div class="pull-left"><strong>Total members: </strong></div><div class="pull-right"><?php echo $user_counts; ?></div>
      <div class="clearboth"></div>

      </div>
  </div>


  <div class="widget sidebar_widget web_boxp">
  	<h3 class="widget_title">Most Recent Posts</h3>
      <div class="widget_content">
      <?php
  	$sql = 'SELECT ID, topic_id, user_id, date, content  FROM '.$wpdb->bbf_reply.' ORDER BY date DESC LIMIT 4';
  	$results = $wpdb->get_results($sql, ARRAY_A);
  	if($results)
  	{
  		echo '<ul class="no_mp">';
  		foreach($results as $result)
  		{ ?>
  			<li>
              <div class="thumbnail" style="min-height:70px;">
              <a href="<?php echo USERS_URI.$result['user_id']; ?>" class="image_wrapper">
              <img src="<?php echo get_user_profile_image_url($result['user_id']); ?>" alt="" /></a>
              </div>
              <div class="info">
              <span class="widgettitle"><a href="<?php echo get_the_permalink($result['topic_id']); ?>"><?php echo bb_substr(get_the_title($result['topic_id']),20); ?></a></span>
              <p><?php
  			$content = $result['content'];
  			 echo $content = bb_substr(strip_tags(remove_div_tag_and_its_content($content)), 50);  ?></p>
              <p style="font-size:11px; font-weight:bold;"><?php echo date("F d, Y h:i:s",strtotime($result['date'])); ?></p>
              </div>
              <div class="clearboth"></div>
              </li>
  		<?php }
  		echo '</ul>';
  	}
  	 ?>
      </div>
  </div>


  <div class="widget sidebar_widget web_boxp">
  	<h3 class="widget_title">Latest Members</h3>
      <div class="widget_content">
      	<div class="pull-left">Username: </div><div class="pull-right">Joined</div>
          <div class="clearboth"></div>
          <?php
  		$sql = "select ID, user_login, user_registered from ".$wpdb->users." ORDER BY ID DESC Limit 10";
  		$last_registered_user = $wpdb->get_results($sql, ARRAY_A);

  		if($last_registered_user)
  		{
  			foreach($last_registered_user as $user){?>
  			<div class="pull-left">
              <a href="<?php echo USERS_URI.$user['ID']; ?>"><strong><?php echo $user['user_login']; ?>: </strong></a>
        </div>
        <div class="pull-right"><?php echo date('d M Y', strtotime($user['user_registered']));  ?></div>
          	<div class="clearboth"></div>

  			<?php }
  		}else
  		{
  			echo 'No user found. ';
  		} ?>
      </div>
  </div>





  <div class="widget sidebar_widget web_boxp">
  	<h3 class="widget_title">Top Posters</h3>
      <div class="widget_content">
      <div class="pull-left">Username: </div><div class="pull-right">Posts</div>
          <div class="clearboth"></div>
      	<?php
  		$sql = "SELECT users.ID, .users.user_login, cast(users_meta.meta_value as SIGNED) as meta_value from ".$wpdb->users." as users INNER JOIN ".$wpdb->usermeta." as users_meta WHERE users.ID = users_meta.user_id and users_meta.meta_key='users_posts' ORDER BY meta_value DESC Limit 5";
  		$last_registered_user = $wpdb->get_results($sql, ARRAY_A);

  		if($last_registered_user)
  		{
  			foreach($last_registered_user as $user){?>
  			<div class="pull-left">
              <a href="<?php echo USERS_URI.$user['ID']; ?>"><strong><?php echo $user['user_login']; ?>: </strong></a>
        </div>
        <div class="pull-right"><?php echo $user['meta_value'];  ?></div>
          	<div class="clearboth"></div>

  			<?php }
  		}else
  		{
  			echo 'No user found. ';
  		} ?>

      </div>
  </div>





</aside>
<!-- /sidebar -->
