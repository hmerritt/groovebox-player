<?php




/*
  https://github.com/Hmerritt/groovebox-player

  This is the main settings file for the groovebox-player

*/
$settings = [




    //  path to tracks/ (will NOT work on a remote server (cannot write to a remote location))
    "path_to_tracks" => "default",




    //  only change if on windows (used in development)
    //  default value: '/'
    "default_slash" => "/",


    //  tell php to display all errors (used in development)
    "show_errors" => true




];








//  change path for api/ and tracks/ to remove the slash
$settings["path_to_api"] = "../api";


if ($settings["path_to_tracks"] == "default")
{

    $settings["path_to_tracks"] = "../tracks";

}





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
