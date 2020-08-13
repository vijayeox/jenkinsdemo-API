<?php

namespace File\Controller;

use Oxzion\Service\FileService;
use Oxzion\Controller\AbstractApiControllerHelper;
use Oxzion\EntityNotFoundException;
use Oxzion\ServiceException;
use \Exception;

class FileCallbackController extends AbstractApiControllerHelper
{
    /**
    * @var FileService Instance of FileService
    */
    private $fileService;
    private $log;
    /**
    * @ignore __construct
    */
    public function __construct(FileService $fileService)
    {
        $this->fileService = $fileService;
        $this->log = $this->getLogger();
    }

    /**
    * Update File Data API
    * @api
    * @link /callback/file
    * @method POST
    * @param array $data Array of file data
    * @return array Returns a JSON Response with Status Code.
    */
    public function updateFileAction()
    {
        $data = $this->extractPostData();
        
        try {
            $fileId = isset($data['id']) ? $data['id'] : null;
            if(!$fileId){
                throw new EntityNotFoundException("Invalid File Id");
            }
            $this->fileService->updateFileAttributes($fileId);
        }catch (ServiceException $e) {
            $this->log->error($e->getMessage(), $e);
            $response = ['data' => $data, 'errors' => $e->getMessage()];
            return $this->getErrorResponse("Error", 412, $response);
        }catch (EntityNotFoundException $e) {
            $this->log->error($e->getMessage(), $e);
            $response = ['data' => $data, 'errors' => $e->getMessage()];
            return $this->getErrorResponse("Error", 404, $response);
        }catch (Exception $e) {
            $this->log->error($e->getMessage(), $e);
            $response = ['data' => $data, 'errors' => "Unexpected error occurred, please try later"];
            return $this->getErrorResponse("Unexpected Error", 500, $response);
        }
        
        return $this->getSuccessResponseWithData($data, 200);
    }
    
}
