<?php
global $current_user;
?>
<div class="user_profile_edit">
<form action="<?php echo USERS_URI.$current_user->ID; ?>/compose/" method="post">
    <h3 class="entry_title">Compose New Message</h2>

    <div class="row">
      <div class="col-md-5">
        <label for="to"><strong>To:</strong><spam class="forum_star">*</spam></label>
        <br />
        <small>To send this message to multiple users type their usernames seperated by ,</small>
      </div>
      <div class="col-md-5">
      <?php
			$to = '';
			if(isset($_POST['to']))
			{
				$to = $_POST['to'];
			}
			if(isset($_GET['to']))
			{
				$to = $_GET['to'];
			}
		 ?>
      	<input type="text" name="to" id="to" class="required" required="required" value="<?php echo $to; ?>" />
      </div>
    </div>

    <div class="row">
      <div class="col-md-5">
        <label for="subject"><strong>Subject:</strong><spam class="forum_star">*</spam></label>
      </div>
      <div class="col-md-5">
        <?php
    		$posted_subject = '';
    		if(isset($_POST['subject'])){ $posted_subject = str_replace(array("Re: ", "Re:"), array("",""), $_POST['subject']); }
    		if(isset($_GET['subject'])){ $posted_subject = str_replace(array("Re: ", "Re:"), array("",""), $_GET['subject']);}
    		if(isset($_GET['reply'])){ $posted_subject = 'Re: '.$posted_subject; }
    		 ?>
      	<input type="text" name="subject" id="subject" class="required" required="required" value="<?php echo $posted_subject;  ?>" />
      </div>
    </div>


    <div class="form_field_container">
        <span class="form_field_left">
            <strong>Message:</strong><spam class="forum_star">*</spam>
        </span>
    <div class="clearboth"></div>
    <?php
		if(isset($_POST['compose_message'])){ $message_content = $_POST['compose_message']; }else{ $message_content = ''; }
		ckEditor('compose_message', $message_content);
	 ?>
    </div>

    <input type="submit" value="Submit" />
</form>
</div>
