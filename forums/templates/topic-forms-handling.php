<?php
global $wpdb;
$current_mysql_time = current_time('mysql');
/******************************************/
/***** Save the new topic into database **********/
/******************************************/
if(is_user_logged_in() && isset($_POST['modify_topic']) && $_POST['modify_topic'] == "creat_topic" && isset($_POST['parent_post']) && isset($_POST['parent_post'][0]) && is_numeric($_POST['parent_post'][0]) && $_POST['parent_post'][0] >= 1)
{
	$forum_meta = new ForumMeta($_POST['parent_post'][0]);
	if(!($forum_meta && $forum_meta->getMeta('forum_status') == 0)){
		wp_redirect(get_bloginfo('url'));
		exit();
	}

	$title = strip_tags(post_ready($_POST['bb_topic_title']));
	$content = post_ready($_POST['ckeditor_content']);

	if($title != "" && $title != " " && $content != "" && $content != " ")
	{
		/*$content = bb_kses($content);*/
		$parent_post = post_ready($_POST['parent_post'][0]);
		$title = strip_tags($title);

		$my_post = array(
		 'post_title' => $title,
		 'post_content' => $content,
		 'post_status' => 'publish',
		 'post_author' => $current_user->ID,
		 'post_type' => 'topic',
		 'post_parent' => $parent_post,
		 'comment_status' => 'closed'
		);

		$current_post_id = wp_insert_post( $my_post );

		//$sql = "START TRANSACTION;";

		$sql = "INSERT INTO ".$wpdb->bbf_topic_meta." (topic_id, topic_posts, topic_last_post_id, topic_last_poster_id, last_poster_name, last_post_time) VALUES ($current_post_id, topic_posts+1, $current_post_id, $current_user->ID, '$current_user->user_login', '$current_mysql_time');";

		if(isset($_POST['parent_post']) && is_all_numeric_values_in_array($_POST['parent_post']))
		{
			$sql .= " UPDATE ".$wpdb->bbf_forum_meta." SET forum_posts = forum_posts+1, forum_topics = forum_topics+1, forum_last_topic_id = $current_post_id, last_topic_title = '".sql_ready($title)."', forum_last_post_id = $current_post_id, last_post_time = '$current_mysql_time', forum_last_poster_id = $current_user->ID, last_poster_name = '$current_user->user_login' WHERE forum_id IN(".implode(",",$_POST['parent_post']).");";
		}



		//$sql .= " COMMIT;";
		mysqli_multi_query($wpdb->dbh, $sql);
		//exit();
		//update_user_posts($current_user->ID);
		wp_redirect(get_permalink($current_post_id));
		exit();

		//$content_2 = mysql_real_escape_string($content);
		//db(stripslashes($content_2));
		//db(wp_unslash( $content_2));
		//echo '<textarea name="" id="" cols="30" rows="10">'.bb_kses($content).'</textarea>';
		//echo '<textarea name="" id="" cols="30" rows="10">'.$content.'</textarea>';
	}
	else
	{
		$message = "You must specify topic subject and your message.";
	}

}


/******************************************/
/***** edit the topic form start from herer **********/
/******************************************/
if(isset($_POST['modify_topic']) && $_POST['modify_topic'] == "edit" && isset($_POST['bb_topic_title']) && $_POST['bb_topic_title'] != "")
{
	global $post, $current_user;

	$topic_author_id = $post->post_author;

	if(is_user_logged_in() && $closed_topic == false && ($topic_author_id == $current_user->ID || is_forum_moderator()))
	{

		$title = strip_tags(post_ready($_POST['bb_topic_title']));
		$content = post_ready($_POST['ckeditor_content']);

		if($title != "" && $title != " " && $content != "" && $content != " ")
		{
			/*$content = bb_kses($content);*/
			$parent_post = post_ready($_POST['parent_post']);
			$my_post = array(
			 'ID' => $current_topic_id,
			 'post_title' => $title,
			 'post_content' => $content
			);
			$current_post_id = wp_update_post( $my_post );
			wp_redirect(get_permalink($current_post_id));
			exit();

		}
		else
		{
			$message = "You must specify topic subject and your message.";
		}
	}
}

/******************************************/
/***** insert reply into database **********/
/******************************************/
if(isset($_POST['modify_reply']) && $_POST['modify_reply'] == "new" && is_user_logged_in())
{
	$content = post_ready($_POST['reply_content']);
	if($content != "" && $content != " ")
	{
		$content = sql_ready($content);
		$parent_forum_ids = get_post_ancestors($parent_forum_id);
		if($parent_forum_ids && is_array($parent_forum_ids) && count($parent_forum_ids) > 1)
  		{
			unset($parent_forum_ids[count($parent_forum_ids)-1]);
			$parent_forum_ids[] = $parent_forum_id;
		}
		else
		{
			$parent_forum_ids = array($parent_forum_id);
		}


		$sql = "INSERT INTO ".$wpdb->bbf_reply." (forum_id, topic_id, user_id, date, content) VALUES ($parent_forum_id, $current_topic_id, $current_user->ID, '$current_mysql_time', '$content')";
		$wpdb->query($sql);

		$lastInsertID = $wpdb->insert_id;

		$sql = "UPDATE ".$wpdb->bbf_forum_meta." as forum_meta, ".$wpdb->bbf_topic_meta." as topic_meta SET forum_meta.forum_posts = forum_meta.forum_posts+1, forum_meta.forum_last_topic_id = $current_topic_id, forum_meta.last_topic_title = '$current_topic_title', forum_meta.forum_last_post_id = $lastInsertID, forum_meta.last_post_time = '$current_mysql_time', forum_meta.forum_last_poster_id = $current_user->ID, forum_meta.last_poster_name = '$current_user->user_login', topic_meta.topic_posts = topic_meta.topic_posts+1, topic_meta.topic_last_post_id = $lastInsertID, topic_meta.topic_last_poster_id = $current_user->ID, topic_meta.last_poster_name = '$current_user->user_login', topic_meta.last_post_time = '$current_mysql_time' WHERE forum_meta.forum_id IN(".implode(",",$parent_forum_ids).") AND topic_meta.topic_id = $current_topic_id";

		/*$sql = "INSERT INTO bb_reply (forum_id, topic_id, user_id, date, content) VALUES ($parent_forum_id, $current_topic_id, $current_user->ID, '$current_date', '$content'); ";

		$sql .= "UPDATE bb_forum_meta SET forum_posts = forum_posts+1, forum_last_topic_id = $current_topic_id, last_topic_title = '$current_topic_title', forum_last_post_id = $lastInsertID, last_post_time = '$current_date', forum_last_poster_id = $current_user->ID, last_poster_name = '$current_user->display_name'
WHERE forum_id = $parent_forum_id; ";

		$sql .= "UPDATE bb_topic_meta
SET topic_posts = topic_posts+1, topic_last_post_id = $lastInsertID, topic_last_poster_id = $current_user->ID, last_poster_name = '$current_user->display_name'
WHERE topic_id = $current_topic_id";
		//mysqli_multi_query($wpdb->dbh, $sql);*/
		$wpdb->query($sql);
		update_user_posts($current_user->ID);
		wp_redirect(get_permalink($current_post_id).'#'.$lastInsertID);
		exit();
	}
	else
	{
		$message = "You must specify your message.";
	}
}


/******************************************/
/***** edit reply start from here **********/
/******************************************/

if(isset($_POST['modify_reply']) && $_POST['modify_reply'] == "edit" && isset($wp_query->query_vars['reply']) && is_numeric($wp_query->query_vars['reply']) && $wp_query->query_vars['reply'] >= 1 && is_user_logged_in() && $closed_topic == false)
{
	global $wpdb, $current_user;
	$reply_id = sql_ready(post_ready($wp_query->query_vars['reply']));
	$post_author_id = false;


	$content = post_ready($_POST['reply_id_content']);
	if($content != "" && $content != " ")
	{
		$sql = 'SELECT user_id FROM '.$wpdb->bbf_reply.' WHERE ID = '.$reply_id.' LIMIT 1';
		$results = $wpdb->get_results($sql, ARRAY_A);
		if($results)
		{
			$post_author_id = $results[0]['user_id'];
		}
		if($post_author_id != false && ($post_author_id == $current_user->ID || is_forum_moderator()))
		{

			$content = sql_ready($content);

			$sql = "UPDATE ".$wpdb->bbf_reply." SET content = '$content' WHERE ID = $reply_id";
			$wpdb->query($sql);
		}
		/*else
		{
			db('No');
			exit();
		}*/
		wp_redirect(get_permalink($current_post_id).'#'/*.$lastInsertID*/);
		exit();
	}
	else
	{
		$message = "You must specify your message.";
	}
}



/******************************************/
/***** Delete this post **********/
/******************************************/

if(isset($_GET['delete']) && is_numeric($_GET['delete']) && $_GET['delete'] >= 1 && is_forum_moderator() && $closed_topic == false)
{
	$sql = 'DELETE FROM '.$wpdb->bbf_reply.' WHERE ID = '.sql_ready($_GET['delete']);
	$wpdb->query($sql);
}

/******************************************/
/***** close and open the topic **********/
/******************************************/

if(isset($_GET['closed']) && is_numeric($_GET['closed']) && $_GET['closed'] == 1 && is_forum_moderator())
{
	$sql = 'UPDATE '.$wpdb->bbf_topic_meta.' SET topic_status=1 WHERE topic_id = '.$current_topic_id;
	$wpdb->query($sql);
	wp_redirect(get_the_permalink($current_topic_id));
	exit();
}

if(isset($_GET['open']) && is_numeric($_GET['open']) && $_GET['open'] == 1 && is_forum_moderator())
{
	$sql = 'UPDATE '.$wpdb->bbf_topic_meta.' SET topic_status=0 WHERE topic_id = '.$current_topic_id;
	$wpdb->query($sql);
	wp_redirect(get_the_permalink($current_topic_id));
	exit();
}

/******************************************/
/***** stick and unstick the topic **********/
/******************************************/

if(isset($_GET['sticky']) && is_numeric($_GET['sticky']) && $_GET['sticky'] == 1 && is_forum_moderator())
{
	$sql = 'UPDATE '.$wpdb->bbf_topic_meta.' SET topic_sticky=1 WHERE topic_id = '.$current_topic_id;
	$wpdb->query($sql);
	wp_redirect(get_the_permalink($current_topic_id));
	exit();
}

if(isset($_GET['unstick']) && is_numeric($_GET['unstick']) && $_GET['unstick'] == 1 && is_forum_moderator())
{
	$sql = 'UPDATE '.$wpdb->bbf_topic_meta.' SET topic_sticky=0 WHERE topic_id = '.$current_topic_id;
	$wpdb->query($sql);
	wp_redirect(get_the_permalink($current_topic_id));
	exit();
}
