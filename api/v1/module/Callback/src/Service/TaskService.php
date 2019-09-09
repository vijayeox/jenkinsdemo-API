<?php
namespace Callback\Service;

    use Oxzion\Auth\AuthConstants;
    use Oxzion\Auth\AuthContext;
    use Oxzion\Service\AbstractService;
    use Oxzion\ValidationException;
    use Oxzion\Utils\RestClient;
    use Zend\Log\Logger;
    use Exception;

    class TaskService extends AbstractService
    {
        protected $dbAdapter;

        public function setRestClient($restClient)
        {
            $this->restClient = $restClient;
        }


        public function __construct($config, Logger $log)
        {
            parent::__construct($config, null, $log);
            $taskServerUrl = $this->config['task']['taskServerUrl'];
            $this->restClient = new RestClient($this->config['task']['taskServerUrl'], array('auth'=>array($this->config['task']['username'],$this->config['task']['authToken'])));
        }


        public function addProjectToTask($name, $description, $uuid)
        {
            try {
                $response = $this->restClient->postWithHeader('projects', array('name' => $name,'description' => $description,'uuid' => $uuid));
                $projectData = json_decode($response['body'], true);
                return $projectData;
            } catch (\GuzzleHttp\Exception\ClientException $e) {
                $this->logger->info(TaskService::class."Failed to create new entity".$e);
            }
        }

        public function deleteProjectFromTask($uuid)
        {
            try {
                $response = $this->restClient->deleteWithHeader('projects/'.$uuid);
                $projectData = json_decode($response['body'], true);
                return $projectData;
            } catch (\GuzzleHttp\Exception\ClientException $e) {
                $this->logger->info(TaskService::class."Failed to Delete entity".$e);
            }
        }

        public function updateProjectInTask($name, $description, $uuid)
        {
            try {
                $response = $this->restClient->updateWithHeader('projects/'.$uuid, array('name' => $name,'description' => $description));
                $projectData = json_decode($response['body'], true);
                return $projectData;
            } catch (\GuzzleHttp\Exception\ClientException $e) {
                $this->logger->info(TaskService::class."Failed to Update entity".$e);
            }
        }

        public function addUserToTask($projectUuid, $username, $firstname, $lastname, $email, $timezone)
        {
            try {
                $response = $this->restClient->postWithHeader('oxusers', array('username' => $username, 'firstname' => $firstname, 'lastname' => $lastname,'email' => $email,'timezone' => $timezone,'projectUuid' => $projectUuid));
                if ($response != 0) {
                    $projectData = json_decode($response['body'], true);
                    return $projectData;
                }
            } catch (Exception $e) {
                $this->logger->info(TaskService::class."Failed to create new entity".$e);
            }
            return 0;
        }

        public function deleteUserFromTask($projectUuid, $username)
        {
            try {
                $response = $this->restClient->deleteWithHeader('oxusers', array('username' => $username, 'projectUuid' => $projectUuid));
                $projectData = json_decode($response['body'], true);
                return $projectData;
            } catch (\GuzzleHttp\Exception\ClientException $e) {
                $this->logger->info(TaskService::class."Failed to create new entity".$e);
            }
        }
    }
