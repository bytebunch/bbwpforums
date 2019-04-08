<?php

class BBWPImageUpload{

  private $_files = array();
  private $file_url = '';
  public $error = "";
  protected $name;
  protected $resize = false;
  protected $maxWidth;
  protected $maxHeight;
  protected $size;
  protected $width;
  protected $height;
  protected $mime;
  protected $allowedExts = array("jpg", "png", "gif");
  protected $imageMimes = array(
      1 => "gif", "jpg", "png", "swf", "psd",
      "bmp", "tiff", "jpc", "jp2", "jpx",
      "jb2", "swc", "iff", "wbmp", "xmb", "ico"
  );
  protected $allowedMimeTypes = array("image/jpeg", "image/jpg", "image/gif", "image/png");

  public function __construct($_files = array()){
    $this->name = THEME_ABS.time('now');
    if(is_array($_files)){
      if(isset($_files['error']) && $_files['error'])
        $this->error = 'There was some problems with your file. We couldn\'t upload it. You can try again.';
      else{
        $this->_files = $_files;
        $this->file_url = $_files['tmp_name'];
      }
    }
    else
      $this->file_url = $_files;
  }

  protected function getImageMime($tmp_name)
  {
    $exif_type = @exif_imagetype($tmp_name);
    if($exif_type && isset($this->imageMimes[$exif_type]))
      return $this->imageMimes[$exif_type];
    else{ //db($tmp_name);
      $this->error = 'There was some problems with your file. We couldn\'t upload it. You can try again.';
      return false;
    }
  }

  /******************************************/
  /***** Set function start from here *********/
  /******************************************/
  public function Set($property, $value = NULL){
    if(isset($property) && $property){
        $this->$property = $value;
    }
  }
  public function SetSize($mb){
    $this->size = ($mb)*(1024*1024);
  }

  public function upload()
  {
    $this->mime = $this->getImageMime($this->file_url);

    $ext = implode(", ", $this->allowedExts);
    $allowedsize = number_format(($this->size)/(1024*1024), 2, '.', '');

    if($this->mime){
      if (in_array($this->mime, $this->allowedExts)) {
        if($this->_files && is_array($this->_files) && count($this->_files) >= 1){
          if($this->size && $this->_files['size'] > $this->size){
            $this->error = "Your file size is very large max file size allowed is ".$allowedsize." MB"; }
            $image = $this->file_url;
        }
        else{
          $image = $this->name."_tmp.".$this->mime;
          $imagesize = @file_put_contents($image, @file_get_contents($this->file_url));
          if($imagesize && isset($this->size) && $imagesize > $this->size)
            $this->error = "Your file size is very large max file size allowed is ".$allowedsize." MB";
          elseif(!$imagesize)
            $this->error = 'There was some problems with your file. We couldn\'t upload it. You can try again.';
        }
      }
      else
      $this->error = "Invalid File! Only ($ext) image types are allowed";
    }
    else
      $this->error = "Invalid File! Only ($ext) image types are allowed";

    if(isset($image) && $image && $this->error == ""){
      $image_meta_data = @getimagesize($image);
      if($image_meta_data && isset($image_meta_data['mime']) && in_array($image_meta_data['mime'], $this->allowedMimeTypes)){
        if($this->maxWidth && $image_meta_data[0] > $this->maxWidth)
          $this->error = "<strong>Maximum dimensions allowed:</strong> width: ".$this->maxWidth." pixels, height: ".$this->maxHeight." pixels";
        if($this->maxHeight && $image_meta_data[1] > $this->maxHeight)
          $this->error = "<strong>Maximum dimensions allowed:</strong> width: ".$this->maxWidth." pixels, height: ".$this->maxHeight." pixels";
        if($this->error === "" && $this->resize === true && $this->width && $this->height && class_exists("BBWPImageResize") && ($image_meta_data[0] > $this->width || $image_meta_data[1] > $this->height)){
          $resizeObj = new BBWPImageResize($image, $this->mime);
          $resizeObj -> resizeImage($this->width, $this->height, "auto");
          $resizeObj -> saveImage($this->name.".".$this->mime, 100);
        }
        elseif($this->error === "" && $this->_files && is_array($this->_files) && count($this->_files) >= 1){
          $moveUpload = @move_uploaded_file($this->_files["tmp_name"], $this->name.".".$this->mime);
          if (false === $moveUpload)
            $this->error = 'There was some problems with your file. We couldn\'t upload it. You can try again.';
        }elseif($this->error === ""){
          $moveUpload = rename($image, $this->name.".".$this->mime);
          if (false === $moveUpload)
            $this->error = 'There was some problems with your file. We couldn\'t upload it. You can try again.';
        }
      }else
        $this->error = "Invalid File! Only ($ext) image types are allowed";
    }

    if(@file_exists($image))
      @unlink($image);

    if($this->error)
      return false;
    else
      return $this->name.".".$this->mime;
  }

}
