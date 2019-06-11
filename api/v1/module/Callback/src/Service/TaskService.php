<?php
namespace Callback\Service;

    use Bos\Auth\AuthConstants;
    use Bos\Auth\AuthContext;
    use Bos\Service\AbstractService;
    use Bos\ValidationException;
    use Oxzion\Utils\RestClient;
    use Zend\Log\Logger;
    use Exception;

    class TaskService extends AbstractService
    {
        protected $dbAdapter;

        public function setRestClient($restClient){
            $this->restClient = $restClient;
        }


        public function __construct($config, Logger $log)
        {
            parent::__construct($config,null,$log);
            $taskServerUrl = $this->config['task']['taskServerUrl'];
            $this->restClient = new RestClient($this->config['task']['taskServerUrl'],array('auth'=>array($this->config['task']['username'],$this->config['task']['authToken'])));
        }


        public function addProjectToTask($name,$description,$uuid){
            try{
                $response = $this->restClient->postWithHeader('projects', array('name' => $name,'description' => $description,'uuid' => $uuid));
                $projectData = json_decode($response['body'],true);
                return $projectData;
            }catch (\GuzzleHttp\Exception\ClientException $e) {
                $this->logger->info(TaskService::class."Failed to create new entity".$e);
            }
        } 

    }