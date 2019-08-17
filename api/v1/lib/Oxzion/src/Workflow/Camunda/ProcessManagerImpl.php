<?php
namespace Oxzion\Workflow\Camunda;

use Oxzion\Workflow\ProcessManager;
use Oxzion\Utils\RestClient;
use Sabre\Xml\Service;
use Oxzion\Service\FormService;
use Oxzion\Utils\XMLUtils;

class ProcessManagerImpl implements ProcessManager
{
    public function __construct()
    {
        $this->restClient = new RestClient(Config::ENGINE_URL);
    }
    public function setRestClient($restClient)
    {
        $this->restClient = $restClient;
    }

    public function deploy($name, $filesArray)
    {
        $fields = array("deployment-name"=>$name);
        $url = "deployment/create";
        try {
            $response = $this->restClient->postMultiPart($url, $fields, $filesArray);
            $result = json_decode($response, true);
            if ($result) {
                return array_keys($result['deployedProcessDefinitions']);
            } else {
                return 0;
            }
        } catch (Exception $e) {
            return 0;
        }
    }
    public function parseBPMN($file, $appId, $workflowId)
    {
        $document = new \DOMDocument();
        $document->load($file);
        $formArray = array();
        $i=0;
        foreach ($document->getElementsByTagNameNs(Config::bpmnSpec, 'process') as $element) {
            $startEventList = $element->getElementsByTagNameNs(Config::bpmnSpec, 'startEvent');
            $startFormId = $startEventList->item(0)->getAttribute('id');
            $startForm = $startEventList->item(0)->getElementsByTagNameNS(Config::camundaSpec, 'formData');
            if ($startForm) {
                $fieldArray = array();
                $formArray[$i]['form'] = $this->generateForm($startEventList->item(0), $appId, $workflowId);
                $fields = $startEventList->item(0)->getElementsByTagNameNS(Config::camundaSpec, 'formField');
                $formArray[$i]['fields'] = $this->generateFields($fieldArray, $fields, $appId, $workflowId);
                $formArray[$i]['start_form'] = $startEventList->item(0)->getAttribute('id');
                $i++;
            }
            $elementList = $element->getElementsByTagNameNs(Config::bpmnSpec, 'userTask');
            foreach ($elementList as $task) {
                $fieldArray = array();
                $formArray[$i]['activity'] = $this->generateActivity($task, $appId, $workflowId);
                $extensionElements = $task->getElementsByTagNameNS(Config::bpmnSpec, 'extensionElements');
                foreach ($extensionElements as $eElem) {
                    $elements = $eElem->getElementsByTagNameNS(Config::camundaSpec, 'formData');
                    if ($elements) {
                        foreach ($elements as $elem) {
                            $fields = $elem->getElementsByTagNameNS(Config::camundaSpec, 'formField');
                            $fieldArray = $this->generateFields($fieldArray, $fields, $appId, $workflowId);
                        }
                    }
                    $hiddenFields = $eElem->getElementsByTagNameNS(Config::camundaSpec, 'field');
                    if ($hiddenFields) {
                        foreach ($hiddenFields as $hiddenField) {
                            $fieldArray = $this->generateHiddenFields($fieldArray, $hiddenField, $appId, $workflowId);
                        }
                    }
                    $formArray[$i]['fields'] = $fieldArray;
                }
                $i++;
            }
        }
        return $formArray;
    }
    private function generateForm($task, $appId, $workflowId)
    {
        $oxForm = new CamundaForm($task, $appId, $workflowId);
        return $oxForm->toArray();
    }
    private function generateActivity($task, $appId, $workflowId)
    {
        $oxActivity = new CamundaActivity($task, $appId, $workflowId);
        return $oxActivity->toArray();
    }
    private function generateFields($fieldArray, $fields, $appId, $workflowId)
    {
        foreach ($fields as $field) {
            $oxField = new CamundaField($field, $appId, $workflowId);
            $fieldArray[] = $oxField->toArray();
        }
        return $fieldArray;
    }
    private function generateHiddenFields($fieldArray, $field, $appId, $workflowId)
    {
        $type = $field->getElementsByTagNameNS(Config::camundaSpec, '*');
        $fieldType = $type->item(0)->localName;
        if ($fieldType == 'expression') {
            $fieldArray[] = array('name'=>$field->getAttribute('name'),'workflow_id'=>$workflowId,'app_id'=>$appId,'text'=>$field->getAttribute('name'),'type'=>'hidden','expression'=>$type->item(0)->nodeValue);
        } else {
            $fieldArray[] = array('name'=>$field->getAttribute('name'),'workflow_id'=>$workflowId,'app_id'=>$appId,'text'=>$field->getAttribute('name'),'type'=>'hidden');
        }
        return $fieldArray;
    }

    public function remove($id)
    {
        return $this->restClient->delete("deployment/".$id)?0:1;
    }

    public function get($id)
    {
        try {
            $response = $this->restClient->get("deployment/".$id);
            $result = json_decode($response, true);
            return $result;
        } catch (Exception $e) {
            return 0;
        }
    }
}
