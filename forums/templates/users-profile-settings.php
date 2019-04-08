<div class="user_profile_settings user_profile_edit">
    <form action="" method="post">
    <h3 class="entry_title">Change Your Password</h2>
        <!--<p>
            <label for="profile_image" id="">Profile Image</label>
            <input type="text" name="first_name" id="first_name" />
        </p>-->
        <div class="row">
          <div class="col-md-5">
            <strong>Old Password:</strong><br>
          </div>
          <div class="col-md-5">
            <input type="password" name="oldpassword" id="oldpassword" class="form-control" required="required">
          </div>
        </div>

        <div class="row">
          <div class="col-md-5">
            <strong>New Password:</strong><br>
          </div>
          <div class="col-md-5">
            <input type="password" pattern=".{6,100}" title="6 characters minimum" required="required" class="required" id="password" name="password">
          </div>
        </div>

        <div class="row">
          <div class="col-md-5">
            <strong>Repeat New Password:</strong><br>
          </div>
          <div class="col-md-5">
            <input type="password" required="required" class="required" id="cpassword" name="cpassword">
          </div>
        </div>

        <input type="submit" value="Change Password" />
    </form>
</div>
