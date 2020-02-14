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
        public function __construct(FileIndexerService $fileIndexerService)
        {
            $this->fileIndexerService = $fileIndexerService;
            $this->log = $this->getLogger();
        }

        public function IndexAction() {
            $params = $this->extractPostData();
            $params['id']  = isset($params['id']) ? $params['id'] : null;
            $params['filedata'] = ($params['id']) ? ($params['id']) : "No File to index";
            $this->log->info("Params- ".json_encode($params));
            $response = $this->fileIndexerService->getRelevantDetails($params['id']);
            if ($response) {
                $this->log->info(FileIndexerController::class.":File has been Indexed");
                return $this->getSuccessResponseWithData($response);
            }
            return $this->getErrorResponse("Failure to Index File ", 400);
        }

        public function batchIndexAction() {
            $params = $this->extractPostData();
            $startdate = isset($params['start_date']) ? $params['start_date'] : null;
            $enddate = isset($params['end_date']) ? $params['end_date'] : null;
            $appUuid = isset($params['app_id']) ? $params['app_id'] : null;
            $this->log->info("Params- ".json_encode($params));
            try{
                $response = $this->fileIndexerService->batchIndexer($appUuid,$startdate,$enddate);
                if ($response) {
                    $this->log->info(FileIndexerController::class.":Files have been Indexed");
                    return $this->getSuccessResponseWithData($response);
                }
            }
            catch(Exception $e){
                return $this->getErrorResponse($e->getMessage(), 400);
            }
            $this->log->info(FileIndexerController::class.":Files have failed indexing");
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
