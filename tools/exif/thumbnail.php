<?php
 $dir = ".";
 $image = exif_thumbnail($dir . "/" . $_GET['file']);
 header("Content-Type: image/jpeg");
 echo $image;
?>
