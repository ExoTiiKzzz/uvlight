<?php
require 'bootstrap.php';
$secret = getenv('SECRET');
$header = json_encode([
    'typ' => 'JWT',
    'alg' => 'HS256'
]);
$payload = json_encode([
    'user_id' => 1,
    'role' => 'admin',
    'exp' => time()+ 3600 * 24
]);
$base64UrlHeader = base64UrlEncode($header);
$base64UrlPayload = base64UrlEncode($payload);
$signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $secret, true);
$base64UrlSignature = base64UrlEncode($signature);
$jwt = $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;

setcookie("jwt", $jwt, time()+3600*24*365*8, "/");
