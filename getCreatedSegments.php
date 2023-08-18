<?php

$settingsFolder = __DIR__ . "/.settings/";

$counter = file_get_contents($settingsFolder . "counter.txt");
$tokenJSON = json_decode(file_get_contents($settingsFolder . "token.json"), true);

$files = array_diff(scandir('C:\Users\user\Desktop\dab'), ['.', '..']);

foreach ($files as $file) {
    $fileinfo = pathinfo($file);
    $segmentName = $fileinfo['filename'];
    $tokenOAuth = $tokenJSON['access_token'];

    $headers = array(
        "Authorization: OAuth " . $tokenOAuth,
    );

    $urlSegment = "https://api-metrika.yandex.net/management/v1/counter/$counter/apisegment/segments";

    $ch = curl_init($urlSegment);

    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $response = curl_exec($ch);

    curl_close($ch);

    file_put_contents($settingsFolder . 'created-segments.log', "\n $response \n", FILE_APPEND);

    break;
}

die();




