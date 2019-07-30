<?php
namespace Callback\Controller;

use Zend\Log\Logger;
use Oxzion\Controller\AbstractApiControllerHelper;
use Oxzion\ValidationException;
use Zend\Db\Adapter\AdapterInterface;
use Oxzion\Utils\RestClient;
use Callback\Service\TaskService;

class TaskCallbackController extends AbstractApiControllerHelper {

    private $taskService;
    protected $log;
        // /**
        // * @ignore __construct
        // */
    public function __construct(TaskService $taskService, Logger $log) {
        $this->taskService = $taskService;
        $this->log = $log;
    }

    public function setTaskService($taskService){
        $this->taskService = $taskService;
    }

    public function addProjectAction(){

        $params = $this->extractPostData();
        $params['projectdata'] = ($params['projectname']) ? ($params['projectname']) : "No Project to ADD";
        $this->log->info(TaskCallbackController::class.":Project Data- ".$params['projectdata']);
        $response = $this->taskService->addProjectToTask($params['projectname'],$params['description'],$params['uuid']);
        if($response){
            $this->log->info(TaskCallbackController::class.":Added project to task");
            return $this->getSuccessResponseWithData($response['data']);
        }
        return $this->getErrorResponse("Adding Project To Task Failure ", 400);
    }

    public function deleteProjectAction(){
        $params = $this->extractPostData();
        $params['projectdata'] = ($params['uuid']) ? ($params['uuid']) : "No Project to Delete";
        $this->log->info(TaskCallbackController::class.":Project Data- ".$params['projectdata']);

        $response = $this->taskService->deleteProjectFromTask($params['uuid']);
        if($response){
            $this->log->info(TaskCallbackController::class.":Project Deleted Successfully");
            return $this->getSuccessResponseWithData($response['data']);
        }
        return $this->getErrorResponse("Delete Project From Task Failure ", 400);
    }

    public function updateProjectAction(){
       $params = $this->extractPostData();
       $params['projectdata'] = ($params['new_projectname']) ? ($params['new_projectname']) : "No Project to Update";
       $this->log->info(TaskCallbackController::class.":Project Data- ".$params['projectdata']);
       $response = $this->taskService->updateProjectInTask($params['new_projectname'],$params['description'],$params['uuid']);
       if($response){
        $this->log->info(TaskCallbackController::class.":Project Updated Successfully");
        return $this->getSuccessResponseWithData($response['data']);
        }
        return $this->getErrorResponse("Update Project Failed ", 400);
    }

    public function createUserAction() {
        $params = $this->extractPostData();
        $params['userData'] = ($params['username']) ? ($params['username']) : "No User to ADD";
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
        $params['userData'] = ($params['username']) ? ($params['username']) : "No User to DELETE";
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
}