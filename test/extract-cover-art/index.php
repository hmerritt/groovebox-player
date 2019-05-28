<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);


require_once('getid3/getid3.php');


$getID3 = new getID3;
$fileinfo = $getID3->analyze("tune.mp3");
$picture = $fileinfo['id3v2']['APIC'][0]['data']; // binary image data


header('Content-type: image/jpg');
echo $picture;


?>