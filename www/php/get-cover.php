<?php


error_reporting(E_ALL);
ini_set('display_errors', 1);


//  check for url vars
if (!isset($_GET["stream"]) || !isset($_GET["track"]))
{
    die(json_encode([
      "status" => "error",
      "reason" => "no URL data entered",
      "example" => "get-cover.php?stream=somestream&track=sometrack"
    ]));
}



//  get settings
require_once("settings.php");


//  import getid3 lib
//  used for extracting album art
require_once("vendors/getid3/getid3.php");



//  get post vars
$stream = $_GET["stream"];
$track = $_GET["track"];



//  formulate file path
//  default/backup image path
$filePath = "../../tracks/" . $stream . "/" . $track . ".mp3";
$defaultCover = "../img/default-cover.png";


//  start getid3 instance
$getID3 = new getID3;

//  open file and extract metadata
$fileInfo = $getID3->analyze($filePath);



//  set header vars
//  open data as an image
header("Content-Type: image/jpeg");


//  check if track has an album cover
if (isset($fileInfo["comments"]["picture"][0]))
{

    //  echo the image to the user
    echo $fileInfo['id3v2']['APIC'][0]['data'];

} else
{
    //  use default cover
    echo readfile($defaultCover);
}


?>
