<?php


/******************************************/
/***** test if requst is from localhost for testing **********/
/******************************************/
function localhost()
{
	$whitelist = array('127.0.0.1', "::1");

	if(in_array($_SERVER['REMOTE_ADDR'], $whitelist)){
		return true;
	}
	else
	{
		return false;
	}
}

/******************************************/
/***** sql ready function **********/
/******************************************/

function sql_ready($string)
{
	global $wpdb;

	/*if (function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc())
	{
		$string = stripslashes($string);
	} */

	if ( isset( $wpdb->use_mysqli ) && !empty( $wpdb->use_mysqli ) )
	{
		  return mysqli_real_escape_string($wpdb->dbh, $string);
	}
	else
	{
		 return mysql_real_escape_string($string);
	}
}

function post_ready($string)
{
	//return $string;
	return bb_kses(stripcslashes(trim($string)));
}
function PostReady($string){
	return stripcslashes(trim($string));
}


/******************************************/
/***** remove div tag and its content **********/
/******************************************/
function remove_div_tag_and_its_content($string)
{

	$dom = new DOMDocument();

	//avoid the whitespace after removing the node
	$dom->preserveWhiteSpace = false;

	//parse html dom elements
	$dom->loadHTML($string);

	//get the table from dom
	if($table = $dom->getElementsByTagName('div')->item(0)) {

	   //remove the node by telling the parent node to remove the child
	   $table->parentNode->removeChild($table);

	   //save the new document

	   return $dom->saveHTML();
	}else
	{
		return $string;
	}
}


/******************************************/
/***** bb_substr function with 3 dots **********/
/******************************************/
function bb_substr($string, $trimchar = 50)
{

	if(strlen($string) > $trimchar)
	{

		$string = substr($string,0,$trimchar).' ...';
	}
	return $string;
}


/******************************************/
/***** Debug functions start from here **********/
/******************************************/
function bb_shutdown()
{
echo '<div style="color:#fff;position:fixed;bottom:20px;left:0px; background-color:#000;">'.$time = microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"].'</div>';
}
if(! ( defined( 'DOING_AJAX' ) && DOING_AJAX ) && is_user_logged_in() && current_user_can( 'manage_options' )){
	//register_shutdown_function('bb_shutdown');
}

if(!function_exists("alert")){
	function alert($alertText){
		echo '<script type="text/javascript">';
		echo "alert(\"$alertText\");";
		echo "</script>";
	}
}
if(!function_exists("db")){
	function db($array1){
		echo "<pre>";
		var_dump($array1);
		echo "</pre>";
	}
}


  /******************************************/
  /***** ArraytoSelectList **********/
  /******************************************/
  if(!function_exists("ArraytoSelectList")){
    function ArraytoSelectList($array, $sValue = ""){
      $output = '';
      foreach($array as $key=>$value){
        if($key == $sValue)
          $output .= '<option value="'.$key.'" selected="selected">'.$value.'</option>';
        else
          $output .= '<option value="'.$key.'">'.$value.'</option>';
      }
      return $output;
  	}
  }

  /******************************************/
  /***** arrayToSerializeString **********/
  /******************************************/
  if(!function_exists("ArrayToSerializeString")){
    function ArrayToSerializeString($array){
      if(isset($array) && is_array($array) && count($array) >= 1)
        return serialize($array);
      else
        return serialize(array());
    }
  }

  /******************************************/
  /***** SerializeStringToArray **********/
  /******************************************/
  if(!function_exists("SerializeStringToArray")){
    function SerializeStringToArray($string){
      if(isset($string) && is_array($string) && count($string) >= 1)
        return $string;
      elseif(isset($string) && $string && @unserialize($string)){
        return unserialize($string);
      }else
        return array();
    }
  }


if(!function_exists("BBWPUpdateErrorMessage")){
	function BBWPUpdateErrorMessage(){
		if(get_option('bbwp_update_message'))
			echo '<div class="updated"><p><strong>'.get_option('bbwp_update_message').'</strong></p></div>';
		elseif(get_option('bbwp_error_message'))
			echo '<div class="error"><p><strong>'.get_option('bbwp_error_message').'</strong></p></div>';
		update_option('bbwp_update_message', '');
		update_option('bbwp_error_message', '');
	}
}

function hidden_debug($debug_data){?>
<div style="display:none"><?php db($debug_data); ?></div>
<?php }

/******************************************/
/***** function for send email start from here **********/
/******************************************/
function send_email($to,$subject,$message1){
	$host_address = $_SERVER['HTTP_HOST'];
	if(localhost())
	{
		require_once(get_template_directory().'/lib/PHPMailer/PHPMailerAutoload.php');
		$message_body = $message1;
		$mail = new PHPMailer;

		$mail->IsSMTP();
		$mail->SMTPSecure = "ssl";
		$mail->Host       = "smtp.gmail.com"; // SMTP server
		$mail->SMTPAuth   = true;
		$mail->Port       = 465;
		$mail->Username   = "nasiranwar2020@gmail.com";
		$mail->Password   = "NasirBro";
		//$mail->addAddress('nuqtadeveloptahir@gmail.com');
		$mail->addAddress($to);

		$mail->isHTML(true);
		$mail->Subject = $subject;
		$mail->Body    = $message_body;
		$mail->send();

	}
	else
	{
		$message = '<html><head><title></title></head><body>';
		$message .= $message1;
		$message .= '</body></html>';
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers .= 'From: '.get_option('admin_email'). "\r\n";

		if(!wp_mail($to,$subject,$message,$headers))
			return false;
		else
			return true;
	}
}// function send_email end here

/******************************************/
/***** get featured image url **********/
/******************************************/
function get_feature_image_url($post_id)
{
	if(has_post_thumbnail($post_id))
	{
		$image5 = wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ), 'full' );
		return $image5[0];
	}
	else
	{
		return false;
	}
}

/******************************************/
/***** generate random integre value **********/
/******************************************/
function generate_random_int($number_values)
{
	$number_values = $number_values-2;
	$lastid = rand(0,9);
	for($i=0; $i <= $number_values; $i++)
	{
		$lastid .= rand(0,9);
	}
	return $lastid;
}

/******************************************/
/***** get_current_loggedin_user_role **********/
/******************************************/

function get_user_role() {

	global $current_user;

	$user_roles = $current_user->roles;
	$user_role = array_shift($user_roles);

	return $user_role;
}


/******************************************/
/***** is_all_numeric_values_in_array **********/
/******************************************/

function is_all_numeric_values_in_array($input_array)
{
	if(is_array($input_array) && count($input_array) >= 1)
	{

		$all_numeric = true;
		foreach ($input_array as $key) {
			if (!(is_numeric($key) && $key >= 1)) {
				$all_numeric = false;
				break;
			}
		return $all_numeric;
		}
	}else
	{
		return false;
	}
}

/******************************************/
/***** get singl post data from database **********/
/******************************************/
function get_single_post_data($post_id, $key = 'post_content'){
	$post_data = get_post($post_id,ARRAY_A);
	if($post_data && is_array($post_data) && isset($post_data[$key])){
		return $post_data[$key];
	}else{
		return false;
	}
}
