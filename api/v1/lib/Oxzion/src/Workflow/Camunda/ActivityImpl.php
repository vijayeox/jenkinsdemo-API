<?php
namespace Oxzion\Workflow\Camunda;

use Oxzion\Workflow\Activity;
use Oxzion\Utils\RestClient;
use Exception;
use Logger;

class ActivityImpl implements Activity
{
    private $restClient;
    protected $logger;
    
    public function __construct($config)
    {
        $class= get_class($this);
        $class = substr($class, strrpos($class, "\\")+1);
        $this->initLogger();
        $this->restClient = new RestClient($config['workflow']['engineUrl']);
    }

    protected function initLogger()
    {
        $this->logger = Logger::getLogger(__CLASS__);
    }

    public function setRestClient($restClient)
    {
        $this->restClient = $restClient;
    }

    public function getActivity($activityId)
    { 
        try {
            $this->logger->info("Entering the getActivity method in ActivityImpl File");
            $response =  $this->restClient->get("task/".$activityId);
            $result = json_decode($response, true);
            return $result;
        } catch (Exception $e) {
            $this->logger->error($e->getMessage(),$e);
            throw $e;
        }
    }

    public function getActivitiesByUser($userId, $params=array())
    {
        try {
            $this->logger->info("Entering the getActivitiesByUser method in ActivityImpl File");
            $queryArray = array_merge($params, array("assignee"=>$userId));
            $response =  $this->restClient->get('task?'.http_build_query($queryArray));
            $result = json_decode($response, true);
            return $result;
        } catch (Exception $e) {
            $this->logger->error($e->getMessage(),$e);
            return array();
        }
    }

    public function claimActivity($activityId, $userId)
    {
        $query = 'task/'.$activityId.'/claim';
        try { 
            $this->logger->info("Entering the claimActivity method in ActivityImpl File");
            $response =  $this->restClient->post($query, array('userId'=>$userId));
            $result = json_decode($response, true);            
        } catch (Exception $e) { 
            $error = json_decode($e->getResponse()->getBody()->getContents(), true);
            throw new WorkflowException($error['message'], $error['type']);
        }
        return $result;
    }
    public function unclaimActivity($activityId, $userId)
    {
        $this->logger->info("Entering the unclaimActivity method in ActivityImpl File");
        $query = 'task/'.$activityId.'/unclaim';
        try {
            $response =  $this->restClient->post($query, array('userId'=>$userId));
            $result = json_decode($response, true);
            return $result;
        } catch (Exception $e) {
            $this->logger->error($e->getMessage(),$e);
            throw $e;
       }
    }
    public function completeActivity($activityId, $parameterArray=array())
    {
        $this->logger->info("Entering the completeActivity method in ActivityImpl File");
        $query = 'task/'.$activityId.'/complete';
        $params = array();
        foreach ($parameterArray as $key => $value) {
            $params[$key]['value'] = $value;
        }
        try {
            $response =  $this->restClient->post($query, array('variables'=>$params));
            $result = json_decode($response, true);
            return $result;
        } catch (Exception $e) {
            $this->logger->error($e->getMessage(),$e);
            throw $e;
        }
    }
    public function submitTaskForm($activityId, $parameterArray=array())
    {
        $this->logger->info("Entering the submitTaskForm method ");
        $query = 'task/'.$activityId.'/submit-form';
        $params = array();
        foreach ($parameterArray as $key => $value) {
            $params[$key]['value'] = $value;
        }
        try {
            $this->logger->info("submitTaskForm parameter array -".print_r($params,true));
            $response =  $this->restClient->post($query, array('variables'=>$params));
            $result = json_decode($response, true);
            $this->logger->info("submitTaskForm method result - $result ");
            return $result;
        } catch (Exception $e) {
            $this->logger->error($e->getMessage(),$e);
            throw $e;
        }
    }

    public function resolveActivity($id, $parameterArray=array())
    {
        $query = 'task/'.$id.'/resolve';
        try {
            $this->logger->info("Entering the resolveActivity method in ActivityImpl File");
            $response =  $this->restClient->post($query, $parameterArray);
            $result = json_decode($response, true);
            return $result;
        } catch (Exception $e) {
            $this->logger->error($e->getMessage(),$e);
            throw $e;
        }
    }

    public function getActivitiesByGroup($groupId)
    {
        try {
            $this->logger->info("Entering the resolveActivity method in ActivityImpl File");
            $response =  $this->restClient->post('task', array("candidateGroup"=>$groupId));
            $result = json_decode($response, true);
            return $result;
        } catch (Exception $e) {
            $this->logger->error($e->getMessage(),$e);
            return array();
        }
    }

    public function saveActivityData($activityId, $parameterArray)
    {
        return $this->restClient->post('process-instance/'.$activityId.'/variables', $parameterArray);
    }

    public function getActivityData($activityId, $parameterArray)
    {
        return $this->restClient->post('process-instance/'.$activityId.'/variables', $parameterArray);
    }
}
