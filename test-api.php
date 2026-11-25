
<?php

$url = 'https://dev.dreamsgallerybd.com/api/products';

$token = '8e0f1a3c5d7e9f1b3d5e7a9c1e3f5b7d9e0f2';

$ch = curl_init($url);

curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_CAINFO => 'D:/Program Files/PHP/extras/ssl/cacert.pem',
    CURLOPT_HTTPHEADER => [
        'Accept: application/json',
        'Authorization: Bearer ' . $token,
    ],
]);

$response = curl_exec($ch);

echo 'HTTP Status: ' . curl_getinfo($ch, CURLINFO_HTTP_CODE) . PHP_EOL;

if ($response === false) {
    echo 'Error: ' . curl_error($ch) . PHP_EOL;
} else {
    echo 'API connection successful!' . PHP_EOL;
}

curl_close($ch);
