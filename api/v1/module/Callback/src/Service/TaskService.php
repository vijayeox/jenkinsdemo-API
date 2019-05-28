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

        public function __construct($config, Logger $log)
        {
            parent::__construct($config,null,$log);
        }


        public function addProjectToTask($name,$description){
            try{
            $headers = $this->getAuthHeader();
            $response = $this->restClient->postWithHeader('/api/v3/projects', array('name' => $name,'description' => $description));
            $projectData = json_decode($response['body'],true);
            return $projectData;
            }catch (\GuzzleHttp\Exception\ClientException $e) {
            $this->logger->info(TaskService::class."Failed to create new entity");
            }
        } 

    }