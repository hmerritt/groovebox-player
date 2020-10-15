<?php
/*

https://github.com/Hmerritt/groovebox-player

  _____                          _
 / ____|                        | |
| |  __ _ __ ___   _____   _____| |__   _____  __
| | |_ | '__/ _ \ / _ \ \ / / _ \ '_ \ / _ \ \/ /
| |__| | | | (_) | (_) \ V /  __/ |_) | (_) >  <
\______|_|  \___/ \___/ \_/ \___|_.__/ \___/_/\_\

*/
$host = (isset($_SERVER['REQUEST_SCHEME']) ? $_SERVER['REQUEST_SCHEME'] : (isset($_SERVER['HTTPS']) ? 'https' : 'http')) .'://'.$_SERVER['HTTP_HOST'].'/';
session_start();
setcookie("Groovebox","",-1);
$_SESSION = array();
session_destroy();
header('Location: '. $host .'login.php');
exit();