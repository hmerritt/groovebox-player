<?php




/*
  https://github.com/Hmerritt/internet-radio

  This is the main settings file for the internet-radio

*/
$settings = [




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
