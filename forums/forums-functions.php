<?php

define("FORUMS_URI",THEME_URI.'forums/');
define("FORUMS_ABS",THEME_ABS.'forums/');
define("FORUM_PT","forum");
define("TOPIC_PT","topic");
/*global $wpdb;
$sql = 'SELECT * FROM bb_posts';

 $result = mysqli_query($wpdb->dbh, $sql);
 db($result);
 db($row = mysqli_fetch_assoc($result));

 db($wpdb->dbh);
 exit();*/

/******************************************/
/***** includes post types and meta boxes start from herer **********/
/******************************************/

if(is_admin())
{
	require_once(FORUMS_ABS."meta_boxes/forum_meta_box.php");
	require_once(FORUMS_ABS."meta_boxes/topic_meta_box.php");
}
require_once(FORUMS_ABS."forums-post-type.php");
require_once(FORUMS_ABS."classes/ForumMeta.php");
require_once(FORUMS_ABS."classes/TopicMeta.php");

/******************************************/
/***** count user posts **********/
/******************************************/

function update_user_posts($user_ID)
{
	$users_posts = 1;
	if(get_user_meta($user_ID,'users_posts', true))
	{
		$users_posts = get_user_meta($user_ID,'users_posts', true) + 1;
	}

	update_user_meta($user_ID,'users_posts', $users_posts );
}


function get_user_posts($user_ID)
{
	$users_posts = 0;
	if(get_user_meta($user_ID,'users_posts', true))
	{
		$users_posts = get_user_meta($user_ID,'users_posts', true);
	}
	return $users_posts;
}

/******************************************/
/***** Registger syles and js **********/
/******************************************/

add_action( 'wp_enqueue_scripts', 'bbf_custom_scripts' );
function bbf_custom_scripts() {
	global $wp_query;

	if((is_bbf() ) && !is_admin())
	{
		// scripts
		if(is_singular(TOPIC_PT) || isset($wp_query->query_vars["signature"]) || isset($wp_query->query_vars["compose"]) || isset($wp_query->query_vars["email"]))
		{
			wp_enqueue_script( 'ckeditor', FORUMS_URI.'templates/js/ckeditor/ckeditor.js', array('jquery'));
			wp_enqueue_script( 'single-topic', FORUMS_URI.'templates/js/single-topic.js', array('jquery'));
		}

	}

}

function is_bbf()
{
	$is_bbf = false;
	global $wp_query, $BBFThemeOptions;
	if(isset($wp_query->query_vars["post_type"]) && ($wp_query->query_vars["post_type"] == FORUM_PT || $wp_query->query_vars["post_type"] == TOPIC_PT) || is_page($BBFThemeOptions->get_bbf_theme_option('page_users_id')))
	{
		$is_bbf = true;
	}

	return $is_bbf;

}


/******************************************/
/***** Logged in user data**********/
/******************************************/
if(is_user_logged_in())
{
	global $current_user;
	$current_user = wp_get_current_user();
	//db($current_user);
	//exit();
}

/******************************************/
/***** Redirect logged in users to home page **********/
/******************************************/
function redirect_logged_in_users()
{
	if(is_user_logged_in())
	{
		header("Location: ".HOME_URL);
	}
}
function redirect_not_logged_in_users()
{
	if(!is_user_logged_in())
	{
		header("Location: ".HOME_URL);
	}
}
/******************************************/
/***** mycustom_logout hook start from here **********/
/******************************************/
add_action("wp_logout","mycustom_logout");
function mycustom_logout() {
	if(isset($_SESSION))
	{
		session_destroy();
	}
	wp_clear_auth_cookie();
}


/******************************************/
/***** Strip evil tags from usre string **********/
/******************************************/
function bb_kses($content)
{
	$allowed_tags = array(
		'p' => array(),
		'a' => array(
			'href' => array(),
			'title' => array()
		),
		'img' => array(
			'src' => array(),
			'title' => array()
		),
		'span' => array(
			'style' => array()
		),
		'blockquote' => array(),
		'div' => array(),
		'br' => array(),
		'em' => array(),
		'ol' => array(),
		'ul' => array(),
		'li' => array(),
		'strong' => array()
	);

	return wp_kses($content, $allowed_tags);
}


/******************************************/
/***** user loged in function statt from here **********/
/******************************************/

function smileys($no_of_smiley = 'all')
{
	$smileys = array();
	$smileys[] = array('smile_20.png','Smile',array(':)',':=)',':-)'));
	$smileys[] = array('sadsmile_20.png','Sad',array(':(',':=(',':-('));
	$smileys[] = array('bigsmile_20.png','Laugh',array(':d',':=d',':-d'));
	$smileys[] = array('cool_20.png','Cool',array('8=)','8-)','B=)','B-)','(cool)'));
	$smileys[] = array('wink_20.png','Wink',array(';)',';-)',';=)'));
	$smileys[] = array('surprised.png','Surprised',array(':o',':=o',':-o'));
	$smileys[] = array('crying_20.png','Crying',array(';(',';-(',';=('));
	$smileys[] = array('sweating_20.png','Sweating',array('(sweat)','(:|'));
	$smileys[] = array('speechless_20.png','Speechless',array(':|',':=|',':-|'));
	$smileys[] = array('kiss_20.png','Kiss',array(':*',':=*',':-*'));
	$smileys[] = array('tongueout_20.png','Cheeky',array(':p',':=p',':-p'));
	$smileys[] = array('fingerscrossed_20.png','Fingers crossed',array('(yn)'));
	$smileys[] = array('blushing_20.png','Blush',array('(blush)',':$',':-$',':=$',':">'));
	$smileys[] = array('wondering_20.png','Wondering',array(':^)'));
	$smileys[] = array('sleepy_20.png','Sleepy',array('(snooze)','|-)','i-)','i=)'));
	$smileys[] = array('dull_20.png','Dull',array('|(','|-(','|=('));
	$smileys[] = array('inlove_20.png','In love',array('(inlove)'));
	$smileys[] = array('evilgrin_20.png','Evil grin',array('(grin)',']:)','>:)'));
	$smileys[] = array('yawning_20.png','Yawn',array('(yawn)'));
	$smileys[] = array('puking_20.png','Puke',array('(puke)',':&',':-&',':=&'));
	$smileys[] = array('doh_20.png','Doh!',array('(doh)'));
	$smileys[] = array('angry_20.png','Angry',array(':@',':-@',':=@','x(','x-(','x=('));
	$smileys[] = array('itwasntme_20.png','It wasn\'t me',array('(wasntme)'));
	$smileys[] = array('party_20.png','Party!!!',array('(party)'));
	$smileys[] = array('facepalm_20.png','Facepalm',array('(facepalm)'));
	$smileys[] = array('worried_20.png','Worried',array(':s',':-s',':=s'));
	$smileys[] = array('mmm_20.png','Mmm...',array('(mm)'));
	$smileys[] = array('nerd_20.png','Nerd',array('(nerd)','8-|','b-|','8|','b|','8=|','b=|'));
	$smileys[] = array('lipssealed_20.png','Lips Sealed',array(':x',':-x',':#',':-#',':=x',':=#'));
	$smileys[] = array('hi_20.png','Hi',array('(hi)'));
	$smileys[] = array('devil_20.png','Devil',array('(devil)'));
	$smileys[] = array('angel_20.png','Angel',array('(angel)'));
	$smileys[] = array('envy_20.png','Envy',array('(envy)'));
	$smileys[] = array('wait_20.png','Wait',array('(wait)'));
	$smileys[] = array('hug_20.png','Bear-hug',array('(bear)','(hug)'));
	$smileys[] = array('makeup_20.png','Make-up',array('(makeup)','(kate)'));
	$smileys[] = array('giggle_20.png','Giggle',array('(giggle)','(chuckle)'));
	$smileys[] = array('clapping_20.png','Clapping',array('(clap)'));
	$smileys[] = array('thinking_20.png','Thinking',array('(think)',':?',':-?',':=?'));
	$smileys[] = array('bow_20.png','Bowing',array('(bow)'));
	$smileys[] = array('rofl_20.png','Rolling on the floor laughing',array('(rofl)'));
	$smileys[] = array('whew_20.png','Relieved',array('(whew)'));
	$smileys[] = array('happy_20.png','Happy',array('(happy)'));
	$smileys[] = array('smirk_20.png','Smirking',array('(smirk)'));
	$smileys[] = array('nod_20.png','Nodding',array('(nod)'));
	$smileys[] = array('shake_20.png','Shaking',array('(shake)'));
	$smileys[] = array('emo_20.png','Emo',array('(emo)'));
	$smileys[] = array('yes_20.png','Yes',array('(y)','(ok)'));
	$smileys[] = array('no_20.png','No',array('(n)'));
	$smileys[] = array('handshake_20.png','Shaking Hands',array('(handshake)'));
	$smileys[] = array('heart_20.png','Heart',array('(h)','(l)'));
	$smileys[] = array('tmi_20.png','TMI',array('(tmi)'));
	$smileys[] = array('heidy_20.png','Heidy',array('(heidy)'));
	$smileys[] = array('flower_20.png','Flower',array('(f)'));
	$smileys[] = array('rain_20.png','Rain',array('(rain)','(london)','(st)'));
	$smileys[] = array('sunshine_20.png','Sun',array('(sun)'));
	$smileys[] = array('music_20.png','Music',array('(music)'));
	$smileys[] = array('coffee_20.png','Coffee',array('(coffee)'));
	$smileys[] = array('pizza_20.png','Pizza',array('(pizza)','(pi)'));
	$smileys[] = array('cash_20.png','Cash',array('(cash)','(mo)','($)'));
	$smileys[] = array('muscle_20.png','Muscle',array('(muscle)','(flex)'));
	$smileys[] = array('cake_20.png','Cake',array('(^)','(cake)'));
	$smileys[] = array('beer_20.png','Beer',array('(beer)'));
	$smileys[] = array('drink_20.png','Drink',array('(d)'));
	$smileys[] = array('dancing_20.png','Dancing',array('(dance)','\o/','\:d/'));
	$smileys[] = array('ninja_20.png','Ninja',array('(ninja)'));
	$smileys[] = array('star_20.png','Star',array('(*)'));
	$smileys[] = array('tumbleweed_20.png','Tumbleweed',array('(tumbleweed)'));
	$smileys[] = array('bandit_20.png','Bandit',array('(bandit)'));
	$smileys[] = array('call_20.png','Call',array('(call)'));
	$smileys[] = array('talking_20.png','Talking',array('(talk)'));
	$smileys[] = array('brokenheart_20.png','Broken heart',array('(u)'));
	$smileys[] = array('time_20.png','Time',array('(o)','(time)'));
	$smileys[] = array('mail_20.png','Mail',array('(e)','(m)'));
	$smileys[] = array('movie_20.png','Movie',array('(~)','(film)','(movie)'));
	$smileys[] = array('phone_20.png','Phone',array('(mp)','(ph)'));
	$smileys[] = array('drunk_20.png','Drunk',array('(drunk)'));
	$smileys[] = array('punch_20.png','Punch',array('(punch)'));
	$smileys[] = array('smoking_20.png','Smoking',array('(smoking)','(ci)'));
	$smileys[] = array('toivo_20.png','Toivo',array('(toivo)'));
	$smileys[] = array('rock_20.png','Rock',array('(rock)'));
	$smileys[] = array('headbang_20.png','Headbang',array('(headbang)','(banghead)'));
	$smileys[] = array('bug_20.png','Bug',array('(bug)'));
	$smileys[] = array('poolparty_20.png','Poolparty',array('(poolparty)'));
	/*$smileys[] = array('talktothe-hand.png','Talk to the hand',array('(talktothehand)'));
	$smileys[] = array('idea.png','Idea',array('(idea)'));
	$smileys[] = array('sheep.png','Sheep',array('(sheep)'));
	$smileys[] = array('cat.png','Cat',array('(cat)',':3'));
	$smileys[] = array('bike.png','Bike',array('(bike)'));
	$smileys[] = array('dog.png','Dog',array('(dog)'));*/


	$output = '';
	foreach($smileys as $smiley)
	{
		$output .= '<img src="'.THEME_URI.'/images/smiley/'.$smiley[0].'" alt="'.$smiley[1].'" data-smiley="'.$smiley[0].'" title="'.$smiley[1].'" />';
	}
	return $output;


}

function smileys_sk($no_of_smiley = 'all', $string = false)
{
	$smileys = array();
	$smileys[] = array('emoticon-0100-smile.gif','Smile',array(':)',':=)',':-)'));
	$smileys[] = array('emoticon-0101-sadsmile.gif','Sad',array(':(',':=(',':-('));
	$smileys[] = array('emoticon-0102-bigsmile.gif','Laugh',array(':d',':=d',':-d',':D'));
	$smileys[] = array('emoticon-0103-cool.gif','Cool',array('8=)','8-)','B=)','B-)','(cool)'));
	$smileys[] = array('emoticon-0105-wink.gif','Wink',array(';)',';-)',';=)'));
	//$smileys[] = array('surprised.png','Surprised',array(':o',':=o',':-o'));
	$smileys[] = array('emoticon-0106-crying.gif','Crying',array(';(',';-(',';=('));
	$smileys[] = array('emoticon-0107-sweating.gif','Sweating',array('(sweat)','(:|'));
	$smileys[] = array('emoticon-0108-speechless.gif','Speechless',array(':|',':=|',':-|'));
	$smileys[] = array('emoticon-0109-kiss.gif','Kiss',array(':*',':=*',':-*'));
	$smileys[] = array('emoticon-0110-tongueout.gif','Cheeky',array(':p',':=p',':-p', ':P'));
	//$smileys[] = array('fingerscrossed_20.png','Fingers crossed',array('(yn)'));
	$smileys[] = array('emoticon-0111-blush.gif','Blush',array('(blush)',':$',':-$',':=$',':">'));
	$smileys[] = array('emoticon-0112-wondering.gif','Wondering',array(':^)'));
	$smileys[] = array('emoticon-0113-sleepy.gif','Sleepy',array('(snooze)','|-)','i-)','i=)'));
	$smileys[] = array('emoticon-0114-dull.gif','Dull',array('|(','|-(','|=('));
	$smileys[] = array('emoticon-0115-inlove.gif','In love',array('(inlove)'));
	$smileys[] = array('emoticon-0116-evilgrin.gif','Evil grin',array('(grin)',']:)','>:)'));
	$smileys[] = array('emoticon-0118-yawn.gif','Yawn',array('(yawn)'));
	$smileys[] = array('emoticon-0119-puke.gif','Puke',array('(puke)',':&',':-&',':=&'));
	$smileys[] = array('emoticon-0120-doh.gif','Doh!',array('(doh)'));
	$smileys[] = array('emoticon-0121-angry.gif','Angry',array(':@',':-@',':=@','x(','x-(','x=('));
	$smileys[] = array('emoticon-0122-itwasntme.gif','It wasn\'t me',array('(wasntme)'));
	$smileys[] = array('emoticon-0123-party.gif','Party!!!',array('(party)'));
	//$smileys[] = array('facepalm_20.png','Facepalm',array('(facepalm)'));
	$smileys[] = array('emoticon-0124-worried.gif','Worried',array(':s',':-s',':=s'));
	$smileys[] = array('emoticon-0125-mmm.gif','Mmm...',array('(mm)'));
	$smileys[] = array('emoticon-0126-nerd.gif','Nerd',array('(nerd)','8-|','b-|','8|','b|','8=|','b=|'));
	$smileys[] = array('emoticon-0127-lipssealed.gif','Lips Sealed',array(':x',':-x',':#',':-#',':=x',':=#'));
	$smileys[] = array('emoticon-0128-hi.gif','Hi',array('(hi)'));
	$smileys[] = array('emoticon-0130-devil.gif','Devil',array('(devil)'));
	$smileys[] = array('emoticon-0131-angel.gif','Angel',array('(angel)'));
	$smileys[] = array('emoticon-0132-envy.gif','Envy',array('(envy)'));
	$smileys[] = array('emoticon-0133-wait.gif','Wait',array('(wait)'));
	//$smileys[] = array('hug_20.png','Bear-hug',array('(bear)','(hug)'));
	$smileys[] = array('emoticon-0135-makeup.gif','Make-up',array('(makeup)','(kate)'));
	$smileys[] = array('emoticon-0136-giggle.gif','Giggle',array('(giggle)','(chuckle)'));
	$smileys[] = array('emoticon-0137-clapping.gif','Clapping',array('(clap)'));
	//$smileys[] = array('thinking_20.png','Thinking',array('(think)',':?',':-?',':=?'));
	$smileys[] = array('emoticon-0139-bow.gif','Bowing',array('(bow)'));
	$smileys[] = array('emoticon-0140-rofl.gif','Rolling on the floor laughing',array('(rofl)'));
	$smileys[] = array('emoticon-0141-whew.gif','Relieved',array('(whew)'));
	$smileys[] = array('emoticon-0142-happy.gif','Happy',array('(happy)'));
	$smileys[] = array('emoticon-0143-smirk.gif','Smirking',array('(smirk)'));
	$smileys[] = array('emoticon-0144-nod.gif','Nodding',array('(nod)'));
	$smileys[] = array('emoticon-0145-shake.gif','Shaking',array('(shake)'));
	$smileys[] = array('emoticon-0147-emo.gif','Emo',array('(emo)'));
	$smileys[] = array('emoticon-0148-yes.gif','Yes',array('(y)','(ok)'));
	$smileys[] = array('emoticon-0149-no.gif','No',array('(n)'));
	$smileys[] = array('emoticon-0150-handshake.gif','Shaking Hands',array('(handshake)'));
	$smileys[] = array('emoticon-0152-heart.gif','Heart',array('(h)','(l)'));
	//$smileys[] = array('tmi_20.png','TMI',array('(tmi)'));
	//$smileys[] = array('heidy_20.png','Heidy',array('(heidy)'));
	$smileys[] = array('emoticon-0155-flower.gif','Flower',array('(f)'));
	$smileys[] = array('emoticon-0156-rain.gif','Rain',array('(rain)','(london)','(st)'));
	$smileys[] = array('emoticon-0157-sun.gif','Sun',array('(sun)'));
	$smileys[] = array('emoticon-0159-music.gif','Music',array('(music)'));
	$smileys[] = array('emoticon-0162-coffee.gif','Coffee',array('(coffee)'));
	$smileys[] = array('emoticon-0163-pizza.gif','Pizza',array('(pizza)','(pi)'));
	$smileys[] = array('emoticon-0164-cash.gif','Cash',array('(cash)','(mo)','($)'));
	$smileys[] = array('emoticon-0165-muscle.gif','Muscle',array('(muscle)','(flex)'));
	$smileys[] = array('emoticon-0166-cake.gif','Cake',array('(^)','(cake)'));
	$smileys[] = array('emoticon-0167-beer.gif','Beer',array('(beer)'));
	$smileys[] = array('emoticon-0168-drink.gif','Drink',array('(d)'));
	$smileys[] = array('emoticon-0169-dance.gif','Dancing',array('(dance)','\o/','\:d/'));
	$smileys[] = array('emoticon-0170-ninja.gif','Ninja',array('(ninja)'));
	$smileys[] = array('emoticon-0171-star.gif','Star',array('(*)'));
	//$smileys[] = array('tumbleweed_20.png','Tumbleweed',array('(tumbleweed)'));
	$smileys[] = array('emoticon-0174-bandit.gif','Bandit',array('(bandit)'));
	$smileys[] = array('emoticon-0129-call.gif','Call',array('(call)'));
	$smileys[] = array('emoticon-0117-talking.gif','Talking',array('(talk)'));
	$smileys[] = array('emoticon-0153-brokenheart.gif','Broken heart',array('(u)'));
	$smileys[] = array('emoticon-0158-time.gif','Time',array('(o)','(time)'));
	$smileys[] = array('emoticon-0154-mail.gif','Mail',array('(e)','(m)'));
	$smileys[] = array('emoticon-0160-movie.gif','Movie',array('(~)','(film)','(movie)'));
	$smileys[] = array('emoticon-0161-phone.gif','Phone',array('(mp)','(ph)'));
	$smileys[] = array('emoticon-0175-drunk.gif','Drunk',array('(drunk)'));
	$smileys[] = array('emoticon-0146-punch.gif','Punch',array('(punch)'));
	$smileys[] = array('emoticon-0176-smoke.gif','Smoking',array('(smoking)','(ci)'));
	$smileys[] = array('emoticon-0177-toivo.gif','Toivo',array('(toivo)'));
	$smileys[] = array('emoticon-0178-rock.gif','Rock',array('(rock)'));
	$smileys[] = array('emoticon-0179-headbang.gif','Headbang',array('(headbang)','(banghead)'));
	$smileys[] = array('emoticon-0180-bug.gif','Bug',array('(bug)'));
	$smileys[] = array('emoticon-0182-poolparty.gif','Poolparty',array('(poolparty)'));
	/*$smileys[] = array('talktothe-hand.png','Talk to the hand',array('(talktothehand)'));
	$smileys[] = array('idea.png','Idea',array('(idea)'));
	$smileys[] = array('sheep.png','Sheep',array('(sheep)'));
	$smileys[] = array('cat.png','Cat',array('(cat)',':3'));
	$smileys[] = array('bike.png','Bike',array('(bike)'));
	$smileys[] = array('dog.png','Dog',array('(dog)'));*/


	$output = '';
	if($string == 'page_emoticons'){
		foreach($smileys as $smiley)
		{
			$output .= '<img src="'.THEME_URI.'/images/smiley/sk/'.$smiley[0].'" alt="'.$smiley[1].'" data-smiley="'.$smiley[0].'" title="'.$smiley[1].'" />&nbsp; &nbsp; = &nbsp; &nbsp;&nbsp;<code>'.$smiley[2][0].'</code><br />';
		}
	}
	elseif($string != false)
	{
		$output = $string;
		foreach($smileys as $smiley)
		{
			$number_of_images = array();
			foreach($smiley[2] as $image)
			{
				$number_of_images[] = '<img src="'.THEME_URI.'/images/smiley/sk/'.$smiley[0].'" alt="'.$smiley[1].'" title="'.$smiley[1].'" />';
			}
			$output = str_replace($smiley[2],$number_of_images,$output);
		}

	}
	else
	{
		foreach($smileys as $smiley)
		{
			$output .= '<img src="'.THEME_URI.'/images/smiley/sk/'.$smiley[0].'" alt="'.$smiley[1].'" data-smiley="'.					$smiley[0].'" title="'.$smiley[1].'" />';
		}
	}

	return $output;


}


//remove_filter('template_redirect', 'redirect_canonical');
/******************************************/
/***** redirect canonical functions start from here **********/
/******************************************/
add_filter( 'redirect_canonical', 'bbf_fix_paged_redirects', 10, 2 );
function bbf_fix_paged_redirects( $redirect_url, $requested_url ) {
	global $wp_query;
	/*if(is_single()){
	db($wp_query);
	echo $redirect_url;
	exit();
}*/
	if(is_single() && $wp_query->query_vars["post_type"] == FORUM_PT &&  strpos( $requested_url, 'page/' ) !== false && ($wp_query->query_vars["paged"] >= 1))
		return $requested_url;

	if(isset($wp_query->query_vars["topics"]) &&  strpos( $requested_url, 'page/' ) !== false && ($wp_query->query_vars["paged"] >= 1)){
		return $requested_url;
	}
	if(isset($wp_query->query_vars["bbf_user"]) && $wp_query->query_vars["bbf_user"] >= 1 && isset($wp_query->query_vars["edit"]))
	{
		return $requested_url;
	}

	if(isset($wp_query->query_vars["bbf_user"]) && $wp_query->query_vars["bbf_user"] >= 1 && isset($wp_query->query_vars["edit"]))
	{
		return $requested_url;
	}

	if(isset($wp_query->query_vars["bbf_user"]) && $wp_query->query_vars["bbf_user"] >= 1){
		return $requested_url;
	}

	if((is_single() && $wp_query->query_vars["post_type"] == TOPIC_PT) &&  (strpos( $requested_url, 'page/' ) !== false))
		return $requested_url;


	return $redirect_url;
}

/******************************************/
/***** template redirect functions start from here **********/
/******************************************/
add_filter( 'template_include', 'bbf_template_redirect', 99 );
function bbf_template_redirect( $template ) {
	global $wp_query, $BBFThemeOptions;
	/*if(is_singular()){
	 echo ("template_include");
	 exit();
 }*/

	if ($wp_query->query_vars["post_type"] == FORUM_PT && is_archive()){
		if (!file_exists(THEME_ABS.'archive-forum.php'))
            $template = FORUMS_ABS . 'templates/archive-forum.php';
 	}
	else if ($wp_query->query_vars["post_type"] == FORUM_PT && is_singular()){
		if (!file_exists(THEME_ABS.'single-forum.php'))
            $template = FORUMS_ABS . 'templates/single-forum.php';
	}


	else if ($wp_query->query_vars["post_type"] == TOPIC_PT && is_archive()){
		if (!file_exists(THEME_ABS.'archive-topic.php'))
            $template = FORUMS_ABS . 'templates/archive-topic.php';
	}

	else if ($wp_query->query_vars["post_type"] == TOPIC_PT && is_singular()){
		if (!file_exists(THEME_ABS.'single-topic.php'))
            $template = FORUMS_ABS . 'templates/single-topic.php';
	}



	/*if (isset($wp_query->query_vars["bbf_user"]) && isset($wp_query->query_vars["edit"])){
		if (!file_exists(THEME_ABS.'users-profile-edit.php'))
            $template = FORUMS_ABS . 'templates/users-profile-edit.php';
	}*/

	else if (is_page($BBFThemeOptions->get_bbf_theme_option('page_users_id')) && isset($wp_query->query_vars["bbf_user"]) /*&& $wp_query->query_vars["name"] == "users"*/){
		//db($wp_query);
		if (!file_exists(THEME_ABS.'users-profile.php'))
            $template = FORUMS_ABS . 'templates/users-profile.php';
	}

	/*if(is_page($BBFThemeOptions->get_bbf_theme_option('page_users_id'))){
		db($wp_query);
	}*/

	return $template;
}


/******************************************/
/***** rewrite rules for forums start from here **********/
/******************************************/

add_action( 'init', 'bbf_rewrite_init' );
function bbf_rewrite_init() {



	add_rewrite_rule( 'topic/([^/]*)/([0-9]+)/?$', 'index.php?post_type='.TOPIC_PT.'&name=$matches[1]&new=$matches[2]', 'top' );
	add_rewrite_rule( 'topic/([^/]*)/edit/?$', 'index.php?post_type='.TOPIC_PT.'&name=$matches[1]&edit=1', 'top' );
	add_rewrite_rule( 'topic/([^/]*)/reply/([0-9]+)/edit/?$', 'index.php?post_type='.TOPIC_PT.'&name=$matches[1]&reply=$matches[2]&edit=1', 'top' );

	// users page rewrite rules
	add_rewrite_rule( USERS_SLUG.'/?$', 'index.php?pagename='.USERS_SLUG, 'top' );
	add_rewrite_rule( USERS_SLUG.'/([^/]+)/?$', 'index.php?pagename='.USERS_SLUG.'&bbf_user=$matches[1]', 'top' );
	add_rewrite_rule( USERS_SLUG.'/([^/]+)/edit/?$', 'index.php?pagename='.USERS_SLUG.'&bbf_user=$matches[1]&edit=1', 'top' );
	add_rewrite_rule( USERS_SLUG.'/([^/]+)/signature/?$', 'index.php?pagename='.USERS_SLUG.'&bbf_user=$matches[1]&signature=1', 'top' );
	add_rewrite_rule( USERS_SLUG.'/([^/]+)/avatar/?$', 'index.php?pagename='.USERS_SLUG.'&bbf_user=$matches[1]&avatar=1', 'top' );
	add_rewrite_rule( USERS_SLUG.'/([^/]+)/settings/?$', 'index.php?pagename='.USERS_SLUG.'&bbf_user=$matches[1]&settings=1', 'top' );

	add_rewrite_rule( USERS_SLUG.'/([^/]+)/topics/?$', 'index.php?pagename='.USERS_SLUG.'&bbf_user=$matches[1]&topics=1', 'top' );
	add_rewrite_rule( USERS_SLUG.'/([^/]+)/topics/page/([^/]+)/?$', 'index.php?pagename='.USERS_SLUG.'&bbf_user=$matches[1]&topics=1&paged=$matches[2]', 'top' );

	add_rewrite_rule( USERS_SLUG.'/([^/]+)/inbox/?$', 'index.php?pagename='.USERS_SLUG.'&bbf_user=$matches[1]&inbox=1', 'top' );
	add_rewrite_rule( USERS_SLUG.'/([^/]+)/inbox/message/([0-9]+)/?$', 'index.php?pagename='.USERS_SLUG.'&bbf_user=$matches[1]&inbox=1&message=$matches[2]', 'top' );
	add_rewrite_rule( USERS_SLUG.'/([^/]+)/inbox/page/([^/]+)/?$', 'index.php?pagename='.USERS_SLUG.'&bbf_user=$matches[1]&inbox=1&paged=$matches[2]', 'top' );

	add_rewrite_rule( USERS_SLUG.'/([^/]+)/sent/?$', 'index.php?pagename='.USERS_SLUG.'&bbf_user=$matches[1]&sent=1', 'top' );
	add_rewrite_rule( USERS_SLUG.'/([^/]+)/sent/message/([0-9]+)/?$', 'index.php?pagename='.USERS_SLUG.'&bbf_user=$matches[1]&sent=1&message=$matches[2]', 'top' );
	add_rewrite_rule( USERS_SLUG.'/([^/]+)/sent/page/([^/]+)/?$', 'index.php?pagename='.USERS_SLUG.'&bbf_user=$matches[1]&sent=1&paged=$matches[2]', 'top' );

	add_rewrite_rule( USERS_SLUG.'/([^/]+)/message/([0-9]+)/?$', 'index.php?pagename='.USERS_SLUG.'&bbf_user=$matches[1]&message=$matches[2]', 'top' );

	add_rewrite_rule( USERS_SLUG.'/([^/]+)/compose/?$', 'index.php?pagename='.USERS_SLUG.'&bbf_user=$matches[1]&compose=1', 'top' );
	add_rewrite_rule( USERS_SLUG.'/([^/]+)/email/?$', 'index.php?pagename='.USERS_SLUG.'&bbf_user=$matches[1]&email=1', 'top' );

	add_rewrite_rule( USERS_SLUG.'/([^/]+)/replies/?$', 'index.php?pagename='.USERS_SLUG.'&bbf_user=$matches[1]&replies=1', 'top' );
	add_rewrite_rule( USERS_SLUG.'/([^/]+)/replies/page/([^/]+)/?$', 'index.php?pagename='.USERS_SLUG.'&bbf_user=$matches[1]&replies=1&paged=$matches[2]', 'top' );
	//flush_rewrite_rules();
}

add_filter( 'query_vars', 'bbf_query_vars' );
function bbf_query_vars( $vars) {
$vars[] = 'users';
	$vars[] = 'edit';
	$vars[] = 'new';
	$vars[] = 'reply';

	$vars[] = 'bbf_user';
	$vars[] = 'signature';
	$vars[] = 'topics';
	$vars[] = 'replies';
	$vars[] = 'avatar';
	$vars[] = 'inbox';
	$vars[] = 'sent';
	$vars[] = 'compose';
	$vars[] = 'message';
	$vars[] = 'email';
	$vars[] = 'delete';
	$vars[] = 'settings';



    return $vars;
}

/******************************************/
/***** add_permastruct **********/
/******************************************/
/*add_permastruct( 'bbf_user', 'users/%bbf_user%', array(
	'with_front'  => false,
	'ep_mask'     => EP_NONE,
	'paged'       => false,
	'feed'        => false,
	'forcomments' => false,
	'walk_dirs'   => true,
	'endpoints'   => false,
) );*/

/******************************************/
/***** get other tables data with wordpress **********/
/******************************************/

/*add_action( 'pre_get_posts', 'get_topic_meta_data' );
function get_topic_meta_data($query) {

	if( is_singular(TOPIC_PT))
			{
				//add_filter( 'posts_clauses', 'single_forum_sub_forum_query', 20, 1 );
				//$query->set('cat', 16);
			}
}*/
function pagination($pages = '', $range = 4)
{
     $showitems = ($range * 2)+1;

     global $paged;
     if(empty($paged)) $paged = 1;

     if($pages == '')
     {
         global $wp_query;
         $pages = $wp_query->max_num_pages;
         if(!$pages)
         {
             $pages = 1;
         }
     }

     if(1 != $pages)
     {
         echo "<div class=\"pagination\"><span>Page ".$paged." of ".$pages."</span>";
         if($paged > 2 && $paged > $range+1 && $showitems < $pages) echo "<a href='".get_pagenum_link(1)."'>&laquo; First</a>";
         if($paged > 1 && $showitems < $pages) echo "<a href='".get_pagenum_link($paged - 1)."'>&lsaquo; Previous</a>";

         for ($i=1; $i <= $pages; $i++)
         {
             if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems ))
             {
                 echo ($paged == $i)? "<span class=\"current\">".$i."</span>":"<a href='".get_pagenum_link($i)."' class=\"inactive\">".$i."</a>";
             }
         }

         if ($paged < $pages && $showitems < $pages) echo "<a href=\"".get_pagenum_link($paged + 1)."\">Next &rsaquo;</a>";
         if ($paged < $pages-1 &&  $paged+$range-1 < $pages && $showitems < $pages) echo "<a href='".get_pagenum_link($pages)."'>Last &raquo;</a>";
         echo "<div class='clearboth'></div></div>\n";
     }
}


/******************************************/
/***** bbf_breadcrumb **********/
/******************************************/

function bbf_breadcrumb()
{
	global $post;
	$output =  '<a class="icon_home" href="'.get_post_type_archive_link( FORUM_PT ).'">Board index</a>';
	$title = get_the_title();
	if(isset($post->post_parent)){
		$anc = get_post_ancestors( $post->ID );
		$anc = array_reverse($anc);
		foreach ( $anc as $ancestor ) {
			$output .= ' <img src="'.THEME_URI.'/images/navbit-arrow-right.png" alt="" class="breadcrumb_seperator" /> <a href="'.get_permalink($ancestor).'" title="'.get_the_title($ancestor).'">'.get_the_title($ancestor).'</a>';
		}
	}
	$output .=  '<img src="'.THEME_URI.'/images/navbit-arrow-right.png" alt="" class="breadcrumb_seperator" /> <a href="'.get_the_permalink().'">'.$title.'</a>';
	return $output;
}

/******************************************/
/***** Dashboard main menu **********/
/******************************************/

function dashboard_main_menu()
{ ?>
<div class="dashboard_main_nav">
    <ul>
        <li class="<?php if(is_page($BBFThemeOptions->get_bbf_theme_option('page_users_id'))){ echo "current-menu-item";}; ?>"><a href="#">Profile</a></li>
    </ul>
    <div class="clearboth"></div>
</div><!-- dashboard main nav div end here-->
<?php }

/******************************************/
/***** Dashboard user profile menu **********/
/******************************************/

function profile_current_page()
{
	global $wp_query;
	$class = "";
	if(isset($wp_query->query_vars["bbf_user"]) && isset($wp_query->query_vars["edit"]))
	{
		$class = "";
	}

	return $class;
}

/******************************************/
/***** Dashboard user profile menu **********/
/******************************************/
function dashboard_profile_menu()
{
	global $wp_query, $current_user, $user_profile_data, $unread_messages, $BBFThemeOptions;
?>

<div class="username">
	<span><?php echo $user_profile_data->data->user_login; ?></span>
	<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".users_dashboard_menu">
		<span class="sr-only">Toggle Navigation</span>
		<span class="icon-bar"></span>
		<span class="icon-bar"></span>
		<span class="icon-bar"></span>
	</button>
	<div class="clearboth"></div>
</div>
<?php if(!(is_user_logged_in() && $current_user->ID === $user_profile_data->ID)){ ?>
<img src="<?php echo get_user_profile_image_url($user_profile_data->ID); ?>" alt="" class="profile_image hidden-xs" style="max-width:100%; display:block; margin:0 auto;" />
<?php } ?>
<div class="navbar-collapse collapse users_dashboard_menu" style="padding:0px;">
<ul class="nav navbar-nav">
    <li><a href="<?php echo USERS_URI.$user_profile_data->ID; ?>/">Profile</a></li>

    <?php if(is_user_logged_in() && $current_user->ID === $user_profile_data->ID){ ?>
	    <li class="<?php if(isset($wp_query->query_vars["edit"])){ echo 'current-menu-item'; } ?>"><a href="<?php echo USERS_URI.$user_profile_data->ID; ?>/edit/">Edit Profile</a></li>
	    <li class="<?php if(isset($wp_query->query_vars["avatar"])){ echo 'current-menu-item'; } ?>"><a href="<?php echo USERS_URI.$user_profile_data->ID; ?>/avatar/">Edit Profile Image</a></li>
	    <li class="<?php if(isset($wp_query->query_vars["signature"])){ echo 'current-menu-item'; } ?>"><a href="<?php echo USERS_URI.$user_profile_data->ID; ?>/signature/">Edit Signature</a></li>
			<li class="<?php if(isset($wp_query->query_vars["settings"])){ echo 'current-menu-item'; } ?>"><a href="<?php echo USERS_URI.$user_profile_data->ID; ?>/settings/">Change Password</a></li>
	    <li class="<?php if(isset($wp_query->query_vars["inbox"])){ echo 'current-menu-item'; } ?>"><a href="<?php echo USERS_URI.$user_profile_data->ID; ?>/inbox/">Inbox <?php if($unread_messages && $unread_messages > 0){ echo ' ('.$unread_messages.')';} ?></a></li>
	    <li class="<?php if(isset($wp_query->query_vars["sent"])){ echo 'current-menu-item'; } ?>"><a href="<?php echo USERS_URI.$user_profile_data->ID; ?>/sent/">Sent Messages</a></li>
	    <li class="<?php if(isset($wp_query->query_vars["compose"])){ echo 'current-menu-item'; } ?>"><a href="<?php echo USERS_URI.$user_profile_data->ID; ?>/compose/">Compose Message</a></li>
    <?php } ?>

    <li class="<?php if(isset($wp_query->query_vars["topics"])){ echo 'current-menu-item'; } ?>"><a href="<?php echo USERS_URI.$user_profile_data->ID; ?>/topics/">Topics Started</a></li>
    <li class="<?php if(isset($wp_query->query_vars["replies"])){ echo 'current-menu-item'; } ?>"><a href="<?php echo USERS_URI.$user_profile_data->ID; ?>/replies/">Replies Created</a></li>
</ul>
</div>
<?php
}


/******************************************/
/***** Update page Counter **********/
/******************************************/

function update_page_counter($id)
{
	$exist_value = get_post_meta($id,"page_views",true);
	if($exist_value && is_numeric($exist_value) && $exist_value >= 1)
	{
		update_post_meta($id,"page_views",$exist_value+1);
	}else
	{
		update_post_meta($id,"page_views",1);
	}
}

/******************************************/
/***** get single page views **********/
/******************************************/

function get_views($id)
{
	$exist_value = get_post_meta($id,"page_views",true);
	//db($id);
	if($exist_value && is_numeric($exist_value) && $exist_value >= 1)
	{
		return $exist_value;

	}else
	{
		return 1;
	}
}

/******************************************/
/***** Join fourm_meta and posts table before query **********/
/******************************************/

function single_forum_sub_forum_query( $pieces )
{
	global $wpdb;
	$bbf_forum_meta_select_columns = array('forum_id','forum_type','forum_posts','forum_topics','forum_last_topic_id','last_topic_title','forum_last_post_id','last_post_time','forum_last_poster_id','last_poster_name','external_link','forum_status');
	$bbf_forum_meta_select_string = '';
	foreach ($bbf_forum_meta_select_columns as $column) {
		$bbf_forum_meta_select_string .= ', '.$wpdb->bbf_forum_meta.'.'.$column;
	}

	//$pieces['fields'] = 'bb_posts.ID, bb_posts.post_title, bb_posts.post_content, bb_posts.post_name, bb_forum_meta.*';
	$pieces['fields'] = $wpdb->posts.'.*'.$bbf_forum_meta_select_string;
	$pieces['join'] = 'inner join '.$wpdb->bbf_forum_meta.' ON '.$wpdb->prefix.'posts.ID='.$wpdb->bbf_forum_meta.'.forum_id';

	return $pieces;
}





/******************************************/
/***** Join fourm_meta and posts table before query **********/
/******************************************/

function get_topic_meta_with_query( $pieces )
{
  global $wpdb;
	$bbf_topic_meta_select_columns = array('topic_id','topic_status','topic_posts','topic_last_post_id','topic_last_poster_id','last_poster_name','last_post_time','topic_sticky');
	$bbf_topic_meta_select_string = '';
	foreach ($bbf_topic_meta_select_columns as $column) {
		$bbf_topic_meta_select_string .= ', '.$wpdb->bbf_topic_meta.'.'.$column;
	}
	//$pieces['fields'] = 'bb_posts.ID, bb_posts.post_title, bb_posts.post_content, bb_posts.post_name, bb_forum_meta.*';
	$pieces['fields'] = $wpdb->posts.'.*'.$bbf_topic_meta_select_string;

	$pieces['join'] = 'inner join '.$wpdb->bbf_topic_meta.' ON '.$wpdb->posts.'.ID='.$wpdb->bbf_topic_meta.'.topic_id';

	if(is_singular(FORUM_PT)){ $pieces['orderby'] = $wpdb->bbf_topic_meta.'.topic_sticky DESC, '.$wpdb->posts.'.post_date DESC'; }
	//db($pieces);
	return $pieces;
}


/******************************************/
/***** Join topic_meta and posts table before query **********/
/******************************************/

function single_topic_meta_select_query( $pieces )
{
	global $wpdb;
	$pieces['fields'] = $wpdb->prefix.'posts.*, '.$wpdb->bbf_topic_meta.'.topic_status, '.$wpdb->bbf_topic_meta.'.topic_sticky';
	$pieces['join'] = 'inner join '.$wpdb->bbf_topic_meta.' ON '.$wpdb->prefix.'posts.ID='.$wpdb->bbf_topic_meta.'.topic_id';
	return $pieces;
}
add_action('pre_get_posts','pre_single_topic_meta_select_query');
function pre_single_topic_meta_select_query($query){

		if ( !is_admin() && $query->is_main_query() && isset($query->query_vars["post_type"]) && $query->query_vars["post_type"] == TOPIC_PT && is_single() && $query->query_vars["name"] != "new") {

			add_filter( 'posts_clauses', 'single_topic_meta_select_query');
    	}

}

/******************************************/
/***** orderby_most_popular_topics before query **********/
/******************************************/
function orderby_most_popular_topics($orderby_statement)
{
	global $wpdb;
	$orderby_statement =' '.$wpdb->bbf_topic_meta.'.topic_posts DESC';
	return $orderby_statement;
}






/******************************************/
/***** user_topics **********/
/******************************************/

function topics_list($topic_type)
{
	global $wp_query, $wpdb;
	$user_id = false;

	if($topic_type && is_numeric($topic_type) && $topic_type > 0)
	{
		$user_id = $topic_type;
	}

	$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

	if($user_id != false)
	{
		$args = array(
			'post_type'         => 'topic',
			'posts_per_page'    => 20,
			'author' => $user_id,
			//'post_parent' => $parent_forum_id,
			//'cache_results' => false,
			//'update_post_meta_cache' => false,
			//'update_post_term_cache' => false,
			'paged' => $paged,
		);
	}

	else if($topic_type == "most_popular")
	{
		$args = array(
		'post_type'         => 'topic',
		'posts_per_page'    => 5,
		//'post_parent' => $parent_forum_id,
		//'cache_results' => false,
		//'update_post_meta_cache' => false,
		//'update_post_term_cache' => false,
		'paged' => $paged,
		);
		add_filter('posts_orderby', 'orderby_most_popular_topics');
	}
	else if($topic_type == "recent_announcements")
	{
		$results = $wpdb->get_col("SELECT post_id FROM $wpdb->postmeta WHERE meta_key = 'bbf_recent_announcements' AND meta_value = 'yes'" );
		if(!$results)
			$results = array(0);
		$args = array(
		'post_type'         => 'topic',
		'posts_per_page'    => 7,
		'post_parent__in' => $results,
		//'cache_results' => false,
		//'update_post_meta_cache' => false,
		//'update_post_term_cache' => false,
		'paged' => $paged,
		);
	}

	else if($topic_type == "most_recent")
	{
		$args = array(
		'post_type'         => 'topic',
		'posts_per_page'    => 7,
		//'post_parent' => $parent_forum_id,
		//'cache_results' => false,
		//'update_post_meta_cache' => false,
		//'update_post_term_cache' => false,
		'paged' => $paged,
		);
	}
	else
	{
		$args = array(
		'post_type'         => 'topic',
		'posts_per_page'    => 20,
		//'post_parent' => $parent_forum_id,
		//'cache_results' => false,
		//'update_post_meta_cache' => false,
		//'update_post_term_cache' => false,
		'paged' => $paged,
		);
	}

	add_filter( 'posts_clauses', 'get_topic_meta_with_query');
	$my_topics = new WP_Query($args);
	//$my_topics = get_posts($args);
	remove_filter( 'posts_clauses', 'get_topic_meta_with_query'/*, 20 */);
	//db($my_topics);
	if($my_topics->have_posts())
	{

		if($user_id != false || $topic_type == 'page_topic_archive'){
			echo '<div class="pagination">';
			echo paginate_links( array(
				'base' => str_replace( 999999999, '%#%', esc_url( get_pagenum_link( 999999999 ) ) ),
				'format' => '?paged=%#%',
				'current' => max( 1, get_query_var('paged') ),
				'total' => $my_topics->max_num_pages
			) );
			echo '<div class="clearboth"></div>';
			echo '</div><!-- pagination div end here-->';
		}
	?>


		<div class="clearboth"></div>
		<div class="bbf_head">
				<div class="col-xs-8 col-sm-8 col-md-6 col-lg-7 bbf_forum_info no_mp">Topics</div>
				<div class="col-xs-4 col-sm-2 col-md-1 bbf_forum_topic_count no_mp text-center">Views<?php //bbp_forum_topic_count($result['ID'])	; ?></div>
				<div class="col-xs-2 col-sm-2 col-md-1 bbf_forum_reply_count hidden-xs no_mp text-center">Posts<?php //bbp_forum_post_count($result['ID'])	; ?></div>
				<div class="col-md-4 col-lg-3 bbf_forum_freshness hidden-sm hidden-xs no_mp text-center">Last post</div>
				<div class="clearboth"></div>
		</div>
	<?php
		$i = 1;
			echo '<ul class="forum_subforums no_mp">';
			while($my_topics->have_posts())
			{
				global $post;
				//db($post);
				$my_topics->the_post();
				if($i%2 == 0)
				{
					$sub_form_class = 'even';
				}else
				{
					$sub_form_class = 'odd';
				}

				?>
			<li class="bbf_heads_childs <?php echo $sub_form_class; ?>">
				<?php
					$topic_url = get_the_permalink();
				?>
				<div class="col-xs-8 col-sm-8 col-md-6 col-lg-7 bbf_forum_info no_mp">
					<?php
					if(!($fImageURL = get_feature_image_url($post->ID))){
						$fImageURL = THEME_URI.'images/forum_read.png';
					}
					?>
						<a href="<?php echo $topic_url; ?>" class="forums_thumbnails"><img src="<?php echo $fImageURL; ?>" alt="<?php echo get_the_title(); ?>" class="forums_thumbnails" /></a>

				<span class="forum_title"><a href="<?php the_permalink(); ?>"><?php echo get_the_title(); ?></a></span>
				<p>Started by: <a href="<?php echo USERS_URI.get_the_author_meta('ID'); ?>"><?php echo get_the_author_meta('user_login'); ?></a></p>
				</div>
				<div class="col-xs-4 col-sm-2 col-md-1 bbf_forum_topic_count no_mp text-center"><?php echo get_views($post->ID); ?></div>
				<div class="col-xs-2 col-sm-2 col-md-1 bbf_forum_reply_count hidden-xs no_mp text-center"><?php echo $post->topic_posts; ?></div>

				<div class="col-md-4 col-lg-3 bbf_forum_freshness hidden-sm hidden-xs no_mp text-center">
					<span class="bbp-topic-freshness-author">
						<!--introduction<br />-->
						 <?php if($post->last_poster_name){?>
							By <a href="<?php echo USERS_URI.$post->topic_last_poster_id; ?>"><?php echo $post->last_poster_name; ?><!--Admin--></a><br />
							<?php echo human_time_diff(strtotime($post->last_post_time), current_time( 'timestamp')); ?> ago

							<?php }else{?>
								No post found.
							<?php } ?>
					</span>
				</div>
				<div class="clearboth"></div>
			</li>
			<?php
			$i++;
			}
			echo '<li class="bbp_footer"></li></ul>';

			if($user_id != false || $topic_type == 'page_topic_archive'){
				$big = 999999999; // need an unlikely integer
				echo '<div class="pagination" style="margin:20px 0;">';
				echo paginate_links( array(
				'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
				'format' => '?paged=%#%',
				'current' => max( 1, get_query_var('paged') ),
				'total' => $my_topics->max_num_pages
				) );
				echo '<div class="clearboth"></div>
				</div><!-- pagination div end here-->';
			}
			//wp_reset_postdata();
			//wp_reset_query();
	 ?>


	<?php
	}

	else
	{
		echo '<div class="web_boxp">No topics found.</div>';
	}
}


/******************************************/
/***** Different topics list **********/
/******************************************/
/*
function most_popular_topics($topic_type = true)
{
	global $wp_query;
	$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;


	if($topic_type = "most_popular")
	{
		$args = array(
			'post_type'         => 'topic',
			'posts_per_page'    => 5,

			'paged' => $paged,
		);
	}

	add_filter('posts_orderby', 'orderby_most_popular_topics');
	add_filter( 'posts_clauses', 'get_topic_meta_with_query');
	$my_topics = new WP_Query($args);
	remove_filter( 'posts_clauses', 'get_topic_meta_with_query');
	remove_filter( 'posts_orderby', 'orderby_most_popular_topics');
	if($my_topics->have_posts())
	{

		?>



		<div class="clearboth"></div>
		<div class="bbf_head">
			<div class="bbf_forum_info">Topics</div>
			<div class="bbf_forum_topic_count">Views</div>
			<div class="bbf_forum_reply_count">Posts</div>
			<div class="bbf_forum_freshness">Last post</div>
			<div class="clearboth"></div>
		</div>
	<?php
		$i = 1;
			echo '<ul class="forum_subforums">';
			while($my_topics->have_posts())
			{
				global $post;
				$my_topics->the_post();
				if($i%2 == 0)
				{
					$sub_form_class = 'even';
				}else
				{
					$sub_form_class = 'odd';
				}

				?>
			<li class="bbf_heads_childs <?php echo $sub_form_class; ?>">
			<div>
				<div class="bbf_forum_info">
				<span class="forum_title"><a href="<?php the_permalink(); ?>"><?php echo get_the_title(); ?></a></span>
				<p>Started by: <a href="<?php echo USERS_URI.get_the_author_meta('ID'); ?>"><?php echo get_the_author_meta('display_name'); ?></a></p>
				</div>
				<div class="bbf_forum_topic_count"><?php echo get_views($post->ID); ?></div>
				<div class="bbf_forum_reply_count"><?php echo $post->topic_posts; ?></div>

				<div class="bbf_forum_freshness">
					<span class="bbp-topic-freshness-author">
						<!--introduction<br />-->
						 <?php if($post->last_poster_name){?>
							By <a href="<?php echo USERS_URI.$post->topic_last_poster_id; ?>"><?php echo $post->last_poster_name; ?><!--Admin--></a><br />
							<?php echo human_time_diff(strtotime($post->last_post_time)); ?> ago

							<?php }else{?>
								No post found.
							<?php } ?>
					</span>
				</div>
				<div class="clearboth"></div>
			</div>
			</li>
			<?php
			$i++;
			}
			echo '<li class="bbp_footer"></li></ul>';
			$big = 999999999;
	 ?>


	<?php
	}
	else
	{
		echo '<div class="web_boxp">No topics found.</div>';
	}
}
*/
/******************************************/
/***** get_users_replies **********/
/******************************************/

function get_users_replies($user_id){

	global $wpdb, $wp_query;

	$sql = "SELECT count(ID) as count FROM ".$wpdb->bbf_reply." WHERE user_id = $user_id";
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

	$total_pages = ceil($total_posts / $posts_per_page );
	if($page_no >= 2)
	{
		$offset = $page_no -1;
		$offset = $posts_per_page*$offset;
	}
	//db($authordata);
	//global $wpdb;
	$sql = "SELECT reply.user_id, reply.date, reply.content, reply.ID, users.user_registered, users.ID as user_id, users.user_login FROM ".$wpdb->bbf_reply." as reply INNER JOIN ".$wpdb->users." as users ON reply.user_id=users.ID WHERE reply.user_id = $user_id ORDER BY reply.date DESC LIMIT $posts_per_page OFFSET $offset";
	$results = $wpdb->get_results($sql);
	//db($results);
	?>
	<?php
if($results){
	pagination($total_pages);
}?>

	<?php

	if($results)
	{
		echo '<ul class="no_mp" style="padding:0px;">';
	$i = 1;
	foreach($results as $result)
	{
		//db($result);
	 ?>
	<li class="bbf_reply_body post_<?php echo $result->ID; ?>">
		<div class="bbf_reply_header">
			<span class="post_date"><?php echo date("F d, Y h:i:s", strtotime($result->date)); ?></span>
			<div class="post_links pull-right">
				<!--<a href="#<?php echo $result->ID; ?>" class="bb_quote_link" data-bbp-author="<?php echo $authordata->data->user_login; ?>" title="reply with quote">Quote</a>-->
				<a href="#<?php echo $current_topic_id; ?>">#<?php echo $i; ?></a>
			</div>
			<div class="clearboth"></div>
		</div>

		<div class="bbf_reply_author col-sm-4 col-md-3">
		<div class="user_info">
		<span class="username"><a href="<?php echo USERS_URI.$result->user_id ?>"><?php echo $result->user_login; ?></a></span>
		<a href="<?php echo USERS_URI.$result->user_id; ?>" class="hidden-xs"><img src="<?php echo get_user_profile_image_url($result->user_id); ?>" alt="" class="profile_image" /></a>
		<span><strong>Join Date:</strong> <?php echo date('F d, Y',strtotime($result->user_registered)); ?></span>
		<?php /*if($user_location = get_user_meta($result->user_id,'location',true)){ ?>
	 <span><strong>Location: </strong><?php echo $user_location; ?></span>
    <?php }*/ ?>

		<span><strong>Posts:</strong> <?php echo get_user_posts($result->user_id);  ?></span>
		<!--<span><strong>Have Thanks:</strong> 1</span>
		<span><strong>Has Thanked:</strong> 3 time</span>-->
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
	echo '</ul>';
	}

	else
	{
		echo '<div class="web_boxp">No topics found.</div>';
	}
	if($results){
		pagination($total_pages);
	}
}



/******************************************/
/***** ckEditor **********/
/******************************************/

function ckEditor($name='',$content='',$smileys = true)
{?>
	<div class="bbf_the_ckeditor_wrapper row">
    <div class="ckeditor_content col-md-8">
    <textarea name="<?php echo $name; ?>" id="editor1" cols="30" rows="10" class="ckeditor"><?php echo $content; ?></textarea>
    </div>
    <aside class="col-md-4">
    	<div class="ckeditor_smileys">
        	<?php echo smileys_sk(); ?>
        </div>
        <!--<p style="text-align:center;"><a href="#" class="more_smileys">[More Smileys]</a></p>-->
    </aside>
	<div class="clearboth"></div>
  </div>
<?php }

/******************************************/
/***** VisitorCounter class **********/
/******************************************/

class VisitorCounter
{

	var $sessionTimeInMin = 5; // time session will live, in minutes

  public  function __construct()
    {
        $ip = $_SERVER['REMOTE_ADDR'];
        $this->cleanVisitors();

        if ($this->visitorExists($ip))
        {

            $this->updateVisitor($ip);
        } else
        {
            $this->addVisitor($ip);
        }
    }

   public function visitorExists($ip)
    {
		global $wpdb;
		if(is_user_logged_in())
		{
			global $current_user;
			$sql = $wpdb->prepare("select * from ".$wpdb->bbf_visitcounter." where ip = %s OR memberid = %d", $ip, $current_user->ID);
		}else
		{
			$sql = $wpdb->prepare("select * from ".$wpdb->bbf_visitcounter." where ip = %s", $ip);
		}

		$results = $wpdb->get_results($sql);

        if ($results)
        {

			return true;
        } else
		{
			return false;
		}
    }

  private function cleanVisitors()
  {
		global $wpdb;
        $sessionTime = 30;
        $sql = "select * from ".$wpdb->bbf_visitcounter;
        $results = $wpdb->get_results($sql, ARRAY_A);
		if($results)
		{
			foreach($results as $result)
			{
				if (time() - $result['lastvisit'] >= $this->sessionTimeInMin * 60)
				{
					$dsql = $wpdb->prepare("delete from ".$wpdb->bbf_visitcounter." where ID = %d", $result['ID']);
					$wpdb->query($dsql);
				}
			}
		}
  }


  private  function updateVisitor($ip)
  {
 		global $wpdb;
		if(is_user_logged_in())
		{
			global $current_user;
			$sql = $wpdb->prepare("update ".$wpdb->bbf_visitcounter." set lastvisit = '" . time() . "', membetype = 'member', memberid = %d, membername = %s where ip = %s limit 1", $current_user->ID, $current_user->data->user_login, $ip);
		}else
		{
			$sql = $wpdb->prepare("update ".$wpdb->bbf_visitcounter." set lastvisit = '" . time() . "' where ip = %s limit 1", $ip);
		}

        $wpdb->query($sql);
    }


  private  function addVisitor($ip)
  {
		global $wpdb;
		if(is_user_logged_in())
		{
			global $current_user;

			$wpdb->insert( $wpdb->bbf_visitcounter,
				array(
					'ip' => $ip,
					'lastvisit' => time(),
					'membetype' => 'member',
					'membername' => $current_user->data->user_login,
					'memberid' => $current_user->ID,
				),
				array("%s", "%s", "%s", "%s", "%d")
			);
			//$sql = $wpdb->prepare("insert into ".$wpdb->bbf_visitcounter." (ID, ip ,lastvisit, membetype, membername, memberid) value(NULL, '$ip', '".time()."', 'member', '".$current_user->data->user_login."', ".$current_user->data->ID.")");
		}else
		{
			$wpdb->insert( $wpdb->bbf_visitcounter,
				array(
					'ip' => $ip,
					'lastvisit' => time(),
					'membetype' => 'guest',
				),
				array("%s", "%s", "%s")
			);
			//$sql = "insert into ".$wpdb->bbf_visitcounter." (ID, ip ,lastvisit, membetype) value(NULL, '$ip', '".time()."', 'guest')";
		}

  }

  public  function getAmountVisitors()
  {
 		global $wpdb;
    $sql = "select count(ID) as count from ".$wpdb->bbf_visitcounter;
		$results = $wpdb->get_results($sql, ARRAY_A);
		$totalVisitors = $results[0]['count'];
		$most_users_online = $totalVisitors;
		if($most_users_onlines = get_option('most_users_online'))
		{
			if($most_users_onlines < $totalVisitors)
			{
				update_option("most_users_online", $totalVisitors);
				update_option("most_users_online_date", time());
			}
			else
			{
				$most_users_online = $most_users_onlines;
			}
		}else
		{
			update_option("most_users_online", $totalVisitors);
			update_option("most_users_online_date", time());
		}

		$members_name = get_current_logged_in_users();
        return array($totalVisitors, $members_name, $most_users_online);
  }
}

/******************************************/
/***** get user sinature by id **********/
/******************************************/
function get_user_signature_by_ID($user_id)
{
	$user_signature = get_user_meta($user_id,'user_signature',true);
	if($user_signature && $user_signature != "" && $user_signature != " ")
	{?>
    <hr style="margin:20px 0; border:1px solid #ccc;" />
    <p><?php echo bbcode_to_html($user_signature); ?></p>
	<?php }
}


if(!is_admin()){
  $visitor_counter = new VisitorCounter;
}
