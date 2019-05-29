<!DOCTYPE html>
<html lang="en">
<head>

    <!--  metadata  -->
    <meta name="author" content="https://github.com/Hmerritt" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=yes">

    <title>Internet Radio</title>


    <!--  tab icons  -->
    <link type="x-image/icon" rel="icon" href="favicon.ico" sizes="16x16">
    <link type="x-image/icon" rel="icon" href="img/logo/favicon-32.ico" sizes="32x32">
    <link type="x-image/icon" rel="icon" href="img/logo/favicon-48.ico" sizes="48x48">
    <link type="x-image/icon" rel="icon" href="img/logo/favicon-64.ico" sizes="64x64">
    <link type="x-image/icon" rel="icon" href="img/logo/favicon-128.ico" sizes="128x128">
    <link type="x-image/icon" rel="icon" href="img/logo/favicon-256.ico" sizes="256x256">

    <!--  apple icons  -->
    <link rel="apple-touch-icon" href="img/logo/logo-64.png">
    <link rel="apple-touch-icon" sizes="152x152" href="img/logo/logo-152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="img/logo/logo-180.png">
    <link rel="apple-touch-icon" sizes="167x167" href="img/logo/logo-167.png">
    <link rel="apple-touch-startup-image" href="img/logo/logo-180.png">
    <meta name="apple-mobile-web-app-title" content="Internet Radio">


    <!--  styles  -->
    <link href="css/main.css" rel="stylesheet" type="text/css">

    <!--  scripts  -->
    <script src="js/libs/jquery.js" type="text/javascript"></script>
    <script src="js/libs/oscilloscope.js" type="text/javascript"></script>
    <script src="js/main.js" type="text/javascript"></script>

</head>
<body>




    <div class="content">
        <div class="container">
            <div class="container-small">
                <header class="title">
                    <h1 class="overflow-ellipsis">Groovebox <strong>Disco</strong></h1>
                </header>
                <div class="album-art no-user-select">
                    <img src="img/cover.jpg" alt="Album Art" draggable="false">
                </div>
                <div class="track-info">
                    <h2 class="track-name overflow-ellipsis" title="Don't leave me now">Don't leave me now</h2>
                    <h3 class="track-artist overflow-ellipsis" title="Madleen Kane">Madleen Kane</h3>
                </div>
                <div class="volume no-user-select">
                    <div class="volume-bar">
                        <div class="volume-bar-percentage"></div>
                    </div>
                    <div class="volume-text">
                        <p>
                            <span>
                                <svg style="width:18px;height:18px" viewBox="0 0 24 24">
                                    <path fill="#000000" d="M14,3.23V5.29C16.89,6.15 19,8.83 19,12C19,15.17 16.89,17.84 14,18.7V20.77C18,19.86 21,16.28 21,12C21,7.72 18,4.14 14,3.23M16.5,12C16.5,10.23 15.5,8.71 14,7.97V16C15.5,15.29 16.5,13.76 16.5,12M3,9V15H7L12,20V4L7,9H3Z" />
                                </svg>
                            </span>
                            70%
                        </p>
                    </div>
                </div>
            </div>
            <div class="oscilloscope">
                <canvas id="canvas" width="1200px" height="300"></canvas>
            </div>
        </div>
    </div>




</body>
</html>
