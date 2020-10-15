<?php
/**
 * //////////////////////////////////////////////
 * Your settings
 * //////////////////////////////////////////////
*/
$login = 'Admin';
$password = 'admin';
$playlistByDefault = '';
/**
 * //////////////////////////////////////////////
 *  * Don't edit the rest of the file
 * //////////////////////////////////////////////
*/
if ($playlistByDefault == '') {
	$playlistsAvailable = scandir(__DIR__ .'/tracks',1);
	$playlistsAvailable = array_splice($playlistsAvailable, 0, (count($playlistsAvailable)-2));
	sort($playlistsAvailable, SORT_NATURAL | SORT_FLAG_CASE);
	$playlistByDefault = $playlistsAvailable[0];
}
$host = $_SERVER['REQUEST_SCHEME'] .'://'.$_SERVER['HTTP_HOST'].'/';
if(isset($_POST['login'])){
    if(($_POST['login']==$login)&&($_POST['password']==$password)){
        setcookie("Groovebox",$_POST['login'],time() + (365 * 24 * 60 * 60));
        header('Location: '. $host .'pl/'. $playlistByDefault);
    }
}
?><!--

https://github.com/Hmerritt/groovebox-player

  _____                          _
 / ____|                        | |
| |  __ _ __ ___   _____   _____| |__   _____  __
| | |_ | '__/ _ \ / _ \ \ / / _ \ '_ \ / _ \ \/ /
| |__| | | | (_) | (_) \ V /  __/ |_) | (_) >  <
\______|_|  \___/ \___/ \_/ \___|_.__/ \___/_/\_\

-->
<!DOCTYPE html>
<html lang="en">
<head>

    <!--  metadata  -->
    <meta name="author" content="https://github.com/Hmerritt" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=yes">

    <!--  title  -->
    <title>Groovebox</title>

    <!--  tab icons  -->
    <link type="x-image/icon" rel="icon" href="<?php echo $host;?>client/favicon.ico" sizes="16x16">
    <link type="x-image/icon" rel="icon" href="<?php echo $host;?>client/img/logo/favicon-32.ico" sizes="32x32">
    <link type="x-image/icon" rel="icon" href="<?php echo $host;?>client/img/logo/favicon-48.ico" sizes="48x48">
    <link type="x-image/icon" rel="icon" href="<?php echo $host;?>client/img/logo/favicon-64.ico" sizes="64x64">
    <link type="x-image/icon" rel="icon" href="<?php echo $host;?>client/img/logo/favicon-128.ico" sizes="128x128">
    <link type="x-image/icon" rel="icon" href="<?php echo $host;?>client/img/logo/favicon-256.ico" sizes="256x256">

    <!--  apple icons  -->
    <link rel="apple-touch-icon" href="<?php echo $host;?>client/img/logo/logo-64.png">
    <link rel="apple-touch-icon" sizes="152x152" href="<?php echo $host;?>client/img/logo/logo-152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo $host;?>client/img/logo/logo-180.png">
    <link rel="apple-touch-icon" sizes="167x167" href="<?php echo $host;?>client/img/logo/logo-167.png">
    <link rel="apple-touch-startup-image" href="<?php echo $host;?>client/img/logo/logo-180.png">
    <meta name="apple-mobile-web-app-title" content="Internet Radio">


    <!--  styles  -->
    <link href="<?php echo $host;?>client/css/main.css" rel="stylesheet" type="text/css">

    <!--  scripts  -->
    <script src="<?php echo $host;?>client/js/libs/jquery.js" type="text/javascript"></script>
    <script src="<?php echo $host;?>client/js/libs/oscilloscope.js" type="text/javascript"></script>
    <script src="<?php echo $host;?>client/js/libs/unfetch.js" type="text/javascript"></script>
    <script src="<?php echo $host;?>client/js/main.js" type="text/javascript"></script>

</head>
<body>
    <div class="content site-wrap">
            <div class="container">
                <div class="container-small login">
                    <header class="title">
                        <h1>Authentification</h1>
                    </header>

                <form action="#" method="post">
                  
                    <p><input name="login" type="text" class="form-control" placeholder="Login"></p>
                  
                    <p><input name="password" type="password" class="form-control" placeholder="Password"></p>
                    
                    <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
                    
                </form>

               </div>
            </div>
    </div>
</body>
</html>