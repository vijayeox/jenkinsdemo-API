<?php

namespace Email\Controller;

use Zend\Log\Logger;
use Oxzion\Controller\AbstractApiController;
use Email\Model\EmailTable;
use Email\Model\Email;
use Email\Service\EmailService;
use Zend\Db\Adapter\AdapterInterface;
use Bos\ValidationException;
use Zend\InputFilter\Input;
use Bos\Auth\AuthContext;
use Bos\Auth\AuthConstants;

class EmailController extends AbstractApiController {	
    /**
    * @var EmailService Instance of Email Service
    */
	private $emailService;
    /**
    * @ignore __construct
    */
    public function __construct(EmailTable $table, EmailService $emailService, Logger $log, AdapterInterface $dbAdapter)
    {
    	parent::__construct($table, $log, __CLASS__, Email::class);
    	$this->setIdentifierName('emailId');
    	$this->emailService = $emailService;
    }
    /**
    * GET Email API
    * @api
    * @link /email/emailid
    * @method GET
    * @return array $dataget of Emails by User
    * <code>status : "success|error",
    *       data :  {
                    string email,
                    string username,
                    string host,
                    integer isdefault,
                    integer userid,
                    integer id
                    }
    * </code>
    */
    public function get($id){
        $result = $this->emailService->getEmailAccountByUserId($id);
        if($result == 0||empty($result)){
            return $this->getErrorResponse("File not found", 404, ['id' => $id]);
        }
        return $this->getSuccessResponseWithData($result);
    }

    /**
    * Create Email API
    * @api
    * @link /email
    * @method POST
    * @param array $data Array of elements as shown</br>
    * <code> name : string,
             description : string,
    * </code>
    * @return array Returns a JSON Response with Status Code and Created Email.</br>
    * <code> status : "success|error",
    *        data : array Created Email Object
                    string email,
                    string username,
                    string host,
                    integer isdefault,
                    integer userid,
                    integer id
    * </code>
    */
    public function create($data) {
    	$data = $this->params()->fromPost();
    	try {
    		$count = $this->emailService->createOrUpdateEmailAccount($data);
    	} catch(ValidationException $e) {	
    		$response = ['data' => $data, 'errors' => $e->getErrors()];
    		return $this->getErrorResponse("Validation Errors",404, $response);
    	}
    	if($count == 0) {
    		return $this->getFailureResponse("Failed to create a new entity", $data);
    	}
        unset($data['password']);
    	return $this->getSuccessResponseWithData($data,201);
    }
    
    /*public function update($id, $data) {
    	try {
    		$count = $this->emailService->updateEmailAccount($id, $data);
    	} catch (ValidationException $e) {
    		$response = ['data' => $data, 'errors' => $e->getErrors()];
    		return $this->getErrorResponse("Validation Errors",404, $response);
    	}
        unset($data['password']);
    	if($count == 0) {
    		return $this->getErrorResponse("Entity not found for id - $id", 404);
    	}
    	return $this->getSuccessResponseWithData($data,200);
    }*/
    
    /*public function delete($id) {
    	$response = $this->emailService->deleteEmail($id);
    	if($response == 0) {
		return $this->getErrorResponse("Email not found", 404, ['id' => $id]);
    	}
    	return $this->getSuccessResponse();
    }*/
    /**
    * GET List Email API
    * @api
    * @link /email
    * @method GET
    * @return array $dataget list of Emails by User
    * <code>status : "success|error",
    *       data :  {
                    string email,
                    string username,
                    string host,
                    integer isdefault,
                    integer userid,
                    integer id
                    }
    * </code>
    */
    public function getList(){
        $result = $this->emailService->getEmailAccountsByUserId();
        return $this->getSuccessResponseWithData($result);
    }

    /**
    * GET default email API
    * @api
    * @link /email/:emailId/default
    * @method get
    * @param json object of userid
    * @return array $dataget list of default emails 
    * <code>status : "success|error",
    *       data : all default email data passed back in json format
    * </code>
    */
    public function emailDefaultAction() {
        $id = $this->params()->fromRoute()['emailId'];
        try {
            $responseData = $this->emailService->emailDefault($id);
        } catch (ValidationException $e) {
            $response = ['data' => $data, 'errors' => $e->getErrors()];
            return $this->getErrorResponse("Validation Errors",404, $response);
        }
        if($responseData == 0) {
            return $this->getErrorResponse("Entity not found for id - $id", 404);
        }
        return $this->getSuccessResponseWithData($responseData,200);
    }

    /**
    * Delete Email API
    * @api
    * @link /email[/:emailId]
    * @method DELETE
    * @param $id ID of Email to Delete
    * @return array success|failure response
    */
    public function deleteEmailAction() {
        $email = $this->params()->fromRoute()['address'];
        try {
            $responseData = $this->emailService->deleteEmail($email);
        } catch (ValidationException $e) {
            $response = ['data' => $data, 'errors' => $e->getErrors()];
            return $this->getErrorResponse("Validation Errors",404, $response);
        }
        if($responseData == 0) {
            return $this->getErrorResponse("Entity not found", 404);
        }
        return $this->getSuccessResponseWithData($responseData,200);
    }

     /**
    * Update Email API
    * @api
    * @link /email[/:emailId]
    * @method PUT
    * @param array $id ID of Email to update 
    * @param array $data 
    * <code> status : "success|error",
    *        data : {
                    string email,
                    string username,
                    string host,
                    integer isdefault,
                    integer userid,
                    integer id
                    }
    * </code>
    * @return array Returns a JSON Response with Status Code and Created Email.
    */
    public function updateEmailAction() {
        $request = $this->getRequest();
        $data = $this->processBodyContent($request);
        $email = $this->params()->fromRoute()['address'];
        try {
            $responseData = $this->emailService->updateEmail($email,$data);
        } catch (ValidationException $e) {
            $response = ['data' => $data, 'errors' => $e->getErrors()];
            return $this->getErrorResponse("Validation Errors",404, $response);
        }
        if($responseData == 0) {
            return $this->getErrorResponse("Entity not found", 404);
        }
        return $this->getSuccessResponseWithData($responseData,200);
    }

}