<?php




/*

  https://github.com/Hmerritt/internet-radio

  This file contains functions on extracting the cover-art from a file on the server
  Example usage;

  /radio/api/?coverArt&mount=disco

*/








//  import the stream class
require_once("libs/stream.php");








//  metadata class to retrieve data from icecast endpoints such as; status-json.xsl
class CoverArt
{








    public function __construct()
    {


        //  get the user's settings
        global $settings;


        //  import the user's settings
        $this->settings = $settings;


    }








    //  get the cover-art for the currently playing track on a specific playlist
    public function currentlyPlaying($playlist)
    {


        //  get the currently playing song by loaded latest stream data
        $currentTrack = (new Stream())->audio($playlist)["track"];


        //  create path from the track name
        $filePath = "../tracks/" . $playlist . "/" . $currentTrack;



        //  start getid3 instance
        $getID3 = new getID3;

        //  open file and extract metadata
        $fileInfo = $getID3->analyze($filePath);



        //  set a default cover as a fallback
        $defaultCover = "../client/img/default-cover.png";



        //  check if track has an album cover
        if (isset($fileInfo["comments"]["picture"][0]))
        {


            //  check for the image type (jpeg/png)
            if (isset($getID3->info["id3v2"]["APIC"][0]["image_mime"]))
            {

                //  if a type exists - use it when creating the content header
                $mimetype = $getID3->info["id3v2"]["APIC"][0]["image_mime"];

            } else
            {

                //  if not type if found - use jpeg as a fallback
                $mimetype = "image/jpeg";

            }


            //  set image header - interpret data as an image
            header("Content-Type: $mimetype");



            //  echo the image to the user
            return $fileInfo["id3v2"]["APIC"][0]["data"];


        } else
        {

            //  use default cover
            header("Content-Type: image/x-png");
            return readfile($defaultCover);

        }


    }








    //  get the cover-art for any track on a specific mount
    public function track($playlist, $trackName)
    {





    }








}






?>
