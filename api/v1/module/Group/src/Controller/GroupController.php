<?php

namespace Group\Controller;

use Exception;
use Group\Model\Group;
use Group\Model\GroupTable;
use Group\Service\GroupService;
use Oxzion\AccessDeniedException;
use Oxzion\Controller\AbstractApiController;
use Oxzion\ServiceException;
use Oxzion\Service\OrganizationService;
use Oxzion\ValidationException;
use Zend\Db\Adapter\AdapterInterface;

class GroupController extends AbstractApiController
{
    private $groupService;
    private $orgService;

    /**
     * @ignore __construct
     */
    public function __construct(GroupTable $table, GroupService $groupService, AdapterInterface $dbAdapter, OrganizationService $orgService)
    {
        parent::__construct($table, Group::class);
        $this->setIdentifierName('groupId');
        $this->groupService = $groupService;
        $this->orgService = $orgService;
        $this->log = $this->getLogger();
    }

    /**
     * ! DEPRECATED
     * GET Group API The code is to get the list of all the groups for the user. I am putting this function here, but Im not sure whether this has to be here or in the User Module. We can move that later when it is required.
     * @api
     * @link /group/getGroupsforUser/:userId
     * @method GET
     * @param $id ID of Group
     * @return array $data
     * @return array Returns a JSON Response with Status Code and Created Group.
     */
    public function getGroupsforUserAction()
    {
        $params = $this->params()->fromRoute();
        $data = $this->params()->fromQuery();
        $userId = $params['userId'];
        try {
            $groupList = $this->groupService->getGroupsforUser($userId, $data);
        } catch (AccessDeniedException $e) {
            $response = ['data' => $data, 'errors' => $e->getErrors()];
            return $this->getErrorResponse($e->getMessage(), 403, $response);
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
    public function create($data)
    {
        $files = $this->params()->fromFiles('logo');
        $id = $this->params()->fromRoute();
        $id['orgId'] = isset($id['orgId']) ? $id['orgId'] : null;
        $this->log->info(__CLASS__ . "-> Create Group - " . json_encode($data, true));
        try {
            if (!isset($id['groupId'])) {
                $count = $this->groupService->createGroup($data, $files, $id['orgId']);
            } else {
                $count = $this->groupService->updateGroup($id['groupId'], $data, $files, $id['orgId']);
            }
        } catch (ValidationException $e) {
            $response = ['data' => $data, 'errors' => $e->getErrors()];
            return $this->getErrorResponse("Validation Errors", 404, $response);
        } catch (AccessDeniedException $e) {
            $response = ['data' => $data, 'errors' => $e->getErrors()];
            return $this->getErrorResponse($e->getMessage(), 403, $response);
        } catch (ServiceException $e) {
            return $this->getErrorResponse($e->getMessage(), 404);
        }
        return $this->getSuccessResponseWithData($data, 201);
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
    public function update($id, $data)
    {
        $this->log->info(__CLASS__ . "-> Update Group - " . json_encode($data, true));
        try {
            $count = $this->groupService->updateGroup($id, $data);
        } catch (ValidationException $e) {
            $response = ['data' => $data, 'errors' => $e->getErrors()];
            return $this->getErrorResponse("Validation Errors", 404, $response);
        } catch (AccessDeniedException $e) {
            return $this->getErrorResponse($e->getMessage(), 403);
        } catch (ServiceException $e) {
            return $this->getErrorResponse($e->getMessage(), 404);
        }
        return $this->getSuccessResponseWithData($data, 200);
    }

    /**
     * Delete Group API
     * @api
     * @link /group[/:groupId]
     * @method DELETE
     * @param $id ID of Group to Delete
     * @return array success|failure response
     */
    public function delete($id)
    {
        $params = $this->params()->fromRoute();
        $this->log->info(__CLASS__ . "-> Delete Group - " . json_encode($params, true) . " for ID " .json_encode($id, true));
        try {
            $response = $this->groupService->deleteGroup($params);
        } catch (AccessDeniedException $e) {
            return $this->getErrorResponse($e->getMessage(), 403);
        } catch (ServiceException $e) {
            return $this->getErrorResponse($e->getMessage(), 500);
        } catch (Exception $e) {
            return $this->getErrorResponse($e->getMessage(), 500);
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
        $params = $this->params()->fromRoute();
        try {
            $result = $this->groupService->getGroupByUuid($id, $params);
            if (count($result) == 0) {
                return $this->getSuccessResponseWithData($result);
            }
            $orgId = $this->orgService->getOrganization($result['org_id']);
            if ($result) {
                $baseUrl = $this->getBaseUrl();
                $logo = $result['logo'];
                $result['logo'] = $baseUrl . "/group/" . $orgId['uuid'] . "/logo/" . $result["uuid"];
            }
        } catch (AccessDeniedException $e) {
            return $this->getErrorResponse($e->getMessage(), 403);
        } catch (Exception $e) {
            $this->log->error($e->getMessage(), $e);
            return $this->getErrorResponse($e->getMessage(), 500);
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
        $filterParams = $this->params()->fromQuery();
        $params = $this->params()->fromRoute();
        $this->log->info(__CLASS__ . "-> Get List - " . json_encode($params, true));
        try {
            $result = $this->groupService->getGroupList($filterParams, $params);
            if ($result) {
                for ($x = 0; $x < sizeof($result['data']); $x++) {
                    $baseUrl = $this->getBaseUrl();
                    $logo = $result['data'][$x]['logo'];
                    $orgId = $this->orgService->getOrganization($result['data'][$x]['org_id']);
                    $result['data'][$x]['logo'] = $baseUrl . "/group/" . $orgId['uuid'] . "/logo/" . $result['data'][$x]["uuid"];
                }
            }
        } catch (AccessDeniedException $e) {
            return $this->getErrorResponse($e->getMessage(), 403);
        } catch (ServiceException $e) {
            $this->log->error($e->getMessage(), $e);
            return $this->getErrorResponse($e->getMessage(), 500);
        }
        return $this->getSuccessResponseDataWithPagination($result['data'], $result['total']);
    }

    public function groupsListAction()
    {
        $filterParams = $this->extractPostData();
        $params = $this->params()->fromRoute();
        $this->log->info(__CLASS__ . "-> Group Listing - " . json_encode($params, true));
        try {
            $result = $this->groupService->getGroupList($filterParams, $params);
            if ($result) {
                for ($x = 0; $x < sizeof($result['data']); $x++) {
                    $baseUrl = $this->getBaseUrl();
                    $logo = $result['data'][$x]['logo'];
                    $orgId = $this->orgService->getOrganization($result['data'][$x]['org_id']);
                    $result['data'][$x]['logo'] = $baseUrl . "/group/" . $orgId['uuid'] . "/logo/" . $result['data'][$x]["uuid"];
                }
            }
        } catch (Exception $e) {
            $this->log->error($e->getMessage(), $e);
            return $this->getErrorResponse($e->getMessage(), 500);
        }
        return $this->getSuccessResponseDataWithPagination($result['data'], $result['total']);
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
    public function saveUserAction()
    {
        $params = $this->params()->fromRoute();
        $data = $this->extractPostData();
        $this->log->info(__CLASS__ . "-> Save User to Groups - " . json_encode($params, true));
        try {
            $count = $this->groupService->saveUser($params, $data);
        } catch (ValidationException $e) {
            $response = ['data' => $data, 'errors' => $e->getErrors()];
            return $this->getErrorResponse("Validation Errors", 404, $response);
        } catch (AccessDeniedException $e) {
            return $this->getErrorResponse($e->getMessage(), 403);
        } catch (ServiceException $e) {
            return $this->getErrorResponse($e->getMessage(), 404);
        }
        return $this->getSuccessResponseWithData($data, 200);
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
    public function getuserlistAction()
    {
        $params = $this->params()->fromRoute();
        $filterParams = $this->params()->fromQuery(); // empty method call
        $this->log->info(__CLASS__ . "-> Get user list for the group - " . json_encode($params, true));
        try {
            $count = $this->groupService->getUserList($params, $filterParams);
        } catch (ValidationException $e) {
            $response = ['errors' => $e->getErrors()];
            return $this->getErrorResponse("Validation Errors", 404, $response);
        } catch (AccessDeniedException $e) {
            return $this->getErrorResponse($e->getMessage(), 403);
        } catch (Exception $e) {
            $this->log->error($e->getMessage(), $e);
            return $this->getErrorResponse($e->getMessage(), 500);
        }
        return $this->getSuccessResponseDataWithPagination($count['data'], $count['total']);
    }
}
