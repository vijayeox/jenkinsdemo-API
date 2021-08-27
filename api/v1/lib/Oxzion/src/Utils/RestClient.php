<?php
namespace Oxzion\Utils;

use Exception;
use Oxzion\HttpException;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\MultipartStream;

class RestClient
{
    private $client;
    
    public function __construct($baseUrl, $params=array())
    {
        $config = ['verify' => false,'timeout'  => 400.0];
        if (isset($baseUrl)) {
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
        $response = $this->client->request('GET', $url, $payload);
        $var = $response->getBody()->getContents();
        if ($response->getStatusCode() >= 200 && $response->getStatusCode() < 300) {
            return $var;
        }
        throw new HttpException($var, $response->getStatusCode());
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
        $var = $response->getBody()->getContents();
        if ($response->getStatusCode() >= 200 && $response->getStatusCode() < 300) {
            return $var;
        }
        throw new HttpException($var, $response->getStatusCode());
    }

    public function postMultiPart($url, $formParams = array(), $fileParams = array(), array $headers = null)
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
        $headerList = ['Connection' => 'close', 'Content-Type' => 'multipart/form-data; boundary=' . $boundary];
        if ($headers) {
            $headerList = array_merge($headerList, $headers);
        }
        $params = ['headers' => $headerList, 'body' => new MultipartStream($multipart_form, $boundary)];
        $response = $this->client->post($url, $params);
        $var = $response->getBody()->getContents();
        if ($response->getStatusCode() >= 200 && $response->getStatusCode() < 300) {
            return $var;
        }
        throw new HttpException($var, $response->getStatusCode());
    }

    public function post($url, $formParams = array())
    {
        if ($formParams) {
            $response = $this->client->request('POST', $url, ['json' => $formParams]);
        } else {
            $response = $this->client->request('POST', $url, ['headers' => ['Content-Type' => 'application/json']]);
        }
        $var = $response->getBody()->getContents();
        if ($response->getStatusCode() >= 200 && $response->getStatusCode() < 300) {
            return $var;
        }
        throw new HttpException($var, $response->getStatusCode());
    }

    public function postWithHeader($url, $formParams = array(), $headers = array())
    {
        $response = $this->client->request('POST', $url, ['headers' => $headers, 'json' => $formParams]);
        return array('body' => $response->getBody()->getContents(), 'headers' => $response->getHeaders(),'status' =>$response->getStatusCode());
    }

    public function deleteWithHeader($url, $formParams = array(), $headers = array())
    {
        $response = $this->client->request('DELETE', $url, ['headers' => $headers, 'json' => $formParams]);
        return array('body' => $response->getBody()->getContents(), 'headers' => $response->getHeaders(),'status' =>$response->getStatusCode());
    }

    public function updateWithHeader($url, $formParams = array(), $headers = array())
    {
        $response = $this->client->request('PUT', $url, ['headers' => $headers, 'json' => $formParams]);
        return array('body' => $response->getBody()->getContents(), 'headers' => $response->getHeaders(),'status' =>$response->getStatusCode());
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
        $var = $response->getBody()->getContents();
        if ($response->getStatusCode() >= 200 && $response->getStatusCode() < 300) {
            return $var;
        }
        throw new HttpException($var, $response->getStatusCode());
    }

    public function postWithHeaderAsBody($url, string $formParams, $headers = array())
    {
        $response = $this->client->request('POST', $url, ['headers' => $headers, 'body' => $formParams]);
        return array('body' => $response->getBody()->getContents(), 'headers' => $response->getHeaders(),'status' =>$response->getStatusCode());
    }
}
