<?php
/**
* Attachment Api
*/
namespace Attachment\Controller;

use Zend\Log\Logger;
use Attachment\Service\AttachmentService;
use Zend\Db\Adapter\AdapterInterface;
use Attachment\Model\AttachmentTable;
use Attachment\Model\Attachment;
use Oxzion\Controller\AbstractApiController;
use Oxzion\Utils\Query;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Sql;
use Oxzion\ValidationException;
use Zend\InputFilter\Input;
/**
 * Attachment Controller
 */
class AttachmentController extends AbstractApiController {
    /**
    * @var AttachmentService Instance of Attchment Service
    */
    private $attachmentService;
    /**
    * @ignore __construct
    */
    public function __construct(AttachmentTable $table,AttachmentService $attachmentService, Logger $log, AdapterInterface $dbAdapter) {
         parent::__construct($table, $log, __CLASS__, AttachmentController::class);
        $this->attachmentService = $attachmentService;
        $this->setIdentifierName('attachmentId');
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
    public function create($data){
        $files = $this->params()->fromFiles('files');
        $filesList = array();
        try{
            if($files['name']){
                $filesList = $this->attachmentService->upload($data,array($files));
            } else {
                $filesList = $this->attachmentService->upload($data,$files);
            }
        }catch(ValidationException $e){
            $response = ['data' => $data, 'errors' => $e->getErrors()];
            return $this->getErrorResponse("Validation Errors",404, $response);
        }
        return $this->getSuccessResponseWithData(array("filename"=>$filesList),201);
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
    public function get($id){
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
    public function getList(){
        return $this->getInvalidMethod();
    }
}
