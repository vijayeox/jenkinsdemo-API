<?php
namespace Oxzion\Utils;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Psr7\MultipartStream;

class RestClient
{
    private $client;
    
    public function __construct($baseUrl, $params=array())
    {
        $config = ['verify' => false,'timeout'  => 220.0];
        if(isset($baseUrl)) {
            $config['base_uri'] = $baseUrl;
        }
        $this->client = new Client(array_merge($config, $params));
    }

    public function get($url, $params = array(), $headers = array())
    {
        $payload = array();
        if (isset($params) && !empty($params)) {
            $payload['json'] = $params;
        }
        if (isset($headers) && !empty($headers)) {
            $payload['headers'] = $headers;
        }
        try {
            $response = $this->client->request('GET', $url, $payload);
        } catch (Exception $e) {
            return $e->getMessage();
        }
        return $response->getBody()->getContents();
    }

    public function delete($url, $params = array(), $headers = null)
    {
        $payload = array();
        if (isset($params) && !empty($params)) {
            $payload['json'] = $params;
        }
        if (isset($headers) && !empty($headers)) {
            $payload['headers'] = $headers;
        }
        $response = $this->client->request('DELETE', $url, $payload);
        return $response->getBody()->getContents();
    }

    public function postMultiPart($url, $formParams = array(), $fileParams = array())
    {
        $boundary = uniqid();
        $multipart_form = array();
        if ($formParams) {
            foreach ($formParams as $key => $value) {
                $multipart_form[] = array('name' => $key, 'contents' => $value);
            }
        }
        if ($fileParams) {
            foreach ($fileParams as $key => $value) {
                $multipart_form[] = array('name' => $key, 'contents' => fopen($value, 'r'), 'headers' => ['Content-Type' => 'application/octet-stream']);
            }
        }
        $params = ['headers' => ['Connection' => 'close', 'Content-Type' => 'multipart/form-data; boundary=' . $boundary], 'body' => new MultipartStream($multipart_form, $boundary)];
        try {
            $response = $this->client->post($url, $params);
            $var = $response->getBody()->getContents();
            return $var;
        } catch (ServerException $e) {
            return $e->getMessage();
        }
    }

    public function post($url, $formParams = array())
    {
        try {
            if ($formParams) {
                $response = $this->client->request('POST', $url, ['json' => $formParams]);
            } else {
                $response = $this->client->request('POST', $url, ['headers' => ['Content-Type' => 'application/json']]);
            }
            return $response->getBody()->getContents();
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function postWithHeader($url, $formParams = array(), $headers = array())
    {
        try {
            $response = $this->client->request('POST', $url, ['headers' => $headers, 'json' => $formParams]);
            return array('body' => $response->getBody()->getContents(), 'headers' => $response->getHeaders());
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function deleteWithHeader($url, $formParams = array(), $headers = array())
    {
        try {
            $response = $this->client->request('DELETE', $url, ['headers' => $headers, 'json' => $formParams]);
            return array('body' => $response->getBody()->getContents(), 'headers' => $response->getHeaders());
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function updateWithHeader($url, $formParams = array(), $headers = array())
    {
        try {
            $response = $this->client->request('PUT', $url, ['headers' => $headers, 'json' => $formParams]);
            return array('body' => $response->getBody()->getContents(), 'headers' => $response->getHeaders());
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function put($url, $params = array(), $headers = null)
    {
        $payload = array();
        if (isset($params) && !empty($params)) {
            $payload['json'] = $params;
        }
        if (isset($headers) && !empty($headers)) {
            $payload['headers'] = $headers;
        }
        $response = $this->client->request('PUT', $url, $payload);
        return $response->getBody()->getContents();
    }
}
