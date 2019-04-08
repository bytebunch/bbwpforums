<?php
global $current_user;
?>
<div class="user_profile_edit">
<!-- <h3><strong>Note:</strong> This feature is not working yet, It wil be fixed soon.</h3> -->
<form action="<?php echo USERS_URI.$current_user->ID; ?>/email/" method="post">

    <!--<h3 class="entry_title">Compose New Email</h3>-->

    <div class="row">
      <div class="col-md-5">
        <label for="eto"><strong>Email To:</strong><spam class="forum_star">*</spam></label>
      </div>
      <div class="col-md-5">
      <?php
			$to = '';
			if(isset($_POST['eto']))
			{
				$to = $_POST['eto'];
			}
			if(isset($_GET['eto']))
			{
				$to = $_GET['eto'];
			}
		 ?>

        	<input type="text" name="eto" id="eto" class="required" required="required" value="<?php echo $to; ?>" />
      </div>
    </div>

    <div class="row">
      <div class="col-md-5">
        <label for="esubject"><strong>Subject:</strong><spam class="forum_star">*</spam></label>
      </div>
      <div class="col-md-5">
        <?php
		$posted_subject = '';

		if(isset($_POST['esubject'])){ $posted_subject = $_POST['esubject']; }

		 ?>
        	<input type="text" name="esubject" id="esubject" class="required" required="required" value="<?php echo $posted_subject;  ?>" />
      </div>
    </div>

    <div class="form_field_container">
        <span class="form_field_left">
            <strong>Message:</strong><spam class="forum_star">*</spam>
        </span>
    <div class="clearboth"></div>
    <?php
		if(isset($_POST['compose_email']) && $updateMessage == false){ $message_content = $_POST['compose_email']; }else{ $message_content = ''; }
		ckEditor('compose_email', $message_content);
	 ?>
    </div>

    <input type="submit" value="Submit" />
</form>
</div>
