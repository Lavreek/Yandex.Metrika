<?php

$client_id = file_get_contents(__DIR__ . '/.secret/auth/client_id.txt');
$client_secret = file_get_contents(__DIR__ . '/.secret/auth/client_secret.txt');

$url = "https://oauth.yandex.ru/token?grant_type=authorization_code";
$ch = curl_init($url);

$headers = array(
    "Content-type: application/x-www-form-urlencoded",
    "Authorization: Basic " . base64_encode("$client_id:$client_secret")
);

$code = file_get_contents(__DIR__ . '/.secret/auth/code.txt');
$data = 'grant_type=authorization_code&code=1588932';

curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_POST, 1); 
curl_setopt($ch, CURLOPT_POSTFIELDS, $data); 

$response = curl_exec($ch);

echo "\n" . $response . "\n";