<?php




/*

  https://github.com/Hmerritt/internet-radio

  This file contains functions on retrieving metadata from icecast.
  Example usage;

  /radio/api/?stream&playlist=disco

*/








//  stream class is used to get the currently playing song for a specific playlist
class Stream
{








    public function __construct()
    {


        //  get the user's settings
        global $settings;


        //  import the user's settings
        $this->settings = $settings;



        //  define the default layout for the _streamdata file
        $this->defaultStreamData = [
            "stream" => [
                "track" => "",
                "start" => 0,
                "end" => 0,
                "length" => 0
            ],
            "played" => []
        ];


    }








    //  get audio track for a playlist
    public function audio($playlist)
    {


        //  get directory path for the playlist
        $playlistDir = "../tracks/$playlist/";


        //  check if the requested playlist folder exists
        if (file_exists($playlistDir))
        {


            //  check for existing _streamdata file within the playlist folder
            if (file_exists($playlistDir . "_streamdata"))
            {


                //  open the _streamdata and decode it
                $streamData = json_decode(file_get_contents($playlistDir . "_streamdata"), true);


                //  get time into current track - in seconds
                //  add straight into the _streamdata
                $streamData["stream"]["timeIntoTrack"] = $streamData["stream"]["length"] - ($streamData["stream"]["end"] - time());


                //  check if track has already ended
                //  compare length with the time into the track
                if ($streamData["stream"]["timeIntoTrack"] > $streamData["stream"]["length"])
                {

                    //  select another track to play
                    $this->select_track($playlist, $streamData);
                    return ""; // song has ended; dynamically select another track

                }


                echo json_encode($streamData);


            } else
            {


                //  the _streamdata does not exist - create it using the default values
                $this->write_streamdata($playlist, $this->defaultStreamData);

            }


        } else
        {


            //  the requested playlist folder does not exist
            return_error("400", "invalid_playlist", "'playlist' requested does not exist. example; ?stream&playlist=disco");


        }


    }








    //  select another track to play for a specified playlist
    private function select_track($playlist, $streamData)
    {


        //  get directory path for the playlist
        $playlistDir = "../tracks/$playlist/";



        //  add the last track into the played array
        $streamData["played"][] = $streamData["stream"]["track"];


        //  search all audio files within the playlist directory
        //  remove all previously played tracks from the scan
        $audioFiles = array_values(
                     array_diff(
                         preg_grep(
                             '~\.(mp3|aac|wav|ogg)$~', scandir($playlistDir)
                         ),
                         $streamData["played"]
                     )
                 );



        //  check if array is empty
        if (empty($audioFiles))
        {


            //  check if played tracks is more than 0
            //  if true - all tracks have been played
            //  if false - there are no tracks to play
            if (sizeof($streamData["played"]) > 1)
            {


                //  reset the _streamdata
                //  remove unneeded items from the _streamdata
                unset($streamData["stream"]["timeIntoTrack"]);


                //  clear all played tracks
                //  keep that last played track so it does not play twice by mistake
                $streamData["played"] = [end($streamData["played"])];


                //  update the _streamdata file with updated values
                $this->write_streamdata($playlist, $streamData);


                //  re-run this function with the updated _streamdata
                $this->select_track($playlist, $streamData);


                //  do not conntinue running this function
                return false;


            } else
            {


                echo "there are no tracks to play";
                die();


            }


        }



        //  get the next track by generating a random number
        //  use the number as the index for the scanned files
        $newTrack = $audioFiles[rand(0, sizeof($audioFiles)-1)];



        echo "<pre>"; print_r($newTrack); echo "</pre>";


    }








    //  write to the _streamdata file in a playlist folder
    private function write_streamdata($playlist, $newStreamData)
    {


        //  write the new data into _streamdata
        //  if the _streamdata does not exist - it will create it
        file_put_contents("../tracks/$playlist/_streamdata", json_encode($newStreamData, JSON_PRETTY_PRINT));


    }








}






?>
