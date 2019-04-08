<?php
add_action( 'add_meta_boxes', 'topic_add_custom_box' );

// backwards compatible (before WP 3.0)

/* Adds a box to the main column on the Post and Page edit screens */
function topic_add_custom_box() {
    add_meta_box('topic_attributes','Topic Attributes','topic_inner_custom_box',TOPIC_PT,'side','core');
}

/* Prints the box content */
function topic_inner_custom_box( $post ) {
	global $post;
	$topic_meta = new TopicMeta($post->ID);
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

    <strong class="label">Parent:</strong>
    <label class="screen-reader-text" for="parent_id">Topic Parent</label>
    <select name="parent_id" id="parent_id" tabindex="104" style="max-width:160px;">
        <option value="" class="level-0">— No parent —</option>
        <?php
		$args = array('post_type' => FORUM_PT, 'posts_per_page' => -1, "posts_status" => 'any');
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

<!-- <p>
    <strong class="label">Order:</strong>
    <label class="screen-reader-text" for="menu_order">topic Order</label>
    <input name="menu_order" type="number" step="1" size="4" id="menu_order" value="<?php echo $post->menu_order; ?>" style="max-width:160px;">
</p> -->
</div>


  <?php
}
/* Do something with the data entered */
add_action( 'save_post', 'topic_save_postdata' );
/* When the post is saved, saves our custom data */
function topic_save_postdata($post) {
	global $post, $wpdb, $current_user;
  //db($post);
  //exit();

  if(isset($_POST['parent_id']) && is_numeric($_POST['parent_id']) && $_POST['parent_id'] >= 1){
	  if($post->post_type == TOPIC_PT){
      $current_post_id = $post->ID;

      $sql = "SELECT ID FROM ".$wpdb->bbf_topic_meta." WHERE topic_id = ".$post->ID;
      $results = $wpdb->query($sql, ARRAY_A);

      if($results){
        if($post->post_parent != $_POST['parent_id']){

          // update new forums meta
          $parent_forum_ids = get_post_ancestors($_POST['parent_id']);
          if($parent_forum_ids && is_array($parent_forum_ids) && count($parent_forum_ids) > 1){
            unset($parent_forum_ids[count($parent_forum_ids)-1]);
            $parent_forum_ids[] = $_POST['parent_id'];
          }else
            $parent_forum_ids = array($_POST['parent_id']);

          $sql = " UPDATE ".$wpdb->bbf_forum_meta." SET forum_posts = forum_posts+1, forum_topics = forum_topics+1 WHERE forum_id IN(".implode(",",$parent_forum_ids).");";
          $wpdb->query($sql);


          // update old forums meta
          $old_parent_forum_ids = get_post_ancestors($post->post_parent);
          if($old_parent_forum_ids && is_array($old_parent_forum_ids) && count($old_parent_forum_ids) > 1){
            unset($old_parent_forum_ids[count($old_parent_forum_ids)-1]);
            $old_parent_forum_ids[] = $post->post_parent;
          }else
            $old_parent_forum_ids = array($post->post_parent);

          $sql = " UPDATE ".$wpdb->bbf_forum_meta." SET forum_posts = forum_posts-1, forum_topics = forum_topics-1 WHERE forum_id IN(".implode(",",$old_parent_forum_ids).");";
          $wpdb->query($sql);


        }
      }else{
        $sql = "INSERT INTO ".$wpdb->bbf_topic_meta." (topic_id, topic_posts, topic_last_post_id, topic_last_poster_id, last_poster_name, last_post_time) VALUES ($current_post_id, topic_posts+1, $current_post_id, $current_user->ID, '$current_user->user_login', Now())";
        $wpdb->query($sql);

        $parent_forum_ids = get_post_ancestors($_POST['parent_id']);
        if($parent_forum_ids && is_array($parent_forum_ids) && count($parent_forum_ids) > 1){
          unset($parent_forum_ids[count($parent_forum_ids)-1]);
          $parent_forum_ids[] = $_POST['parent_id'];
        }else
        {
          $parent_forum_ids = array($_POST['parent_id']);
        }

        $sql = " UPDATE ".$wpdb->bbf_forum_meta." SET forum_posts = forum_posts+1, forum_topics = forum_topics+1, forum_last_topic_id = $current_post_id, last_topic_title = '".sql_ready(get_the_title($current_post_id))."', forum_last_post_id = $current_post_id, last_post_time = Now(), forum_last_poster_id = $current_user->ID, last_poster_name = '$current_user->user_login' WHERE forum_id IN(".implode(",",$parent_forum_ids).");";
        $wpdb->query($sql);
      }

    }


		//$sql = "INSERT INTO ".$wpdb->prefix."bb_topic_meta (topic_id, topic_posts, topic_last_post_id, topic_last_poster_id, last_poster_name, last_post_time) VALUES (".$post->ID.", topic_posts+1, ".$post->ID.", $current_user->ID, '$current_user->display_name', Now()) ON DUPLICATE KEY UPDATE topic_id=VALUES(".$post->ID.") ";
		//$wpdb->query($sql);
	}

}
