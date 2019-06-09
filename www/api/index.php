
<?php




/*

  This api file acts as the main gateway for all of the internet-radio's
  functions. Example usage;

  /radio/api/?medadata&mount=disco
  /radio/api/?stream&mount=disco

*/






//  get user's settings
require "settings.php";





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





//  send error msg to user
//  stop script
function return_error($status, $reason, $msg)
{


    //  create response text
    $response = [
        "status" => full_status_code($status),
        "reason" => $reason,
        "msg" => $msg
    ];


    //  stop script and return the error
    die(json_encode($response));


}





//  check for empty url parameters
if (empty($_GET))
{

    //  no url parameters set
    //  throw error and stop the script
    return_error("400", "no_url_params", "no url parameters set. example; ?medadata&mount=disco");

}




?>