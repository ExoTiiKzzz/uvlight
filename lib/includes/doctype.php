<?php
function doctype($title, $path){
    $doctype = '<!DOCTYPE html>
    <html lang="fr" dir="ltr">
    <head>
        <title>'.$title.'</title>
        <meta name="viewport" content="width=device-width">
        
        <!-- CSS only -->
        <link rel="stylesheet" href="'.$path.'uvlight/assets/css/bootstrap.min.css"/>
        <link rel="stylesheet" href="'.$path.'uvlight/assets/fontawesome/css/all.css"/>
        <!-- JS, Popper.js, and jQuery -->
        <!-- jQuery Library -->
        <script src="'.$path.'uvlight/assets/js/jquery.js"></script>
        <script src="'.$path.'uvlight/assets/js/popper.js"></script>
        <script src="'.$path.'uvlight/assets/js/sweetalert.js"></script>
        <link rel="stylesheet" href="'.$path.'uvlight/assets/css/main.css">
        <link rel="stylesheet" href="'.$path.'uvlight/assets/css/navbar.css">
        <link rel="stylesheet" href="'.$path.'uvlight/assets/css/sidenav.css">

    
    </head>';
    return $doctype;
}
?>