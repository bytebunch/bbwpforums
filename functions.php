<?php
global $registered_logged_in_users, $unread_messages, $wpdb, $BBFThemeOptions;
define("HOME_URL",trailingslashit(home_url()));
define("THEME_URI",trailingslashit(get_template_directory_uri()));
define("THEME_ABS",trailingslashit(get_template_directory()));

$wpdb->bbf_forum_meta = $wpdb->prefix . 'bbf_forum_meta';
$wpdb->bbf_livechat = $wpdb->prefix . 'bbf_livechat';
$wpdb->bbf_messages = $wpdb->prefix . 'bbf_messages';
$wpdb->bbf_reply = $wpdb->prefix . 'bbf_reply';
$wpdb->bbf_topic_meta = $wpdb->prefix . 'bbf_topic_meta';
$wpdb->bbf_visitcounter = $wpdb->prefix . 'bbf_visitcounter';

/*------------------------------------*\
	External Modules/Files
\*------------------------------------*/

// Load any external files you have here
require_once('inc/generic_functions.php');
require_once('inc/classes/BBWoW.php');


if(!class_exists('BBWPSanitization')){
  require_once('inc/classes/BBWPSanitization.php');
  //require_once('D:\OneDrive\web\Misc\php\Tahir Code/classes/BBWPSanitization.php');
}

if(is_admin() && !class_exists('BBWPFieldTypes')){
  require_once('inc/classes/BBWPFieldTypes.php');
  //require_once('D:\OneDrive\web\Misc\php\Tahir Code/classes/BBWPFieldTypes.php');
}

require_once('inc/classes/BBFThemeOptions.php');
$BBFThemeOptions = new BBFThemeOptions();

define("USERS_URI",trailingslashit(get_permalink($BBFThemeOptions->get_bbf_theme_option('page_users_id'))));
define("USERS_SLUG",get_single_post_data($BBFThemeOptions->get_bbf_theme_option('page_users_id'),"post_name"));

require_once('inc/ajax_functions.php');
require_once('inc/delete_hooks.php');
require_once('forums/forums-functions.php');

if(is_admin()){
  require_once('inc/theme_options.php');
}



/*------------------------------------*\
	Theme Support
\*------------------------------------*/

if (!isset($content_width))
{
    $content_width = 900;
}

if (function_exists('add_theme_support'))
{
    add_theme_support( 'title-tag' );
    add_theme_support('menus');
    // Enables post and comment RSS feed links to head
    add_theme_support('automatic-feed-links');
    // Add Thumbnail Theme Support
    add_theme_support('post-thumbnails');
    // Localisation Support
    load_theme_textdomain('bbblank', get_template_directory() . '/languages');
}

/*------------------------------------*\
	Functions
\*------------------------------------*/

// HTML5 Blank navigation
function html5blank_nav()
{
	wp_nav_menu(
	array(
		'theme_location'  => 'header-menu',
		'menu'            => '',
		'container'       => 'div',
		'container_class' => 'menu-{menu slug}-container',
		'container_id'    => '',
		'menu_class'      => 'menu',
		'menu_id'         => '',
		'echo'            => true,
		'fallback_cb'     => 'wp_page_menu',
		'before'          => '',
		'after'           => '',
		'link_before'     => '',
		'link_after'      => '',
		'items_wrap'      => '<ul>%3$s</ul>',
		'depth'           => 0,
		'walker'          => ''
		)
	);
}

add_action('wp_enqueue_scripts', 'html5blank_scripts_styles'); // Add Custom Scripts to wp_head
// Load HTML5 Blank scripts (header.php)
function html5blank_scripts_styles()
{
  global $wp_query, $BBFThemeOptions;

  // css syles
  wp_register_style('normalize', THEME_URI . 'normalize.min.css', array(), '3.0.3', 'all');
  wp_enqueue_style('normalize'); // Enqueue it!

  wp_register_style('bootstrap3.3.6.css', THEME_URI . 'bootstrap.min.css', array(), '3.3.6');
  wp_enqueue_style('bootstrap3.3.6.css'); // Enqueue it!

  wp_register_style('style.css', THEME_URI . 'style.css', array(), '1.0', 'all');
  wp_enqueue_style('style.css'); // Enqueue it!


  // javascript
  //wp_register_script('conditionizr', get_template_directory_uri() . '/js/lib/conditionizr-4.3.0.min.js', array(), '4.3.0'); // Conditionizr
  //wp_enqueue_script('conditionizr'); // Enqueue it!

  //wp_register_script('modernizr', get_template_directory_uri() . '/js/lib/modernizr-2.7.1.min.js', array(), '2.7.1'); // Modernizr
  //wp_enqueue_script('modernizr'); // Enqueue it!

  if(is_page('contact-us'))
	{
		wp_enqueue_script( 'maps', 'http://maps.googleapis.com/maps/api/js?sensor=false', array('jquery'));
	}

  // js scripts
  if($BBFThemeOptions->get_bbf_theme_option('nodejs_live_chat') == 1){
    wp_register_script('socket.io', THEME_URI . 'js/lib/socket.io.client.js', array('jquery'), '1.0.0',true);
    wp_enqueue_script('socket.io');
  }


  wp_register_script('bootstrap3.3.6.js', THEME_URI . 'js/lib/bootstrap.min.js', array('jquery'), '3.3.6'); // Custom scripts
  wp_enqueue_script('bootstrap3.3.6.js'); // Enqueue it!

  wp_register_script('bbfscripts', THEME_URI . 'js/custom.js', array('jquery'), '1.0.0'); // Custom scripts
  wp_enqueue_script('bbfscripts'); // Enqueue it!

  //$js_variables = array('ajax_url' => admin_url('admin-ajax.php'), 'theme_uri' => THEME_URI);
  //wp_localize_script( 'bbblogscripts', 'bbblog', $js_variables );
}


add_action('admin_enqueue_scripts', 'adminpanel_scripts_styles'); // Add Theme Stylesheet
// Load HTML5 Blank styles
function adminpanel_scripts_styles()
{
  // admin css syles
  global $wp_scripts;
  $ui = $wp_scripts->query('jquery-ui-core');

  wp_enqueue_script('uploads');
  wp_enqueue_media();

  if (is_ssl())
    $url = "https://code.jquery.com/ui/{$ui->ver}/themes/smoothness/jquery-ui.min.css";
  else
    $url = "http://code.jquery.com/ui/{$ui->ver}/themes/smoothness/jquery-ui.min.css";

  wp_register_style( 'jquery-ui', $url, array(), $ui->ver);
  wp_enqueue_style('jquery-ui');


  wp_register_style( 'admin_css', THEME_URI . 'admin.css', array('wp-color-picker'), '1.0.0' );
  wp_enqueue_style('admin_css');

  // admin javascript
  wp_register_script( 'adminscript', THEME_URI . 'js/admin.js', array('jquery', 'jquery-ui-sortable' ,'jquery-ui-datepicker', 'wp-color-picker'), '1.0.0' );
  wp_enqueue_script( 'adminscript' );

  //wp_register_script('adminscript', get_template_directory_uri() . '/js/admin.js', array('jquery'), '1.0.0'); // Custom scripts
  //wp_enqueue_script('adminscript'); // Enqueue it!
}

add_action('init', 'register_html5_menu'); // Add HTML5 Blank Menu
// Register HTML5 Blank Navigation
function register_html5_menu()
{
    register_nav_menus(array( // Using array to specify more menus if needed
        'header-menu' => __('Header Menu', 'bbblank'), // Main Navigation
        'sidebar-menu' => __('Sidebar Menu', 'bbblank'), // Sidebar Navigation
        'extra-menu' => __('Extra Menu', 'bbblank') // Extra Navigation if needed (duplicate as many as you need!)
    ));
}

add_filter('wp_nav_menu_args', 'my_wp_nav_menu_args'); // Remove surrounding <div> from WP Navigation
// Remove the <div> surrounding the dynamic navigation to cleanup markup
function my_wp_nav_menu_args($args = '')
{
    $args['container'] = false;
    return $args;
}

// add_filter('nav_menu_css_class', 'my_css_attributes_filter', 100, 1); // Remove Navigation <li> injected classes (Commented out by default)
// add_filter('nav_menu_item_id', 'my_css_attributes_filter', 100, 1); // Remove Navigation <li> injected ID (Commented out by default)
// add_filter('page_css_class', 'my_css_attributes_filter', 100, 1); // Remove Navigation <li> Page ID's (Commented out by default)
// Remove Injected classes, ID's and Page ID's from Navigation <li> items
/*function my_css_attributes_filter($var)
{
    return is_array($var) ? array() : '';
}*/

add_filter('the_category', 'remove_category_rel_from_category_list'); // Remove invalid rel attribute
// Remove invalid rel attribute values in the categorylist
function remove_category_rel_from_category_list($thelist)
{
    return str_replace('rel="category tag"', 'rel="tag"', $thelist);
}

add_filter('body_class', 'add_slug_to_body_class'); // Add slug to body class (Starkers build)
// Add page slug to body class, love this - Credit: Starkers Wordpress Theme
function add_slug_to_body_class($classes)
{
    global $post;
    if (is_home()) {
        $key = array_search('blog', $classes);
        if ($key > -1) {
            unset($classes[$key]);
        }
    } elseif (is_page()) {
        $classes[] = sanitize_html_class($post->post_name);
    } elseif (is_singular()) {
        $classes[] = sanitize_html_class($post->post_name);
    }

    return $classes;
}

// If Dynamic Sidebar Exists
if (function_exists('register_sidebar'))
{
    // Define Sidebar Widget Area 1
    register_sidebar(array(
        'name' => __('Widget Area 1', 'bbblank'),
        'description' => __('Description for this widget-area...', 'bbblank'),
        'id' => 'widget-area-1',
        'before_widget' => '<div id="%1$s" class="%2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3>',
        'after_title' => '</h3>'
    ));

    // Define Sidebar Widget Area 2
    register_sidebar(array(
        'name' => __('Widget Area 2', 'bbblank'),
        'description' => __('Description for this widget-area...', 'bbblank'),
        'id' => 'widget-area-2',
        'before_widget' => '<div id="%1$s" class="%2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3>',
        'after_title' => '</h3>'
    ));
}

add_action('widgets_init', 'my_remove_recent_comments_style'); // Remove inline Recent Comment Styles from wp_head()
// Remove wp_head() injected Recent Comment styles
function my_remove_recent_comments_style()
{
    global $wp_widget_factory;
    remove_action('wp_head', array(
        $wp_widget_factory->widgets['WP_Widget_Recent_Comments'],
        'recent_comments_style'
    ));
}

add_action('init', 'html5wp_pagination'); // Add our HTML5 Pagination
// Pagination for paged posts, Page 1, Page 2, Page 3, with Next and Previous Links, No plugin
function html5wp_pagination()
{
    global $wp_query;
    $big = 999999999;
    echo paginate_links(array(
        'base' => str_replace($big, '%#%', get_pagenum_link($big)),
        'format' => '?paged=%#%',
        'current' => max(1, get_query_var('paged')),
        'total' => $wp_query->max_num_pages
    ));
}

// Custom Excerpts
function html5wp_index($length) // Create 20 Word Callback for Index page Excerpts, call using html5wp_excerpt('html5wp_index');
{
    return 20;
}

// Create 40 Word Callback for Custom Post Excerpts, call using html5wp_excerpt('html5wp_custom_post');
function html5wp_custom_post($length)
{
    return 40;
}

// Create the Custom Excerpts callback
function html5wp_excerpt($length_callback = '', $more_callback = '')
{
    global $post;
    if (function_exists($length_callback)) {
        add_filter('excerpt_length', $length_callback);
    }
    if (function_exists($more_callback)) {
        add_filter('excerpt_more', $more_callback);
    }
    $output = get_the_excerpt();
    $output = apply_filters('wptexturize', $output);
    $output = apply_filters('convert_chars', $output);
    $output = '<p>' . $output . '</p>';
    echo $output;
}

add_filter('excerpt_more', 'html5_blank_view_article'); // Add 'View Article' button instead of [...] for Excerpts
// Custom View Article link to Post
function html5_blank_view_article($more)
{
    global $post;
    return '... <a class="view-article" href="' . get_permalink($post->ID) . '">' . __('View Article', 'bbblank') . '</a>';
}

add_filter('show_admin_bar', 'remove_admin_bar'); // Remove Admin bar
// Remove Admin bar
function remove_admin_bar()
{
    return false;
}

add_filter('style_loader_tag', 'html5_style_remove'); // Remove 'text/css' from enqueued stylesheet
// Remove 'text/css' from our enqueued stylesheet
function html5_style_remove($tag)
{
    return preg_replace('~\s+type=["\'][^"\']++["\']~', '', $tag);
}

add_filter('post_thumbnail_html', 'remove_thumbnail_dimensions', 10); // Remove width and height dynamic attributes to thumbnails
add_filter('image_send_to_editor', 'remove_thumbnail_dimensions', 10); // Remove width and height dynamic attributes to post images
// Remove thumbnail width and height dimensions that prevent fluid images in the_thumbnail
function remove_thumbnail_dimensions( $html )
{
    $html = preg_replace('/(width|height)=\"\d*\"\s/', "", $html);
    return $html;
}

add_filter('avatar_defaults', 'html5blankgravatar'); // Custom Gravatar in Settings > Discussion
// Custom Gravatar in Settings > Discussion
function html5blankgravatar ($avatar_defaults)
{
    $myavatar = get_template_directory_uri() . '/img/gravatar.jpg';
    $avatar_defaults[$myavatar] = "Custom Gravatar";
    return $avatar_defaults;
}

add_action('get_header', 'enable_threaded_comments'); // Enable Threaded Comments
// Threaded Comments
function enable_threaded_comments()
{
    if (!is_admin()) {
        if (is_singular() AND comments_open() AND (get_option('thread_comments') == 1)) {
            wp_enqueue_script('comment-reply');
        }
    }
}

// Custom Comments Callback
function html5blankcomments($comment, $args, $depth)
{
	$GLOBALS['comment'] = $comment;
	extract($args, EXTR_SKIP);

	if ( 'div' == $args['style'] ) {
		$tag = 'div';
		$add_below = 'comment';
	} else {
		$tag = 'li';
		$add_below = 'div-comment';
	}
?>
    <!-- heads up: starting < for the html tag (li or div) in the next line: -->
    <<?php echo $tag ?> <?php comment_class(empty( $args['has_children'] ) ? '' : 'parent') ?> id="comment-<?php comment_ID() ?>">
	<?php if ( 'div' != $args['style'] ) : ?>
	<div id="div-comment-<?php comment_ID() ?>" class="comment-body">
	<?php endif; ?>
	<div class="comment-author vcard">
	<?php if (isset($args['avatar_size']) && $args['avatar_size'] != 0 && is_numeric($args['avatar_size'])){ echo get_avatar( $comment, $args['avatar_size'] ); } ?>
	<?php printf(__('<cite class="fn">%s</cite> <span class="says">says:</span>', 'bbblank'), get_comment_author_link()) ?>
	</div>
<?php if ($comment->comment_approved == '0') : ?>
	<em class="comment-awaiting-moderation"><?php _e('Your comment is awaiting moderation.', 'bbblank') ?></em>
	<br />
<?php endif; ?>

	<div class="comment-meta commentmetadata"><a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ) ?>">
		<?php
			printf( __('%1$s at %2$s', 'bbblank'), get_comment_date(),  get_comment_time()) ?></a><?php edit_comment_link(__('(Edit)', 'bbblank'),'  ','' );
		?>
	</div>

	<?php comment_text() ?>

	<div class="reply">
	<?php comment_reply_link(array_merge( $args, array('add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
	</div>
	<?php if ( 'div' != $args['style'] ) : ?>
	</div>
	<?php endif; ?>
<?php }

/*------------------------------------*\
	Actions + Filters + ShortCodes
\*------------------------------------*/

// Remove Actions
remove_action('wp_head', 'feed_links_extra', 3); // Display the links to the extra feeds such as category feeds
remove_action('wp_head', 'feed_links', 2); // Display the links to the general feeds: Post and Comment Feed
remove_action('wp_head', 'rsd_link'); // Display the link to the Really Simple Discovery service endpoint, EditURI link
remove_action('wp_head', 'wlwmanifest_link'); // Display the link to the Windows Live Writer manifest file.
remove_action('wp_head', 'index_rel_link'); // Index link
remove_action('wp_head', 'parent_post_rel_link', 10, 0); // Prev link
remove_action('wp_head', 'start_post_rel_link', 10, 0); // Start link
remove_action('wp_head', 'adjacent_posts_rel_link', 10, 0); // Display relational links for the posts adjacent to the current post.
remove_action('wp_head', 'wp_generator'); // Display the XHTML generator that is generated on the wp_head hook, WP version
remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
//remove_action('wp_head', 'rel_canonical');
remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);

// Add Filters
add_filter('widget_text', 'do_shortcode'); // Allow shortcodes in Dynamic Sidebar
add_filter('widget_text', 'shortcode_unautop'); // Remove <p> tags in Dynamic Sidebars (better!)
add_filter('the_excerpt', 'shortcode_unautop'); // Remove auto <p> tags in Excerpt (Manual Excerpts only)
add_filter('the_excerpt', 'do_shortcode'); // Allows Shortcodes to be executed in Excerpt (Manual Excerpts only)

// Remove Filters
remove_filter('the_excerpt', 'wpautop'); // Remove <p> tags from Excerpt altogether

// Shortcodes above would be nested like this -
// [html5_shortcode_demo] [html5_shortcode_demo_2] Here's the page title! [/html5_shortcode_demo_2] [/html5_shortcode_demo]


/******************************************/
/***** Only administrator can get access to wpadmin **********/
/******************************************/
add_action( 'init', 'blockusers_init' );
function blockusers_init() {
	if ( is_admin() && ! current_user_can( 'administrator' ) &&
		! ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {
		wp_redirect( home_url() );
		exit;
	}
}

/******************************************/
/***** Error Update Message **********/
/******************************************/
function ErrorUpdateMessage($errorMessage = false, $updateMessage = false){
  if(isset($errorMessage) && $errorMessage != false){ ?>
    <div class="<?php if(isset($updateMessage) && $updateMessage != false){ echo 'update_message'; }else{ echo 'error_message'; } ?>"><?php echo $errorMessage; ?></div>
  <?php }
}


/******************************************/
/***** check if user's email is verified. **********/
/******************************************/
add_filter('wp_authenticate_user', 'bb_email_verify_auth_login',10,2);
function bb_email_verify_auth_login ($user, $password) {
	global $BBFThemeOptions;
	if ( !is_wp_error($user) &&  $BBFThemeOptions->get_option('verify_email') == 'yes' && $user->roles[0] != 'administrator'){
    $verify_email = get_user_meta($user->ID,'bb_verify_email',true);
		if($verify_email && $verify_email != "verified")
      return new WP_Error( 'verify_email', 'Please verify your email first.', $user );
	}
  return $user;
}



/******************************************/
/***** get the list of current logged in users **********/
/******************************************/

function get_current_logged_in_users()
{

	global $wpdb, $registered_logged_in_users;
	if(!$registered_logged_in_users)
	{
		$sql = "select membername, memberid from ".$wpdb->bbf_visitcounter." where membetype = 'member'";
		$registered_logged_in_users = $wpdb->get_results($sql, ARRAY_A);
	}

	return $registered_logged_in_users;
}


/******************************************/
/***** get avatar image start from here **********/
/******************************************/

add_filter( 'get_avatar', 'avatar_override_by_bb', 10, 5 );
function avatar_override_by_bb( $avatar, $id_or_email, $size, $default, $alt ) {
	$custom_avatar = "<img alt='' src='".get_template_directory_uri()."/images/profile_placeholder.png' class='avatar avatar-$size photo' height='$size' width='$size' />";
		//Get user data
		if ( is_numeric( $id_or_email ) ) {
			$user = get_user_by( 'id', ( int )$id_or_email );
		} elseif( is_object( $id_or_email ) )  {
			$comment = $id_or_email;
			if ( empty( $comment->user_id ) ) {
				$user = get_user_by( 'id', $comment->user_id );
			} else {
				$user = get_user_by( 'email', $comment->comment_author_email );
			}
			if ( !$user ) return $custom_avatar;
		} elseif( is_string( $id_or_email ) ) {
			$user = get_user_by( 'email', $id_or_email );
		} else {
			return $custom_avatar;
		}
		if ( !$user ) return $custom_avatar;
		$user_id = $user->ID;


		$porfile_image_meta_key = "profile_image_url";
		if(get_user_meta($user_id, $porfile_image_meta_key, true) && get_user_meta($user_id, $porfile_image_meta_key, true) != "")
		{
			$custom_avatar = "<img alt='".get_user_meta($user_id, "display_name", true)."' src='".get_user_meta($user_id, $porfile_image_meta_key, true)."' class='avatar avatar-$size photo' height='$size' width='$size' />";
		}

		if ( !isset($custom_avatar) ) return $avatar;

		return $custom_avatar;
} //end avatar_override


/******************************************/
/***** useer profile functions starts from here **********/
/******************************************/


function delete_user_profile_image($user_id)
{
	if($image_relative_path = get_user_meta($user_id, 'profile_image_url', true))
	{
		$image_abs_path = str_replace(get_bloginfo('url')."/",ABSPATH,$image_relative_path);
		if(file_exists($image_abs_path))
		{
			unlink($image_abs_path);
			update_user_meta($user_id,'profile_image_url','');
		}
	}
}

function get_user_profile_image_url($user_id)
{
	$image_url = THEME_URI.'/images/profile_placeholder.png';
	if($image_relative_path = get_user_meta($user_id, 'profile_image_url', true))
	{
		$image_url = $image_relative_path;
	}
	return $image_url;
}


/******************************************/
/***** Live Chat box **********/
/******************************************/

function get_chat_box_messages()
{
	global $wpdb;
	$output = '';
	$sql = 'SELECT * from '.$wpdb->bbf_livechat.' ORDER BY time DESC LIMIT 50';
	$results = $wpdb->get_results($sql, ARRAY_A);
	if($results)
	{
		$i = 1;

		$output .= '<ul>';
		foreach($results as $result)
		{
			if($i%2 == 0)
			{
				$class = 'even';
			}else
			{
				$class = 'odd';
			}
			$i++;
			$output .= '<li class="'.$class.'">
                    <div class="col-sm-2 live_chat_profile"><a href="'.USERS_URI.$result['user_id'].'">'.$result['user_name'].'</a></div>
                    <div class="col-sm-10 live_chat_body">
                    <!-- <p class="posted_date">'.human_time_diff(strtotime($result['time']), current_time( 'timestamp')).' ago</p> -->
                        <p class="posted_date">'.date('F d, Y H:i:s',strtotime($result['time'])).'</p>
                        <p class="posted_conent">'.$result['message'].'</p>
                    </div>
                    <div class="clearboth"></div>
                </li>';
		}
		$output .= '</ul>';
	}
	return $output;
}

//=======================================================================//
//  FUNCTION: Live chat box                                           //
//========================= start OF FUNCTION ===========================//
function live_chat_box()
{
	if(is_user_logged_in()){
	global $current_user, $BBFThemeOptions;
 ?>
<div class="live_chat_box">
<form action="" method="post" class="live_chat_form">
<input type="hidden" name="user_id" value="<?php echo $current_user->data->ID; ?>" class="user_id" />
    <div class="bbf_head">
        Live Chat
    </div>

    <div class="dark_box_header">
      <div class="col-sm-2 col-md-2 col-lg-2" style="line-height:34px;"><strong>Message:</strong></div>
      <div class="col-sm-7 col-md-8 col-lg-8"><input type="text" name="live_chat_message" id="live_chat_message" class="live_chat_message" /></div>
      <div class="col-sm-3 col-md-2 col-lg-2"><input type="submit" value="Submit" class="live_chat_submit" style="height:34px;width:100%;" /></div>
      <div class="clearboth"></div>
      <?php /* <strong>Message:</strong> <input type="text" name="live_chat_message" id="live_chat_message" class="live_chat_message" /> <input type="submit" value="Submit" class="live_chat_submit" /> */ ?>
    </div>
    <div class="live_caht_box_content_container">
        <div class="col-sm-12 col-md-10 live_chat_box_content">
            <?php echo get_chat_box_messages(); ?>
        </div>
        <div class="col-sm-2 live_chat_box_active_users hidden-xs hidden-sm">
          <?php
        		$current_logged_users = get_current_logged_in_users();

        		if($current_logged_users)
        		{
        			echo '<ul>';
        			foreach($current_logged_users as $user)
        			{
        				echo '<li><a href="'.USERS_URI.$user['memberid'].'">'.$user['membername'].'</a></li>';
        			}
        			echo '</ul>';
        		}

        	?>
        </div>
        <div class="clearboth"></div>
    </div>
    <div class="dark_box_header" style="margin-bottom:20px; text-align:center;">
        <span class="display_none ajax_loader"><img src="<?php echo THEME_URI.'images/act_indicator.gif' ?>" alt="" /></span>
				<?php if($BBFThemeOptions->get_bbf_theme_option('nodejs_live_chat') != 1){ ?>
				Updates every 30 Seconds.
				<?php } ?>
				<a href="<?php echo get_permalink($BBFThemeOptions->get_bbf_theme_option('page_emoticons_id')); ?>">Click here</a> to see the list of allowed emoticons.
    </div>
</form>
</div>

<?php
  }
}


/******************************************/
/***** is moderator function start from here **********/
/******************************************/

function is_forum_moderator()
{

	if(is_user_logged_in())
	{
		global $current_user;

		$user_roles = $current_user->roles;
		$user_role = array_shift($user_roles);

		if($user_role == 'administrator')
		{
			return true;
		}else
		{
			return false;
		}

	}else
	{
		return false;
	}
}



//======================== START OF FUNCTION ==========================//
// FUNCTION: bbcode_to_html                                            //
//=====================================================================//
function bbcode_to_html($bbtext){
  $bbtags = array(
    '[heading1]' => '<h1>','[/heading1]' => '</h1>',
    '[heading2]' => '<h2>','[/heading2]' => '</h2>',
    '[heading3]' => '<h3>','[/heading3]' => '</h3>',
    '[h1]' => '<h1>','[/h1]' => '</h1>',
    '[h2]' => '<h2>','[/h2]' => '</h2>',
    '[h3]' => '<h3>','[/h3]' => '</h3>',

    '[paragraph]' => '<p>','[/paragraph]' => '</p>',
    '[para]' => '<p>','[/para]' => '</p>',
    '[p]' => '<p>','[/p]' => '</p>',
    '[left]' => '<p style="text-align:left;">','[/left]' => '</p>',
    '[right]' => '<p style="text-align:right;">','[/right]' => '</p>',
    '[center]' => '<p style="text-align:center;">','[/center]' => '</p>',
    '[justify]' => '<p style="text-align:justify;">','[/justify]' => '</p>',

    '[bold]' => '<span style="font-weight:bold;">','[/bold]' => '</span>',
    '[italic]' => '<span style="font-weight:bold;">','[/italic]' => '</span>',
    '[underline]' => '<span style="text-decoration:underline;">','[/underline]' => '</span>',
    '[b]' => '<span style="font-weight:bold;">','[/b]' => '</span>',
    '[i]' => '<span style="font-weight:bold;">','[/i]' => '</span>',
    '[u]' => '<span style="text-decoration:underline;">','[/u]' => '</span>',
    '[break]' => '<br>',
    '[br]' => '<br>',
    '[newline]' => '<br>',
    '[nl]' => '<br>',

    '[unordered_list]' => '<ul>','[/unordered_list]' => '</ul>',
    '[list]' => '<ul>','[/list]' => '</ul>',
    '[ul]' => '<ul>','[/ul]' => '</ul>',

    '[ordered_list]' => '<ol>','[/ordered_list]' => '</ol>',
    '[ol]' => '<ol>','[/ol]' => '</ol>',
    '[list_item]' => '<li>','[/list_item]' => '</li>',
    '[li]' => '<li>','[/li]' => '</li>',

    '[*]' => '<li>','[/*]' => '</li>',
    '[code]' => '<code>','[/code]' => '</code>',
    '[preformatted]' => '<pre>','[/preformatted]' => '</pre>',
    '[pre]' => '<pre>','[/pre]' => '</pre>',
	'[spoiler]' => '<div class="spoiler_container">Spoiler! <button class="orange_btn show_spoiler_button">Show</button><div class="spoiler_content">','[/spoiler]' => '</div></div>',
  );

  $bbtext = str_ireplace(array_keys($bbtags), array_values($bbtags), $bbtext);

  $bbextended = array(
    "/\[url](.*?)\[\/url]/i" => "<a href=\"http://$1\" title=\"$1\">$1</a>",
    "/\[url=(.*?)\](.*?)\[\/url\]/i" => "<a href=\"$1\" title=\"$1\">$2</a>",
    "/\[email=(.*?)\](.*?)\[\/email\]/i" => "<a href=\"mailto:$1\">$2</a>",
    "/\[mail=(.*?)\](.*?)\[\/mail\]/i" => "<a href=\"mailto:$1\">$2</a>",
    "/\[img\]([^[]*)\[\/img\]/i" => "<img src=\"$1\" alt=\" \" />",
    "/\[image\]([^[]*)\[\/image\]/i" => "<img src=\"$1\" alt=\" \" />",
    "/\[image_left\]([^[]*)\[\/image_left\]/i" => "<img src=\"$1\" alt=\" \" class=\"img_left\" />",
    "/\[image_right\]([^[]*)\[\/image_right\]/i" => "<img src=\"$1\" alt=\" \" class=\"img_right\" />",
  );

  foreach($bbextended as $match=>$replacement){
    $bbtext = preg_replace($match, $replacement, $bbtext);
  }
  return $bbtext;
}
//=====================================================================//
//  FUNCTION: bbcode_to_html                                           //
//========================= END OF FUNCTION ===========================//

//=======================================================================//
//  FUNCTION: Show messages                                           //
//========================= start OF FUNCTION ===========================//

function show_messages($showOnly, $per_page)
{
	global $current_user, $wpdb, $wp_query;
	$output = '';
	$message_author = 'Author';
	$message_link = USERS_URI.$current_user->ID.'/inbox/message/';

	$sql = 'SELECT * FROM '.$wpdb->bbf_messages.' WHERE ';

	if($showOnly == 'inbox')
	{
		$sql_pagination = "SELECT count(ID) as count FROM ".$wpdb->bbf_messages." WHERE deleted = 0 AND user_id = ".$current_user->ID;
		$sql .= "deleted = 0 AND user_id = ".$current_user->ID." ";
	}
	if($showOnly == 'sent')
	{
		$sql_pagination = "SELECT count(ID) as count FROM ".$wpdb->bbf_messages." WHERE author_id = ".$current_user->ID;
		$sql .= "deleted = 0 AND author_id = ".$current_user->ID." ";
		$message_author = 'Recipient';
		$message_link = USERS_URI.$current_user->ID.'/sent/message/';
	}


	$totalmessages = $wpdb->get_results($sql_pagination, ARRAY_A);

	if($totalmessages)
	{
		$totalmessages = $totalmessages[0]['count'];
	}else
	{
		$totalmessages = $per_page;
	}


	$offset = 0;
	$page_no = $wp_query->query_vars['paged'];


	$total_pages = ceil($totalmessages / $per_page );
	if($page_no >= 2)
	{
		$offset = $page_no -1;
		$offset = $per_page*$offset;
	}

	$sql .= "ORDER BY time DESC LIMIT $per_page OFFSET $offset";
	$results = $wpdb->get_results($sql, ARRAY_A);

	if($results)
	{
		pagination($total_pages, 3);

		$output .= '<center><table class="messages">';
		$output .= '<tr class="first_row"><td style="width:5%;">ID</td><td>Subject</td>
			<td style="width:10%;" class="hidden-xs">'.$message_author.'</td><td style="width:21%;" class="hidden-xs hidden-sm">Sent</td><td style="width:5%;">Mark</td></tr>';


		if($page_no >= 2)
		{
			$i = ($per_page*($page_no-1))+1;
		}else
		{
			$i = 1;
		}

		foreach($results as $result)
		{
			if($result['read_status'] == 0)
			{
				$readclass = 'no-read';
			}else
			{
				$readclass = 'readed';
			}

			$output .= '<tr class="'.$readclass.'"><td>'.$i.'</td>';
			$output .= '<td class="text-align-left"><a href="'.$message_link.$result['ID'].'">'.bb_substr($result['subject'], 100).'</a></td>';
			if($showOnly == 'inbox')
			{
				$output .= '<td class="hidden-xs">'.$result['author_name'].'</td>';
			}
			if($showOnly == 'sent')
			{
				$output .= '<td>'.$result['user_name'].'</td>';
			}
			$output .= '<td class="hidden-xs hidden-sm">'.date('D M d, Y h:i A', strtotime($result['time'])).'</td>';
			$output .= '<td><input type="checkbox" value="'.$result['ID'].'" name="delete_messages[]" /></td>';
			$output .= '</tr>';

			$i++;
		}
		$output .= '</table></center>';
		if($showOnly != 'sent')
		{
			$output .= '<div style="float:right; margin-top:20px;">
			<select name="mark_options" id="" style="display:inline-block; max-width:250px; width:150px;">
			<option value=""> -- Select -- </option>
			<option value="delete_marked">Delete Marked</option>
			</select>
			<input type="submit" value="Go" />
			</div>
			<div class="clearboth"></div>
			';
		}

	}else
	{
		$output = 'No messages found.';
	}


	echo $output;
  echo '<p></p>';
	pagination($total_pages, 3);
}



//=======================================================================//
//  FUNCTION: get unread messages                                        //
//========================= start OF FUNCTION ===========================//

function get_unread_messages()
{
	global $unread_messages;
	$unread_messages = 0;
	if(is_user_logged_in())
	{
		global $current_user, $wpdb;
		$sql = $wpdb->prepare('SELECT count(read_status) as count FROM '.$wpdb->bbf_messages.' WHERE user_id = %d AND deleted = 0 AND read_status = 0', $current_user->ID);

    $results = $wpdb->get_results($sql, ARRAY_A);
		if($results)
		{
			$unread_messages = $results[0]['count'];
		}
	}
}


/******************************************/
/***** installation of bbfourms theme **********/
/******************************************/
add_action("after_switch_theme", "install_bbforums_theme");
function install_bbforums_theme(){
	include_once(THEME_ABS.'inc/theme_installation.php');
}


if(is_user_logged_in() && !is_admin()){
  get_unread_messages();
}
