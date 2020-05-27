<?php
require_once "HttpClient.php";
error_reporting(0);

$client = new HttpClient();

$method = "POST";
$url = "https://www.coredna.com/assessment-endpoint.php";
$data = array(
    'name' => 'Michele Peghini',
    'email' => 'michelepeghini@gmail.com',
    'url' => 'https://github.com/michelepeghini/http-client'
);

try {
    $response = $client->request($method, $url, $data);
    print_r($response);
} catch (Exception $e) {
    echo ($e->getMessage());
}
