<?php
// namespace HttpClient;

/**
 * This is a lightweight HTTP client.
 * @author Michele Peghini <michele.peghini@coredna.com>
 * @version 1.0
 */
class HttpClient
{
    /**
     * An array of available http methods, used for internal validation of requests.
     */
    private const METHODS = ['GET', 'PUT', 'POST', 'OPTIONS', 'DELETE'];

    /**
     * An array of available protocols, used for internal validation of requests.
     */
    private const PROTOCOLS = ['http', 'https'];

    /**
     * Validates request method and converts it to uppercase
     * @param string $method the method of the request as a string
     * @throws Exception invalid method 
     * @return string the uppercase $method string
     */
    private function validateMethod($method)
    {
        $sanitized_method = strtoupper($method);
        if (!in_array($sanitized_method, self::METHODS)) {
            throw new Exception("Invalid method!");
        }
        return $sanitized_method;
    }

    /**
     * Validates the URL of the request
     * @param string $url, the URL of the request as a string
     * @throws Exception invalid method 
     * @return array an associative array composed of "protocol" and "url"   
     */
    private function validateUrl($url)
    {
        $url_arr = explode('://', $url);
        if (count($url_arr) !== 2) {
            throw new Exception("Invalid URL format!");
        }
        if (!in_array($url_arr[0], self::PROTOCOLS)) {
            throw new Exception("Invalid protocol!");
        }
        return array(
            'protocol' => $url_arr[0],
            'url' => $url
        );
    }

    /**
     * Validates the data of the request
     * @param array $data, the payload of the request
     * @throws Exception invalid data format 
     * @return string a json-encoded string   
     */
    private function validateData($data)
    {
        $json_data = json_encode($data);
        if (!$json_data) {
            throw new Exception("Invalid data format!");
        }
        return $json_data;
    }

    /**
     * Get Authentication token
     * @param string the url to send OPTIONS request to
     * @throws Exception Unable to get token
     * @return string the authentication token 
     */
    private function getToken($url)
    {
        $options = array(
            'http' => array(
                'method' => 'OPTIONS',
                'header' => 'Content-type: application/x-www-form-urlencoded'
            )
        );
        $context = stream_context_create($options);
        $response = file_get_contents(
            $url,
            false,
            $context
        );

        if ($response == false) {
            throw new Exception('Unable to get token!');
        }
        $this->token = $response;
    }

    /**
     * Send a request to given url, with given metod and optional data.
     * @param string $method the http request method
     * @param string $url the url for the request
     * @param array $data the data to send as an associative array
     */
    public function request($method, $url, $data = array())
    {
        $url_arr = $this->validateUrl($url);
        $request = array(
            'method' => $this->validateMethod($method),
            'protocol' => $url_arr['protocol'],
            'url' => $url_arr['url'],
            'data' => $this->validateData($data)
        );
        $token = $this->getToken($url_arr['url']); //"d417fcbc-94b7-4357-aff3-536c33364428"

        $options = array(
            'http' => array(
                'method' => $request['method'],
                'header' => array(
                    "Authentication: Basic $token",
                    "Content-type: application/x-www-form-urlencoded"
                ),
                'content' => $request['data']
            )
        );
        $context = stream_context_create($options);
        $response = file_get_contents(
            $request['url'],
            false,
            $context
        );

        if ($response == false) {
            throw new Exception("Unable to fulfill request!");
        };

        $json_response = json_decode($response->data);
        if ($json_response === NULL) {
            throw new Exception("Unable to read response!");
        }
        return $json_response;
    }
}
