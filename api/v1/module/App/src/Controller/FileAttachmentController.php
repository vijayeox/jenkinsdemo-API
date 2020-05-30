<?php

namespace App\Controller;

use Exception;
use Oxzion\Auth\AuthConstants;
use Oxzion\Auth\AuthContext;
use Oxzion\ValidationException;
use Oxzion\Controller\AbstractApiController;
use Oxzion\Service\FileService;
use Zend\Db\Adapter\AdapterInterface;

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
     * int: org_id
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
            if ($files['name']) {
                $fileInfo = $this->fileService->addAttachment($params,$files);
            } else {
                $fileInfo = $this->fileService->addAttachment($params,$files);
            }
            return $this->getSuccessResponseWithData($fileInfo, 201);
        } catch (ValidationException $e) {
            $response = ['errors' => $e->getErrors()];
            $this->log->error($e->getMessage(), $e);
            return $this->getErrorResponse("Validation Errors", 404, $response);
        } catch (Exception $e) {
            $this->log->error($e->getMessage(), $e);
        }
        if (!isset($_FILES['file'])) {
            return $this->getErrorResponse("File Not attached", 400, $data);
        } else if (!isset($dataArray['type'])) {
            return $this->getErrorResponse("File type not specified", 400, $data);
        }
        return $this->getErrorResponse("File Not attached", 400, $data);
    }
}
