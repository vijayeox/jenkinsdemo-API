<?php

namespace App\Controller;

use Exception;
use Oxzion\Auth\AuthConstants;
use Oxzion\Auth\AuthContext;
use Oxzion\ValidationException;
use Oxzion\Controller\AbstractApiController;
use Oxzion\Service\FileService;
use Zend\Db\Adapter\AdapterInterface;
use Oxzion\ServiceException;

class FileAttachmentController extends AbstractApiController
{
    /**
     * @var ImportService Instance of ImportService Service
     */
    private $fileService;

    /**
     * @ignore __construct
     */
    public function __construct(FileService $fileService, AdapterInterface $dbAdapter)
    {
        parent::__construct(null, null);
        $this->setIdentifierName('appId');
        $this->fileService = $fileService;
        $this->log = $this->getLogger();
    }

    /*
     * POST Import the CSV fuction
     * @api
     * @link /app/appId/cache
     * @method POST
     * @return Status mesassge based on success and failure
     * <code>status : "success|error",
     *       data :  {
     * String stored_procedure_name
     * int: account_id
     * string: app_id
     * string: app_name
     * }
     * </code>
     */
    /**
     * Create Entity API
     * @api
     * @link /app/appId/cache
     * @method POST
     * @param array $data Array of elements as shown
     * <code> {
     *               data : integer,
     *               name : string,
     *               Fields from Entity
     *   } </code>
     * @return array Returns a JSON Response with Status Code and Created Entity.
     */
    
    public function addAttachmentAction()
    {
        $params = $this->params()->fromPost();
        try {
            $files = isset($_FILES['file']) ? $_FILES['file'] : $this->params()->fromFiles('files');
            if (!$files) {
                return $this->getErrorResponse("No file Found", 404, $params);
            }
            $fileInfo = $this->fileService->addAttachment($params,$files);
            return $this->getSuccessResponseWithData($fileInfo, 201);
        } catch (ValidationException $e) {
            $response = ['errors' => $e->getErrors()];
            $this->log->error($e->getMessage(), $e);
            return $this->getErrorResponse("Validation Errors", 404, $response);
        } catch (Exception $e) {
            $this->log->error($e->getMessage(), $e);
        }
        if (!isset($_FILES['file'])) {
            return $this->getErrorResponse("File Not attached", 400, $files);
        }
        return $this->getErrorResponse("File Not attached", 400, $files);
    }

    public function removeAttachmentAction() {
        $params = $this->params()->fromRoute();
        try {
            $this->fileService->deleteAttachment($params);
            return $this->getSuccessResponse("Attachment has been successfully deleted", 201);
        }
        catch (ServiceException $e) {
            $this->log->error($e->getMessage(), $e);
            return $this->getErrorResponse($e->getMessage(), 400);
        }
        catch (Exception $e) {
            $this->log->error($e->getMessage(), $e);
            return $this->getErrorResponse("Unexpected error has occured", 500);
        }
        return $this->getErrorResponse("File Not found", 404);
    }

    public function renameAttachmentAction() {
        $params = $this->params()->fromRoute();
        $body = $this->extractPostData();
        $data = array_merge($params,$body);
        try {
            $this->fileService->renameAttachment($data);
            return $this->getSuccessResponse("Attachment has been successfully renamed", 201);
        }
        catch (ServiceException $e){
            $this->log->error($e->getMessage(), $e);
            return $this->getErrorResponse($e->getMessage(), 500);
        }
        catch (Exception $e) {
            $this->log->error($e->getMessage(), $e);
            return $this->getErrorResponse("Unexpected error has occured", 400);
        }
        return $this->getErrorResponse("File Not found", 404);
    }
}
