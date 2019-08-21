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
    }
