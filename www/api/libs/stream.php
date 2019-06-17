<?php




/*

  https://github.com/Hmerritt/internet-radio

  This file contains functions on providing metadata and direct audio stream for the files within a playlist folder.
  Example usage;

  /radio/api/?stream&playlist=disco
  /radio/api/?metadata&playlist=disco

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








    //  play audio stream for the currently playing track in a playlist
    public function audio($playlist)
    {


        //  get the metadata for the current track
        $metadata = $this->metadata($playlist);


        //  set the header as the tracks mime-type
        header("Content-Type: " . $metadata["type"]);


        //  open audio file and output it into the browser
        echo file_get_contents("../tracks/$playlist/" . $metadata["track"]);


    }








    //  get audio metadata for the currently playing track in a playlist
    public function metadata($playlist)
    {


        //  get directory path for the playlist
        $playlistDir = "../tracks/$playlist/";



        //  check if the requested playlist folder exists
        if (file_exists($playlistDir))
        {


            //  check for existing _streamdata file within the playlist folder
            if (!file_exists($playlistDir . "_streamdata"))
            {

                //  the _streamdata does not exist - create it using the default values
                $this->write_streamdata($playlist, $this->defaultStreamData);

            }



            //  open the _streamdata and decode it
            $streamData = json_decode(file_get_contents($playlistDir . "_streamdata"), true);


            //  get time into current track - in seconds
            //  add straight into the _streamdata
            $streamData["stream"]["timeIntoTrack"] = $streamData["stream"]["length"] - ($streamData["stream"]["end"] - time());


            //  check if track has already ended
            //  compare length with the time into the track
            //  add a varience of 10 seconds to prevent loading a track that is about to end
            if ($streamData["stream"]["timeIntoTrack"] > $streamData["stream"]["length"] - 10)
            {


                // song has ended; select another track to play
                $newTrack = $this->select_track($playlist, $streamData);


                //  get the track metadata
                $trackData = (new getID3)->analyze($playlistDir . $newTrack);


                //  get the length of the new track (in seconds)
                //  add the last track into the played array
                //  reset the time-into-track var
                $newTrackLength = floor($trackData['playtime_seconds']);
                $streamData["played"][] = $streamData["stream"]["track"];
                $streamData["stream"]["timeIntoTrack"] = 0;


                //  check if mime-type exists
                if (isset($trackData["mime_type"]))
                {

                    //  use detected mime-type
                    $streamData["stream"]["type"] = $trackData["mime_type"];

                } else
                {

                    //  if no mime-type is found - fallback to mpeg
                    $streamData["stream"]["type"] = "audio/mpeg";


                }


                //  add the new track info the the streamData var
                $streamData["stream"]["track"] = $newTrack;
                $streamData["stream"]["start"] = time();
                $streamData["stream"]["end"] = time() + $newTrackLength;
                $streamData["stream"]["length"] = $newTrackLength;


                //  update the _streamdata file with the new track info
                $this->write_streamdata($playlist, $streamData);


            }



            //  echo the track info
            return $streamData["stream"];



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
                return $this->select_track($playlist, $streamData);


            } else
            {


                echo "there are no tracks to play";
                die();


            }


        }



        //  get the next track by generating a random number
        //  use the number as the index for the scanned files
        return $audioFiles[rand(0, sizeof($audioFiles)-1)];



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
