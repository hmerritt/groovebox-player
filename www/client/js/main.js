


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




    //  set main groovebox var
    //  stores metadata on each mount point
    var groovebox = {
        "playlist": $("playlist#playlist").attr("content"),
        "songsPlayed": 0,
        "isBegun": false,
        "pathToApi": "../api"
    };








    //  detect if browser allows autoplaying audio
    function canAutoplay()
    {


        //  IE11 now returns undefined again for window.chrome
        //  and new Opera 30 outputs true for window.chrome
        //  but needs to check if window.opr is not undefined
        //  and new IE Edge outputs to true now for window.chrome
        //  and if not iOS Chrome check
        //  so use the below updated condition
        var isChromium = window.chrome;
        var winNav = window.navigator;
        var vendorName = winNav.vendor;
        var isOpera = typeof window.opr !== "undefined";
        var isIEedge = winNav.userAgent.indexOf("Edge") > -1;
        var isIOSChrome = winNav.userAgent.match("CriOS");


        //  most browsers allow autoplay
        var response = true;


        //  detect chrome browser
        if (
          isChromium !== null &&
          typeof isChromium !== "undefined" &&
          vendorName === "Google Inc." &&
          isOpera === false &&
          isIEedge === false
        ) {
           // is Google Chrome
           response = false;
        }


        return response;


    }









    /*  metadata  */




    //  get stream metadata for a specific playlist
    function getStreamData(playlist)
    {


        fetch(groovebox["pathToApi"] +"/?metadata&playlist="+ playlist).then(function (r)
        {

            //  expect a json response
            return r.json();

        }).then(function (data)
        {


            //  add metadata to main groovebox var
            groovebox["stream"] = data;



            //  split the full title into title and artist
            groovebox["metadata"] = {
                "fullTitle": groovebox["stream"]["track"].replace(/\.[^/.]+$/, "")
            };


            //  check for dash " - " within the file name
            if (groovebox["metadata"]["fullTitle"].includes(" - "))
            {

                //  split the file name; artist - title
                groovebox["metadata"]["title"] = groovebox["metadata"]["fullTitle"].split(" - ")[1];
                groovebox["metadata"]["artist"] = groovebox["metadata"]["fullTitle"].split(" - ")[0];

            } else
            {

                //  use the full-title as the main title and leave the artist blank
                groovebox["metadata"]["title"] = groovebox["metadata"]["fullTitle"];
                groovebox["metadata"]["artist"] = "-";

            }


            //  add the URl for the cover-art to the metadata
            groovebox["metadata"]["coverArt"] = groovebox["pathToApi"] +"/?cover&playlist="+ groovebox["playlist"];



            //  updata the metadata in the UI
            applyMetadata();


        });


    }





    //  add the metadata values from the groovebox var into the user-interface
    function applyMetadata()
    {


        //  get metadata values
        var metadata = groovebox["metadata"];


        //  get the current audio file name
        var currentAudioFile = $(".track-info").attr("file");


        //  check if the (new) metadata is different than the currently playing song
        if (groovebox["stream"]["track"] !== currentAudioFile)
        {


            //  update the metadata


            //  update the track name
            $(".track-info .track-name").html(metadata["title"]).attr("title", metadata["title"]);


            //  update the track artist
            $(".track-info .track-artist").html(metadata["artist"]).attr("title", metadata["artist"]);


            //  update the cover-art
            $(".album-art img").attr("src", metadata["coverArt"] +"&"+ new Date().getTime());


            //  update the current audio file
            $(".track-info").attr("file", groovebox["stream"]["track"]);


        }


    }








    /*  audio  */




    //  log action
    console.log("[Audio] Attempting to start audio");





    //  setup audio element
    //  set volume to 50%
    var audio = new Audio();





    //  define what happens when audio first starts playing
    function setupAudio()
    {


        //  change has begun to true
        groovebox["isBegun"] = true;


        //  start oscilloscope
        oscilloscope();


    }




    //  detect if browser can start the audioContext with no user interaction
    //  chrome cannot autoplay audio
    if (canAutoplay())
    {


        //  create audio context
        setupAudio();


        //  play audio
        togglePlayback();


    } else
    {

        //  show play button
        setControlsIcon("play");


        //  show audio controls
        $(".audio-controls").removeClass("hidden");

    }





    function changeAudio()
    {


        //  change audio element
        //  set volume to 50%
        //  allow cross origin
        audio.src = groovebox["pathToApi"] +"/?stream&playlist="+ groovebox["playlist"] +"&"+ new Date().getTime();
        audio.volume = 0.5;
        audio.setAttribute("crossorigin", "anonymous");
        audio.setAttribute("allow", "accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture");


        //  get latest track metadata
        getStreamData(groovebox["playlist"]);


    }




    //  get audio metadata on page load
    changeAudio();






    //  wait for the track to end
    audio.addEventListener("ended", function()
    {


        //  re-fetch the audio stream (a new track should be playing)
        changeAudio();


        //  play the new audio
        togglePlayback();


        //  add one to the song counter
        groovebox["songsPlayed"] += 1;


    });








    /*  audio state (play/pause)  */




    //  stop/start music playback
    function togglePlayback()
    {


        //  check if audio has begun yet
        if (!groovebox["isBegun"])
        {

            setupAudio();

        }



        //  check if audio is playing
        if (!audio.paused && audio.duration > 0)
        {


            audio.pause();
            setControlsIcon("play");


        } else
        {


            // attempt to play the audio
            var playPromise = audio.play();


            //  catch error if the audio fails to start
            if (playPromise !== undefined)
            {

                playPromise.then(function (_)
                {

                    // Automatic playback started!
                    // Show playing UI.
                    setControlsIcon("pause");
                    $(".audio-controls").addClass("hidden");
                    console.log("[Audio] Audio started successfully!");

                }).catch(function (error)
                {

                    // Auto-play was prevented
                    // Show paused UI.
                    toggleControls("play");
                    console.error("[Audio] Audio playback was prevented (" + error + ")");

                });

            }


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




    //  initiate the oscilloscope
    //  ---only run this once---
    function oscilloscope()
    {


        //  create an audio stream container
        var audioContext = new window.AudioContext();


        //  create source from html5 audio element
        var source = audioContext.createMediaElementSource(audio);


        //  attach oscilloscope
        var scope = new Oscilloscope(source);

        //  start default animation loop
        scope.animate(canvas.getContext("2d"));


        //  reconnect audio output to speakers
        source.connect(audioContext.destination);


    }




    //  calculate the oscilloscopes position on the page
    function oscilloscopePosition(position)
    {


        //  create obj var to store all object positions
        //  calculate positions of elements on the page
        var obj = {
            "oscilloscope": {
                "height": $(".oscilloscope").height(),
                "position": {
                    "coverArt": {},
                    "trackInfo": {}
                }
            },
            "coverArt": {
                "top": $(".album-art").offset()["top"],
                "height": $(".album-art").height()
            },
            "trackInfo": {
                "top": $(".track-info").offset()["top"],
                "height": 68
            }
        };



        //  calculate the middle each element
        obj["coverArt"]["middle"] = obj["coverArt"]["top"] + (obj["coverArt"]["height"] / 2);
        obj["trackInfo"]["middle"] = obj["trackInfo"]["top"] + (obj["trackInfo"]["height"] / 2);



        //  calculate correct middle taking into account the height of the 'oscilloscope' element
        obj["oscilloscope"]["position"]["coverArt"]["middle"] = obj["coverArt"]["middle"] - (obj["oscilloscope"]["height"] / 2);


        //  calculate below padding taking into account the height of the 'oscilloscope' element
        obj["oscilloscope"]["position"]["trackInfo"]["below"] = (obj["trackInfo"]["top"] + obj["trackInfo"]["height"]) - 60;



        //  check what position to put the oscilloscope
        //  detect screen width
        //  if larger than 550 (mobile size)
        if ($(window).outerWidth() > 550)
        {


            //  place the oscilloscope in the center of the cover-art
            $(".oscilloscope").css({
              "top": obj["oscilloscope"]["position"]["coverArt"]["middle"] +"px"
            });


        } else
        {


            //  place the oscilloscope just below the trackInfo
            $(".oscilloscope").css({
              "top": obj["oscilloscope"]["position"]["trackInfo"]["below"] +"px"
            });


        }



    }




    //  calculate oscilloscope's position on page load
    oscilloscopePosition();


    //  re-calculate oscilloscope's position if the screen size changes
    $(window).resize(function()
    {

        oscilloscopePosition();

    });







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
