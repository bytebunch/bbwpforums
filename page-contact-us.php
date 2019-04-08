<?php
session_start();
/**
*Template Name: Contact Us
*/
$errorMessage = false;
$updateMessage = false;
if(isset($_POST['user_name']) && isset($_POST['user_email']) && isset($_POST['user_message']) && isset($_POST['captcha'])){
	if(isset($_SESSION['secure_contact']) && $_SESSION['secure_contact'] == (int)$_POST['captcha']){
		$subject = "";
		if(isset($_POST['user_subject'])){
		 $subject = $_POST['user_subject'];
		}
		$_POST['user_subject'] = $subject = BBWPSanitization::Textfield($subject);
		$_POST['user_name'] = $name = BBWPSanitization::Username($_POST['user_name']);
		$_POST['user_email'] = $email = BBWPSanitization::Email($_POST['user_email']);
		$_POST['user_message'] = $message = BBWPSanitization::Textarea($_POST['user_message'], array());
	  if($name && $email && $message){
			unset($_POST);
	    $sent_message = 'Name: '.esc_html($name)."<br />Email: ".esc_html($email)."<br />Subject: ".esc_html($subject)."<br />Message: ".esc_html($message);
	    $to = get_bloginfo('admin_email');
	    $email_subject = 'Contact us - '.get_bloginfo('title');

	    send_email($to, $email_subject, $sent_message);
	    $errorMessage = 'Your message has been sent.';
	    $updateMessage = true;
	  }else{
			$errorMessage = 'Please fill all the required fields.';
		}
	}else{
		$errorMessage = 'Captcha code was incorrect, please try again.';
	}

}
get_header(); ?>

<main role="main" class="content_wrapper contact_us_wrapper">
	<div id="contact_us_map" class="hidden-xs"></div>
	<div class="container">
		<div class="row">
			<!-- alert messaaaaage start from here -->
			<?php ErrorUpdateMessage($errorMessage, $updateMessage); ?>
			<!-- alert messaaaaage end here -->
			<div class="col-xs-12 col-sm-5 col-md-5 pull-right contact_us_form">
        	<h2>Get in Touch </h2>
            <p>Please fill out the form below and we will get back to you within 1-2 business days. We look forward to serving you!</p>
            <form action="" method="post">
            	<input type="text" name="user_name" id="" required="required" placeholder="Name*" value="<?php if(isset($_POST['user_name'])){ echo $_POST['user_name']; } ?>" />
                <input type="text" name="user_subject" placeholder="subject" value="<?php if(isset($_POST['user_subject'])){ echo $_POST['user_subject']; } ?>" />
                <input type="email" name="user_email" id="" required="required" placeholder="Email*" value="<?php if(isset($_POST['user_email'])){ echo $_POST['user_email']; } ?>" />
                <textarea name="user_message" id="" cols="30" rows="5" placeholder="Message*" required="required"><?php if(isset($_POST['user_message'])){ echo $_POST['user_message']; } ?></textarea>
								<img src="<?php echo THEME_URI."lib/captcha/contact.php"; ?>" alt="captcha code" style="float:left;margin-bottom:10px;border:1px solid #ccc;">
								<input type="text" name="captcha" placeholder="Type the digits shown in above image." id="" autocomplete="off" required="required">
								<input type="submit" value="Submit" />
            </form>
        </div>
        <div class="clearboth"></div>
		</div><!-- row div end here-->
	</div><!-- container div end here-->
</main>

<?php get_footer(); ?>
