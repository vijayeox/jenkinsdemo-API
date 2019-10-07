<?php
namespace FileIndexer\Controller;

    use Zend\Log\Logger;
    use Oxzion\Controller\AbstractApiControllerHelper;
    use Oxzion\ValidationException;
    use Zend\Db\Adapter\AdapterInterface;
    use Oxzion\Utils\RestClient;
    use FileIndexer\Service\FileIndexerService;

    class FileIndexerController extends AbstractApiControllerHelper
    {
        private $fileIndexerService;
        protected $log;

        /**
        * @ignore __construct
        */
        public function __construct(FileIndexerService $fileIndexerService, Logger $log)
        {
            $this->fileIndexerService = $fileIndexerService;
            $this->log = $log;
        }

        public function IndexAction() {
            $params = $this->extractPostData();
            $params['id']  = isset($params['id']) ? $params['id'] : null;
            $params['filedata'] = ($params['id']) ? ($params['id']) : "No File to index";
            $this->log->info(FileIndexerController::class.":File Id- ".$params['filedata']);
            $response = $this->fileIndexerService->getRelevantDetails($params['id']);
            if ($response) {
                $this->log->info(FileIndexerController::class.":File has been Indexed");
                return $this->getSuccessResponseWithData($response);
            }
            return $this->getErrorResponse("Failure to Index File ", 400);
        }

        public function deleteIndexAction() {
            $params = $this->extractPostData();
            $params['id']  = isset($params['id']) ? $params['id'] : null;
            $params['filedata'] = ($params['id']) ? ($params['id']) : "No File to Delete";
            $this->log->info(FileIndexerController::class.":File Id- ".$params['filedata']);
            $response = $this->fileIndexerService->deleteDocument($params['id']);
            if ($response) {
                $this->log->info(FileIndexerController::class.":File has been Deleted");
                return $this->getSuccessResponseWithData($response);
            }
            return $this->getErrorResponse("Failure to Delete File ", 400);
        }
    }
