<?php
namespace Oxzion\Workflow\Camunda;

use Oxzion\Workflow\ProcessManager;
use Oxzion\Utils\RestClient;

class ProcessManagerImpl implements ProcessManager {
  private $restClient;
  public function __construct(){
    $this->restClient = new RestClient(Config::ENGINE_URL);
  }
  public function setRestClient($restClient){
    $this->restClient = $restClient;
  }

  public function deploy($tenantId,$name,$filesArray){
    $fields = array("deployment-name"=>$name,"tenant-id"=>$tenantId);
    $url = "deployment/create";
    try{
     $response = $this->restClient->postMultiPart($url,$fields,$filesArray);
     $result = json_decode($response,true);
     if($result){
       $process = new Process();
       $process->exchangeArray($result);
       return $process->toArray();
     } else {
      return 0;
    }
  } catch (Exception $e){
    return 0;
  }
}

public function remove($id){
  return $this->restClient->delete("deployment/".$id)?0:1;
}

public function get($id){
  try {
    $response = $this->restClient->get("deployment/".$id);
    $result = json_decode($response,true);
    return $result;
  } catch (Exception $e){
    return 0;
  }
}
}
?>