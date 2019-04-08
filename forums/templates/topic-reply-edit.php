<h2><?php echo get_the_title(); ?></h2>
<div class="web_boxp">
<?php global $wp_query, $wpdb;
//db($wp_query);
 ?>
<form action="" method="post" class="ckeditor_form reply_form_edit">
<?php
if(isset($message))
{
	echo '<p class="form_message">'.$message.'</p>';
}
 ?>
  
  
  <?php 
  
  $reply_id = $wp_query->query_vars['reply'];
  $content = "";
  $sql = "SELECT content from ".$wpdb->bbf_reply." WHERE ID = $reply_id";
  $results = $wpdb->get_results($sql, ARRAY_A);
  if($results)
  {
	  $content = $results[0]['content'];
  }
  
  ckEditor('reply_id_content',$content);
  
  ?>
  <p class="form_message"></p>
  <input type="hidden" name="modify_reply" value="edit" />
  <!--<input type="button" value="Click Me" class="clickme" />-->
  <input type="submit" value="Submit" />
</form>
</div>