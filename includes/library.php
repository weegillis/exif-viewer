<?php 
// this version 0.4.120621-mtf
//
// adapted from www.quietless.com/kitchen/extract-exif-data-using-php-to-display-gps-tagged-images-in-google-maps/
function toDecimal($deg, $min, $sec, $hem) { $d =  round(($deg + $min/60 + $sec/3600), 7); return ($hem=='S' || $hem=='W') ? $d*=-1 : $d;}
function divide($a) { $e = explode('/', $a); return (!$e[0] || !$e[1]) ? 0 : $e[0] / $e[1]; }
function getGPS() {
 global $exif, $tags; 
 if ($exif) {
  $lat = $exif['GPS']['GPSLatitude']; 
  $log = $exif['GPS']['GPSLongitude'];
  if (!$lat || !$log) return null;
  $tags['latdeg'] = divide($lat[0]);
  $lat_min = divide($lat[1]);
  $lat_min_frac = $lat_min - (int)($lat_min);
  $lat_min = (int)($lat_min);
  $tags['latmin'] = $lat_min;
  $tags['latsec'] = round($lat_min_frac * 60, 2);
  $tags['lathem'] = $exif['GPS']['GPSLatitudeRef'];
  $tags['logdeg'] = divide($log[0]);
  $log_min = divide($log[1]);
  $log_min_frac = $log_min - (int)($log_min);
  $log_min = (int)($log_min);
  $tags['logmin'] = $log_min;
  $tags['logsec'] = round($log_min_frac * 60, 2);
  $tags['loghem'] = $exif['GPS']['GPSLongitudeRef'];
  $tags['latitude'] = toDecimal($tags['latdeg'], $tags['latmin'], $tags['latsec'], $tags['lathem']);
  $tags['longitude'] = toDecimal($tags['logdeg'], $tags['logmin'], $tags['logsec'], $tags['loghem']);
  return array($tags['latitude'], $tags['longitude']);
  } else {
  return null;
 }
}
// end adaptation
function imgConfirm($arg) { $img1 = $arg . ".jpg"; $img2 = $arg . ".JPG"; return file_exists($img1) ? $img1 : (file_exists($img2) ? $img2 : null);};
function hasSection($arg) { global $exif; return stristr($exif['FILE']['SectionsFound'], $arg); }
function strTrunc($arg, $trunc) { return substr($arg, 0, strlen($arg)-$trunc); };
function filter($arg) { return preg_replace('/[^\p{L}\p{M}\p{Z}\p{N}\p{P}]/u', '', $arg); };
function ins($arg) { echo $arg; }
// thumbs
function getEXIF($dir) {
 global $exif;
 if (is_dir($dir)) {
  if ($dh = opendir($dir)) {
   $count = 1;
   while (($file = readdir($dh)) !== false) {
    if (stristr($file, '.jpg')) {
     $exif = @exif_read_data($file, 0, true, true);
     $str = " <div class=\"item\">\n  <div class=\"thum\"><a href=\"viewer.php?img=" . strTrunc($file, 4) . "\"><img src=\"";
     $str .= hasSection('THUMBNAIL') ? "thumbnail.php?file=" . $file . "\"" : $file . "\" width=\"195\"";
	 $sub = filter($exif['IFD0']['Subject']);
	 $alt = $sub ? $sub : strTrunc($file, 4);
     $str .= " alt=\"$alt\" title=\"";
	 $com = hasSection('COMMENT') ? $exif['COMMENT'][0] : null;
	 $ttl = filter($exif['IFD0']['Title']);
	 $title = $ttl ? $ttl : $com;
     $str .= $title ? $title : strTrunc($file, 4); 
     $str .= "\"></a></div>\n  <ul>\n";
     $str .= "   <li>File: <b>" . $exif['FILE']['FileName'] . "</b></li>\n";
     $str .= $sub ? "   <li>Subject: $sub</li>\n" : null;
     $str .= "   <li>Date taken: " . strTrunc($exif['EXIF']['DateTimeOriginal'], 9) . "</li>\n";
     $str .= "   <li>Dimensions: " . $exif['COMPUTED']['Width'] . " x " . $exif['COMPUTED']['Height'] . " </li>\n";	 
     $gps = hasSection('GPS') ? getGPS() : null;
     if ($gps != null) {
      $str .= "   <li>Latitude  : " . $gps[0] . "&deg;</li>\n";
      $str .= "   <li>Longitude : " . $gps[1] . "&deg;</li>\n";
	  $str .= "   <li class=\"win\"><a href=\"http://www.wikimapia.org/#lat=" . $gps[0] . "&amp;lon=" . $gps[1] . "&amp;z=17\" title=\"Off-site\">Map Reference " . $count++ . "</a></li>\n";
     }	 
     $str .= "  </ul>\n </div>\n";
     echo $str;
    }
   }
   closedir($dh);
  }
 }
}
// viewer
function getImg($img) {
 global $exif, $tags;
 $imgx = imgConfirm($img);
 if ($imgx) { 
  $str = "<img src=\"$imgx\" ";
  $chtm =$exif['COMPUTED']['html'];
  if (!$chtm) {
   $fil_wid = $exif['COMPUTED']['Width'];
   $fil_hgt = $exif['COMPUTED']['Height'];
   $str .= "width=\"" . (($fil_wid > 0) ? $fil_wid : "100%") . "\" height=\"" . (($fil_hgt > 0) ? $fil_hgt : "100%") . "\"";
  } else {
   $str .= $chtm;
  }
  $str .= " alt=\"" . $tags['alt'] . "\" title=\"" . $tags['title'] . "\">";
  echo $str;
 } else {
  echo "Image not found.";
 }
}
function readExif($img) {
 global $exif, $use_com, $errStr, $tags;
 $errStr = ""; 
 $imgx = imgConfirm($img);
 if ($imgx) {
  if (@exif_read_data($imgx)) {
   $exif = @exif_read_data($imgx, 0, true, false);
   echo "<div id=\"exifdump\">\n";
    print_r($exif);
   echo "</div>\n";
   $comment = hasSection('COMMENT') ? $exif['COMMENT'][0] : null;
   $alt = filter($exif['IFD0']['Subject']);
   $title = filter($exif['IFD0']['Title']);
   $comments = filter($exif['IFD0']['Comments']);
   $author = filter($exif['IFD0']['Author']);
   $alt= $alt ? $alt : strTrunc($imgx, 4);
   $title = $title ? $title : strTrunc($imgx, 4);
   $comments = $comments ? $comments : null;
   $author = $author ? $author : null;
   $tags = array(
    'alt'=> $alt,
    'title' => $title,
    'author' => $author,
    'comments' => $comments,
    'comment' => $comment
   );
   if (hasSection('GPS')) {
    $gps = $exif['GPS']['GPSAltitudeRef'];
    $alt_ref = ($gps !== null) ? $gps : null;
	$tags['altref'] = (int)($alt_ref);
    $gps = $exif['GPS']['GPSAltitude'];
    $gps_alt = ($gps !== null) ? round(divide($gps), 4) : "N/A";
	$tags['altitude'] = $gps_alt;
    $gps = getGPS();
    if ($gps != null) {
	 $tags['latitude'] = $gps[0];
	 $tags['longitude'] = $gps[1];
    }
   } else {
    $errStr .= "    <tr>\n<td colspan=\"3\"><p>No GPS tags found.<p></td>\n";
   }	
  } else {
   $errStr .= "    <tr>\n<td colspan=\"3\"><p>No EXIF tags found.</p></td>\n";
  }
 }
}
function printData() {
 global $errStr, $exif, $tags;
 $str = ""; 
 if ($tags['comment']) { $str .= "    <tr>\n     <td colspan=\"3\">\n      <p class=\"comment\">" . $tags['comment'] . "</p>\n     </td>\n    </tr>\n"; }
 if ($tags['comments']) { $str .= "    <tr>\n     <td colspan=\"3\">\n      <p class=\"comment\">" . $tags['comments'] . "</p>\n     </td>\n    </tr>\n"; }
 if (!$errStr) {
  $str .= "    <tr><th scope=\"col\">LATITUDE</th><th scope=\"col\">LONGITUDE</th><th scope=\"col\">ALTITUDE</th></tr>\n    <tr>\n";
  $str .= "     <td>\n      <p>" . $tags['latdeg'] . "&deg; " . $tags['latmin'] . "&rsquo; " . $tags['latsec'] . "&rdquo; " . $tags['lathem'] . "</p>\n      <p>" . $tags['latitude'] . "&deg;</p>\n     </td>\n";
  $str .= "     <td>\n      <p>" . $tags['logdeg'] . "&deg; " . $tags['logmin'] . "&rsquo; " . $tags['logsec'] . "&rdquo; " . $tags['loghem'] . "</p>\n      <p>" . $tags['longitude'] . "&deg;</p>\n     </td>\n";    
  $h = isset($_REQUEST['h']) ? $_REQUEST['h'] : 0;
  $z = ((intval($h) <= 4 && intval($h) >= -4) ? "&amp;z=" . (string)(17 + intval($h)) : "&amp;z=17"); 
  $str .= "     <td>\n      <p>" . (($tags['altitude'] == "N/A") ? $tags['altitude'] : $tags['altitude'] . " m " . (($tags['altref']) ? "Below" : "Above") . " Sea Level.") . "</p>\n      <p>Go to this <a href=\"http://www.wikimapia.org/#lat=" . $tags['latitude'] . "&amp;lon=" . $tags['longitude'] . $z . "\">location on the map</a></p>\n     </td>\n";  
 } else {
  $str .= $errStr;
 }
 $str .= "    </tr>";
 echo $str;
}  
//
 ?>
