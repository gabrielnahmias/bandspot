<?php

/*
 ========================================================================
 |  Copyright 2007 - Andrei Dumitrache. All rights reserved.
 | 	 http://www.hogsmeade-village.com
 |
 |   This file is part of Elemovements News (X-News) v2.0.0
 |
 |   This program is Freeware.
 |   Please read the license agreement to find out how you can modify this web script.
 |
 ========================================================================
*/

@session_start();

  $nrc=6;
  $rt=5;
  $st=4;


$rt1=$rt*10;
$st1=$st*10;


$code = mt_rand();
$code = substr(sha1($code), 0, $nrc);
$code = str_replace(array('0', 'O', 'o'), rand(1, 9), $code);

$_SESSION['xnewscaptchacode']=md5($code);

$image = imagecreate(140, 30);
$bgcolor = imagecolorallocate($image, 255, 255, 255);
$linecolor = imagecolorallocate($image, rand(205,210), rand(213,218), rand(220,228));
//$textcolor = imagecolorallocate($image, 150, 150, 203);
$textcolor = imagecolorallocate($image, 179, 179, 217);

for ($i=1; $i<$rt1; $i=$i+4) {
  $x2 = rand(0,140);
  $y2 = rand(0,30);
  imageline($image, 2, $i, $x2, $y2 , $linecolor);
}

$x=1;

for ($i=0; $i<$nrc; $i++) {
 $size = rand(5, 7);
 $x = $x + rand(12 , 20);
 $y = rand(7 , 12);
 imagestring($image, $size, $x, $y, $code{$i} , $textcolor);
}


for ($i=1; $i<$st1; $i=$i+8) {
  $x2 = rand(0,140);
  $y2 = rand(0,30);
  imageline($image, 140, $i, $x2, $y2 , $linecolor);
}

header('Content-type: image/jpeg');
imagejpeg($image);
imagedestroy($image);    
?>
