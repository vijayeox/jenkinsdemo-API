<?php
namespace User\Controller;

use Zend\Log\Logger;
use User\Model\User;
use User\Model\UserTable;
use User\Service\UserService;
use Oxzion\Controller\AbstractApiController;
use Oxzion\ValidationResult;
use Oxzion\ValidationException;
use Zend\View\Model\JsonModel;
use Zend\Db\Sql\Sql;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Adapter\AdapterInterface;



class UserController extends AbstractApiController {

	private $dbAdapter;
	public function __construct(UserTable $table, Logger $log, UserService $userService) {
		parent::__construct($table, $log, __CLASS__, User::class);
		$this->setIdentifierName('userId');
		$this->userService = $userService;
	}

	    /**
    *   $data should be in the following JSON format
    *   {
    *       'id' : integer,
    *       'name' : string,
    *       'org_id' : integer,
    *       'status' : string,
    *       'description' : string,
    *       'start_date' : dateTime (ISO8601 format yyyy-mm-ddThh:mm:ss),
    *       'end_date' : dateTime (ISO8601 format yyyy-mm-ddThh:mm:ss)
    *       'media_type' : string,
    *       'media_location' : string,
    *       'groups' : [
    *                       {'id' : integer}.
    *                       ....multiple 
    *                  ],
    *   }
    *
    *
    */

     public function create($data) {
        try {
            $count = $this->userService->createUser($data);
        } catch(ValidationException $e) {
            $response = ['data' => $data, 'errors' => $e->getErrors()];
            /*
        	PLease see the html error codes. https://www.restapitutorial.com/httpstatuscodes.html
        	Not found = 406
        	While this is not exactly not found we don't have a better HTML error code for create.
       		*/
            return $this->getErrorResponse("Validation Errors",406, $response);
        }

        if($count == 0) {
            return $this->getFailureResponse("Failed to create a new user", $data);
        }
        /*
        PLease see the html error codes. https://www.restapitutorial.com/httpstatuscodes.html
        Successful create = 201
        */
        return $this->getSuccessResponseWithData($data,201);
    }

    public function get($id) {
        $result = $this->userService->getUser($id);
        if ($result == 0) {
            return $this->getErrorResponse("Failed to find User",404, $response);
        }
        return $this->getSuccessResponseWithData($result);
    }

    public function getList() {
        $result = $this->userService->getUsers();
        return $this->getSuccessResponseWithData($result);
    }

    public function update($id, $data) {
        try {
            $count = $this->userService->updateUser($id,$data);
        } catch(ValidationException $e) {
            $response = ['data' => $data, 'errors' => $e->getErrors()];
            return $this->getErrorResponse("Validation Errors",406, $response);
        }
        if($count == 0) {
            return $this->getErrorResponse("Entity not found for id - $id", 404);
        }
        return $this->getSuccessResponseWithData($data,200);
    }

    public function delete($id) {
        try {
            $response = $this->userService->deleteUser($id);
        } catch(ValidationException $e) {
            $response = ['data' => $data, 'errors' => $e->getErrors()];
            return $this->getErrorResponse("Validation Errors",406, $response);
        }
        if($response == 0) {
            return $this->getErrorResponse("Entity not found for id - $id", 404);
        }
        return $this->getSuccessResponse();
    }

    
    public function assignManagerToUserAction() {
        $params = $this->params()->fromRoute();
        // print_r($params);exit;
        try {
            $response = $this->userService->assignManagerToUser($params['userId'], $params['managerId']);
            if($response == 0) {
                return $this->getErrorResponse("Entity not found", 404);
            }
            return $this->getSuccessResponse();
        } catch(ValidationException $e) {
            $response = ['data' => $data, 'errors' => $e->getErrors()];
            return $this->getErrorResponse("Validation Errors",406, $response);
        }

    }

    public function removeManagerForUserAction() {
        $params = $this->params()->fromRoute();
        try {
            $response = $this->userService->removeManagerForUser($params['userId'], $params['managerId']);
            if($response == 0) {
                return $this->getErrorResponse("Entity not found for id - $id", 404);
            }
            return $this->getSuccessResponse();
        } catch(ValidationException $e) {
            $response = ['data' => $data, 'errors' => $e->getErrors()];
            return $this->getErrorResponse("Validation Errors",406, $response);
        }

    }





}