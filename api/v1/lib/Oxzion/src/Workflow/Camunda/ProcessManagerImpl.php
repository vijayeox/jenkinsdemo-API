<?php
namespace Oxzion\Workflow\Camunda;

use Oxzion\Workflow\ProcessManager;
use Oxzion\Utils\RestClient;
use Sabre\Xml\Service;
use Oxzion\Service\FormService;
use Oxzion\Utils\XMLUtils;
use Exception;

class ProcessManagerImpl implements ProcessManager
{
    private $restClient;
    public function __construct($config)
    {
        $this->restClient = new RestClient($config['workflow']['engineUrl']);
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
            throw $e;
        }
    }
    public function parseBPMN($file, $appId)
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
                $formArray[$i]['form'] = $this->generateForm($startEventList->item(0), $appId);
                $fields = $startEventList->item(0)->getElementsByTagNameNS(Config::camundaSpec, 'formField');
                $formArray[$i]['form']['fields'] = $this->generateFields($fieldArray, $fields, $appId);
                $formArray[$i]['start_form'] = $startEventList->item(0)->getAttribute('id');
            }
            $elementList = $element->getElementsByTagNameNs(Config::bpmnSpec, 'userTask');
            $j=0;
            foreach ($elementList as $task) {
                $fieldArray = array();
                $formArray[$i]['activity'][$j] = $this->generateActivity($task, $appId);
                $extensionElements = $task->getElementsByTagNameNS(Config::bpmnSpec, 'extensionElements');
                foreach ($extensionElements as $eElem) {
                    $elements = $eElem->getElementsByTagNameNS(Config::camundaSpec, 'formData');
                    if ($elements) {
                        foreach ($elements as $elem) {
                            $fields = $elem->getElementsByTagNameNS(Config::camundaSpec, 'formField');
                            $fieldArray = $this->generateFields($fieldArray, $fields, $appId);
                        }
                    }
                    $hiddenFields = $eElem->getElementsByTagNameNS(Config::camundaSpec, 'field');
                    if ($hiddenFields) {
                        foreach ($hiddenFields as $hiddenField) {
                            $fieldArray = $this->generateHiddenFields($fieldArray, $hiddenField, $appId);
                        }
                    }
                    $formArray[$i]['activity'][$j]['fields'] = $fieldArray;
                }
                $j++;
            }
            $i++;
        }
        return $formArray;
    }
    private function generateForm($task, $appId)
    {
        $oxForm = new CamundaForm($task, $appId);
        return $oxForm->toArray();
    }
    private function generateActivity($task, $appId)
    {
        $oxActivity = new CamundaActivity($task, $appId);
        return $oxActivity->toArray();
    }
    private function generateFields($fieldArray, $fields, $appId)
    {
        foreach ($fields as $field) {
            $oxField = new CamundaField($field, $appId);
            $fieldArray[] = $oxField->toArray();
        }
        return $fieldArray;
    }
    private function generateHiddenFields($fieldArray, $field, $appId)
    {
        $type = $field->getElementsByTagNameNS(Config::camundaSpec, '*');
        $fieldType = $type->item(0)->localName;
        if ($fieldType == 'expression') {
            $fieldArray[] = array('name'=>$field->getAttribute('name'),'app_id'=>$appId,'text'=>$field->getAttribute('name'),'type'=>'hidden','expression'=>$type->item(0)->nodeValue);
        } else {
            $fieldArray[] = array('name'=>$field->getAttribute('name'),'app_id'=>$appId,'text'=>$field->getAttribute('name'),'type'=>'hidden');
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
            throw $e;
        }
    }
}
