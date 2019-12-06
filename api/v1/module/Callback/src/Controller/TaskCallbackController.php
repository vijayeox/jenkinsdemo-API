<?php
namespace Callback\Controller;

use Zend\Log\Logger;
use Oxzion\Controller\AbstractApiControllerHelper;
use Oxzion\ValidationException;
use Zend\Db\Adapter\AdapterInterface;
use Oxzion\Utils\RestClient;
use Callback\Service\TaskService;

class TaskCallbackController extends AbstractApiControllerHelper
{
    private $taskService;
    protected $log;
        // /**
        // * @ignore __construct
        // */
    public function __construct(TaskService $taskService, Logger $log)
    {
        $this->taskService = $taskService;
        $this->log = $log;
    }


    public function setTaskService($taskService)
    {
        $this->taskService = $taskService;
    }


    public function addProjectAction()
    {
        $params = $this->extractPostData();
        $params['projectname']  = isset($params['projectname']) ? $params['projectname'] : null;
        $params['projectdata'] = ($params['projectname']) ? ($params['projectname']) : "No Project to ADD";
        $this->log->info(TaskCallbackController::class.":Project Data- ".$params['projectdata']);
        $response = $this->taskService->addProjectToTask($params['projectname'], $params['description'], $params['uuid']);
        if ($response) {
            $this->log->info(TaskCallbackController::class.":Added project to task");
            return $this->getSuccessResponseWithData($response['data']);
        }
        return $this->getErrorResponse("Adding Project To Task Failure ", 400);
    }

    public function deleteProjectAction()
    {
        $params = $this->extractPostData();

        $params['projectdata'] = isset($params['uuid']) ? ($params['uuid']) : "No Project to Delete";
        $this->log->info(TaskCallbackController::class.":Project Data- ".$params['projectdata']);

        $response = $this->taskService->deleteProjectFromTask($params['uuid']);
        if ($response) {
            $this->log->info(TaskCallbackController::class.":Project Deleted Successfully");
            return $this->getSuccessResponseWithData($response['data']);
        }
        return $this->getErrorResponse("Delete Project From Task Failure ", 400);
    }

    public function updateProjectAction()
    {
        $params = $this->extractPostData();

        $params['projectdata'] = isset($params['new_projectname']) ? ($params['new_projectname']) : "No Project to Update";
        $this->log->info(TaskCallbackController::class.":Project Data- ".$params['projectdata']);
        $response = $this->taskService->updateProjectInTask($params['new_projectname'], $params['description'], $params['uuid']);
        if ($response) {
            $this->log->info(TaskCallbackController::class.":Project Updated Successfully");
            return $this->getSuccessResponseWithData($response['data']);
        }
        return $this->getErrorResponse("Update Project Failed ", 400);
    }


    public function createUserAction() {
        $params = $this->extractPostData();
        $params['projectUuid'] = isset($params['projectUuid']) ? $params['projectUuid'] : NULL;
        $params['userData'] = isset($params['username']) ? ($params['username']) : "No User to ADD";
        $this->log->info(TaskCallbackController::class.":User Data- ".$params['userData']);
        $response = $this->taskService->addUserToTask($params['projectUuid'],$params['username'],$params['firstname'],$params['lastname'],$params['email'],$params['timezone']);
        if($response['status'] == "success"){
            $this->log->info(TaskCallbackController::class.":Added user to task");
            return $this->getSuccessResponseWithData($response['data']);
        }
        else {
            $this->log->info(TaskCallbackController::class.":Deletion of User from task failed");
            return $this->getErrorResponse("failed to delete user",400,$response['data']);
        }
        return $this->getErrorResponse("Adding User To Task Failure ", 400);
    }

    public function deleteUserAction() {
        $params = $this->extractPostData();
        $params['userData'] = isset($params['username']) ? ($params['username']) : "No User to DELETE";
        $params['projectUuid'] = isset($params['projectUuid']) ? $params['projectUuid'] : NULL;
        $this->log->info(TaskCallbackController::class.":User Data- ".$params['userData']);
        $response = $this->taskService->deleteUserFromTask($params['projectUuid'],$params['username']);
        if($response['status'] == "success"){
            $this->log->info(TaskCallbackController::class.":Deleted User from task");
            return $this->getSuccessResponseWithData($response['data']);
        }
        else {
            $this->log->info(TaskCallbackController::class.":Deletion of User from task failed");
            return $this->getErrorResponse("failed to delete user",400,$response['data']);
        }
        return $this->getErrorResponse("Adding User To Task Failure ", 400);
    }

    public function createGroupAction() {
        $params = $this->extractPostData();
        $params['groupData'] = isset($params['groupname']) ? ($params['groupname']) : "No Group to ADD";
        $this->log->info(TaskCallbackController::class.":Group Data- ".$params['groupData']);
        $response = $this->taskService->addGroupToTask($params['groupname']);
        if($response['success']){
            $this->log->info(TaskCallbackController::class.":Added group to task");
            return $this->getSuccessResponseWithData($response['result']);
        } else {
            $this->log->info(TaskCallbackController::class.":Addition of Group to task failed");
            return $this->getErrorResponse("failed to add group", 400, $response['errors']);
        }
        return $this->getErrorResponse("Adding Group To Task Failure ", 400);
    }

    public function updateGroupAction(){
        $params = $this->extractPostData();
        $params['groupdata'] = isset($params['new_groupname']) ? ($params['new_groupname']) : "No Group to Update";
        $this->log->info(TaskCallbackController::class.":Group Data- ".$params['groupdata']);
        $response = $this->taskService->updateGroupInTask($params['old_groupname'], $params['new_groupname']);
        if ($response['success']) {
            $this->log->info(TaskCallbackController::class.":Group Updated Successfully");
            return $this->getSuccessResponseWithData($response['result']);
        } else {
            $this->log->info(TaskCallbackController::class.":Updation of Group in task failed");
            return $this->getErrorResponse("failed to update group", 400, $response['errors']);
        }
        return $this->getErrorResponse("Update Group Failed ", 400);
    }

    public function deleteGroupAction(){
        $params = $this->extractPostData();
        $params['groupdata'] = isset($params['groupname']) ? ($params['groupname']) : "No Group to Delete";
        $this->log->info(TaskCallbackController::class.":Group Data- ".$params['groupdata']);
        $response = $this->taskService->deleteGroupFromTask($params['groupname']);
        if ($response['success']) {
            $this->log->info(TaskCallbackController::class.":Group Deleted Successfully");
            return $this->getSuccessResponseWithData($response['result']);
        } else {
            $this->log->info(TaskCallbackController::class.":Deletion of Group in task failed");
            return $this->getErrorResponse("failed to delete group", 400, $response['errors']);
        }
        return $this->getErrorResponse("Delete Group From Task Failure ", 400);
    }

    public function updateGroupUsersAction() {
        $params = $this->extractPostData();
        $params['userData'] = isset($params['usernames']) ? ($params['usernames']) : "No User to ADD";
        $this->log->info(TaskCallbackController::class.":Users to group Data- ".json_encode($params['userData']));
        $response = $this->taskService->addUsersToGroup($params['groupname'],$params['usernames']);
        if($response['success']){
            $this->log->info(TaskCallbackController::class.":Added users to group");
            return $this->getSuccessResponseWithData($response['result']);
        } else {
            $this->log->info(TaskCallbackController::class.":Addition of Users to group failed");
            return $this->getErrorResponse("failed to add users to group", 400, $response['errors']);
        }
        return $this->getErrorResponse("Adding User To Group Failure ", 400);
    }

}