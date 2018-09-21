<?php

namespace Attachment\Controller;

use Zend\Log\Logger;
use Attachment\Service\AttachmentService;
use Zend\Db\Adapter\AdapterInterface;
use Oxzion\Controller\AbstractApiController;
use Oxzion\Utils\Query;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Sql;
use Oxzion\ValidationException;
use Zend\InputFilter\Input;

class AttachmentController extends AbstractApiController {
    private $attachmentService;

    public function __construct(AttachmentService $attachmentService, Logger $log, AdapterInterface $dbAdapter) {
        $this->attachmentService = $attachmentService;
    }
    public function create($data){
        $files = $this->params()->fromFiles('files');
        $filesList = array();
        try{
            $filesList = $this->attachmentService->createUpload($files);
        }catch(ValidationException $e){
            $response = ['data' => $data, 'errors' => $e->getErrors()];
            return $this->getErrorResponse("Validation Errors",404, $response);
        }
        return $this->getSuccessResponseWithData(array("filename"=>$filesList),201);
    }

}
