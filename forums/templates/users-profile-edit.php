<?php
global $current_user, $BBFThemeOptions;
?>
<div class="user_profile_edit">
    <form action="" method="post">
    <h3 class="entry_title">Account Detail</h2>

      <div class="row">
        <div class="col-md-5">
          <strong>Username:</strong><br>
          <small>You can't edit your username. <a href="<?php echo get_permalink($BBFThemeOptions->get_bbf_theme_option('page_contact_us_id')); ?>">Contact us</a></small>
        </div>
        <div class="col-md-5">
          <input type="text" name="" id="" value="<?php echo $user_profile_data->data->user_login; ?>" disabled="disabled">
        </div>
      </div>

      <div class="row">
        <div class="col-md-5">
          <label for="fname"><strong>First Name: </strong><spam class="forum_star">*</spam></label>
          <br />
          <small>Length must be between 3 characters and 20 characters.</small>
        </div>
        <div class="col-md-5">
          <input type="text" name="fname" id="fname" class="required" required="required" pattern=".{3,20}" title="3 min and 20 max characters" value="<?php echo get_user_meta($user_profile_data->ID,'first_name',true); ?>" />
        </div>
      </div>

      <div class="row">
        <div class="col-md-5">
          <label for="lname"><strong>Last Name:</strong></label>
          <br />
          <small>Length must be between 3 characters and 20 characters.</small>
        </div>
        <div class="col-md-5">
          <input type="text" name="lname" id="lname" value="<?php echo get_user_meta($user_profile_data->ID,'last_name',true); ?>" />
        </div>
      </div>

      <div class="row">
        <div class="col-md-5">
          <label for="email"><strong>Email address:</strong><spam class="forum_star">*</spam></label>
          <br />
          <small>Your contact email address.</small>
        </div>
        <div class="col-md-5">
          <input type="email" name="email" id="email" class="required" required="required" value="<?php echo $user_profile_data->data->user_email; ?>" />
        </div>
      </div>

      <?php /*<div class="row">
        <div class="col-md-5">
          <label for="display_name"><strong>Display Name: </strong><spam class="forum_star">*</spam></label>
          <br />
          <small>This is your public display name. It should me min 3 and max 20 chars long.</small>
        </div>
        <div class="col-md-5">
          <input type="text" class="required" required="required" pattern=".{3,20}" title="3 min and 20 max characters" name="display_name" id="display_name" value="<?php echo $user_profile_data->data->display_name; ?>" />
        </div>
      </div> */ ?>

      <div class="row">
        <div class="col-md-5">
          <label for="location"><strong>Location:</strong></label>
          <br />
          <small>Your city and country name i.e. City, Country</small>
        </div>
        <div class="col-md-5">
          <input type="text" name="location" id="location" value="<?php echo esc_html(get_user_meta($user_profile_data->ID,'location',true)); ?>" />
        </div>
      </div>

      <div class="row">
        <div class="col-md-5">
          <label for="gender"><strong>Gender:</strong></label>
        </div>
        <div class="col-md-5">
          <select name="gender" id="gender">
             <option value="Male" <?php if(get_user_meta($user_profile_data->ID,'gender',true) == 'Male'){ echo 'selected="selected"';} ?>>Male</option>
             <option value="Female" <?php if(get_user_meta($user_profile_data->ID,'gender',true) == 'Female'){ echo 'selected="selected"';} ?>>Female</option>
         </select>
        </div>
      </div>

        <?php /* <div class="form_field_container">
            <span class="form_field_left">
            <label for="fb_id"><strong>Facebook ID:</strong></label>
            </span>
            <span class="form_field_right">
            <input type="text" name="fb_id" id="fb_id" value="<?php echo get_user_meta($url_user_id,'fb_id',true); ?>" />
        </span>
        <div class="clearboth"></div>
        </div>

        <div class="form_field_container">
            <span class="form_field_left">
            <label for="skype_id"><strong>Skype ID:</strong></label>
            </span>
            <span class="form_field_right">
            <input type="text" name="skype_id" id="skype_id" value="<?php echo get_user_meta($url_user_id,'skype_id',true); ?>" />
        </span>
        <div class="clearboth"></div>
        </div>

        <div class="form_field_container">
            <span class="form_field_left">
            <label for="ingame_name"><strong>In-game name:</strong></label>
            </span>
            <span class="form_field_right">
            <input type="text" name="ingame_name" id="ingame_name" value="<?php echo get_user_meta($url_user_id,'ingame_name',true); ?>" />
        </span>
        <div class="clearboth"></div>
      </div> */ ?>

        <input type="submit" value="Submit" />
    </form>
</div>
