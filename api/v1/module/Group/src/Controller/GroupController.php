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
	public function __construct(GroupTable $table, GroupService $groupService, Logger $log, AdapterInterface $dbAdapter) {
		parent::__construct($table, $log, __CLASS__, Group::class);
		$this->setIdentifierName('groupId');
		$this->groupService = $groupService;
	}

//The code is to get the list of all the groups for the user. I am putting this function here, but Im not sure whether this has to be here or in the User Module. We can move that later when it is required.
	public function getGroupsforUserAction() {
		$params = $this->params()->fromRoute();
		$userId = $params['userId'];
		$groupList = $this->groupService->getGroupsforUser($userId); //Service to get the list of groups
		return $this->getSuccessResponseWithData($groupList);
	}

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

    public function delete($id) {
        $response = $this->groupService->deleteGroup($id);
        if($response == 0) {
            return $this->getErrorResponse("Group not found", 404, ['id' => $id]);
        }
        return $this->getSuccessResponse();
    }

    
    public function assignManagerToUserAction() {
        try {
            $response = $this->userService->deleteUser($id);
        } catch(ValidationException $e) {
            $response = ['data' => $data, 'errors' => $e->getErrors()];
            return $this->getErrorResponse("Validation Errors",406, $response);
        }

    }
}