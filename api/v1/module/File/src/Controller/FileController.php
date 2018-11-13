<?php
/**
* File Api
*/
namespace File\Controller;

use Zend\Log\Logger;
use File\Model\FileTable;
use File\Model\File;
use File\Service\FileService;
use Zend\Db\Adapter\AdapterInterface;
use Oxzion\Controller\AbstractApiController;
use Oxzion\ValidationException;
use Zend\InputFilter\Input;
/**
 * File Controller
 */
class FileController extends AbstractApiController {
    /**
    * @var FileService Instance of File Service
    */
    private $fileService;
    /**
    * @ignore __construct
    */
    public function __construct(FileTable $table, FileService $fileService, Logger $log, AdapterInterface $dbAdapter) {
        parent::__construct($table, $log, __CLASS__, File::class);
        $this->setIdentifierName('fileId');
        $this->fileService = $fileService;
    }

    /**
    * Create File API
    * @api
    * @link /file
    * @method POST
    * @param array $data Array of elements as shown
    * <code> {
    *               id : integer,
    *               name : string,
    *               status : string,
    *               formid : integer,
    *               Fields from Form
    *   } </code>
    * @return array Returns a JSON Response with Status Code and Created File.
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
    /**
    * GET List File API
    * @api
    * @link /file
    * @method GET
    * @return array Returns a JSON Response with Error Message.
    */
    public function getList() {
        return $this->getInvalidMethod();
    }
    /**
    * Update File API
    * @api
    * @link /file[/:fileId]
    * @method PUT
    * @param array $id ID of File to update 
    * @param array $data 
    * @return array Returns a JSON Response with Status Code and Created File.
    */
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
    /**
    * Delete File API
    * @api
    * @link /file[/:fileId]
    * @method DELETE
    * @param $id ID of File to Delete
    * @return array success|failure response
    */
    public function delete($id){
        $response = $this->fileService->deleteFile($id);
        if($response == 0){
            return $this->getErrorResponse("File not found", 404, ['id' => $id]);
        }
        return $this->getSuccessResponse();
    }
    /**
    * GET File API
    * @api
    * @link /file[/:fileId]
    * @method GET
    * @param $id ID of File
    * @return array $data 
    * @return array Returns a JSON Response with Status Code and Created File.
    */
    public function get($id){
        $result = $this->fileService->getFile($id);
        if($result == 0){
            return $this->getErrorResponse("File not found", 404, ['id' => $id]);
        }
        return $this->getSuccessResponseWithData($result);
    }


}
