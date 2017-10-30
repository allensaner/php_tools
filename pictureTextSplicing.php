<?php
header ("Content-type: image/png");

ini_set('memory_limit','2048M');

$filename = "./aa/snap000984.jpg";
$size = getimagesize($filename) ;
$count = 1183-984+1;
$count = 200;
$hight = $size[1]-80;
$longImg =imagecreatetruecolor($size[0],$hight*$count);
$rh = 100;
$y=0;
$tmpNo = 0;
$tmpImg = null;
for($i=0;$i<$count;$i++){
    $str = 984 + $i;
    $filename = "./aa/snap00".getFileName($str);
    $img = imagecreatefromjpeg($filename);
    $sx = imagesx($img); $sy = imagesy($img);
    $lines = getLineHasChar($img,$sx,$sy);
    $lineCount = count($lines);
    for($j=0;$j<$lineCount;$j+=2) {
        $min = $lines[$j]- 5;
        $max = $lines[$j+1] + 5;
        if (empty($tmpImg))
            $tmpImg =imagecreatetruecolor($sx,$max-$min);
        imagecopymerge($tmpImg, $img, 0, 0, 0, $min, $sx, $max, $rh);
        imagejpeg($tmpImg,'./bb/'.getFileName($tmpNo));
        $tmpNo++;   
        imagecopymerge($longImg, $img, 0, $y, 0, $min, $sx, $max, $rh);
        $y+= $max-$min;
    }
}

imagePng($longImg);
function getFileName($str){
    if ($str<10) {
        $str = '000'.$str;
    } elseif ($str<100) {
        $str = '00'.$str;
    } elseif ($str<1000) {
        $str = '0'.$str;
    } elseif ($str<10000) {
        $str = ''.$str;
    }
    return $str.".jpg";
}
function getLineHasChar(&$i,$sx,$sy){
    $line = [];
    $startY = 0;
    $preHasChar = false;
    for ($y=0;$y<$sy;$y++) {
    $hasChar = false;
    for ($x=0;$x<$sx;$x++) {
        $rgb = imagecolorat($i,$x,$y);
        $r = ($rgb>>16) & 0xFF;
        $g = ($rgb>>8) & 0xFF;
        $b = $rgb & 0xFF;
        if ($r<100){
            $hasChar = true;
            break;
        }
    }
    if ($preHasChar && !$hasChar) {
            $lineHasChar[] = $y-1;
            $preHasChar = false;
    } elseif (!$preHasChar && $hasChar) {
            $lineHasChar[] = $y;
            $preHasChar = true;
    }
    }
    return $lineHasChar;
}
