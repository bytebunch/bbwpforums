<?php
/*------------------------------------*\
	Simple captcha with white background
\*------------------------------------*/
/*session_start();
$_SESSION['secure']=rand(11, 99).rand(11, 99);
$text = $_SESSION['secure'];
$font_size = 10;
$image_width = 60;
$image_height = 33;
$image = imagecreate($image_width, $image_height);
imagecolorallocate($image, 255, 255, 255);
$text_color = imagecolorallocate($image, 0,0,0);
imagettftext($image,15,0,5,24,$text_color,"cap_fonts/courbd.ttf",$text);
header('Content-type: image/png');
imagepng($image);
imagedestroy($image);
*/



/*------------------------------------*\
	Simple captcha with white(gray lines) background
  Downloaded from: http://www.the-art-of-web.com/php/captcha/
\*------------------------------------*/
  // initialise image with dimensions of 120 x 30 pixels
  $image = @imagecreatetruecolor(120, 30) or die("Cannot Initialize new GD image stream");

  // set background to white and allocate drawing colours
  $background = imagecolorallocate($image, 0xFF, 0xFF, 0xFF);
  imagefill($image, 0, 0, $background);
  $linecolor = imagecolorallocate($image, 0xCC, 0xCC, 0xCC);
  $textcolor = imagecolorallocate($image, 0x33, 0x33, 0x33);

  // draw random lines on canvas
  for($i=0; $i < 6; $i++) {
    imagesetthickness($image, rand(1,3));
    imageline($image, 0, rand(0,30), 120, rand(0,30), $linecolor);
  }

  session_start();

  // add random digits to canvas
  $digit = '';
  for($x = 15; $x <= 95; $x += 20) {
    $digit .= ($num = rand(0, 9));
    imagechar($image, rand(4, 5), $x, rand(2, 14), $num, $textcolor);
  }

//imagettftext($image,13,0,5,24,$textcolor,"cap_fonts/courbd.ttf",$digit);
  // record digits in session variable
  $_SESSION['secure'] = $digit;

  // display image and clean up
  header('Content-type: image/png');
  imagepng($image);
  imagedestroy($image);
?>
