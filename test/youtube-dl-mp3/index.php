<?php


//  show all errors
error_reporting(E_ALL);
ini_set('display_errors', 1);




//  get video to download audio from
$videoUrl = "https://www.youtube.com/watch?v=j1c_L4Dnguo";


//  use youtube-dl to download the audio from the video and save it as a file
$download_audio = shell_exec("youtube-dl --extract-audio --add-metadata --xattrs --embed-thumbnail --audio-quality 0 --audio-format mp3 -o '%(title)s.mp3' $videoUrl");


//  echo the output to the browser once finished
echo "
<pre>
$download_audio
</pre>";


?>