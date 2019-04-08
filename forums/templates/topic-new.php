<?php


$parent_forum_ids = get_post_ancestors($parent_forum_id);
?>
<h2><?php echo get_the_title($parent_forum_id); ?></h2>
<div class="web_boxp">
<form action="" method="post" class="ckeditor_form topic_form">
<?php
if(isset($message))
{
	echo '<p class="form_message">'.$message.'</p>';
}
 ?>

  <h3>Create New Topic</h3>

  <p>
    <label for="bb_topic_title">Topic Title (Maximum Length: 80):</label>
    <br>
    <input type="text" id="bb_topic_title" value="" name="bb_topic_title" class="bb_title">
  </p>
  <?php ckEditor('ckeditor_content'); ?>
  <p class="form_message"></p>

  <input type="hidden" name="parent_post[]" value="<?php echo $parent_forum_id; ?>" />

  <?php
  if($parent_forum_ids && is_array($parent_forum_ids) && count($parent_forum_ids) > 1)
  {
	  unset($parent_forum_ids[count($parent_forum_ids)-1]);
	  foreach($parent_forum_ids as $forum_Id)
	  {?>
		  <input type="hidden" name="parent_post[]" value="<?php echo $forum_Id; ?>" />
	  <?php }
  }
  ?>

  <input type="hidden" name="modify_topic" value="creat_topic" />
  <!--<input type="button" value="Click Me" class="clickme" />-->
  <input type="submit" value="Submit" />
</form>
</div>
