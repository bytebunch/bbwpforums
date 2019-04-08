<?php

/* ******************************************* */
/*  ajax functon for search listing start from here  */
/* ******************************************* */

add_action( 'wp_ajax_nopriv_livechat_ajax', 'livechat_ajax' );
add_action( 'wp_ajax_livechat_ajax', 'livechat_ajax' );

function livechat_ajax(){

	global $current_user, $wpdb, $BBFThemeOptions;
	$output = '';
	$current_mysql_time = current_time('mysql');
	if(isset($_POST['live_chat_message']))
	{
		$live_chat_message = smileys_sk('all',post_ready($_POST['live_chat_message']));
		if($live_chat_message != '' && $live_chat_message != " " && is_user_logged_in())
		{
			$live_chat_message = sql_ready(strip_tags($live_chat_message,'<img>'));


			$sql = "INSERT INTO ".$wpdb->bbf_livechat." (user_id, user_name, message, time) VALUES (".$current_user->data->ID.", '".$current_user->data->user_login."', '$live_chat_message', '$current_mysql_time')";
			$wpdb->query($sql);
			if($BBFThemeOptions->get_bbf_theme_option('nodejs_live_chat') == 1){

				include_once(THEME_ABS.'lib/elephant.io/autoload.php');

			}
		}
	}

	if($BBFThemeOptions->get_bbf_theme_option('nodejs_live_chat') != 1){
		$output = get_chat_box_messages();
	}
	unset($BBFThemeOptions);


	die($output);

}// livechat_ajax function end here
