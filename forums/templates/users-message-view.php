<?php 
global $current_user, $wpdb;

$message_id = sql_ready(post_ready($wp_query->query_vars["message"]));


if(isset($wp_query->query_vars["inbox"])){
$sql = 'UPDATE '.$wpdb->bbf_messages.' SET read_status=1 WHERE ID = '.$message_id.' AND user_id = '.$current_user->ID;
$wpdb->query($sql);
$sql = 'SELECT * FROM '.$wpdb->bbf_messages.' WHERE ID = '.$message_id.'  AND user_id = '.$current_user->ID.' LIMIT 1';
}
elseif(isset($wp_query->query_vars["sent"]))
{
	$sql = 'SELECT * FROM '.$wpdb->bbf_messages.' WHERE ID = '.$message_id.'  AND author_id = '.$current_user->ID.' LIMIT 1';
}
else
{
	$sql = 'SELECT * FROM '.$wpdb->bbf_messages.' WHERE ID = 0';
}


$results = $wpdb->get_results($sql, ARRAY_A);

if($results)
{
	$message_id = $results[0]['ID'];
	$subject = $results[0]['subject'];
	$message = $results[0]['message'];
	$from = $results[0]['author_name'];
	$from_id = $results[0]['author_id'];
	$to = $results[0]['user_name'];
	$to_id = $results[0]['user_id'];
	$date = date("D d M, Y h:i A",strtotime($results[0]['time'])); ?>
	<h3><?php echo $subject; ?></h3>
	<p>
    	<span class="view_message_left_span">Subject: </span> <?php echo $subject; ?> <br />
        <span class="view_message_left_span">From: </span> <?php echo $from; ?> <br />
        <span class="view_message_left_span">Sent: </span> <?php echo $date; ?> <br />
        <span class="view_message_left_span">To: </span> <?php echo $to; ?>
    </p>
	<div>
    	<span class="view_message_left_span">Message: </span><br /><br />
        <?php echo bbcode_to_html($message); ?>
    </div>
    
    <p>&nbsp;</p>
    <?php if(isset($wp_query->query_vars["inbox"])){
		
	$sql = 'UPDATE '.$wpdb->bbf_messages.' SET read_status=1 WHERE ID = '.sql_ready($message_id).' AND user_id = '.$current_user->ID;
$wpdb->query($sql);
		
		 ?>
         
        <a style="display:block; float:left;margin-right:30px; padding:8px 32px; font-size:14px; font-weight:700; text-transform:uppercase;" href="<?php echo USERS_URI.$current_user->ID; ?>/compose/?to=<?php echo urlencode($from); ?>&subject=<?php echo urlencode($subject); ?>&reply=1" class="orange_btn">Reply</a>
        
    <?php /*?><form action="<?php echo USERS_URI.$current_user->ID; ?>/compose/?reply=1" method="post" style="display:block;float:left; margin-right:20px;">
    	<input type="hidden" name="to" value="<?php echo $from; ?>" />
        <input type="hidden" name="subject" value="<?php echo $subject; ?>" />
    	<input type="submit" value="Reply" />
    </form><?php */?>
    
    <form action="<?php echo USERS_URI.$current_user->ID; ?>/inbox/" method="post" style="display:block;float:left; margin-right:20px;">
    <input type="hidden" name="mark_options" value="delete_marked" />
    <input type="hidden" name="delete_messages[]" value="<?php echo $message_id;  ?>" />
    <input type="submit" value="Delete" class="delete_this" />
    </form>
    
    <div class="clearboth"></div>
    
<?php
	}
	
	
	
	
	
	$sql = 'SELECT * FROM '.$wpdb->bbf_messages.' WHERE (author_id = '.$from_id.' OR author_id = '.$to_id.') AND (user_id = '.$to_id.' OR user_id = '.$from_id.') AND deleted = 0 ORDER BY time DESC LIMIT 30 ';
	$historyResults = $wpdb->get_results($sql, ARRAY_A);

	if($historyResults && count($historyResults) >= 2)
	{?>
		<div class="dark_box_header" style="margin-top:30px; text-align:center;">
        	<h3>Message history</h3>
        </div>
        <div class="message_history" style="max-height:500px; overflow:auto;">
        <table class="messages"><tr class="first_row"><td style="width:10%;">Author</td><td>Message</td></tr>
        <?php
			foreach($historyResults as $result)
			{
				echo '<tr>';
				echo '<td>'.$result['author_name'].'</td>';
				echo '<td style="text-align:left">'.$result['message'].'</td>';
				echo'</tr>';
			}
		 ?>
         </table>
         </div>
	<?php }
	
	
	
	
	
 }else
{
	echo '<h3>No message found.</h3>';
}