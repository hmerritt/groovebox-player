<?php



/*
  https://github.com/Hmerritt/internet-radio

  This is the main settings file for the internet-radio

*/
$settings = [




    //  port that the icecast server is running on
    "icecast_port" => "7400",


    //  domain-name or host that icecast is running from
    //  e.g. example.com if running on a remote server
    //  or   localhost
    "icecast_host" => "harrymerritt.me",



    //  set the file extension for the music on the server
    //  default ".mp3"
    "music_file_ext" => ".mp3",




    //  only change if on windows (used in development)
    //  default value: '/'
    "default_slash" => "/",


    //  tell php to display all errors (used in development)
    "show_errors" => true




];




//  check settings var to decide the error show/hide state
if ($settings["show_errors"])
{

    //  show all errors
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

} else
{

    //  hide all errors
    error_reporting(0);
    ini_set('display_errors', 0);

}




?>
