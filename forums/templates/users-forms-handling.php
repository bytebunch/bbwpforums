<?php
global $wpdb;
$current_mysql_time = current_time('mysql');
/******************************************/
/***** Edit signature forms **********/
/******************************************/
if(isset($_POST['edit_signature']) && $_POST['edit_signature'] && $_POST['edit_signature'] != " " && is_user_logged_in() && $current_user->ID == $url_user_id)
{
	$user_signature = bb_kses(stripcslashes(trim($_POST['edit_signature'])));
	if($user_signature && $user_signature != " ")
	{
		update_user_meta($current_user->ID, 'user_signature', $user_signature);
		/*db($user_signature);
		db(sql_ready($user_signature));
		exit();*/
		$errorMessage = "Your signature has been updated successfully.";
		$updateMessage = true;
	}
}

/******************************************/
/***** Change password start **********/
/******************************************/
if(isset($_POST['oldpassword']) && isset($_POST['password']) && isset($_POST['cpassword']) && is_user_logged_in() && $current_user->ID === $user_profile_data->ID)
{
	$oldpassword = $_POST['oldpassword'];
	$password = $_POST['password'];
	$cpassword = $_POST['cpassword'];

	if ( wp_check_password( $oldpassword, $current_user->data->user_pass, $current_user->ID) ){

		if(strlen($password) >= 6 && $password == $cpassword)
		{
			/* add new user to wow datbase */
			if(class_exists("BBWoW")){
				$bbwow = new BBWoW();
				$bbwow->UpdateUser($current_user->user_login, $password, $current_user->user_email);
				unset($bbwow);
				$bbwow = new BBWoW("mop_auth");
				$bbwow->UpdateUser($current_user->user_login, $password, $current_user->user_email);
				unset($bbwow);
			}

			wp_set_password( $password, $current_user->ID );
			$errorMessage = 'Your password has been successfully changed. Please login again to get access to your account. <a href="'.get_permalink($BBFThemeOptions->get_option("page_login_id")).'">Login</a>';
			$updateMessage = true;
		}else
			$errorMessage = "Your password must be between 6 characters and 100 characters. Type same password in both fields.";
	}
	else
		 $errorMessage = 'Please type your correct old password.';
}

/******************************************/
/***** Profile image forms start **********/
/******************************************/
if((isset($_FILES["profile_image"]) || isset($_POST['profile_image_by_url'])) && is_user_logged_in() && $current_user->ID === $user_profile_data->ID)
{

	if(!class_exists("BBWPImageUpload"))
		include_once(THEME_ABS."inc/classes/BBWPImageUpload.php");
	if(!class_exists("BBWPImageResize"))
		include_once(THEME_ABS."inc/classes/BBWPImageResize.php");

		if(isset($_FILES["profile_image"]) && isset($_FILES["profile_image"]['tmp_name']) && $_FILES["profile_image"]['tmp_name']){
			$BBWPImageUpload = new BBWPImageUpload($_FILES["profile_image"]); }
		elseif(isset($_POST['profile_image_by_url']) && $_POST['profile_image_by_url'])
			$BBWPImageUpload = new BBWPImageUpload($_POST['profile_image_by_url']);

		if(isset($BBWPImageUpload)){
			$BBWPImageUpload->SetSize(2);
			$BBWPImageUpload->Set('name', ABSPATH."wp-content/uploads/users/".$current_user->ID."_".time('now')."_".generate_random_int(5));
			$BBWPImageUpload->Set('maxWidth', 2048);
			$BBWPImageUpload->Set('maxHeight', 2048);
			$BBWPImageUpload->Set('resize', true);
			$BBWPImageUpload->Set('width', 768);
			$BBWPImageUpload->Set('height', 768);
			$upload = $BBWPImageUpload->upload();
			if($upload == false)
				$errorMessage = $BBWPImageUpload->error;
			else{
				delete_user_profile_image($current_user->ID);
				$image_uri = str_replace(ABSPATH, HOME_URL,$upload);
				update_user_meta($current_user->ID, 'profile_image_url', $image_uri);
				$updateMessage = true;
				$errorMessage = 'Your profile image has been updated.';
			}
		}
}


/******************************************/
/***** Edit user profile **********/
/******************************************/
if(isset($_POST['email']) /*&& isset($_POST['display_name'])*/ && isset($_POST['fname'])  && is_user_logged_in() && $current_user->ID === $user_profile_data->ID)
{
	$email = BBWPSanitization::Email($_POST['email']);
	$fname = BBWPSanitization::Textfield($_POST['fname']);
	//$display_name = BBWPSanitization::Textfield($_POST['display_name']);
	$lname = '';
	$gender = 'Male';
	$location = '';
	if(isset($_POST['lname']) && $_POST['lname'] != "")
		$lname = BBWPSanitization::Textfield($_POST['lname']);
	if(isset($_POST['gender']) && ($_POST['gender'] === "Male" || $_POST['gender'] === "Female"))
		$gender = BBWPSanitization::Username($_POST['gender']);
	if(isset($_POST['location']) && $_POST['location'] != "")
		$location = BBWPSanitization::Textfield($_POST['location']);

	global $current_user;

	if(!$email)
			$errorMessage = "Please type your correct email address.";
	if(isset($_POST['lname']) && $_POST['lname'] != "" && (strlen($lname) > 20 || strlen($lname) < 3)){
		$errorMessage = "Your last name must be between 3 characters and 20 characters."; }
	if (!ctype_alnum ($fname) || strlen($fname) < 3 || strlen($fname) > 20)
		$errorMessage = "Your first name must be between 3 characters and 20 characters. You can use only alphabet and numeric characters.";
	/*if (!ctype_alnum ($display_name) || strlen($display_name) < 3 || strlen($display_name) > 20)
		$errorMessage = "Your Display Name must be between 3 characters and 20 characters. You can use only alphabet and numeric characters.";*/
	if ($email != $current_user->data->user_email && email_exists($email))
			$errorMessage = "That email is already registered to a user.";

	if($errorMessage == false)
	{
		$userdata = array("ID" => $current_user->ID, "first_name" => $fname, "last_name" => $lname, "user_email" => $email/*, "display_name" => $display_name*/);
		wp_update_user( $userdata );
		update_user_meta($current_user->ID, 'gender', $gender);
		update_user_meta($current_user->ID, 'location', $location);
		//update_user_meta($url_user_id, 'fb_id', sql_ready(post_ready($_POST['fb_id'])));
		//update_user_meta($url_user_id, 'skype_id', sql_ready(post_ready($_POST['skype_id'])));
		//update_user_meta($url_user_id, 'ingame_name', sql_ready(post_ready($_POST['ingame_name'])));
		$errorMessage = "Your data has been updated successfully.";
		$updateMessage = true;
	}
}

/******************************************/
/***** Compose new message **********/
/******************************************/
if(isset($_POST['to']) && isset($_POST['subject']) && isset($_POST['compose_message'])  && is_user_logged_in() && $current_user->ID == $url_user_id)
{
	$to = post_ready($_POST['to']);
	$subject = post_ready($_POST['subject']);
	$message = post_ready($_POST['compose_message']);

	if (strlen($subject) < 3 || strlen($subject) > 150)
	{
		$errorMessage = "Your subject must be between 3 characters and 150 characters. You can use only alphabet characters.";
	}
	if($message == '')
	{
		$errorMessage = 'Please type your message.';
	}
	if($to == '')
	{
		$errorMessage = 'Type the username of the person, who you want to send this message.';
	}
	elseif($to != '' && $errorMessage == false)
	{
		$user_names = explode(',', $to);
		foreach($user_names as $user_name)
		{
			$user = false;
			$user = get_user_by('login',trim($user_name));

			if($user)
			{
				//db($user);
				$sql = 'INSERT INTO '.$wpdb->bbf_messages.' (author_id, author_name, user_id, user_name, subject, message, time) VALUES ('.$current_user->ID.', "'.$current_user->data->user_login.'", '.$user->ID.', "'.$user->data->user_login.'", "'.sql_ready($subject).'", "'.sql_ready($message).'", "'.$current_mysql_time.'")';
				$wpdb->query($sql);
				$errorMessage = 'Your message has been sent.';
				$updateMessage = true;

				if(isset($_POST['to'])){ unset($_POST['to']); }
				if(isset($_POST['subject'])){ unset($_POST['subject']); }
				if(isset($_POST['compose_message'])){ unset($_POST['compose_message']); }


			}
			elseif($user == false && count($user_names) == 1)
			{
				$errorMessage = 'No user found with the provided username.';
			}
		}
	}
}

/******************************************/
/***** delete messages **********/
/******************************************/
if(isset($_POST['delete_messages']) && isset($_POST['mark_options']) && $_POST['mark_options'] == 'delete_marked' && is_user_logged_in() && $current_user->ID == $url_user_id)
{
	if(is_array($_POST['delete_messages']))
	{
		foreach($_POST['delete_messages'] as $message_id)
		{
			if(is_numeric($message_id) && $message_id >= 1)
			{
				$sql = 'UPDATE '.$wpdb->bbf_messages.' SET deleted=1 WHERE ID = '.sql_ready($message_id).' AND user_id = '.$current_user->ID;
				$wpdb->query($sql);
				$errorMessage = 'Your message has been deleted.';
				$updateMessage = true;
			}
		}
	}
}

/******************************************/
/***** Send Email **********/
/******************************************/

if(isset($_POST['eto']) && isset($_POST['esubject']) && isset($_POST['compose_email'])  && is_user_logged_in() && $current_user->ID == $url_user_id){

	$to = post_ready($_POST['eto']);
	$subject = post_ready($_POST['esubject']);
	$message = post_ready($_POST['compose_email']);

	if (strlen($subject) < 3 || strlen($subject) > 150)
	{
		$errorMessage = "Your subject must be between 3 characters and 150 characters. You can use only alphabet characters.";
	}
	if($message == '')
	{
		$errorMessage = 'Please type your message.';
	}
	if($to == '')
	{
		$errorMessage = 'Type the username of the person, who you want to send this message.';
	}
	if($to != '' && $errorMessage == false)
	{
		$user = get_user_by('login',trim($to));
		if($user)
		{
			send_email($user->data->user_email, $subject, $message);
			$errorMessage = 'Your message has been sent.';
			$updateMessage = true;
			unset($_POST);
		}else{
			$errorMessage = 'No user found with the provided username.';
		}
	}
}
