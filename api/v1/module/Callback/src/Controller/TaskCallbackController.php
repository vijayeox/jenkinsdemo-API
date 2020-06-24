<?php
namespace Callback\Controller;

use Callback\Service\TaskService;
use Exception;
use Oxzion\Controller\AbstractApiControllerHelper;

class TaskCallbackController extends AbstractApiControllerHelper
{
    private $taskService;
    private $log;
    /**
     * @ignore __construct
     */
    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
        $this->log = $this->getLogger();
    }

    // public function setTaskService($taskService)
    // {
    //     $this->taskService = $taskService;
    // }

    public function addProjectAction()
    {
        $params = $this->extractPostData();
        $this->log->info(__CLASS__ . "-> Callback - Add Project to task - " . json_encode($params, true));
        try {
            $params['projectname'] = isset($params['projectname']) ? $params['projectname'] : null;
            $params['projectdata'] = ($params['projectname']) ? ($params['projectname']) : "No Project to ADD";
            $params['manager_login'] = isset($params['manager_login']) ? $params['manager_login'] : null;
            $params['parent_identifier'] = isset($params['parent_identifier']) ? $params['parent_identifier'] : null;
            $this->log->info(TaskCallbackController::class . ":Project Data- " . $params['projectdata']);
            $response = $this->taskService->addProjectToTask($params['projectname'], $params['description'], $params['uuid'], $params['parent_identifier'], $params['manager_login']);
            // print_r($response);exit;
            if ($response) {
                $this->log->info(TaskCallbackController::class . ":Added project to task");
                return $this->getSuccessResponseWithData($response['data']);
            }
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $this->log->error("Callback Error! While trying to add project to task" . print_r($response, true));
            return $this->getErrorResponse($e->getMessage(), 500, $response);
        }
        return $this->getErrorResponse("Adding Project To Task Failure ", 400);
    }

    public function deleteProjectAction()
    {
        $params = $this->extractPostData();
        $this->log->info(__CLASS__ . "-> Callback - Delete Project from Task- " . json_encode($params, true));
        try {
            $params['projectdata'] = isset($params['uuid']) ? ($params['uuid']) : "No Project to Delete";
            $this->log->info(TaskCallbackController::class . ":Project Data- " . $params['projectdata']);
            $response = $this->taskService->deleteProjectFromTask($params['uuid']);
            if ($response) {
                $this->log->info(TaskCallbackController::class . ":Project Deleted Successfully");
                return $this->getSuccessResponseWithData($response['data']);
            }
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $this->log->error("Callback Error! While trying to delete the project from task" . print_r($response, true));
            return $this->getErrorResponse($e->getMessage(), 500, $response);
        }
        return $this->getErrorResponse("Delete Project From Task Failure ", 400);
    }

    public function updateProjectAction()
    {
        $params = $this->extractPostData();
        $params['parent_identifier'] = isset($params['parent_identifier']) ? $params['parent_identifier'] : null;
        $this->log->info(__CLASS__ . "-> Callback - Update project in task - " . json_encode($params, true));
        try {
            $params['projectdata'] = isset($params['new_projectname']) ? ($params['new_projectname']) : "No Project to Update";
            if (isset($params['manager_login'])) {
                $response = $this->taskService->updateProjectInTask($params['new_projectname'], $params['description'], $params['uuid'], $params['parent_identifier'] ,$params['manager_login']);
            } else {
                $response = $this->taskService->updateProjectInTask($params['new_projectname'], $params['description'], $params['uuid'], $params['parent_identifier']);
            }
            if ($response) {
                $this->log->info(TaskCallbackController::class . ":Project Updated Successfully");
                return $this->getSuccessResponseWithData($response['data']);
            }
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $this->log->error("Callback Error! While trying to update the project in task" . print_r($response, true));
            return $this->getErrorResponse($e->getMessage(), 500, $response);
        }
        return $this->getErrorResponse("Update Project Failed ", 400);
    }

    public function createUserAction()
    {
        $params = $this->extractPostData();
        $this->log->info(__CLASS__ . "-> Callback - Add user to tasks - " . json_encode($params, true));
        try {
            $params['projectUuid'] = isset($params['projectUuid']) ? $params['projectUuid'] : null;
            $params['userData'] = isset($params['username']) ? ($params['username']) : "No User to ADD";
            $this->log->info(TaskCallbackController::class . ":User Data- " . $params['userData']);
            $response = $this->taskService->addUserToTask($params['projectUuid'], $params['username'], $params['firstname'], $params['lastname'], $params['email'], $params['timezone']);
            if ($response['status'] == "success") {
                $this->log->info(TaskCallbackController::class . ":Added user to task");
                return $this->getSuccessResponseWithData($response['data']);
            } else {
                $this->log->info(TaskCallbackController::class . ":Deletion of User from task failed");
                return $this->getErrorResponse("failed to delete user", 400, $response['data']);
            }
        } catch (Exception $e) {
            $this->log->error("Error while creating the user" . json_encode($response, true));
            return $this->getErrorResponse($e->getMessage(), 500, $response);
        }
        return $this->getErrorResponse("Adding User To Task Failure ", 400);
    }

    public function deleteUserAction()
    {
        $params = $this->extractPostData();
        $params['userData'] = isset($params['username']) ? ($params['username']) : "No User to DELETE";
        $params['projectUuid'] = isset($params['projectUuid']) ? $params['projectUuid'] : null;
        $this->log->info(TaskCallbackController::class . ":User Data- " . $params['userData']);
        $response = $this->taskService->deleteUserFromTask($params['projectUuid'], $params['username']);
        if ($response['status'] == "success") {
            $this->log->info(TaskCallbackController::class . ":Deleted User from task");
            return $this->getSuccessResponseWithData($response['data']);
        } else {
            $this->log->info(TaskCallbackController::class . ":Deletion of User from task failed");
            return $this->getErrorResponse("failed to delete user", 400, $response['data']);
        }
        return $this->getErrorResponse("Adding User To Task Failure ", 400);
    }

    public function createGroupAction()
    {
        $params = $this->extractPostData();
        $params['groupData'] = isset($params['groupname']) ? ($params['groupname']) : "No Group to ADD";
        $this->log->info(TaskCallbackController::class . ":Group Data- " . $params['groupData']);
        $response = $this->taskService->addGroupToTask($params['groupname']);
        if ($response['success']) {
            $this->log->info(TaskCallbackController::class . ":Added group to task");
            return $this->getSuccessResponseWithData($response['result']);
        } else {
            $this->log->info(TaskCallbackController::class . ":Addition of Group to task failed");
            return $this->getErrorResponse("failed to add group", 400, $response['errors']);
        }
        return $this->getErrorResponse("Adding Group To Task Failure ", 400);
    }

    public function updateGroupAction()
    {
        $params = $this->extractPostData();
        $params['groupdata'] = isset($params['new_groupname']) ? ($params['new_groupname']) : "No Group to Update";
        $this->log->info(TaskCallbackController::class . ":Group Data- " . $params['groupdata']);
        $response = $this->taskService->updateGroupInTask($params['old_groupname'], $params['new_groupname']);
        if ($response['success']) {
            $this->log->info(TaskCallbackController::class . ":Group Updated Successfully");
            return $this->getSuccessResponseWithData($response['result']);
        } else {
            $this->log->info(TaskCallbackController::class . ":Updation of Group in task failed");
            return $this->getErrorResponse("failed to update group", 400, $response['errors']);
        }
        return $this->getErrorResponse("Update Group Failed ", 400);
    }

    public function deleteGroupAction()
    {
        $params = $this->extractPostData();
        $params['groupdata'] = isset($params['groupname']) ? ($params['groupname']) : "No Group to Delete";
        $this->log->info(TaskCallbackController::class . ":Group Data- " . $params['groupdata']);
        $response = $this->taskService->deleteGroupFromTask($params['groupname']);
        if ($response['success']) {
            $this->log->info(TaskCallbackController::class . ":Group Deleted Successfully");
            return $this->getSuccessResponseWithData($response['result']);
        } else {
            $this->log->info(TaskCallbackController::class . ":Deletion of Group in task failed");
            return $this->getErrorResponse("failed to delete group", 400, $response['errors']);
        }
        return $this->getErrorResponse("Delete Group From Task Failure ", 400);
    }

    public function updateGroupUsersAction()
    {
        $params = $this->extractPostData();
        $params['userData'] = isset($params['users']) ? ($params['users']) : "No User to ADD";
        $this->log->info(TaskCallbackController::class . ":Users to group Data- " . json_encode($params['userData']));
        $response = $this->taskService->addUsersToGroup($params['groupname'], $params['users']);
        if ($response['success']) {
            $this->log->info(TaskCallbackController::class . ":Added users to group");
            return $this->getSuccessResponseWithData($response['result']);
        } else {
            $this->log->info(TaskCallbackController::class . ":Addition of Users to group failed");
            return $this->getErrorResponse("failed to add users to group", 400, $response['errors']);
        }
        return $this->getErrorResponse("Adding User To Group Failure ", 400);
    }
}
