<?php

add_action( 'add_meta_boxes', 'forum_add_custom_box' );

// backwards compatible (before WP 3.0)

/* Adds a box to the main column on the Post and Page edit screens */
function forum_add_custom_box() {

    add_meta_box('forum_attributes','Forum Attributes','forum_inner_custom_box',FORUM_PT,'side','core');
}

/* Prints the box content */
function forum_inner_custom_box( $post ) {
	global $post;
	$forum_meta = new ForumMeta($post->ID);

	//db($post);
?>
<style type="text/css">
strong.label {
	display: inline-block;
	width: 60px;
}
</style>
<div class="inside">
<p>
    <strong class="label">Type:</strong>
    <label class="screen-reader-text" for="bb_forum_type_select">Type:</label>
    <select name="bb_forum_type" id="bb_forum_type_select" style="max-width:160px;">
        <option value="0" <?php selected( $forum_meta->getMeta('forum_type')); ?>>Forum</option>
        <option value="1" <?php selected( $forum_meta->getMeta('forum_type')); ?>>Category</option>
    </select>
</p>
<p>

    <strong class="label">Parent:</strong>
    <label class="screen-reader-text" for="parent_id">Forum Parent</label>
    <select name="parent_id" id="parent_id" tabindex="104" style="max-width:160px;">
        <option value="" class="level-0">— No parent —</option>
        <?php
		$args = array('post_type' => $post->post_type, 'posts_per_page' => -1, "posts_status" => 'any','exclude' => $post->ID);
		$posts_result = get_posts($args);
		//db($posts_result);
		//exit();
		if($posts_result)
		{
			foreach($posts_result as $result)
			{
				echo '<option class="level-0" value="'.$result->ID.'" '.selected( $post->post_parent, $result->ID, false).'>'.$result->post_title.'</option>';
			}
		}
		?>
    </select>
</p>
<p>
  <label for="bbf_forum_status"><strong>Close forum for new topics:</strong></label>&nbsp;
  <input name="bbf_forum_status" type="checkbox" id="bbf_forum_status" value="yes" <?php if($forum_meta->getMeta('forum_status') == 1){ echo 'checked="checked"'; } ?> />
</p>
<p>
  <label for="bbf_recent_announcements"><strong>Announcements and News:</strong></label>&nbsp;
  <input name="bbf_recent_announcements" type="checkbox" id="bbf_recent_announcements" value="yes" <?php if(get_post_meta($post->ID,'bbf_recent_announcements',true) == 'yes'){ echo 'checked="checked"'; } ?> />
</p>
<p>
  <label for="bbf_forum_external_link"><strong>External Link: </strong></label>&nbsp;
  <input name="bbf_forum_external_link" type="url" id="bbf_forum_external_link" value="<?php echo $forum_meta->getMeta('external_link'); ?>" />
</p>
<!-- <p>
    <strong class="label">Order:</strong>
    <label class="screen-reader-text" for="menu_order">Forum Order</label>
    <input name="menu_order" type="number" step="1" size="4" id="menu_order" value="<?php echo $post->menu_order; ?>" style="max-width:160px;">
</p> -->
</div>


  <?php
}
/* Do something with the data entered */
add_action( 'save_post', 'event_save_postdata' );
/* When the post is saved, saves our custom data */
function event_save_postdata($post) {
	global $post, $wpdb;
	if(isset($_POST['bb_forum_type']))
	{
		$forum_type = $_POST['bb_forum_type'];
		if($post->post_type == FORUM_PT){

      $external_link = '';
      $forum_status = 0;
      if(isset($_POST['bbf_forum_external_link']) && PostReady($_POST['bbf_forum_external_link']) != ""){
        $external_link = $_POST['bbf_forum_external_link'];
      }
      if(isset($_POST['bbf_forum_status']) && PostReady($_POST['bbf_forum_status']) != ""){
        $forum_status = 1;
      }

			$sql = "SELECT ID FROM ".$wpdb->bbf_forum_meta." WHERE forum_id = ".$post->ID;
			$result = $wpdb->query($sql);
			if($result)
			{
				$sql = "UPDATE ".$wpdb->bbf_forum_meta." SET forum_type='$forum_type', external_link='$external_link', forum_status=$forum_status WHERE forum_id = ".$post->ID;
			}
			else
			{
				$sql = "INSERT INTO ".$wpdb->bbf_forum_meta." (forum_id, forum_type, external_link, forum_status) VALUES (".$post->ID.", '".$forum_type."', '$external_link', $forum_status)";
				/*$sql = "INSERT INTO bb_forum_meta (forum_id, forum_type) VALUES (".$post->ID.", '".$forum_type."') ON DUPLICATE KEY UPDATE forum_type=VALUES(forum_type) ";*/
			}
			$wpdb->query($sql);

      if(isset($_POST['bbf_recent_announcements']) && $_POST['bbf_recent_announcements'] == 'yes'){
        update_post_meta($post->ID,'bbf_recent_announcements','yes');
      }else{
        if(get_post_meta($post->ID,'bbf_recent_announcements',true))
          update_post_meta($post->ID,'bbf_recent_announcements',$_POST['bbf_recent_announcements']);
      }
		}
	}

}
