<?php
class BBFThemeOptionsPage{

  public function __construct(){
    add_action( 'admin_menu', array($this,'admin_menu'));
  }

  public function admin_menu(){
    add_theme_page('Theme Options', 'Theme Options', 'manage_options','theme_options', array($this,'theme_options'));
  }

  public function theme_options(){
    $this->SaveOptions();
    BBWPUpdateErrorMessage();
    global $BBFThemeOptions;
  ?>
    <div class="wrap bytebunch_admin_page_container"><div id="icon-tools" class="icon32"></div>
    <h2> Theme Options </h2>
    <form method="post" action="">
      <input type="hidden" name="bbf_theme_options_page" value="update">
    <h2 class="nav-tab-wrapper bbwp_nav_wrapper">
      <a href="#bbf_general_settings" class="nav-tab">General Settings</a>
      <a href="#bbf_home_settings" class="nav-tab">Home Page Settings</a>
      <a href="#email_templates" class="nav-tab">Email Templates</a>
    </h2>
    <div class="bbwp_tab_nav_content" id="bbf_general_settings">
      <table class="form-table">
        <tbody>
          <tr>
            <th scope="row"><label for="verify_email">Verify Email</label></th>
            <td><input type="radio" name="verify_email" id="" value="yes" <?php checked($BBFThemeOptions->get_option('verify_email'), "yes"); ?>> Yes &nbsp;&nbsp;&nbsp; <input type="radio" name="verify_email" id="" value="no" <?php checked($BBFThemeOptions->get_option('verify_email'), "no"); ?>> No</td>
          </tr>
        </tbody>
      </table>
    </div>
    <div class="bbwp_tab_nav_content" id="bbf_home_settings" style="display:none;">
      <?php
      $BBWPFieldTypes = new BBWPFieldTypes('bbf_home_page_settings');
      $BBWPFieldTypes->SaveOptions();
      $BBWPFieldTypes->DisplayOptions();
      ?>
    </div>
    <div class="bbwp_tab_nav_content" id="email_templates" style="display:none;">
      <table class="form-table">
        <tbody>
          <tr><th scope="row">Coming Soon</th>
          </tr>
        </tbody>
      </table>
    </div>
    <?php
    //echo '<h2>General Settings</h2>';
    //$BBWPFieldTypes = new BBWPFieldTypes($this->prefix($key));
    //$BBWPFieldTypes->SaveOptions();
    //$BBWPFieldTypes->DisplayOptions();
    submit_button();
    echo '</form>';
    echo '</div>';
  }

  public function SaveOptions(){
    if(isset($_POST['bbf_theme_options_page']) && $_POST['bbf_theme_options_page'] == 'update'){
      global $BBFThemeOptions;
      $BBFThemeOptions->set_option('verify_email', $_POST['verify_email']);
      update_option('bbwp_update_message', 'Your settings have been updated.');
    }
  }

}
$BBFThemeOptionsPage = new BBFThemeOptionsPage();
