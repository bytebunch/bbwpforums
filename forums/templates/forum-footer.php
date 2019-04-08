<div class="dark_box_header" style="margin-top:20px;">
  <h3>Who is online</h3>
</div>
<div class="dark_box_content"> In total there are <strong><?php
	 // make a new counter
//content here


$visitors = $visitor_counter->getAmountVisitors();
$total_visitors =  $visitors[0];
$total_members = count($visitors[1]);
$total_guests = $total_visitors - $total_members;

echo $total_visitors; // show the counter ?></strong> users online :: <?php echo $total_members; ?> registered and <?php  echo $total_guests; ?> guests (based on users active over the past 5 minutes)<br>
  Most users ever online was <strong><?php echo $visitors[2]; ?></strong> on <?php echo date('D M d, Y g:i A',get_option('most_users_online_date'));

  ?><br>
  <br>
  Registered users: <?php if($total_members >= 1)
  {
	  $i = 1;
    //echo '<span style="display:none;">';
    //db($visitors[1]);
    //echo '</span>';
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
  } ?>  <br>
  <em>Legend: <a href="#">Administrators</a>, <a href="#">Super moderators</a>, <a href="#">Forum moderators</a>, <a href="#">Members</a></em> </div>
<!-- dark_box_content div end here-->

<div class="dark_box_header">
  <h3>Statistics</h3>
</div>
<?php
	global $wpdb;

	$topic_counts = wp_count_posts('topic');
	$topic_counts = $topic_counts->publish;
	$user_counts = count_users();
	$user_counts = $user_counts['total_users'];

	$sql = "select count(ID) as count from ".$wpdb->bbf_reply."";
	$results = $wpdb->get_results($sql, ARRAY_A);
	$post_counts = $results[0]['count'];

	$sql = "select ID, user_login from ".$wpdb->users." ORDER BY ID DESC Limit 1";
	$last_registered_user = $wpdb->get_results($sql, ARRAY_A);



 ?>
<div class="dark_box_content"> Total posts <?php echo $post_counts; ?> • Total topics <?php echo $topic_counts; ?> • Total members <?php  echo $user_counts;?> • Our newest member <a href="<?php echo USERS_URI.$last_registered_user[0]['ID'] ?>"><?php echo $last_registered_user[0]['user_login']; ?></a> </div>
<!-- dark_box_content div end here-->
