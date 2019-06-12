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

        private function convertParams($params){
            if(!is_object($params)){
                if(key($params)){
                    $params = json_decode(key($params),true);
                }
            }
            return $params;
        }

        public function addProjectAction(){
           $params = $this->convertParams($this->params()->fromPost());
           $response = $this->taskService->addProjectToTask($params['name'],$params['description'],$params['uuid']);
           if($response){
                $this->log->info(TaskCallbackController::class.":Added project to task");
                return $this->getSuccessResponseWithData($response['data']);
            }
            return $this->getErrorResponse("Adding Project To Task Failure ", 400);
        }

        public function deleteProjectAction(){
           $params = $this->convertParams($this->params()->fromPost());
           $response = $this->taskService->deleteProjectFromTask($params['uuid']);
            if($response){
                $this->log->info(TaskCallbackController::class.":Project Deleted Successfully");
                return $this->getSuccessResponseWithData($response['data']);
            }
            return $this->getErrorResponse("Delete Project From Task Failure ", 400);
        }

        public function updateProjectAction(){
           $params = $this->convertParams($this->params()->fromPost());
           $response = $this->taskService->updateProjectInTask($params['name'],$params['description'],$params['uuid']);
            if($response){
                $this->log->info(TaskCallbackController::class.":Project Updated Successfully");
                return $this->getSuccessResponseWithData($response['data']);
            }
            return $this->getErrorResponse("Update Project Failed ", 400);
        }
        
    }