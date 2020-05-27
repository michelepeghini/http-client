<?php
require_once "HttpClient.php";

$client = new HttpClient();

$method = "GET";
$url = "https://www.coredna.com/assessment-endpoint.php";
$data = array(
    'name' => 'Michele Peghini',
    'email' => 'michelepeghini@gmail.com',
    'url' => 'https://github.com/michelepeghini/http-client'
);

try {
    $client->request($method, $url, $data);
} catch (Exception $e) {
    echo ($e->getMessage());
}
