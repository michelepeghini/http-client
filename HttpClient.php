<?php

/**
 * This is a lightweight HTTP client.
 *  
 */
class HttpClient
{
    private static $METHODS = ['GET', 'PUT', 'POST', 'OPTIONS', 'DELETE'];

    /**
     * Basic request method.
     * 
     * @param $method the http request method
     * @param $url the url for the request
     * @param $data the data to send
     */
    public function request($method, $url, $data)
    {
        if (!in_array(strtoupper($method), HttpClient::$METHODS)) {
            throw new Exception("Invalid method!");
        }
        echo ("$method $url | $data");
    }
}
