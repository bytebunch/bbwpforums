<h2><?php echo get_the_title(); ?></h2>
<div class="web_boxp">
<?php global $wp_query;
//db($wp_query);
 ?>
<form action="" method="post" class="ckeditor_form topic_form">
<?php
if(isset($message))
{
	echo '<p class="form_message">'.$message.'</p>';
}
 ?>
  
  <p>
    <label for="bb_topic_title">Topic Title (Maximum Length: 80):</label>
    <br>
    <input type="text" id="bb_topic_title" value="<?php echo get_the_title(); ?>" name="bb_topic_title" class="bb_title">
  </p>
  <?php ckEditor('ckeditor_content',get_the_content()); ?>
  <p class="form_message"></p>
  <input type="hidden" name="modify_topic" value="edit" />
  <!--<input type="button" value="Click Me" class="clickme" />-->
  <input type="submit" value="Submit" />
</form>
</div>