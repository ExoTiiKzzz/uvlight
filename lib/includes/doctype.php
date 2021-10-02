<?php
function doctype($title, $path){
    $doctype = '<!DOCTYPE html>
    <html lang="fr" dir="ltr">
    <head>
        <title>'.$title.'</title>
        <meta name="viewport" content="width=device-width">
        
        <!-- CSS only -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"/>
        <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css"/>
        <!-- JS, Popper.js, and jQuery -->
        <!-- jQuery Library -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
        <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
        <link rel="stylesheet" href="'.$path.'uvlight/assets/css/main.css">
        <link rel="stylesheet" href="'.$path.'uvlight/assets/css/navbar.css">
        <link rel="stylesheet" href="'.$path.'uvlight/assets/css/sidenav.css">

    
    </head>';
    return $doctype;
}
?>