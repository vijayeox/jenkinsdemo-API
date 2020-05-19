<?php
namespace Callback\Service;

use Exception;
use Oxzion\Service\AbstractService;
use Oxzion\Utils\RestClient;
use Zend\Log\Logger;

class TaskService extends AbstractService
{
    protected $dbAdapter;

    public function setRestClient($restClient)
    {
        $this->restClient = $restClient;
    }

    public function __construct($config)
    {
        parent::__construct($config, null);
        $this->restClient = new RestClient($this->config['task']['taskServerUrl'], array('auth' => array($this->config['task']['username'], $this->config['task']['authToken'])));
    }

    public function addProjectToTask($name, $description, $uuid, $parentIdentifier, $manager_login = null)
    {
        try {
            $response = $this->restClient->postWithHeader('projects', array('name' => $name, 'description' => $description, 'uuid' => $uuid, 'manager_login' => $manager_login, 'parent_identifier' => $parentIdentifier));
            $projectData = json_decode($response['body'], true);
            return $projectData;
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $this->logger->info("Failed to create new entity" . $e);
        }
    }

    public function deleteProjectFromTask($uuid)
    {
        try {
            $response = $this->restClient->deleteWithHeader('projects/' . $uuid);
            $projectData = json_decode($response['body'], true);
            return $projectData;
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $this->logger->info("Failed to Delete entity" . $e);
        }
    }

    public function updateProjectInTask($name, $description, $uuid, $parentIdentifier, $manager_login = null)
    {
        try {
            if ($manager_login) {
                $response = $this->restClient->updateWithHeader('projects/' . $uuid, array('name' => $name, 'description' => $description, 'parent_identifier' => $parentIdentifier, 'manager_login' => $manager_login));
            } else {
                $response = $this->restClient->updateWithHeader('projects/' . $uuid, array('name' => $name, 'description' => $description, 'parent_identifier' => $parentIdentifier));
            }

            $projectData = json_decode($response['body'], true);
            return $projectData;
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $this->logger->info("Failed to Update entity" . $e);
        }
    }

    public function addUserToTask($projectUuid, $username, $firstname, $lastname, $email, $timezone)
    {
        try {
            $response = $this->restClient->postWithHeader('oxusers', array('username' => $username, 'firstname' => $firstname, 'lastname' => $lastname, 'email' => $email, 'timezone' => $timezone, 'projectUuid' => $projectUuid));
            if ($response != 0) {
                $projectData = json_decode($response['body'], true);
                return $projectData;
            }
        } catch (Exception $e) {
            $this->logger->info("Failed to create new entity" . $e);
        }
        return 0;
    }

    public function addGroupToTask($groupname)
    {
        try {
            $response = $this->restClient->post('groups', array('name' => $groupname));
            return json_decode($response, true);
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $this->logger->info(TaskService::class . "Failed to create new entity" . $e);
        }
    }

    public function updateGroupInTask($groupname, $new_groupname)
    {
        try {
            $response = $this->restClient->put('groups', array('name' => $groupname, 'newname' => $new_groupname));
            return json_decode($response, true);
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $this->logger->info(TaskService::class . "Failed to create new entity" . $e);
        }
    }

    public function deleteGroupFromTask($groupname)
    {
        try {
            $response = $this->restClient->delete('groups', array('name' => $groupname));
            return json_decode($response, true);
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $this->logger->info(TaskService::class . "Failed to create new entity" . $e);
        }
    }

    public function deleteUserFromTask($projectUuid, $username)
    {
        try {
            $response = $this->restClient->deleteWithHeader('oxusers', array('username' => $username, 'projectUuid' => $projectUuid));
            $projectData = json_decode($response['body'], true);
            return $projectData;
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $this->logger->error("Failed to create new entity", $e);
        }
    }

    public function addUsersToGroup($groupname, $usernames)
    {
        try {
            $response = $this->restClient->put('group_members', array('name' => $groupname, 'usernames' => $usernames));
            return json_decode($response, true);
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $this->logger->info(TaskService::class . "Failed to create new entity" . $e);
        }
    }

}
