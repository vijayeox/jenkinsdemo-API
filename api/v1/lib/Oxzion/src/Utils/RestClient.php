<?php
namespace Oxzion\Utils;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\MultipartStream;

class RestClient{
  private $client;
  public function __construct($baseUrl){
    $this->client = new Client(['base_uri' => $baseUrl,'timeout'  => 2.0,]);
  }
  public function get($url,$params=array()){
    $response = $this->client->request('GET',$baseUrl.$url, $params);
    return $response->getBody()->getContents();
  }
  public function delete($url,$params=array()){
    $response = $this->client->request('DELETE',$baseUrl.$url,$params);
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
  public function put($url,$params){
    $response = $client->request('PUT',$baseUrl.$url);
    return $response->getBody()->getContents();
  }
}
?>