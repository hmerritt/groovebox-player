


$(document).ready(function()
{





    /*  oscilloscope  */


    //  create an audio stream container
    var audioContext = new window.AudioContext();

    //  setup audio element
    //  add it into the dom
    var audioElement = document.createElement('audio');
                      audioElement.controls = true;
                      audioElement.autoplay = true;
                      audioElement.src = 'test.mp3';
                      document.body.appendChild(audioElement);

    //  create source from html5 audio element
    var source = audioContext.createMediaElementSource(audioElement);

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
        return audioElement.volume;
    }


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
                newVolume = audioElement.volume += 0.05;
                audioElement.volume = newVolume.toFixed(2);
            }

        //  turn volume down
        } else
        {

            //  do nothing if muted
            if (currentVolume !== 0)
            {
                newVolume = audioElement.volume -= 0.05;
                audioElement.volume = newVolume.toFixed(2);
            }

        }


        //  update volume percentage bar
        var volumePercentage = (getVolume() * 100).toFixed(0);
        $(".volume .volume-bar-percentage").css({"width": volumePercentage + "%"});
        $(".volume .volume-text strong").text(volumePercentage+ " %");

        //  log action
        console.log("[volume] "+ action + ": "+ getVolume());

    }




    /*  key press  */


    //  detect user keypress
    $('body').keypress(function(e)
    {

        switch (e.which)
        {

            //  plus key
            case 61:
                changeVolume("up");
                break;

            //  plus key
            case 45:
                changeVolume("down");
                break;

        }

        //console.log(e.which);

    });





});