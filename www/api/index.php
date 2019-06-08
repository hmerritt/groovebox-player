
<?php




/*

  This api file acts as the main gateway for all of the internet-radio's
  functions. Example usage;

  /radio/api/?medadata&mount=disco

*/






//  get user's settings
require "settings.php";





//  check for empty url parameters
if (empty($_GET))
{

    //  no url parameters set
    //  throw error
    $return = [
        "status" => "error",
        "reason" => "no_url_params",
        "msg" => "no url parameters set. example; ?medadata&mount=disco"
    ];


    //  stop script and return the error
    die(json_encode($return));
}




?>
