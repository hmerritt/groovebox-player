


$(document).ready(function()
{




    var audioContext = new window.AudioContext();


    // setup audio element
    var audioElement = document.createElement('audio');
                      audioElement.controls = true;
                      audioElement.autoplay = true;
                      audioElement.src = 'https://merritt.es/radio/60s';
                      document.body.appendChild(audioElement);


    // create source from html5 audio element
    var source = audioContext.createMediaElementSource(audioElement);

    // attach oscilloscope
    var scope = new Oscilloscope(source);

    // start default animation loop
    scope.animate(canvas.getContext("2d"));

    // reconnect audio output to speakers
    source.connect(audioContext.destination);




});
