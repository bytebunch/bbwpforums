<!--<div class="clearboth"></div>
    <div class="forum_head">
    <div class="bbf_reply_author">Author</div>
    <div class="bbf_reply_content_container">Posts</div>
    <div class="clearboth"></div>
</div>-->
<?php
$sql = "SELECT count(ID) as count FROM ".$wpdb->bbf_reply." WHERE topic_id = $current_topic_id";
$totalPosts = $wpdb->get_results($sql, ARRAY_A);

if($totalPosts)
{
	$total_posts = $totalPosts[0]['count'];
}else
{
	$total_posts = 9;
}


$posts_per_page = 10;
$offset = 0;
$page_no = $wp_query->query_vars['paged'];
$topic_author_id = get_the_author_meta('ID');


$total_pages = ceil($total_posts / $posts_per_page );
if($page_no >= 2)
{
	$offset = $page_no -1;
	$offset = $posts_per_page*$offset;
}
//db($authordata);
//global $wpdb;
$sql = "SELECT reply.user_id, reply.date, reply.content, reply.ID, users.user_registered, users.ID as user_id, users.user_login FROM ".$wpdb->bbf_reply." as reply INNER JOIN ".$wpdb->users." as users ON reply.user_id=users.ID WHERE reply.topic_id = $current_topic_id ORDER BY reply.date ASC LIMIT $posts_per_page OFFSET $offset";

$results = $wpdb->get_results($sql);

?>
<h2><?php echo get_the_title(); ?></h2>
<?php
if($results){
	pagination($total_pages);
}?>
<ul class="no_mp bbf_replies_container">
<?php
if($page_no == 1 || $page_no == ""){
?>
<li class="bbf_reply_body">
	<div class="bbf_reply_header">
    	<span class="post_date"><?php echo get_the_date(); ?></span>
    	<div class="post_links pull-right">
         	<?php if(is_user_logged_in()){?>
					<a href="#this_topic_reply" class="bbf_reply_link" title="Reply">Reply</a>
				<?php
				if(($topic_author_id == get_current_user_id() || is_forum_moderator()) && $closed_topic == false){?>
                <a href="<?php echo get_permalink(); ?>edit" title="Edit" class="bbf_edit_link">Edit</a>
				<?php }

				 }else{?>
			<a href="<?php echo get_permalink($BBFThemeOptions->get_bbf_theme_option('page_login_id')); ?>?mode=1" class="bbf_reply_link" title="Reply">Reply</a>
			<?php }
			// topic close for moderator
			if(is_forum_moderator() && $closed_topic == false)
			{?>
            <a href="<?php echo get_permalink(); ?>?closed=1" title="Close this topic" class="bbf_close_link delete_this">Close this topic</a>
			<?php }
			if(is_forum_moderator() && $closed_topic == true)
			{?>
            <a href="<?php echo get_permalink(); ?>?open=1" title="Open this topic" class="bbf_open_link delete_this">Open this topic</a>
			<?php }
			// topic close for moderator
			if(is_forum_moderator() && $sticky_topic == false)
			{?>
            <a href="<?php echo get_permalink(); ?>?sticky=1" title="Make this topic sticky" class="bbf_sticky_topic_link">Stick this topic</a>
			<?php }
			if(is_forum_moderator() && $sticky_topic == true)
			{?>
            <a href="<?php echo get_permalink(); ?>?unstick=1" title="Unstick this topic" class="bbf_unstick_topic_link">Unstick this topic</a>
			<?php }


			 ?>

            <a href="#<?php echo $current_topic_id; ?>">#0</a>
        </div>
        <div class="clearboth"></div>
    </div>
	<div class="bbf_reply_author col-sm-4 col-md-3">
    <div class="user_info">
    <span class="username">
    <?php $author_display_name = get_the_author_meta('user_login'); ?>
    <a href="<?php echo USERS_URI.$topic_author_id; ?>"><?php echo $author_display_name; ?></a></span>
    <a href="<?php echo USERS_URI.$topic_author_id ?>" class="hidden-xs"><img src="<?php echo get_user_profile_image_url($topic_author_id); ?>" alt="" class="profile_image" /></a>
    <span><strong>Join Date:</strong> <?php echo date('F d, Y',strtotime(get_the_author_meta('user_registered'))); ?></span>

   <?php if($user_location = get_user_meta($topic_author_id,'location',true)){ ?>
	 <span><strong>Location: </strong><?php echo $user_location; ?></span>
    <?php } ?>


    <span><strong>Posts:</strong> <?php echo get_user_posts($topic_author_id);  ?></span>
    <?php if(is_user_logged_in()){?>
		<a href="<?php echo USERS_URI.$current_user->ID; ?>/compose/?to=<?php echo urlencode($author_display_name); ?>" class="orange_btn private_message_btn">PM</a>
        <a href="<?php echo USERS_URI.$current_user->ID; ?>/email/?eto=<?php echo urlencode($author_display_name); ?>" class="orange_btn email_btn">Email</a>
	<?php } ?>



    <?php /*?><span><strong>Have Thanks:</strong> 1</span>
    <span><strong>Has Thanked:</strong> 3 time</span><?php */?>
    </div>
    </div><!-- bbf_reply_author div end here-->
    <div class="bbf_reply_content_container col-sm-8 col-md-9">
    <h2 class="topic_title"><?php echo get_the_title(); ?></h2>
	<div class="bbf_reply_content">
	<?php
	$content = apply_filters( 'the_content', get_the_content() );
	$content = str_replace( ']]>', ']]&gt;', $content );
	echo bbcode_to_html($content); ?>
    </div>
    <div class="clearboth"></div>
        <?php get_user_signature_by_ID($topic_author_id); ?>
    </div><!-- bbf_reply_content_container div end here-->
    <div class="clearboth"></div>
</li>
<?php }
if($results)
{
	if($page_no >= 2)
{
	$i = (10*($page_no-1))+1;
}else
{
	$i = 1;
}

foreach($results as $result)
{
	//db($result);
 ?>
<li class="bbf_reply_body post_<?php echo $result->ID; ?>">
	<div class="bbf_reply_header">
    	<span class="post_date"><?php echo date("F d, Y h:i",strtotime($result->date));/*get_the_date("F d, Y h:i:s");*/ ?></span>
    	<div class="post_links pull-right">
        	<?php if(is_user_logged_in()){ ?>
			<a href="#<?php echo $result->ID; ?>" class="bb_quote_link" data-bbp-author="<?php echo $result->user_login; ?>" title="reply with quote">Quote</a>
			<?php
				if(($result->user_id == get_current_user_id() || is_forum_moderator()) && $closed_topic == false)
				{ ?>
                <a href="<?php echo get_permalink(); ?>reply/<?php echo $result->ID; ?>/edit/" title="Edit" class="bbf_edit_link">Edit</a>
				<?php }
				if(is_forum_moderator() && $closed_topic == false){?>
					<a href="<?php echo get_permalink(); ?>?delete=<?php echo $result->ID; ?>" title="Delete" class="bbf_delete_link delete_this">Delete</a>
				<?php }

				 }else{?>

            <a href="<?php echo get_permalink($BBFThemeOptions->get_bbf_theme_option('page_login_id')); ?>?mode=2" class="bb_quote_link" data-bbp-author="<?php echo $authordata->data->user_login; ?>" title="reply with quote">Quote</a>

			<?php } ?>

            <a href="#<?php echo $current_topic_id; ?>">#<?php echo $i; ?></a>
        </div>
        <div class="clearboth"></div>
    </div>

    <div class="bbf_reply_author col-sm-4 col-md-3">
    <div class="user_info">
    <span class="username"><a href="<?php echo USERS_URI.$result->user_id ?>"><?php echo $result->user_login; ?></a></span>
    <a href="<?php echo USERS_URI.$result->user_id; ?>" class="hidden-xs"><img src="<?php echo get_user_profile_image_url($result->user_id); ?>" alt="" class="profile_image" /></a>
    <span><strong>Join Date:</strong> <?php echo date('F d, Y',strtotime($result->user_registered)); ?></span>

	<?php if($user_location = get_user_meta($result->user_id,'location',true)){ ?>
	 <span><strong>Location: </strong><?php echo $user_location; ?></span>
    <?php } ?>



    <span><strong>Posts:</strong> <?php echo get_user_posts($result->user_id);  ?></span>
    <?php if(is_user_logged_in()){?>
		<a href="<?php echo USERS_URI.$current_user->ID; ?>/compose/?to=<?php echo urlencode($result->user_login); ?>" class="orange_btn private_message_btn">PM</a>
        <a href="<?php echo USERS_URI.$current_user->ID; ?>/email/?eto=<?php echo urlencode($result->user_login); ?>" class="orange_btn email_btn">Email</a>
	<?php } ?>

    <?php /*?><span><strong>Have Thanks:</strong> 1</span>
    <span><strong>Has Thanked:</strong> 3 time</span><?php */?>
    </div>
    </div><!-- bbf_reply_author div end here-->


    <div class="bbf_reply_content_container col-sm-8 col-md-9">
		<div class="bbf_reply_content">
		<?php echo bbcode_to_html($result->content); ?>
        </div>
        <?php get_user_signature_by_ID($result->user_id); ?>
    </div><!-- bbf_reply_content_container div end here-->
    <div class="clearboth"></div>
</li>
<?php
$i++;
 }
}
?>

</ul>
<?php
if($results){
	pagination($total_pages);
}?>

<a href="#" name="this_topic_reply" id="this_topic_reply">&nbsp;</a>

<?php
if(is_user_logged_in())
{
	if($closed_topic == false){?>

	<div class="topic_reply web_box">
    <form action="" method="post" class="">
        <div class="dark_box_header">
          <h3>Reply To: <?php echo get_the_title(); ?></h3>
        </div>
        <div class="web_boxp">
        <?php ckEditor('reply_content'); ?>
        <p class="form_message"></p>
        <input type="hidden" name="modify_reply" value="new" />
        <p style="margin-top:20px;margin-bottom:0px;"><input type="submit" value="Submit" /></p>
        </div>
    </form>
    </div>
	<?php }else
	{
		echo '<p><a href="#" class="orange_btn">This topic has been closed</a></p>';
	}
?>


<?php }
else{?>

<p><a href="<?php echo get_permalink($BBFThemeOptions->get_bbf_theme_option('page_login_id')); ?>?mode=1" class="orange_btn">Post Reply</a></p>
<?php }
?>
