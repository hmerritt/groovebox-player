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


                //  the _streamdata does not exist - create it
                echo "_streamdata does not exist; lets create it";

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
                             '~\.('. $this->settings["music_file_ext"] .')$~', scandir($playlistDir)
                         ),
                         $streamData["played"]
                     )
                 );



        //  check if array is empty
        //  if true - all tracks have been played
        if (empty($audioFiles))
        {


            //  reset the _streamdata
            $this->create_streamdata($playlist);

            //


        }



        echo "<pre>"; print_r($audioFiles); echo "</pre>";


    }








    //  create the _streamdata file in a playlist folder
    private function create_streamdata($playlist)
    {


        //
        echo "create or reset _streamdata";


    }








}






?>
