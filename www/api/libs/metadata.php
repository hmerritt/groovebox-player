<?php




/*

  https://github.com/Hmerritt/internet-radio

  This file contains functions on retrieving metadata from icecast.
  Example usage;

  /radio/api/?metadata&mount=disco

*/






//  metadata class to retrieve data from icecast endpoints such as; status-json.xsl
class Metadata
{




    public function __construct()
    {


        //  import the user's settings
        global $settings;


        //  set the status-json link using the settings
        $this->statusJsonLink = "http://". $settings["icecast_host"] .":". $settings["icecast_port"] ."/status-json.xsl";


    }




    //  get all metadata from the status-json.xsl
    public function everything()
    {


        //  get metadata
        $everything = json_decode(file_get_contents($this->statusJsonLink));


        //  add metadata to the response content
        $response = response_json("200", $everything);


        //  return fetched json data
        return $response;


    }




    //  get mount specific metadata from the status-json.xsl
    public function mount($mountPoint)
    {


        //  get metadata
        $everything = json_decode(file_get_contents($this->statusJsonLink ."?mount=/". $mountPoint), true)["icestats"]["source"];


        //  add metadata to the response content
        $response = response_json("200", $everything);


        //  return fetched json data
        return $response;


    }




}






?>
