<?php




/*

  https://github.com/Hmerritt/internet-radio

  This api file acts as the main gateway for all of the internet-radio's
  functions. Example usage;

  /radio/api/?metadata&mount=disco
  /radio/api/?stream&mount=disco

*/








//  get user's settings
require_once("settings.php");


//  get lib to extract metadata from audio file
require_once("vendors/getid3/getid3.php");








//  search http status codes
//  return the full status code from a partial match
function full_status_code($status)
{


    //  create array of status codes
    //  match status codes to their text
    $statusCodes = [

        "100 Continue",
        "101 Switching Protocol",
        "102 Processing",
        "103 Early Hints",

        "200 OK",
        "201 Created",
        "202 Accepted",
        "203 Non-Authoritative Information",
        "204 No Content",
        "205 Reset Content",
        "206 Partial Content",
        "207 Multi-Status",
        "208 Multi-Status",
        "226 IM Used",

        "300 Multiple Choice",
        "301 Moved Permanently",
        "302 Found",
        "303 See Other",
        "304 Not Modified",
        "305 Use Proxy",
        "306 unused",
        "307 Temporary Redirect",
        "308 Permanent Redirect",

        "400 Bad Request",
        "401 Unauthorized",
        "402 Payment Required",
        "403 Forbidden",
        "404 Not Found",
        "405 Method Not Allowed",
        "406 Not Acceptable",
        "407 Proxy Authentication Required",
        "408 Request Timeout",
        "409 Conflict",
        "410 Gone",
        "411 Length Required",
        "412 Precondition Failed",
        "413 Payload Too Large",
        "414 URI Too Long",
        "415 Unsupported Media Type",
        "416 Requested Range Not Satisfiable",
        "417 Expectation Failed",
        "418 I'm a teapot",
        "412 Misdirected Request",
        "422 Unprocessable Entity",
        "423 Locked",
        "424 Failed Dependency",
        "425 Too Early",
        "426 Upgrade Required",
        "428 Precondition Required",
        "429 Too Many Requests",
        "431 Request Header Fields Too Large",
        "451 Unavailable For Legal Reasons",

        "500 Internal Server Error",
        "501 Not Implemented",
        "502 Bad Gateway",
        "503 Service Unavailable",
        "504 Gateway Timeout",
        "505 HTTP Version Not Supported",
        "506 Variant Also Negotiates",
        "507 Insufficient Storage",
        "508 Loop Detected",
        "510 Not Extended",
        "511 Network Authentication Required",

    ];


    //  define status text as undefined in case there are no matches
    $statusText = "undefined";


    //  loop all status codes
    foreach ($statusCodes as $str)
    {

        //  attempt to match the full status code text with the entered status of the function
        if (preg_match("/$status/", $str, $match))
        {
            $statusText = $str;
            break;
        }

    }


    //  return the matched status code (if any)
    return $statusText;


}





//  create a standard resonse in json format
//  http status code + content
function response_json($status, $content)
{


    //  create response var
    return [
        "status" => full_status_code($status),
        "content" => $content
    ];


}





//  send error msg to user
//  stop script
function return_error($status, $reason, $msg)
{


    //  create content text
    $content = [
        "reason" => $reason,
        "msg" => $msg
    ];


    //  stop script and return the error
    die(json_encode(response_json($status, $content)));


}





//  check for empty url parameters
if (empty($_GET))
{

    //  no url parameters set
    //  throw error and stop the script
    return_error("400", "no_url_params", "no url parameters set. example; ?metadata&playlist=disco");

}








//  check for the existance of valid url params





//  if stream param exists
//  get an audio stream from a specific playlist
if (isset($_GET["stream"]))
{


    //  check for playlist url param
    if (isset($_GET["playlist"]))
    {


        //  import the stream class
        require_once("libs/stream.php");


        //  init Stream class
        $stream = new Stream();



        //  get the current song playing
        die($stream->audio($_GET["playlist"]));


    } else
    {


        //  the playlist param is crucial to the stream request
        //  throw error
        return_error("400", "no_playlist_param", "'playlist' parameter is missing. example; ?stream&playlist=disco");


    }


}







//  if metadata param exists
//  get an audio metadata from a specific playlist
if (isset($_GET["metadata"]))
{


    //  check for playlist url param
    if (isset($_GET["playlist"]))
    {


        //  import the metadata class
        require_once("libs/stream.php");


        //  init Stream class
        $metadata = new Stream();


        //  set json header - expect a json response
        header("Content-Type: application/json");



        //  get the current song playing
        die(json_encode($metadata->metadata($_GET["playlist"])));


    } else
    {


        //  the playlist param is crucial to the metadata request
        //  throw error
        return_error("400", "no_playlist_param", "'playlist' parameter is missing. example; ?metadata&playlist=disco");


    }


}







//  if cover param exists
//  get the cover art for the current track on a specific playlist
if (isset($_GET["coverArt"]) ||
    isset($_GET["art"])      ||
    isset($_GET["cover"]))
{


    //  check for playlist param
    if (isset($_GET["playlist"]))
    {


        //  import the metadata class
        require_once("libs/cover_art.php");


        //  init Metadata class
        $coverArt = new CoverArt();


        //  echo the cover art
        die($coverArt->currentlyPlaying($_GET["playlist"]));


    } else
    {

        //  the playlist param is crucial to the stream request
        //  throw error
        return_error("400", "no_playlist_param", "'playlist' parameter is missing. example; ?coverArt&playlist=disco");

    }


}








?>
