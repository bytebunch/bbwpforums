<?php
global $current_user, $BBFThemeOptions;
$bbforums_version = $BBFThemeOptions->get_bbf_theme_option('theme_version');
$installed_ver = get_option('bbforums_version');
if ( $installed_ver != $bbforums_version ) {

 // installation of tables start from here
 function install_db_tables () {
   global $wpdb;
   $charset_collate = $wpdb->get_charset_collate();

   $sql = "CREATE TABLE ".$wpdb->bbf_forum_meta." (
     ID bigint(20) NOT NULL AUTO_INCREMENT,
     forum_id bigint(20) NOT NULL,
     forum_type tinyint(1) NOT NULL,
     forum_posts bigint(20) NOT NULL,
     forum_topics bigint(20) NOT NULL,
     forum_last_topic_id bigint(20) NOT NULL,
     last_topic_title text NOT NULL,
     forum_last_post_id bigint(20) NOT NULL,
     last_post_time datetime NOT NULL,
     forum_last_poster_id bigint(20) NOT NULL,
     last_poster_name varchar(250) NOT NULL,
     external_link text NOT NULL,
     forum_status tinyint(1) NOT NULL,
     PRIMARY KEY  (ID)
     ) $charset_collate;";
   $sql .= "CREATE TABLE ".$wpdb->bbf_livechat." (
     ID bigint(20) NOT NULL AUTO_INCREMENT,
     user_id bigint(20) NOT NULL,
     user_name varchar(250) NOT NULL,
     message text NOT NULL,
     time datetime NOT NULL,
     PRIMARY KEY  (ID)
   ) $charset_collate;";
   $sql .= "CREATE TABLE ".$wpdb->bbf_messages." (
   ID bigint(20) NOT NULL AUTO_INCREMENT,
   author_id bigint(20) NOT NULL,
   author_name varchar(50) NOT NULL,
   user_id bigint(20) NOT NULL,
   user_name varchar(50) NOT NULL,
   subject varchar(250) NOT NULL,
   message text NOT NULL,
   time datetime NOT NULL,
   read_status tinyint(1) NOT NULL,
   deleted tinyint(1) NOT NULL,
   PRIMARY KEY  (ID)
   ) $charset_collate;";
   $sql .= "CREATE TABLE ".$wpdb->bbf_reply." (
   ID bigint(20) NOT NULL AUTO_INCREMENT,
   forum_id bigint(20) NOT NULL,
   topic_id bigint(20) NOT NULL,
   user_id bigint(20) NOT NULL,
   date datetime NOT NULL,
   content text NOT NULL,
   PRIMARY KEY  (ID)
   ) $charset_collate;";
   $sql .= "CREATE TABLE ".$wpdb->bbf_topic_meta." (
   ID bigint(20) NOT NULL AUTO_INCREMENT,
   topic_id bigint(20) NOT NULL,
   topic_status tinyint(1) NOT NULL,
   topic_sticky tinyint(1) NOT NULL,
   topic_posts bigint(20) NOT NULL,
   topic_last_post_id bigint(20) NOT NULL,
   topic_last_poster_id bigint(20) NOT NULL,
   last_poster_name varchar(250) NOT NULL,
   last_post_time datetime NOT NULL,
   PRIMARY KEY  (ID)
   ) $charset_collate;";
   $sql .= "CREATE TABLE ".$wpdb->bbf_visitcounter." (
   ID bigint(20) NOT NULL AUTO_INCREMENT,
   ip varchar(25) NOT NULL,
   lastvisit varchar(25) NOT NULL,
   membetype varchar(15) NOT NULL,
   membername varchar(50) NOT NULL,
   memberid bigint(20) NOT NULL,
   PRIMARY KEY  (ID)
   ) $charset_collate;";

     require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
     dbDelta( $sql );
 }

 install_db_tables();
 update_option( 'bbforums_version', $bbforums_version );
}

//Theme Home Page settings
$bbf_home_page_settings  = array(
  "bbf_theme_logo" =>
  array(
    "meta_key" => "bbf_theme_logo",
    "field_title" => "Logo",
    "field_type" => "image",
    "field_duplicate" => ""
  ),
  "bbf_home_page_content" =>
  array(
    "meta_key" => "bbf_home_page_content",
    "field_title" => "Home Page Content",
    "field_type" => "editor",
    "field_duplicate" => ""
  ),
  "bbf_footer_copyright_text" =>
  array(
    "meta_key" => "bbf_footer_copyright_text",
    "field_title" => "Footer Copyright Text",
    "field_type" => "editor",
    "field_duplicate" => ""
  )
);
update_option( 'bbf_home_page_settings', ArrayToSerializeString($bbf_home_page_settings));


// check if uploads folder exist
if(!is_dir(ABSPATH."wp-content/uploads"))
 mkdir(ABSPATH."wp-content/uploads");
if(!is_dir(ABSPATH."wp-content/uploads/users"))
 mkdir(ABSPATH."wp-content/uploads/users");



// create new topic as post for new topic page urlencode
//db(get_page_by_path("new",ARRAY_A,TOPIC_PT));
//exit();
if(!get_page_by_path("new",ARRAY_A,TOPIC_PT)){
 $my_post = array(
  'post_title' => 'New',
  'post_content' => "Don't delete this topic.",
  'post_status' => 'publish',
  'post_author' => $current_user->ID,
  'post_type' => TOPIC_PT,
  'comment_status' => 'closed'
 );
 wp_insert_post( $my_post );
}

// create all the necessory pages
$my_post = array(
 'post_title' => 'New Page',
 'post_content' => "",
 'post_status' => 'publish',
 'post_author' => $current_user->ID,
 'post_type' => 'page',
 'comment_status' => 'closed'
);


// contact us page
$get_page = $BBFThemeOptions->get_bbf_theme_option('page_contact_us_id');
if(!($get_page && is_numeric($get_page) && $get_page >= 1 && get_permalink($get_page))){
  $get_page = NULL;
  if($get_page = get_page_by_path("contact-us"))
    $BBFThemeOptions->set_bbf_theme_option('page_contact_us_id', $get_page->ID);
  elseif($get_page = get_page_by_title("Contact us"))
    $BBFThemeOptions->set_bbf_theme_option('page_contact_us_id', $get_page->ID);
  else{
    $my_post['post_title'] = 'Contact us';
    $my_post['post_content'] = '';
    $my_post['page_template'] = 'page-contact-us.php';
    $postid = wp_insert_post( $my_post );
    $BBFThemeOptions->set_bbf_theme_option('page_contact_us_id', $postid);
  }
}

// Privacy Policy
$get_page = $BBFThemeOptions->get_bbf_theme_option('page_privacy_policy_id');
if(!($get_page && is_numeric($get_page) && $get_page >= 1 && get_permalink($get_page))){
  $get_page = NULL;
  if($get_page = get_page_by_path("privacy-policy"))
    $BBFThemeOptions->set_bbf_theme_option('page_privacy_policy_id', $get_page->ID);
  elseif($get_page = get_page_by_title("Privacy policy"))
    $BBFThemeOptions->set_bbf_theme_option('page_privacy_policy_id', $get_page->ID);
  else{
    $my_post['post_title'] = 'Privacy policy';
    $my_post['post_content'] = '<h3>Coming Soon</h3>';
    $my_post['page_template'] = '';
    $postid = wp_insert_post( $my_post );
    $BBFThemeOptions->set_bbf_theme_option('page_privacy_policy_id', $postid);
  }
}


// Terms of use page
$get_page = $BBFThemeOptions->get_bbf_theme_option('page_terms_of_use_id');
if(!($get_page && is_numeric($get_page) && $get_page >= 1 && get_permalink($get_page))){
  $get_page = NULL;
  if($get_page = get_page_by_path("terms-of-use"))
    $BBFThemeOptions->set_bbf_theme_option('page_terms_of_use_id', $get_page->ID);
  elseif($get_page = get_page_by_title("Terms of use"))
    $BBFThemeOptions->set_bbf_theme_option('page_terms_of_use_id', $get_page->ID);
  else{
    $my_post['post_title'] = 'Terms of use';
    $my_post['post_content'] = '<h3>Coming Soon</h3>';
    $my_post['page_template'] = '';
    $postid = wp_insert_post( $my_post );
    $BBFThemeOptions->set_bbf_theme_option('page_terms_of_use_id', $postid);
  }
}

// Verify page
$get_page = $BBFThemeOptions->get_bbf_theme_option('page_verify_id');
if(!($get_page && is_numeric($get_page) && $get_page >= 1 && get_permalink($get_page))){
  $get_page = NULL;
  if($get_page = get_page_by_path("verify"))
    $BBFThemeOptions->set_bbf_theme_option('page_verify_id', $get_page->ID);
  elseif($get_page = get_page_by_title("Verify"))
    $BBFThemeOptions->set_bbf_theme_option('page_verify_id', $get_page->ID);
  else{
    $my_post['post_title'] = 'Verify';
    $my_post['post_content'] = '';
    $my_post['page_template'] = 'page-verify.php';
    $postid = wp_insert_post( $my_post );
    $BBFThemeOptions->set_bbf_theme_option('page_verify_id', $postid);
  }
}


// Login page
$get_page = $BBFThemeOptions->get_bbf_theme_option('page_login_id');
if(!($get_page && is_numeric($get_page) && $get_page >= 1 && get_permalink($get_page))){
  $get_page = NULL;
  if($get_page = get_page_by_path("login"))
    $BBFThemeOptions->set_bbf_theme_option('page_login_id', $get_page->ID);
  elseif($get_page = get_page_by_title("Login"))
    $BBFThemeOptions->set_bbf_theme_option('page_login_id', $get_page->ID);
  elseif($get_page = get_page_by_title("Sign in"))
    $BBFThemeOptions->set_bbf_theme_option('page_login_id', $get_page->ID);
  elseif($get_page = get_page_by_title("Signin"))
    $BBFThemeOptions->set_bbf_theme_option('page_login_id', $get_page->ID);
  else{
    $my_post['post_title'] = 'Login';
    $my_post['post_content'] = '';
    $my_post['page_template'] = 'page-login.php';
    $postid = wp_insert_post( $my_post );
    $BBFThemeOptions->set_bbf_theme_option('page_login_id', $postid);
  }
}


// Forgot password page
$get_page = $BBFThemeOptions->get_bbf_theme_option('page_forgot_password_id');
if(!($get_page && is_numeric($get_page) && $get_page >= 1 && get_permalink($get_page))){
  $get_page = NULL;
  if($get_page = get_page_by_path("forgot-password"))
    $BBFThemeOptions->set_bbf_theme_option('page_forgot_password_id', $get_page->ID);
  elseif($get_page = get_page_by_title("Forgot password"))
    $BBFThemeOptions->set_bbf_theme_option('page_forgot_password_id', $get_page->ID);
  elseif($get_page = get_page_by_title("Forgot your password"))
    $BBFThemeOptions->set_bbf_theme_option('page_forgot_password_id', $get_page->ID);
  else{
    $my_post['post_title'] = 'Forgot password';
    $my_post['post_content'] = '';
    $my_post['page_template'] = 'page-forgot-password.php';
    $postid = wp_insert_post( $my_post );
    $BBFThemeOptions->set_bbf_theme_option('page_forgot_password_id', $postid);
  }
}

// create account page
$get_page = $BBFThemeOptions->get_bbf_theme_option('page_register_id');
if(!($get_page && is_numeric($get_page) && $get_page >= 1 && get_permalink($get_page))){
  $get_page = NULL;
  if($get_page = get_page_by_path("register"))
    $BBFThemeOptions->set_bbf_theme_option('page_register_id', $get_page->ID);
  elseif($get_page = get_page_by_title("Register"))
    $BBFThemeOptions->set_bbf_theme_option('page_register_id', $get_page->ID);
  elseif($get_page = get_page_by_title("Create account"))
    $BBFThemeOptions->set_bbf_theme_option('page_register_id', $get_page->ID);
  elseif($get_page = get_page_by_title("Sign up"))
    $BBFThemeOptions->set_bbf_theme_option('page_register_id', $get_page->ID);
  elseif($get_page = get_page_by_path("signup"))
    $BBFThemeOptions->set_bbf_theme_option('page_register_id', $get_page->ID);
  else{
    $my_post['post_title'] = 'Register';
    $my_post['post_content'] = '';
    $my_post['page_template'] = 'page-register.php';
    $postid = wp_insert_post( $my_post );
    $BBFThemeOptions->set_bbf_theme_option('page_register_id', $postid);
  }
}


// users page
$get_page = $BBFThemeOptions->get_bbf_theme_option('page_users_id');
if(!($get_page && is_numeric($get_page) && $get_page >= 1 && get_permalink($get_page))){
  $get_page = NULL;
  if($get_page = get_page_by_path("users"))
    $BBFThemeOptions->set_bbf_theme_option('page_users_id', $get_page->ID);
  elseif($get_page = get_page_by_title("Users"))
    $BBFThemeOptions->set_bbf_theme_option('page_users_id', $get_page->ID);
  elseif($get_page = get_page_by_path("user-dashboard"))
    $BBFThemeOptions->set_bbf_theme_option('page_users_id', $get_page->ID);
  else{
    $my_post['post_title'] = 'Users';
    $my_post['post_content'] = '';
    $my_post['page_template'] = 'page-users.php';
    $postid = wp_insert_post( $my_post );
    $BBFThemeOptions->set_bbf_theme_option('page_users_id', $postid);
  }
}

// create emoticon page
$get_page = $BBFThemeOptions->get_bbf_theme_option('page_emoticons_id');
if(!($get_page && is_numeric($get_page) && $get_page >= 1 && get_permalink($get_page))){
  $content_emoticon = '<p>'.smileys_sk('all','page_emoticons').'</p>
  <p>This list of emoticons are based on skype. If you want me to add more emoticons in this list you can suggest.</p>';
  $my_post['post_title'] = 'Emoticons';
  $my_post['post_content'] = $content_emoticon;
  $my_post['page_template'] = '';
  $postid = wp_insert_post( $my_post );
  $BBFThemeOptions->set_bbf_theme_option('page_emoticons_id', $postid);
}
