<?php

/**
 * Attachment Api
 */

namespace Attachment\Controller;

use Attachment\Model\Attachment;
use Attachment\Model\AttachmentTable;
use Attachment\Service\AttachmentService;
use Exception;
use Oxzion\Controller\AbstractApiController;
use Oxzion\ValidationException;
use Zend\Db\Adapter\AdapterInterface;

/**
 * Attachment Controller
 */
class AttachmentController extends AbstractApiController
{
    /**
     * @var AttachmentService Instance of Attchment Service
     */
    private $attachmentService;
    /**
     * @ignore __construct
     */

    public function __construct(AttachmentTable $table, AttachmentService $attachmentService, AdapterInterface $dbAdapter)
    {
        parent::__construct($table, AttachmentController::class);
        $this->attachmentService = $attachmentService;
        $this->setIdentifierName('attachmentId');
        $this->log = $this->getLogger();
    }

    /**
     * Create Attachment API
     * @api
     * @link /attachment
     * @method POST
     * @param array $data Array of elements as shown</br>
     * <code> TYPE : string,
     *  string file_name,
     *  integer extension,
     *  string uuid,
     *  string type,
     *  dateTime path Full Path of File,
     * </code>
     * @return array Returns a JSON Response with Status Code and Created Attachment.</br>
     * <code> status : "success|error",
     *        data : array Created Attachment Object
     * </code>
     */
    public function create($data)
    {
        $this->log->info(__CLASS__ . "->create attachment - " . print_r($data, true));
        $filesList = array();
        $dataArray = array();
        $dataArray = $data;
        try {
            $files = isset($_FILES['file']) ? $_FILES['file'] : $this->params()->fromFiles('files');
            if (!$files) {
                return $this->getErrorResponse("File Not attached", 404, $data);
            } else if (!$dataArray) {
                return $this->getErrorResponse("Empty Dataset Sent", 404, $dataArray);
            }
            if ($files['name']) {
                $filesList = $this->attachmentService->upload($dataArray, array($files));
            } else {
                $filesList = $this->attachmentService->upload($dataArray, $files);
            }
            return $this->getSuccessResponseWithData(array("filename" => $filesList), 201);
        } catch (ValidationException $e) {
            $response = ['data' => $dataArray, 'errors' => $e->getErrors()];
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

    /**
     * GET Attachment API
     * @api
     * @link /attachment
     * @method GET
     * @param $id ID of Attachment to Delete
     * @return array $data
     * <code>
     * {
     *  integer id,
     *  string file_name,
     *  integer extension,
     *  string uuid,
     *  string type,
     *  dateTime path Full Path of File,
     * }
     * </code>
     * @return array Returns a JSON Response with Status Code and Created Attachment.
     */
    public function get($id)
    {
        $result = $this->attachmentService->getAttachment($id);
        return $this->getSuccessResponseWithData($result);
    }

    /**
     * GET List Attachment API
     * @api
     * @link /attachment
     * @method GET
     * @return Error Response Array
     */
    public function getList()
    {
        return $this->getInvalidMethod();
    }
}
