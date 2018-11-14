<?php

namespace Group\Controller;

use Zend\Log\Logger;
use Group\Model\GroupTable;
use Group\Model\Group;
use Group\Service\GroupService;
use Oxzion\Controller\AbstractApiController;
use Zend\Db\Adapter\AdapterInterface;
use Oxzion\ValidationException;
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
    * @link /group/getGroupsforUser
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
    * assignManagerToUser API
    * @api
    * @link /group/assignManagerToUser
    * @method POST
    * @param $id ID of Group to Delete
    * @return array success|failure response
    */
    public function assignManagerToUserAction() {
        try {
            $response = $this->userService->deleteUser($id);
        } catch(ValidationException $e) {
            $response = ['data' => $data, 'errors' => $e->getErrors()];
            return $this->getErrorResponse("Validation Errors",406, $response);
        }

    }
}