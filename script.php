<?php
require_once "HttpClient.php";

$client = new HttpClient();

$method = "fGET";
$url = "https://github.com/michelepeghini/http-client";
$data = "michele";

try {
    $client->request($method, $url, $data);
} catch (Exception $e) {
    echo ($e->getMessage());
}
