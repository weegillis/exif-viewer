<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title> <?php ins($title); ?>&bull; photo inspector</title>
<meta name="robots" content="noindex,nofollow">
<link rel="stylesheet" href="/css/exif.css">
</head>
<body>
<?php 
 $image = isset($_REQUEST['img']) ? $_REQUEST['img'] : null;
 readExif($image); 
 ?> 
<div id="header"><div id="breadcrumb"><a href="./" title="Back to Photo Navigator">&lt;&lt;&lt; Thumbnails</a></div><div class="headerbox"><?php ins($tags['title']) ?></div></div>
<div id="image"><div class="imagebox"><div class="imageshow"><?php getImg($image); ?></div></div></div>
<div id="exif">
 <div class="exifbox"> 
  <div class="exifboxtable">  
   <table>
<?php printData(); ?> 
   </table>   
  </div>  
 </div> 
</div>
</body>
</html>
