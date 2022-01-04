<?php
function doctype($title, $path){
    $doctype = '<!DOCTYPE html>
    <html lang="fr" dir="ltr">
    <head>
        <title>'.$title.'</title>
        <meta name="viewport" content="width=device-width">
        
        <!-- CSS only -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css"/>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"/>
        <!-- JS, Popper.js, and jQuery -->
        <!-- jQuery Library -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.0/umd/popper.min.js"></script>
        <link rel="stylesheet" href="'.$path.'uvlight/assets/css/main.css">
        <link rel="stylesheet" href="'.$path.'uvlight/assets/css/navbar.css">
        <link rel="stylesheet" href="'.$path.'uvlight/assets/css/sidenav.css">

    
    </head>';
    return $doctype;
}
?>