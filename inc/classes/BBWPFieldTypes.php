<?php

class BBWPFieldTypes{

  private $prefix = "";
  private $saveType = "option";
  private $dataID = '';
  private $displaytype = array("wrapper_open" => '<table class="form-table">', 'wrapper_close' => '</table>', 'container_open' => '<tr>', 'container_close' => '</tr>', 'label_open' => '<th scope="row">', 'label_close' => '</th>', 'input_open' => '<td>', 'input_close' => '</td>');

  public function __construct($prefix = ""){
    if(isset($prefix) && $prefix && is_string($prefix))
      $this->prefix = $prefix;
    /*$this->displaytype = array(
      "wrapper_open" => '<div class="form-wrap">',
      'wrapper_close' => '</div>',
      'container_open' => '<div class="form-field">',
      'container_close' => '</div>',
      'label_open' => '',
      'label_close' => '',
      'input_open' => '',
      'input_close' => ''
    );*/
  }// construct function end here

  /******************************************/
  /***** AddNewFields function start from here *********/
  /******************************************/
  public function AddNewFields($edit_field = false){
    $input_values = array();
    if($edit_field){
      $existing_values = SerializeStringToArray(get_option($this->prefix));
      if($existing_values && is_array($existing_values) && array_key_exists($edit_field, $existing_values)){
        $input_values = $existing_values[$edit_field];
      }else{
        echo '<div class="error"><p><strong>We did not find any recrod with given data.</strong></p></div>';
        return;
      }
      echo '<input type="hidden" name="update_field" value="'.$edit_field.'">';
    }else
      echo '<input type="hidden" name="update_field" value="new">';
    ?>
    <input type="hidden" name="bb_field_types_save" value="<?php echo $this->prefix("bb_field_types_save"); ?>">
    <div style="float:left;" class="form-wrap" id="col-left">
      <div class="form-field">
        <label for="field_title">Field Title <span class="require_star">*</span></label>
        <?php $selected_value = ""; if(isset($input_values['field_title'])){ $selected_value = $input_values['field_title']; } ?>
        <input type="text" name="field_title" id="field_title" class="regular-text" value="<?php echo $selected_value; ?>" required="required">
      </div>
      <div class="form-field">
        <label for="meta_key">Meta Key <span class="require_star">*</span></label>
        <?php $selected_value = ""; if(isset($input_values['meta_key'])){ $selected_value = $input_values['meta_key']; } ?>
        <input type="text" name="meta_key" id="meta_key" class="regular-text" value="<?php echo $selected_value; ?>" required="required">
      </div>
      <div class="form-field">
        <label for="field_type">Field Type <span class="require_star">*</span></label>
        <select name="field_type" id="field_type" class="<?php echo $this->prefix("field_type"); ?>" required="required">
          <?php
          $selected_value = ""; if(isset($input_values['field_type'])){ $selected_value = $input_values['field_type']; }
          $types = array(
            'text' => 'Text',
            'editor' => 'Editor',
            'image' => 'Image',
            'file' => 'Files',
            'textarea' => 'Text Area',
            'color' => 'Color Picker',
            'date' => 'Date Picker',
            'checkbox_list' => 'Check Box List',
            'checkbox' => 'Check Box',
            'select' => 'Select List',
            'password' => 'Password',
            'radio' => 'Radio Buttons',
          );
          echo ArraytoSelectList($types, $selected_value);
          ?>

        </select>
      </div>
      <div class="form-field">
        <label for="field_description">Help Text</label>
        <?php $selected_value = ""; if(isset($input_values['field_description'])){ $selected_value = $input_values['field_description']; } ?>
        <textarea name="field_description" id="field_description" cols="30" rows="5" class="regular-text"><?php echo $selected_value; ?></textarea>
        <p class="description">Tell to the user about what is the field</p>
      </div>
      <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes"></p>
  </div> <!-- style="width:50%; float:left;"  -->
    <div class="form-wrap" id="col-right" style="float:right;">
        <div class="options_of_fields" style="padding:20px; background-color:#fff;">
          <h3 style="margin:0 0 20px 0px;">Options of field</h3><p>By default on this box will be displayed a information about custom fields, after the custom field be selected, this box will be displayed some extra options of the field (if required) or a information about the selected field</p>
          <div class="hidden_fields checkbox_list select radio form-field" style="display:none;">
            <label for="field_type_values">Choices: </label>
            <?php $selected_value = ""; if(isset($input_values['field_type_values'])){ $selected_value = implode("\n", $input_values['field_type_values']); } ?>
            <textarea name="field_type_values" id="field_type_values" cols="30" rows="5" class="regular-text"><?php echo $selected_value; ?></textarea>
            <p class="description">Enter each choice on a new line.</p>
          </div>
          <div class="hidden_fields text color select radio form-field">
            <label for="default_value">Default Value: </label>
            <?php $selected_value = ""; if(isset($input_values['default_value'])){ $selected_value = $input_values['default_value']; } ?>
            <input type="text" name="default_value" id="default_value" class="regular-text" value="<?php echo $selected_value; ?>" />
          </div>
          <div class="hidden_fields text image form-field">
            <label for="field_duplicate" style="display:inline-block;">Can be duplicated: </label>
            <?php $selected_value = ""; if(isset($input_values['field_duplicate'])){ $selected_value = $input_values['field_duplicate']; } ?>
            <input type="checkbox" name="field_duplicate" id="field_duplicate" <?php if($selected_value === 'on'){ echo 'checked="checked"'; } ?> />
          </div>
        </div>
    </div>
    <div class="clearboth"></div>
    <script>
    jQuery(document).ready(function($) {
      $(".options_of_fields .hidden_fields").hide();
      var bb_field_type_value = $("select.<?php echo $this->prefix('field_type'); ?>").val();
      $(".options_of_fields ."+bb_field_type_value).show();
      $("select.<?php echo $this->prefix('field_type'); ?>").change(function(){
        bb_field_type_value = $(this).val();
        $(".options_of_fields .hidden_fields").hide();
        $(".options_of_fields ."+bb_field_type_value).show();
      });
    });
    </script>
  <?php }

  /******************************************/
  /***** DeleteField function start from here *********/
  /******************************************/
  static function DeleteFields($meta_key, $db_key){
    $existing_values = SerializeStringToArray(get_option($db_key));
    if($existing_values && is_array($existing_values) && count($existing_values) >= 1){
      if(isset($meta_key) && is_array($meta_key) && count($meta_key) >= 1){
        foreach($meta_key as $value){
          if($value && array_key_exists($value, $existing_values))
            unset($existing_values[$value]);
        }
      }
      elseif(isset($meta_key) && $meta_key && array_key_exists($meta_key, $existing_values)){
        unset($existing_values[$meta_key]);
      }
      update_option($db_key, ArrayToSerializeString($existing_values));
      update_option("bbwp_update_message", 'Your setting have been updated.');
    }
  }

  /******************************************/
  /***** SortFields function start from here *********/
  /******************************************/
  static function SortFields($newValues, $db_key){
    $existing_values = SerializeStringToArray(get_option($db_key));
    if(is_array($existing_values) && count($existing_values) >= 1 && isset($newValues) && is_array($newValues) && count($newValues) >= 1 ){
      $new_values = array();
      foreach($newValues as $value){
        if($value && array_key_exists($value, $existing_values)){
          $new_values[$value] = $existing_values[$value]; }
      }
      if(count($existing_values) == count($new_values)){
        update_option($db_key, ArrayToSerializeString($new_values));
        update_option("bbwp_update_message", 'Your setting have been updated.');
      }
    }
  }

  /******************************************/
  /***** UpdateFields function start from here *********/
  /******************************************/
  public function UpdateFields(){

    if(isset($_POST['bb_field_types_save']) && $_POST['bb_field_types_save'] === $this->prefix("bb_field_types_save")){
      if(isset($_POST['field_title']) && $_POST['field_title'] && isset($_POST['meta_key']) && $_POST['meta_key'] && isset($_POST['field_type']) && $_POST['field_type'])
      {
        $value = BBWPSanitization::Textfield($_POST['field_title']);
        $key = sanitize_key($_POST['meta_key']);
        $type = sanitize_key($_POST['field_type']);
        $existing_values = SerializeStringToArray(get_option($this->prefix));

        if(isset($_POST["update_field"]) && $_POST["update_field"] == "new" && array_key_exists($key, $existing_values)){
          update_option("bbwp_error_message",  'This meta key is already exist. Please choose new meta key or delete the old one first.');
          return;
        }

        if($value && $key && $type){

          $update_message = 'Your setting have been updated.';

          if(isset($_GET["action"]) && $_GET["action"] == "edit" && isset($_GET['page']) && isset($_GET['meta_key']))
            $update_message = '<p>Your setting have been updated.</p><p><a href="?page='.sanitize_key($_GET['page']).'">← Back to Main Page</a></p>';

          $new_field_values = array();
          $new_field_values['meta_key'] = $key;
          $new_field_values['field_title'] = $value;
          $new_field_values['field_type'] = $type;

          if(isset($_POST["default_value"])){
            $default_value = BBWPSanitization::Textfield($_POST["default_value"]);
            if($default_value)
              $new_field_values['default_value'] = $default_value;
          }
          else
            $new_field_values['default_value'] = "";

          if(isset($_POST['field_description'])){
            $field_description = BBWPSanitization::Textfield($_POST["field_description"]);
            if($field_description)
              $new_field_values['field_description'] = $field_description;
          }
          $new_field_values['field_duplicate'] = '';
          if(isset($_POST['field_duplicate'])){
            $new_field_values['field_duplicate'] = 'on';
          }

          if(($type == "checkbox_list" || $type == "select" || $type == "radio") && isset($_POST["field_type_values"]) && $_POST["field_type_values"])
          {
            $field_type_values = BBWPSanitization::Textarea($_POST["field_type_values"]);
            if($field_type_values)
              $new_field_values['field_type_values'] = array_values(array_filter(explode("\n", str_replace("\r", "", $field_type_values))));
            else{
              update_option("bbwp_error_message",  'There was some problem with '.$type.' values. Please try again.');
              return;
            }
          }

          $existing_values[$key] = $new_field_values;
          update_option($this->prefix, ArrayToSerializeString($existing_values));
          update_option("bbwp_update_message", $update_message);
          if(isset($new_field_values['default_value']) && $this->saveType === "option")
            update_option($new_field_values['meta_key'], $new_field_values['default_value']);
        }
        else
          update_option("bbwp_error_message", 'There was some problem. Please try again with different meta key name.');
      }
    }
  }

  /******************************************/
  /***** DisplayOptions function start from here *********/
  /******************************************/
  public function DisplayOptions(){
    $existing_values = SerializeStringToArray(get_option($this->prefix));
    if(isset($existing_values) && $existing_values && count($existing_values) >= 1){
      echo '<input type="hidden" name="'.$this->prefix('update_options').'" value="'.$this->prefix('update_options').'" />';
      echo $this->displaytype['wrapper_open'];

      foreach($existing_values as $value){
        echo $this->displaytype['container_open'];

        $field_description = '';
        if(isset($value['field_description']))
          $field_description = '<p class="description">'.$value['field_description'].'</p>';

        echo $this->displaytype['label_open'].'<label for="'.$value['meta_key'].'">'.$value['field_title'].'</label>'.$field_description.$this->displaytype['label_close'].$this->displaytype['input_open'];

        $default_value = "";
        if(isset($value['default_value']) && $value['default_value'])
          $default_value = $value['default_value'];

        if($this->saveType === "option")
          $selected_value = get_option($value['meta_key']);
        elseif($this->saveType === "user" && is_numeric($this->dataID) && $this->dataID >= 1)
          $selected_value = get_user_meta($this->dataID, $value['meta_key'], true);
        elseif($this->saveType === "post" && is_numeric($this->dataID) && $this->dataID >= 1)
          $selected_value = get_post_meta($this->dataID, $value['meta_key'], true);
        elseif($this->saveType === "term" && is_numeric($this->dataID) && $this->dataID >= 1)
          $selected_value = get_term_meta($this->dataID, $value['meta_key'], true);
        elseif($this->saveType === "comment" && is_numeric($this->dataID) && $this->dataID >= 1)
          $selected_value = get_comment_meta($this->dataID, $value['meta_key'], true);

        if(!(isset($selected_value) && $selected_value))
          $selected_value = $default_value;
        if(isset($value['field_duplicate']) && $value['field_duplicate'] == 'on'){
          $selected_value = SerializeStringToArray($selected_value);
        }

        if($value['field_type'] == 'text' || $value['field_type'] == 'password' ){
          if(isset($value['field_duplicate']) && $value['field_duplicate'] == 'on'){
            echo '<div><input type="text" class="field_duplicate regular-text bb_new_tag" data-name="'.$value['meta_key'].'" />
            <input type="button" class="button tagadd bb_tagadd" value="Add"><div class="bbtagchecklist input_bbtagchecklist">';
            if($selected_value && is_array($selected_value) && count($selected_value) >= 1){
              foreach ($selected_value as $field_type_value) {
                echo '<span><input type="text" value="'.$field_type_value.'" name="'.$value['meta_key'].'[]" class="regular-text" /><a href="#" class="bb_delete_it bb_dismiss_icon">&nbsp;</a></span>';
              }
            }
            echo '</div></div>';
          }
          else
            echo '<input type="'.$value['field_type'].'" name="'.$value['meta_key'].'" id="'.$value['meta_key'].'" value="'.$selected_value.'" class="regular-text">';
        }
        elseif($value['field_type'] == 'image'){
          if(isset($value['field_duplicate']) && $value['field_duplicate'] == 'on'){
            //<p class="description">You can use Ctrl+Click to select multiple images from media library.</p>
            echo '<input type="button" id="" class="bytebunch_multiple_upload_button button" value="Select Images" data-name="'.$value['meta_key'].'">';
            echo '<div class="bb_multiple_images_preview bb_image_preview">';
            if($selected_value && is_array($selected_value) && count($selected_value) >= 1){
              foreach ($selected_value as $field_type_value) {
                echo '<span><img src="'.$field_type_value.'"><a href="#" class="bb_dismiss_icon bb_delete_it">&nbsp;</a><input type="hidden" name="'.$value['meta_key'].'[]" value="'.$field_type_value.'" /></span>';
              }
            }
            echo '<div class="clearboth"></div></div>';
          }else{
            echo '<input type="text" name="'.$value['meta_key'].'" id="'.$value['meta_key'].'" value="'.$selected_value.'" class="regular-text">
            <input type="button" id="" class="bytebunch_file_upload_button button" value="Select Image">';
            echo '<div class="bb_single_image_preview bb_image_preview">';
            if($selected_value){
              echo '<span><img src="'.$selected_value.'"><a href="#" class="bb_dismiss_icon">&nbsp;</a></span>';
            }
            echo '<div class="clearboth"></div></div>';
          }

        }
        elseif($value['field_type'] == 'file'){
          echo '<input type="text" name="'.$value['meta_key'].'" id="'.$value['meta_key'].'" value="'.$selected_value.'" class="regular-text">
              <input type="button" id="" class="bytebunch_file_upload_button button" value="Upload File">';
        }
        elseif($value['field_type'] == 'editor'){
          $setting = array('textarea_rows' => 10, 'textarea_name' => $value['meta_key'], 'teeny' => false, 'tinymce' => true, 'quicktags' => true);
          wp_editor($selected_value, $value['meta_key'], $setting);
        }
        elseif($value['field_type'] == 'textarea'){
          echo '<textarea name="'.$value['meta_key'].'" id="'.$value['meta_key'].'" rows="5">'.$selected_value.'</textarea>';
        }
        elseif($value['field_type'] == 'color'){
          echo '<input type="text" name="'.$value['meta_key'].'" id="'.$value['meta_key'].'" value="'.$selected_value.'" class="bytebunch-wp-color-picker regular-text">';
        }
        elseif($value['field_type'] == 'date'){
          echo '<input type="text" name="'.$value['meta_key'].'" id="'.$value['meta_key'].'" value="'.$selected_value.'" class="bytebunch-wp-date-picker regular-text">';
        }
        elseif($value['field_type'] == 'select'){
          echo '<select name="'.$value['meta_key'].'" id="'.$value['meta_key'].'">';
          foreach($value['field_type_values'] as $field_type_value){
            if($field_type_value == $selected_value)
              echo '<option value="'.$field_type_value.'" selected="selected">'.$field_type_value.'</option>';
            else
              echo '<option value="'.$field_type_value.'">'.$field_type_value.'</option>';
          }
          echo '</select>';
        }
        elseif($value['field_type'] == 'radio'){
          foreach($value['field_type_values'] as $key=>$field_type_value){
            if($field_type_value == $selected_value)
              echo ' <input type="radio" id="'.$value['meta_key'].$key.'" value="'.$field_type_value.'" name="'.$value['meta_key'].'" checked="checked" /> <label for="'.$value['meta_key'].$key.'">'.$field_type_value.'</label> ';
            else
              echo ' <input type="radio" id="'.$value['meta_key'].$key.'" value="'.$field_type_value.'" name="'.$value['meta_key'].'" /> <label for="'.$value['meta_key'].$key.'">'.$field_type_value.'</label> ';
            echo '&nbsp;&nbsp;';
          }
        }
        elseif($value['field_type'] == 'checkbox'){
          if($selected_value)
            echo '<input type="'.$value['field_type'].'" name="'.$value['meta_key'].'" id="'.$value['meta_key'].'" checked="checked">';
          else
            echo '<input type="'.$value['field_type'].'" name="'.$value['meta_key'].'" id="'.$value['meta_key'].'">';
        }
        elseif($value['field_type'] == 'checkbox_list'){
          $selected_value = SerializeStringToArray($selected_value);
          if(!($selected_value && is_array($selected_value)))
            $selected_value = array();
          foreach($value['field_type_values'] as $key=>$field_type_value){
            if(in_array($field_type_value, $selected_value))
              echo ' <input type="checkbox" id="'.$value['meta_key'].$key.'" value="'.$field_type_value.'" name="'.$value['meta_key'].'[]" checked="checked" /> <label for="'.$value['meta_key'].$key.'">'.$field_type_value.'</label> ';
            else
              echo ' <input type="checkbox" id="'.$value['meta_key'].$key.'" value="'.$field_type_value.'" name="'.$value['meta_key'].'[]" /> <label for="'.$value['meta_key'].$key.'">'.$field_type_value.'</label> ';
            echo '&nbsp;&nbsp;';
          }
        }
        echo $this->displaytype['input_close'];
        echo $this->displaytype['container_close'];
      }
      echo $this->displaytype['wrapper_close'];
    }
  }

  /******************************************/
  /***** SaveOptions function start from here *********/
  /******************************************/
  public function SaveOptions(){
    $existing_values = SerializeStringToArray(get_option($this->prefix));
    if(isset($existing_values) && $existing_values && count($existing_values) >= 1){
      if(isset($_POST[$this->prefix("update_options")]) && $_POST[$this->prefix("update_options")] === $this->prefix("update_options"))
      {
        foreach($existing_values as $value){
          $dbvalue = "";
          if(isset($_POST[$value['meta_key']]) && $_POST[$value['meta_key']]){
            if(is_array($_POST[$value['meta_key']]) && count($_POST[$value['meta_key']]) >= 1){
              $dbvalue = array();
              foreach($_POST[$value['meta_key']] as $selected_value){
                $selected_value = BBWPSanitization::Textfield($selected_value);
                if($selected_value)
                  $dbvalue[] = $selected_value;
              }
            }
            else{
                if($value['field_type'] == 'textarea' || $value['field_type'] == 'editor'){
                  $dbvalue = BBWPSanitization::Textarea($_POST[$value['meta_key']]); }
                else{
                  $dbvalue = BBWPSanitization::Textfield($_POST[$value['meta_key']]); }
            }
          }
          else{
            if(isset($value['default_value']))
              $dbvalue = $value['default_value'];
          }

          if(is_array($dbvalue))
            $dbvalue = ArrayToSerializeString($dbvalue);

          if($this->saveType === "option")
              update_option($value['meta_key'], $dbvalue);
          elseif($this->saveType === "user" && is_numeric($this->dataID) && $this->dataID >= 1)
              update_user_meta($this->dataID, $value['meta_key'], $dbvalue);
          elseif($this->saveType === "post" && is_numeric($this->dataID) && $this->dataID >= 1)
            update_post_meta($this->dataID, $value['meta_key'], $dbvalue);
          elseif($this->saveType === "term" && is_numeric($this->dataID) && $this->dataID >= 1)
              update_term_meta($this->dataID, $value['meta_key'], $dbvalue);
          elseif($this->saveType === "comment" && is_numeric($this->dataID) && $this->dataID >= 1)
              update_comment_meta($this->dataID, $value['meta_key'], $dbvalue);
        }

        if($this->saveType == "option")
          update_option("bbwp_update_message", 'Your setting have been updated.');
      }
    }
  }

  /******************************************/
  /***** Set function start from here *********/
  /******************************************/
  public function Set($property, $value = NULL){
    if(isset($property) && $property){
      if(isset(self::$$property))
        self::$$property = $value;
      else
        $this->$property = $value;
    }
  }

  /******************************************/
  /***** prefix function start from here *********/
  /******************************************/
  public function prefix($string = '', $underscore = "_"){
    return $this->prefix.$underscore.$string;
  }

}
