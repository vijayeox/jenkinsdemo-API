<?php

namespace File\Controller;

use Zend\Log\Logger;
use File\Model\FileTable;
use File\Model\File;
use File\Service\FileService;
use Zend\Db\Adapter\AdapterInterface;
use Oxzion\Controller\AbstractApiController;
use Oxzion\ValidationException;
use Zend\InputFilter\Input;

class FileController extends AbstractApiController {
    private $fileService;

    public function __construct(FileTable $table, FileService $fileService, Logger $log, AdapterInterface $dbAdapter) {
        parent::__construct($table, $log, __CLASS__, File::class);
        $this->setIdentifierName('fileId');
        $this->fileService = $fileService;
    }

    /**
    *   $data should be in the following JSON format
    *   {
    *       'groups' : [
    *                       {'id' : integer}.
    *                       ....multiple 
    *                  ],
    *   }
    *
    *
    */
    public function create($data){
        try{
            $count = $this->fileService->createFile($data);
        } catch (ValidationException $e){
            $response = ['data' => $data, 'errors' => $e->getErrors()];
            return $this->getErrorResponse("Validation Errors",404, $response);
        }
        if($count == 0){
            return $this->getFailureResponse("Failed to create a new entity", $data);
        }
        return $this->getSuccessResponseWithData($data,201);
    }
    
    public function getList() {
        return $this->getErrorResponse("Method Not Found",405);
    }
    public function update($id, $data){
        try{
            $count = $this->fileService->updateFile($id,$data);
        } catch (ValidationException $e){
            $response = ['data' => $data, 'errors' => $e->getErrors()];
            return $this->getErrorResponse("Validation Errors",404, $response);
        }
        if($count == 0){
            return $this->getErrorResponse("Entity not found for id - $id", 404);
        }
        return $this->getSuccessResponseWithData($data,200);
    }
    public function delete($id){
        $response = $this->fileService->deleteFile($id);
        if($response == 0){
            return $this->getErrorResponse("File not found", 404, ['id' => $id]);
        }
        return $this->getSuccessResponse();
    }
    public function get($id){
        $result = $this->fileService->getFile($id);
        if($result == 0){
            return $this->getErrorResponse("File not found", 404, ['id' => $id]);
        }
        return $this->getSuccessResponseWithData($result);
    }


}
