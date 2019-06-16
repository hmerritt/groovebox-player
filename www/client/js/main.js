


$(document).ready(function()
{








    /*  url parameters  */




    //  get url parameter
    function gup( name, url )
    {
        if (!url) url = location.href;
        name = name.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");
        var regexS = "[\\?&]"+name+"=([^&#]*)";
        var regex = new RegExp( regexS );
        var results = regex.exec( url );
        return results == null ? null : results[1];
    }




    //  set main radio var
    //  stores metadata on each mount point
    var radio = {
        "playlist": gup("playlist")
        //"coverArt": "../api/?cover&playlist=" + gup("playlist")
    };








    /*  metadata  */




    //  get stream data for a specific playlist
    function getStreamData(playlist)
    {


        fetch("../api/?stream&playlist=" + playlist).then(function (r)
        {

            //  expect a json response
            return r.json();

        }).then(function (data)
        {


            //  add metadata to main radio var
            radio["stream"] = data;



            //  split the full title into title and artist
            radio["metadata"] = {
                "fullTitle": radio["stream"]["track"].replace(/\.[^/.]+$/, "")
            };


            //  check for dash " - " within the file name
            if (radio["metadata"]["fullTitle"].includes(" - "))
            {

                //  split the file name; artist - title
                radio["metadata"]["title"] = radio["metadata"]["fullTitle"].split(" - ")[1];
                radio["metadata"]["artist"] = radio["metadata"]["fullTitle"].split(" - ")[0];

            } else
            {

                //  use the full-title as the main title and leave the artist blank
                radio["metadata"]["title"] = radio["metadata"]["fullTitle"];
                radio["metadata"]["artist"] = "-";

            }


            //  add the URl for the cover-art to the metadata
            radio["metadata"]["coverArt"] = "../api/?cover&playlist=" + radio["playlist"];



            //  updata the metadata in the UI
            applyMetadata(playlist);


        });


    }





    //  add the metadata values from the radio var into the user-interface
    function applyMetadata(mount)
    {


        //  get metadata values
        var metadata = radio["metadata"];


        //  get the current audio file name
        var currentAudioFile = $(".track-info").attr("file");


        //  check if the (new) metadata is different than the currently playing song
        if (radio["stream"]["track"] !== currentAudioFile)
        {


            //  update the metadata


            //  update the track name
            $(".track-info .track-name").html(metadata["title"]).attr("title", metadata["title"]);


            //  update the track artist
            $(".track-info .track-artist").html(metadata["artist"]).attr("title", metadata["artist"]);


            //  update the cover-art
            $(".album-art img").attr("src", metadata["coverArt"] +"&"+ new Date().getTime());


            //  update the current audio file
            $(".track-info").attr("file", radio["stream"]["track"]);


        }


    }





    //  get metadata on pageload
    getStreamData(radio["playlist"]);





    //  get metadata every i seocnds
    //  metadata needs to be updated frequently to check for changes
    setInterval(function()
    {


        //getStreamData(radio["mount"]);


    }, 15 * 1000);








    /*  audio  */




    //  log action
    console.log("[Audio] Attempting to start audio");



    //  create an audio stream container
    var audioContext = new window.AudioContext();



    //  setup audio element
    //  set volume to 50%
    var audio = new Audio("../api/?stream&mount=" + radio["mount"]);
        audio.volume = 0.5;
        audio.setAttribute("type", "audio/mpeg");
        audio.setAttribute("preload", "none");
        audio.setAttribute("crossorigin", "anonymous");
        audio.setAttribute("allow", "accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture");


    // attempt to play the audio
    var playPromise = audio.play();

    //  catch error if the audio fails to start
    if (playPromise !== undefined)
    {
        playPromise.then(_ => {
          // Automatic playback started!
          // Show playing UI.
          setControlsIcon("pause");
          console.log("[Audio] Auto-play started successfully!");
        })
        .catch(error => {
            // Auto-play was prevented
            // Show paused UI.
            toggleControls("play");
            console.error("[Audio] Auto-play was prevented ("+ error +")");
        });
    }








    /*  audio state (play/pause)  */




    //  stop/start music playback
    function togglePlayback()
    {

        //  check if audio is playing
        if (!audio.paused)
        {
            audio.pause();
            setControlsIcon("play");
        } else
        {
            audio.play();
            setControlsIcon("pause");
        }

    }




    //  set audio controls icon
    function setControlsIcon(icon)
    {

        //  check for which icon
        if (icon == "play")
        {
            $(".audio-controls svg").addClass("hidden");
            $(".audio-controls svg.icon-play").removeClass("hidden");
        } else if (icon == "pause")
        {
            $(".audio-controls svg").addClass("hidden");
            $(".audio-controls svg.icon-pause").removeClass("hidden");
        }

    }




    //  toggle audio controls within the album cover
    function toggleControls(icon)
    {

        //  check if audio controls is already hidden
        if ($(".audio-controls").hasClass("hidden"))
        {

            //  change icon within audi controls
            setControlsIcon(icon);

            //  show audio controls
            $(".audio-controls").removeClass("hidden");

        } else {

            //  hide audio controls
            $(".audio-controls").addClass("hidden");

        }

    }




    $(document).on("click", ".audio-controls", function()
    {

        togglePlayback();

    });








    /*  oscilloscope  */




    //  create source from html5 audio element
    var source = audioContext.createMediaElementSource(audio);


    //  attach oscilloscope
    var scope = new Oscilloscope(source);

    //  start default animation loop
    scope.animate(canvas.getContext("2d"));


    //  reconnect audio output to speakers
    source.connect(audioContext.destination);








    /*  volume  */




    //  return the current volume
    function getVolume()
    {
        return audio.volume;
    }




    //  update volume percentage bar
    function updateVolumeBar()
    {

        //  get volume percentage as a whole number
        var volumePercentage = (getVolume() * 100).toFixed(0);

        //  apply percentage to the width of volume bar
        //  update text below bar to match percentage
        $(".volume .volume-bar-percentage").css({"width": volumePercentage + "%"});
        $(".volume .volume-text strong").text(volumePercentage+ " %");

        //  set bar color
        if (getVolume() > 0.75)
        {
            $(".volume").removeClass("green yellow red").addClass("green");
        } else if (getVolume() > 0.25) {
            $(".volume").removeClass("green yellow red").addClass("yellow");
        } else {
            $(".volume").removeClass("green yellow red").addClass("red");
        }

    }




    //  update volume bar on page-load
    updateVolumeBar();




    //  change the volume
    //  up or down
    //  half a point (0.5)
    function changeVolume(action)
    {

        //  get current volume
        var currentVolume = getVolume(),
            newVolume;

        //  turn volume up
        if (action == "up")
        {

            //  do nothing if already max volume
            if (currentVolume < 1)
            {
                newVolume = audio.volume += 0.05;
                audio.volume = newVolume.toFixed(2);
            }

        //  turn volume down
        } else
        {

            //  do nothing if muted
            if (currentVolume !== 0)
            {
                newVolume = audio.volume -= 0.05;
                audio.volume = newVolume.toFixed(2);
            }

        }


        updateVolumeBar();


        //  log action
        //console.log("[Volume] "+ action + ": "+ getVolume());

    }








    /*  key press  */




    //  detect user keypress
    $('body').keydown(function(e)
    {

        switch (e.which)
        {

            //  space key
            case 32:
                e.preventDefault();
                togglePlayback();
                break;

            //  plus,
            //  up-arrow,
            //  right-arrow key
            case 187:
            case 38:
            case 39:
                e.preventDefault();
                changeVolume("up");
                break;

            //  minus,
            //  down-arrow,
            //  left-arrow key
            case 189:
            case 40:
            case 37:
                e.preventDefault();
                changeVolume("down");
                break;

        }

        //console.log(e.which);

    });








});
