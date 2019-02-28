<?php
namespace Oxzion\Utils;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\MultipartStream;

class RestClient{
  private $client;
  public function __construct($baseUrl){
    $this->client = new Client(['base_uri' => $baseUrl,'timeout'  => 10.0,]);
  }
  public function get($url,$params=array(),$headers=null){
    $payload = array();
    if(isset($params) && !empty($params)){
      $payload['json'] = $params;
    }
    if(isset($headers) && !empty($headers)){
        $payload['headers'] = $headers;
    }

    $response = $this->client->request('GET',$baseUrl.$url, $payload);
    
    return $response->getBody()->getContents();
  }
  public function delete($url,$params=array(),$headers=null){
    $payload = array();
    if(isset($params) && !empty($params)){
      $payload['json'] = $params;
    }
    if(isset($headers) && !empty($headers)){
        $payload['headers'] = $headers;
    }
    $response = $this->client->request('DELETE',$baseUrl.$url,$payload);
    return $response->getBody()->getContents();
  }
  public function postMultiPart($url,$formParams=array(),$fileParams=array()){
    $boundary = uniqid();
    $multipart_form = array();
    if($formParams){
      foreach ($formParams as $key => $value) {
        $multipart_form[] = array('name'=>$key,'contents'=>$value);
      }
    }
    if($fileParams){
      foreach ($fileParams as $key => $value) {
        $multipart_form[] = array('name'=>$key,'contents'=>fopen($value, 'r'),'headers'  => [ 'Content-Type' => 'application/octet-stream']);
      }
    }
    $params = ['headers' => ['Connection' => 'close','Content-Type' => 'multipart/form-data; boundary='.$boundary,],'body' => new MultipartStream($multipart_form, $boundary),];
    $response = $this->client->request('POST', $url, $params);
    return $response->getBody()->getContents();
  }
  public function post($url,$formParams=array()){
    try {
      if($formParams){
        $response = $this->client->request('POST', $url, ['json'=>$formParams]);
      } else {
        $response = $this->client->request('POST', $url,['headers' => ['Content-Type' => 'application/json']]);
      }
      return $response->getBody()->getContents();
    } catch(Exception $e){
      return 0;
    }
  }
  public function postWithHeader($url,$formParams=array(),$headers=array()){
    try {
      $response = $this->client->request('POST', $url,['headers' => $headers,'json' => $formParams]);
        return array('body'=>$response->getBody()->getContents(),'headers'=>$response->getHeaders());
    } catch(Exception $e){
        return 0;
    }
  }
  public function put($url,$params=array(),$headers=null){
    $payload = array();
    if(isset($params) && !empty($params)){
      $payload['json'] = $params;
    }
    if(isset($headers) && !empty($headers)){
        $payload['headers'] = $headers;
    }
    $response = $this->client->request('PUT',$baseUrl.$url,$payload);
    return $response->getBody()->getContents();
  }
}
?>