<?php


add_action( 'init', 'register_bb_post_types' );
function register_bb_post_types()
{


/******************************************/
/***** Register forum post type **********/
/******************************************/
	$labels = array(
		"name" => "Forums",
		"menu_name" => "Forums",
		"singular_name" => "Forum",
		"all_items" => "All Forums",
		"add_new" => "New Forum",
		"add_new_item" => "Create New Forum",
		"edit" => "Edit",
		"edit_item" => "Edit Forum",
		"new_item" => "New Forum",
		"view" => "View Forum",
		"view_item" => "View Forum",
		"search_items" => "Search Forums",
		"not_found" => "No forums found",
		"not_found_in_trash" => "No forums found in Trash",
		"parent_item_colon" => "Parent Forum:"
	);
	/*$capb = array(
		"edit_posts" => "edit_forums",
		"edit_others_posts" => "edit_others_forums",
		"publish_posts" => "publish_forums",
		"read_private_posts" => "read_private_forums",
		"read_hidden_posts" => "read_hidden_forums",
		"delete_posts" => "delete_forums",
		"delete_others_posts" => "delete_others_forums"
	);*/

	$argss = array(
		'labels'              => $labels,
		'rewrite'             => array("slug" => "forums",'with_front' => true),
		'supports'            => array("title","editor","thumbnail"),
		'description'         => "BB Forums",
		//'capabilities'        => $capb,
		//'capability_type'     =>  array('forum', 'forums' ),
		'capability_type'     => 'post',
		//'menu_position'       => 555555,
		'has_archive'         => "forums",
		//'has_archive'         => true,
		'exclude_from_search' => true,
		'show_in_nav_menus'   => true,
		'public'              => true,
		'show_ui'             => true,
		'can_export'          => true,
		'hierarchical'        => true,
		'query_var'           => true,
		//'menu_icon'           => ''
	);

	register_post_type( FORUM_PT, $argss );






/******************************************/
/***** Register Topic post type **********/
/******************************************/
	$labels = array(
		"name" => "Topics",
		"menu_name" => "Topics",
		"singular_name" => "Topic",
		"all_items" => "All Topics",
		"add_new" => "New Topic",
		"add_new_item" => "Create New Topic",
		"edit" => "Edit",
		"edit_item" => "Edit Topic",
		"new_item" => "New Topic",
		"view" => "View Topic",
		"view_item" => "View Topic",
		"search_items" => "Search Topics",
		"not_found" => "No topics found",
		"not_found_in_trash" => "No topics found in Trash",
		"parent_item_colon" => "Forum"
	);

	$argss = array(
		'labels'              => $labels,
		'rewrite'             => array("slug" => "topic",'with_front' => true),
		'supports'            => array("title","editor","thumbnail"),
		'description'         => "BB Topics",
		//'capabilities'        => $capb,
		//'capability_type'     =>  array('forum', 'forums' ),
		'capability_type'     => 'post',
		//'menu_position'       => 555555,
		'has_archive'         => "topics",
		//'has_archive'         => true,
		'exclude_from_search' => false,
		'show_in_nav_menus'   => true,
		'public'              => true,
		'show_ui'             => true,
		'can_export'          => true,
		'hierarchical'        => false,
		'query_var'           => true,
		//'menu_icon'           => ''
	);

	register_post_type( TOPIC_PT, $argss );

	//exit();







	/*$labels = array(
		'name'               => _x( 'Books', 'post type general name', 'your-plugin-textdomain' ),
		'singular_name'      => _x( 'Book', 'post type singular name', 'your-plugin-textdomain' ),
		'menu_name'          => _x( 'Books', 'admin menu', 'your-plugin-textdomain' ),
		'name_admin_bar'     => _x( 'Book', 'add new on admin bar', 'your-plugin-textdomain' ),
		'add_new'            => _x( 'Add New', 'book', 'your-plugin-textdomain' ),
		'add_new_item'       => __( 'Add New Book', 'your-plugin-textdomain' ),
		'new_item'           => __( 'New Book', 'your-plugin-textdomain' ),
		'edit_item'          => __( 'Edit Book', 'your-plugin-textdomain' ),
		'view_item'          => __( 'View Book', 'your-plugin-textdomain' ),
		'all_items'          => __( 'All Books', 'your-plugin-textdomain' ),
		'search_items'       => __( 'Search Books', 'your-plugin-textdomain' ),
		'parent_item_colon'  => __( 'Parent Books:', 'your-plugin-textdomain' ),
		'not_found'          => __( 'No books found.', 'your-plugin-textdomain' ),
		'not_found_in_trash' => __( 'No books found in Trash.', 'your-plugin-textdomain' ),
	);

	$args = array(
		'labels'             => $labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'boo' ),
		'capability_type'    => 'post',
		//'has_archive'        => 'books',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => null,
		'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' )
	);

	register_post_type( 'book', $args );*/

}
