<?php
global $current_user;
?>
<h2>Edit Profile Image</h2>
<p>
  <strong>Maximum dimensions;</strong>
  width: 2048 pixels, height: 2048 pixels, file size: 2 MB<br />
  <strong>Extension allowed;</strong> gif, jpg, jpeg, png
</p>

<form action="" method="post" enctype="multipart/form-data">
<p>
  <strong>Upload new image: </strong>
  <input type="file" name="profile_image" id="" style="display:inline;" />
</p>
<p>OR</p>
<p>
  <strong>New image from URL: </strong>
  <input type="url" name="profile_image_by_url" style="display:inline-block;" />
</p>
<p><input type="submit" value="Submit"></p>
</form>
<?php if($image_url = get_user_meta($current_user->ID, 'profile_image_url', true)){?>
<p><strong>Current image:</strong> <br />
  <img src="<?php echo $image_url; ?>" style="display:block; max-width:100%; margin:0 auto;" />
</p>
<?php } ?>
