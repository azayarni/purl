<?php

if (!function_exists('curl_init')) {
    require_once '../src/Purl.php';
}
        
$ch = curl_init('http://www.example.com');

curl_setopt_array($ch, array(
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_HEADER => 1,
));
        
$result = curl_exec($ch);
        
echo 'GET request was successful. ' . mb_strlen($result) . ' bytes received';