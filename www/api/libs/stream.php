<?php




/*

  https://github.com/Hmerritt/groovebox-player

  This file contains functions on providing metadata and direct audio stream for the files within a playlist folder.
  Example usage;

  /radio/api/?stream&playlist=disco
  /radio/api/?metadata&playlist=disco

*/








//  stream class is used to get the currently playing song for a specific playlist
class Stream
{



    private $settings = array();


    public function __construct(array $settings)
    {

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
        echo file_get_contents($this->settings["path_to_tracks"] ."/$playlist/". $metadata["track"]);


    }








    //  get audio metadata for the currently playing track in a playlist
    public function metadata($playlist, $choice = '')
    {


        //  get directory path for the playlist
        $playlistDir = $this->settings["path_to_tracks"] ."/$playlist/";



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

            if ($choice === 'next') {
                //  reset time into current track - in seconds
                //  add straight into the _streamdata
                $streamData["stream"]["timeIntoTrack"] = $streamData["stream"]["length"];
            } elseif ($choice === 'prev') {
                //  reset time into current track - in seconds
                //  add straight into the _streamdata
                $streamData["stream"]["timeIntoTrack"] = $streamData["stream"]["length"];
                // new track is the old one
                $newTrack = $this->select_old_track($playlist,$streamData);
                if ($newTrack == '') {
                    // it is the first track, no prev track to play. A new one will be selected
                    unset($newTrack);
                }
            } else {
                //  get time into current track - in seconds
                //  add straight into the _streamdata
                $streamData["stream"]["timeIntoTrack"] = $streamData["stream"]["length"] - ($streamData["stream"]["end"] - time());
            }


            //  check if track has already ended
            //  compare length with the time into the track
            //  add a varience of 10 seconds to prevent loading a track that is about to end
            if ($streamData["stream"]["timeIntoTrack"] > $streamData["stream"]["length"] - 10)
            {

                if (!isset($newTrack)) {
                    // song has ended; select another track to play
                    $newTrack = $this->select_track($playlist, $streamData);
                }

                //  get the track metadata
                $trackData = (new getID3)->analyze($playlistDir . $newTrack);


                //  get the length of the new track (in seconds)
                $newTrackLength = floor($trackData['playtime_seconds']);
                //  add the last track into the played array
                if ($choice != 'prev') {
                    $streamData["played"][] = $streamData["stream"]["track"];
                } else {
                    array_pop($streamData["played"]);
                }
                //  reset the time-into-track var
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



    //  get audio metadata for the next track to play in a playlist
    public function next($playlist)
    {

        return $this->metadata($playlist, 'next');

    }

    //  get audio metadata for the next track to play in a playlist
    public function prev($playlist)
    {

        return $this->metadata($playlist, 'prev');

    }


    //  select another track to play for a specified playlist
    private function select_track($playlist, $streamData)
    {


        //  get directory path for the playlist
        $playlistDir = $this->settings["path_to_tracks"] ."/$playlist/";

        //  add the last track into the played array
        if (!in_array($streamData["stream"]["track"],$streamData["played"])) {
            $streamData["played"][] = $streamData["stream"]["track"];
        }

        //  search all audio files within the playlist directory
        //  remove all previously played tracks from the scan
        $audioFiles = array_values(
                     array_diff(
                         preg_grep(
                             '~\.(mp3|aac|wav|ogg|flac)$~', scandir($playlistDir)
                         ),
                         $streamData["played"]
                     )
                 );

        //  check if array is empty
        if (empty($audioFiles) || $audioFiles == array(0=>''))
        {


            //  check if played tracks is more than 0
            //  if true - all tracks have been played
            //  if false - there are no tracks to play
            if (count($streamData["played"]) > 1)
            {


                //  reset the _streamdata
                //  remove unneeded items from the _streamdata
                unset($streamData["stream"]["timeIntoTrack"]);


                //  clear all played tracks
                //  keep that last played track so it does not play twice by mistake
                $last = end($streamData["played"]);
                $streamData["played"] = [];
                $streamData["played"] = [$last];


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
        return $audioFiles[rand(0, count($audioFiles)-1)];



        // echo "<pre>"; print_r($newTrack); echo "</pre>";


    }

    //  select another track to play for a specified playlist
    private function select_old_track($playlist, $streamData)
    {


        //  get directory path for the playlist
        $playlistDir = $this->settings["path_to_tracks"] ."/$playlist/";


        //  search all audio files within the playlist directory
        //  remove all previously played tracks from the scan
        $audioFiles = array(array_pop($streamData["played"]));
        unset($streamData["played"][current(array_keys($streamData["played"],$audioFiles))]);
        //  check if array is empty
        if (empty($audioFiles) || $audioFiles == array(0=>''))
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
                $last = end($streamData["played"]);
                $streamData["played"] = [];
                $streamData["played"] = [$last];


                //  update the _streamdata file with updated values
                $this->write_streamdata($playlist, $streamData);


                //  re-run this function with the updated _streamdata
                return $this->select_old_track($playlist, $streamData);


            } else
            {


                // there are no tracks to play 
                return '';
            }


        } else {

            //  update the _streamdata file with updated values
            $this->write_streamdata($playlist, $streamData);

        }


        //  get the prev track
        return $audioFiles[0];

    }








    //  write to the _streamdata file in a playlist folder
    private function write_streamdata($playlist, $newStreamData)
    {


        //  write the new data into _streamdata
        //  if the _streamdata does not exist - it will create it
        file_put_contents($this->settings["path_to_tracks"] ."/$playlist/_streamdata", json_encode($newStreamData, JSON_PRETTY_PRINT));


    }








}






?>
