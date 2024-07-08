<?php
global $base_path;
require_once $base_path . 'library\core.php';
$errors = [];

if(isset($_GET['path']) && str_contains($_GET['path'], 'downloads'))
{
    $url = $_GET['path'];
    // clear cash
    clearstatcache();

    if(file_exists($url)) {

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="'.basename($url).'"');
        header('Content-Length: '. filesize($url));
        header('Pragma: public');

        // clear buffer
        flush();

        readfile($url,true);

        die();
    }
    else{
        $errors [] = "The file you tried to download does not exist. ";
    }
} else {
    $errors [] = "This file does not exist.";
}

if ($errors) {

    $errors [] = "<a href='index.php?path=error'>back</a>";
    $context = ['errors'=>$errors];
    render('main', $context);
    exit();
}
