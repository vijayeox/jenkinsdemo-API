<?php
namespace Oxzion\Workflow\Camunda;

use Oxzion\Workflow\ProcessEngine;
use Oxzion\Utils\RestClient;
use Exception;

class ProcessEngineImpl implements ProcessEngine
{
    private $restClient;
    public function __construct()
    {
        $this->restClient = new RestClient(Config::ENGINE_URL);
    }
    public function setRestClient($restClient)
    {
        $this->restClient = $restClient;
    }

    public function getProcessDefinition($id)
    {
        $query = 'process-definition/'.$id;
        try {
            $response = $this->restClient->get($query);
            $result = json_decode($response, true);
            return $result;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function startProcess($id, $processVariables = array())
    {
        $query = 'process-definition/'.$id.'/start';
        $params =array();
        try {
            if ($processVariables) {
                foreach ($processVariables as $key => $value) {
                    $params[$key]['value'] = $value;
                }
                $response = $this->restClient->post($query, array("variables"=>$params));
            } else {
                $response = $this->restClient->post($query);
            }
            $result = json_decode($response, true);
            if ($result) {
                return $result;
            } else {
                return 0;
            }
        } catch (Exception $e) { 
            print_r($e->getMessage());exit;
            throw $e;
        }
    }

    public function stopProcess($id)
    {
        $query = 'process-definition/'.$id."?cascade=true";
        return $this->restClient->delete($query)?0:1;
    }

    public function getProcessDefinitionsByParams($id, $paramsArray)
    {
        $query = 'process-definition/';
        return $this->restClient->get($query, $paramsArray);
    }
}
