<?php
namespace Oxzion\Workflow\Camunda;

use Oxzion\Workflow\ProcessManager;
use Oxzion\Utils\RestClient;
use Sabre\Xml\Service;
use Oxzion\Service\FormService;

class ProcessManagerImpl implements ProcessManager {
  public function __construct() {
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
public function parseBPMN($file,$appId){
  $document = new \DOMDocument();
  $document->load($file);
  $formArray = array();
  $i=0;
  foreach ($document->getElementsByTagNameNs(Config::bpmnSpec, 'process') as $element) {
    $elementList = $document->getElementsByTagNameNs(Config::bpmnSpec, 'userTask');
    foreach ($elementList as $task) {
      $fieldArray = array();
      $formArray[$i]['form'] = $this->generateForm($task,$appId,$element->getAttribute('id'));
      $processId = $task->getAttribute('id');
      $extensionElements = $task->getElementsByTagNameNS(Config::bpmnSpec,'extensionElements');
      foreach ($extensionElements as $eElem) {
        $elements = $eElem->getElementsByTagNameNS(Config::camundaSpec,'formData');
        if($elements){
          foreach ($elements as $elem) {
            $fields = $elem->getElementsByTagNameNS(Config::camundaSpec,'formField');
            $fieldArray = $this->generateFields($fieldArray,$fields,$appId);
          }
        }
        $hiddenFields = $eElem->getElementsByTagNameNS(Config::camundaSpec,'field');
        if($hiddenFields){
          foreach ($hiddenFields as $hiddenField) {
            $fieldArray = $this->generateHiddenFields($fieldArray,$hiddenField,$processId,$appId);
          }
        }
        $formArray[$i]['fields'] = $fieldArray;
      }
      $i++;
    }
  }
  return $formArray;
}
private function generateForm($task,$appId,$processId){
  $oxForm = new CamundaForm($task,$appId,$processId);
  return $oxForm->toArray();
}
private function generateFields($fieldArray,$fields,$appId){
  foreach ($fields as $field) {
    $oxField = new CamundaField($field,$appId);
    $fieldArray[] = $oxField->toArray();
  }
  return $fieldArray;
}
private function generateHiddenFields($fieldArray,$field,$appId){
  $type = $field->getElementsByTagNameNS(Config::camundaSpec,'*');
  $fieldType = $type->item(0)->localName;
  if($fieldType == 'expression'){
    $fieldArray[] = array('name'=>$field->getAttribute('name'),'app_id'=>$appId,'text'=>$field->getAttribute('name'),'type'=>'hidden','expression'=>$type->item(0)->nodeValue);
  } else {
    $fieldArray[] = array('name'=>$field->getAttribute('name'),'app_id'=>$appId,'text'=>$field->getAttribute('name'),'type'=>'hidden');
  }
  return $fieldArray;
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