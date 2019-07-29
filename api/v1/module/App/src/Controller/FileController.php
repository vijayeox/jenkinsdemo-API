<?php
namespace App\Controller;
/**
* File Api
*/
use Zend\Log\Logger;
use Oxzion\Model\File;
use Oxzion\Model\FileTable;
use Oxzion\Service\FileService;
use Zend\Db\Adapter\AdapterInterface;
use Oxzion\Controller\AbstractApiController;
use Oxzion\ValidationException;

class FileController extends AbstractApiController
{
    private $fileService;
    /**
    * @ignore __construct
    */
	public function __construct(FileTable $table, fileService $fileService, Logger $log, AdapterInterface $dbAdapter) {
		parent::__construct($table, $log, __CLASS__, File::class);
		$this->setIdentifierName('id');
		$this->fileService = $fileService;
	}
	/**
    * Create File API
    * @api
    * @link /app/appId/form
    * @method POST
    * @param array $data Array of elements as shown
    * <code> {
    *               id : integer,
    *               name : string,
    *               Fields from File
    *   } </code>
    * @return array Returns a JSON Response with Status Code and Created File.
    */
    public function create($data){
        $appId = $this->params()->fromRoute()['appId'];
        $formId = $this->params()->fromRoute()['formId'];
        if($formId){
            $data['form_id'] = $formId;
        } else {
            return $this->getFailureResponse("Form id not Found", $data);
        }
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
    * GET List Files API
    * @api
    * @link /app/appId/form
    * @method GET
    * @return array Returns a JSON Response list of Files based on Access.
    */
    public function getList() {
        return $this->getInvalidMethod();
    }
    /**
    * Update File API
    * @api
    * @link /app/appId/form[/:id]
    * @method PUT
    * @param array $id ID of File to update 
    * @param array $data 
    * @return array Returns a JSON Response with Status Code and Created File.
    */
    public function update($id, $data){
        $appId = $this->params()->fromRoute()['appId'];
        $formId = $this->params()->fromRoute()['formId'];
        if($formId){
            $data['form_id'] = $formId;
        }
        if($appId){
            $data['app_id'] = $appId;
        }
        try{
            $count = $this->fileService->updateFile($data,$id);
        }catch(ValidationException $e){
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
    * @link /app/appId/form[/:id]
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
    * @link /app/appId/form[/:id]
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