<?php

namespace Group\Controller;

use Zend\Log\Logger;
use Group\Model\GroupTable;
use Group\Model\Group;
use Group\Service\GroupService;
use Oxzion\Controller\AbstractApiController;
use Zend\Db\Adapter\AdapterInterface;
use Bos\ValidationException;
use Zend\InputFilter\Input;


class GroupController extends AbstractApiController {

	private $groupService;
    /**
    * @ignore __construct
    */
    public function __construct(GroupTable $table, GroupService $groupService, Logger $log, AdapterInterface $dbAdapter) {
        parent::__construct($table, $log, __CLASS__, Group::class);
        $this->setIdentifierName('groupId');
        $this->groupService = $groupService;
    }

    /**
    * GET Group API The code is to get the list of all the groups for the user. I am putting this function here, but Im not sure whether this has to be here or in the User Module. We can move that later when it is required.
    * @api
    * @link /group/getGroupsforUser/:userId
    * @method GET
    * @param $id ID of Group
    * @return array $data 
    * @return array Returns a JSON Response with Status Code and Created Group.
    */
    public function getGroupsforUserAction() {
        $params = $this->params()->fromRoute();
        $userId = $params['userId'];
		$groupList = $this->groupService->getGroupsforUser($userId); //Service to get the list of groups
		return $this->getSuccessResponseWithData($groupList);
	}
	/**
    * Create Group API
    * @api
    * @link /group
    * @method POST
    * @param array $data Array of elements as shown
    * <code> {
    *               id : integer,
    *               name : string,
    *               Fields from Group
    *   } </code>
    * @return array Returns a JSON Response with Status Code and Created Group.
    */
	public function create($data) {
		$data = $this->params()->fromPost();
		try {
			$count = $this->groupService->createGroup($data);
		} catch(ValidationException $e) {
			$response = ['data' => $data, 'errors' => $e->getErrors()];
			return $this->getErrorResponse("Validation Errors",404, $response);
		}
		if($count == 0) {
			return $this->getFailureResponse("Failed to create a new entity", $data);
		}
		return $this->getSuccessResponseWithData($data,201);
	}

    /**
    * Update Group API
    * @api
    * @link /group[/:groupId]
    * @method PUT
    * @param array $id ID of Group to update 
    * @param array $data 
    * @return array Returns a JSON Response with Status Code and Created Group.
    */
    public function update($id, $data) {
      try {
         $count = $this->groupService->updateGroup($id, $data);
     } catch (ValidationException $e) {
         $response = ['data' => $data, 'errors' => $e->getErrors()];
         return $this->getErrorResponse("Validation Errors",404, $response);
     }
     if($count == 0) {
         return $this->getErrorResponse("Entity not found for id - $id", 404);
     }
     return $this->getSuccessResponseWithData($data,200);
 }

    /**
    * Delete Group API
    * @api
    * @link /group[/:groupId]
    * @method DELETE
    * @param $id ID of Group to Delete
    * @return array success|failure response
    */
    public function delete($id) {
        $response = $this->groupService->deleteGroup($id);
        if($response == 0) {
            return $this->getErrorResponse("Group not found", 404, ['id' => $id]);
        }
        return $this->getSuccessResponse();
    }
    
     /**
    * Save users in a Group API
    * @api
    * @link /group/:groupid/save
    * @method Post
    * @param json object of userid
    * @return array $dataget list of groups by User
    * <code>status : "success|error",
    *       data : all user id's passed back in json format
    * </code>
    */
    public function saveUserAction() {
        $params = $this->params()->fromRoute();
        $id=$params[$this->getIdentifierName()];
        $data = $this->params()->fromPost();
        try {
            $count = $this->groupService->saveUser($params[$this->getIdentifierName()],$data);
        } catch (ValidationException $e) {
            $response = ['data' => $data, 'errors' => $e->getErrors()];
            return $this->getErrorResponse("Validation Errors",404, $response);
        }
        if($count == 0) {
            return $this->getErrorResponse("Entity not found", 404);
        }
        if($count == 2) {
            return $this->getErrorResponse("Enter User Ids", 404);
        }
        return $this->getSuccessResponseWithData($data,200);
    }

    /**
    * GET all users in a particular Group API
    * @api
    * @link /group/:groupid/users
    * @method GET
    * @return array $dataget list of groups by User
    * <code>status : "success|error",
    *       data : all user id's in the group passed back in json format
    * </code>
    */
    public function getuserlistAction() {
    	$params = $this->params()->fromRoute();
        $id=$params[$this->getIdentifierName()];
        try {
            $count = $this->groupService->getUserList($params[$this->getIdentifierName()]);
        } catch (ValidationException $e) {
            $response = ['data' => $data, 'errors' => $e->getErrors()];
            return $this->getErrorResponse("Validation Errors",404, $response);
        }
        if($count == 0) {
            return $this->getErrorResponse("Entity not found for id - $id", 404);
        }
        return $this->getSuccessResponseWithData($count,200);
    }
}