<?php
$host = (isset($_SERVER['REQUEST_SCHEME']) ? $_SERVER['REQUEST_SCHEME'] : (isset($_SERVER['HTTPS']) ? 'https' : 'http')) .'://'.$_SERVER['HTTP_HOST'].'/';
header("HTTP/1.1 301 Moved Permanently");
header('Location: '. $host .'login.php');
exit();