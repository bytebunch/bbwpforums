<?php
/*------------------------------------*\
	Delete user hook
\*------------------------------------*/
add_action( 'delete_user', 'bbf_delete_user' );
function bbf_delete_user( $user_id ) {
	global $wpdb;
  $sql = 'SELECT ID from '.$wpdb->posts.' WHERE post_author = '.$user_id;
  $results = $wpdb->get_results($sql, ARRAY_A);
  if($results){
    foreach($results as $result){
      wp_delete_post( $result['ID'], true );
      $sql = 'DELETE from '.$wpdb->bbf_topic_meta.' WHERE topic_id = '.$result['ID'];
      $wpdb->query($sql);
      $sql = 'DELETE from '.$wpdb->bbf_reply.' WHERE topic_id = '.$result['ID']. 'OR user_id = '.$user_id;
      $wpdb->query($sql);
    }
  }
  //$user_obj = get_userdata( $user_id );
  //$email = $user_obj->user_email;
}


/*------------------------------------*\
	Delete post hook
\*------------------------------------*/
add_action( 'before_delete_post', 'bbf_before_delete_post' );
function bbf_before_delete_post( $postid ){
    // We check if the global post type isn't ours and just return
    global $post_type, $wpdb;
    if ( $post_type != TOPIC_PT )
      return;
    $sql = 'DELETE from '.$wpdb->bbf_topic_meta.' WHERE topic_id = '.$postid;
    $wpdb->query($sql);
    $sql = 'DELETE from '.$wpdb->bbf_reply.' WHERE topic_id = '.$postid;
    $wpdb->query($sql);
}
