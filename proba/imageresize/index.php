<?php
function resizeimage($filename, $size) {
// Set new sizes
    list($width, $height) = getimagesize($filename);
    if ($width > $height) {
        $percent = 1 / ($width / $size);
    } else {
        $percent = 1 / ($height / $size);
    }
    $newwidth = $width * $percent;
    $newheight = $height * $percent;

// Load
    $thumb = imagecreatetruecolor($newwidth, $newheight);
    $source = imagecreatefromjpeg($filename);
// Resize
   imagecopyresized($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
   $thumb = watermark_image($thumb, $percent, array($newwidth, $newheight));
   //exit();
   return $thumb;
}

function watermark_image($image, $percent, $imageSize) {
//set watermark
    $watermark = imagecreatefrompng('water.png');
    $watermark_o_width = imagesx($watermark);
    $watermark_o_height = imagesy($watermark);
    $newWatermarkWidth = ($imageSize[0] - 20) * 0.7;
    $newWatermarkHeight = $watermark_o_height * $newWatermarkWidth / $watermark_o_width;
//combine image
    imagecopyresized($image, $watermark, $imageSize[0] / 2 - $newWatermarkWidth / 2, $imageSize[1] / 2 - $newWatermarkHeight / 2, 0, 0, $newWatermarkWidth, $newWatermarkHeight, imagesx($watermark), imagesy($watermark));
//display
    return $image;
}

// File and new size
$filename = $_GET['file'];
$size = $_GET['size'];
$thumb = resizeimage($filename, $size);
header('Content-Type: image/jpeg');
imagejpeg($thumb);
imagedestroy($thumb);
?>