<?php

namespace Group\Controller;

use Zend\Log\Logger;
use Group\Model\GroupTable;
use Group\Model\Group;
use Group\Service\GroupService;
use Oxzion\Controller\AbstractApiController;
use Zend\Db\Adapter\AdapterInterface;
use Oxzion\ValidationException;
use Oxzion\AccessDeniedException;
use Zend\InputFilter\Input;
use Oxzion\Service\OrganizationService;


class GroupController extends AbstractApiController {

	private $groupService;
    private $orgService;

    /**
    * @ignore __construct
    */
    public function __construct(GroupTable $table, GroupService $groupService, Logger $log, AdapterInterface $dbAdapter, OrganizationService $orgService) {
        parent::__construct($table, $log, __CLASS__, Group::class);
        $this->setIdentifierName('groupId');
        $this->groupService = $groupService;
        $this->orgService = $orgService;
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
        $data=$this->params()->fromQuery();
        print_r($data);
        $userId = $params['userId'];
        try{
		  $groupList = $this->groupService->getGroupsforUser($userId,$data);
        }
        catch(AccessDeniedException $e) {
            $response = ['data' => $data, 'errors' => $e->getErrors()];
            return $this->getErrorResponse($e->getMessage(),403, $response);
        } //Service to get the list of groups
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
		$files = $this->params()->fromFiles('logo');
        $id=$this->params()->fromRoute();
		try {
            if(!isset($id['groupId'])){
			     $count = $this->groupService->createGroup($data,$files);
            }else{
                 $count = $this->groupService->updateGroup($id['groupId'],$data,$files); 
            }

		} catch(ValidationException $e) {
			$response = ['data' => $data, 'errors' => $e->getErrors()];
			return $this->getErrorResponse("Validation Errors",404, $response);
		}
        catch(AccessDeniedException $e) {
            $response = ['data' => $data, 'errors' => $e->getErrors()];
            return $this->getErrorResponse($e->getMessage(),403, $response);
        }
        if($count == 0) {
			return $this->getFailureResponse("Failed to create a new entity", $data);
		}else if($count == 2) {
            return $this->getErrorResponse("Updating non-existent Group", 404, $data);
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
        $data = $this->params()->fromQuery();
        try{
           $response = $this->groupService->deleteGroup($id,$data);
           if($response == 0) {
                 return $this->getErrorResponse("Group not found", 404, ['id' => $id]);
            }
        }
        catch(AccessDeniedException $e) {
            return $this->getErrorResponse($e->getMessage(),403);
        }

        return $this->getSuccessResponse();
    }


     /**
     * GET Group API
     * @api
     * @link /group[/:groupId]
     * @method GET
     * @param array $dataget of Group
     * @return array $data
     * <code> {
     *               id : integer,
     *               name : string,
     *               logo : string,
     *               status : String(Active|Inactive),
     *   } </code>
     * @return array Returns a JSON Response with Status Code and Created Group.
     */
    public function get($id)
    {   
        $data = $this->params()->fromQuery();
        try{
            $result = $this->groupService->getGroupByUuid($id,$data);
            $orgId = $this->orgService->getOrganization($result['org_id']);
            if ($result == 0) {
                return $this->getErrorResponse("Group not found", 404, ['id' => $id]);
            }

             if ($result) {
                    $baseUrl =$this->getBaseUrl();
                    $logo = $result['logo'];
                    $result['logo'] = $baseUrl . "/group/".$orgId['uuid']."/logo/".$result["uuid"];
                }
        }
        catch(AccessDeniedException $e) {
            return $this->getErrorResponse($e->getMessage(),403);
        }
        return $this->getSuccessResponseWithData($result);
    }




    /**
     * GET List Group API
     * @api
     * @link /group
     * @method GET
     * @return array Returns a JSON Response with Invalid Method/
     */
    public function getList()
    {
        $filterParams = $this->params()->fromQuery(); // empty method call
        try{
            $result = $this->groupService->getGroupList($filterParams);
            if ($result) {
                for($x=0;$x<sizeof($result['data']);$x++){
                    $baseUrl =$this->getBaseUrl();
                    $logo = $result['data'][$x]['logo'];
                    $orgId = $this->orgService->getOrganization($result['data'][$x]['org_id']);
                    $result['data'][$x]['logo'] = $baseUrl . "/group/".$orgId['uuid']."/logo/".$result['data'][$x]["uuid"];
                }
            }
        }
        catch(AccessDeniedException $e) {
            return $this->getErrorResponse($e->getMessage(),403);
        }
        return $this->getSuccessResponseDataWithPagination($result['data'],$result['total']);
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
        $id=$params['groupId'];
        $data = $this->params()->fromPost();
        try {
            $count = $this->groupService->saveUser($id,$data);
        } catch (ValidationException $e) {
            $response = ['data' => $data, 'errors' => $e->getErrors()];
            return $this->getErrorResponse("Validation Errors",404, $response);
        }
        catch(AccessDeniedException $e) {
            return $this->getErrorResponse($e->getMessage(),403);
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
        $group = $this->params()->fromRoute();
        $id=$group[$this->getIdentifierName()];
        $filterParams = $this->params()->fromQuery(); // empty method call
          
        try {
            $count = $this->groupService->getUserList($group[$this->getIdentifierName()],$filterParams);
        } catch (ValidationException $e) {
            $response = ['data' => $data, 'errors' => $e->getErrors()];
            return $this->getErrorResponse("Validation Errors",404, $response);
        }
        catch(AccessDeniedException $e) {
            return $this->getErrorResponse($e->getMessage(),403);
        }
        if($count == 0) {
            return $this->getErrorResponse("Entity not found for id - $id", 404);
        }
        return $this->getSuccessResponseDataWithPagination($count['data'],$count['total']);
    }
}