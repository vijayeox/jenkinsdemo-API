<?php
namespace Organization\Controller;

use Exception;
use Oxzion\AccessDeniedException;
use Oxzion\Controller\AbstractApiController;
use Oxzion\Model\Organization;
use Oxzion\Model\OrganizationTable;
use Oxzion\ServiceException;
use Oxzion\Service\OrganizationService;
use Oxzion\ValidationException;
use Zend\Db\Adapter\AdapterInterface;

class OrganizationController extends AbstractApiController
{
    private $orgService;

    /**
     * @ignore __construct
     */
    public function __construct(OrganizationTable $table, OrganizationService $orgService, AdapterInterface $dbAdapter)
    {
        parent::__construct($table, Organization::class);
        $this->setIdentifierName('orgId');
        $this->orgService = $orgService;
    }

    /**
     * Create Organization API
     * @api
     * @method POST
     * @link /organization
     * @param array $data Array of elements as shown
     * <code> {
     *               id : integer,
     *               name : string,
     *               logo : string,
     *               status : String(Active|Inactive),
     *   } </code>
     * @return array Returns a JSON Response with Status Code and Created Organization.
     */
    public function create($data)
    {
        $files = $this->params()->fromFiles('logo') ? $this->params()->fromFiles('logo') : null;
        $id = $this->params()->fromRoute();
        $this->log->info("Create Organization - " . print_r($data, true) . "\n Files - " . print_r($files, true));
        try {
            if (!isset($id['orgId'])) {
                $count = $this->orgService->createOrganization($data, $files);
            } else {
                $count = $this->orgService->updateOrganization($id['orgId'], $data, $files);
            }
        } catch (ValidationException $e) {
            $response = ['data' => $data, 'errors' => $e->getErrors()];
            $this->log->error($e->getMessage(), $e);
            return $this->getErrorResponse("Validation Errors", 404, $response);
        } catch (ServiceException $e) {
            $this->log->error($e->getMessage(), $e);
            return $this->getErrorResponse($e->getMessage(), 500);
        } catch (Exception $e) {
            $this->log->error($e->getMessage(), $e);
            return $this->getErrorResponse($e->getMessage(), 500);
        }
        return $this->getSuccessResponseWithData($data, 201);
    }

    /**
     * GET List Organization API
     * @api
     * @link /organization
     * @method GET
     * @return array Returns a JSON Response with Invalid Method/
     */
    public function getList()
    {
        $filterParams = $this->params()->fromQuery(); // empty method call
        $this->log->info("Get Oranization List - " . print_r($filterParams, true));
        try {
            $result = $this->orgService->getOrganizations($filterParams);
            if ($result) {
                for ($x = 0; $x < sizeof($result['data']); $x++) {
                    $baseUrl = $this->getBaseUrl();
                    $result['data'][$x]['logo'] = $baseUrl . "/organization/logo/" . $result['data'][$x]['uuid'];
                    $result['data'][$x]['preferences'] = json_decode($result['data'][$x]['preferences'], true);
                }
            }
        } catch (Exception $e) {
            $this->log->error($e->getMessage(), $e);
            return $this->getErrorResponse($e->getMessage(), 500);
        }
        return $this->getSuccessResponseDataWithPagination($result['data'], $result['total']);
    }

    /**
     * Update Organization API
     * @api
     * @link /organization[/:orgId]
     * @method PUT
     * @param array $id ID of Organization to update
     * @param array $data
     * @return array Returns a JSON Response with Status Code and Created Organization.
     */
    public function update($id, $data)
    {
        $files = $this->params()->fromFiles('logo');
        try {
            $count = $this->orgService->updateOrganization($id, $data, $files);
        } catch (ValidationException $e) {
            $response = ['data' => $data, 'errors' => $e->getErrors()];
            return $this->getErrorResponse("Validation Errors", 404, $response);
        }
        if ($count == 0) {
            return $this->getErrorResponse("Entity not found for id - $id", 404);
        }
        return $this->getSuccessResponseWithData($data, 200);
    }

    /**
     * Delete Organization API
     * @api
     * @link /organization[/:orgId]
     * @method DELETE
     * @link /organization[/:orgId]
     * @param $id ID of Organization to Delete
     * @return array success|failure response
     */
    public function delete($id)
    {
        try {
            $response = $this->orgService->deleteOrganization($id);
            if ($response == 0) {
                return $this->getErrorResponse("Organization not found", 404, ['id' => $id]);
            }
        } catch (Exception $e) {
            $this->log->error($e->getMessage(), $e);
            return $this->getErrorResponse($e->getMessage(), 500);
        }
        return $this->getSuccessResponse();
    }

    /**
     * GET Organization API
     * @api
     * @link /organization[/:orgId]
     * @method GET
     * @param array $dataget of Organization
     * @return array $data
     * <code> {
     *               id : integer,
     *               name : string,
     *               logo : string,
     *               status : String(Active|Inactive),
     *   } </code>
     * @return array Returns a JSON Response with Status Code and Created Organization.
     */
    public function get($id)
    {
        try {
            $result = $this->orgService->getOrganizationByUuid($id);
            if (!$result) {
                return $this->getErrorResponse("Organization not found", 404, ['id' => $id]);
            } else {
                $baseUrl = $this->getBaseUrl();
                $result['logo'] = $baseUrl . "/organization/logo/" . $result["uuid"];
                $result['preferences'] = json_decode($result['preferences'], true);
            }
        } catch (Exception $e) {
            $this->log->error($e->getMessage(), $e);
            return $this->getErrorResponse($e->getMessage(), 500);
        }
        return $this->getSuccessResponseWithData($result);
    }

    /**
     * Add User To Organization API
     * @api
     * @link /user/:userId/organization/:organizationId'
     * @method POST
     * @param $id and $orgid that adds a particular user to a organization
     * @return array success|failure response
     */
    public function addUserToOrganizationAction()
    {
        $params = $this->params()->fromRoute();
        $id = $params['orgId'];
        $data = $this->extractPostData();
        try {
            $count = $this->orgService->saveUser($id, $data);
            if ($count == 0) {
                return $this->getErrorResponse("Entity not found", 404);
            }
            if ($count == 2) {
                return $this->getErrorResponse("Enter User Ids", 404);
            }
        } catch (ValidationException $e) {
            $response = ['data' => $data, 'errors' => $e->getErrors()];
            return $this->getErrorResponse("Validation Errors", 404, $response);
        }
        return $this->getSuccessResponseWithData($data, 200);
    }

    /**
     * GET all users in a particular Organization API
     * @api
     * @link /oeganization/:orgId/users
     * @method GET
     * @return array $dataget list of organization by User
     * <code>status : "success|error",
     *       data : all user id's in the organization passed back in json format
     * </code>
     */
    public function getListOfOrgUsersAction()
    {
        $organization = $this->params()->fromRoute();
        $id = $organization[$this->getIdentifierName()];
        $filterParams = $this->params()->fromQuery(); // empty method call
        try {
            $count = $this->orgService->getOrgUserList($organization[$this->getIdentifierName()], $filterParams, $this->getBaseUrl());
        } catch (ValidationException $e) {
            $response = ['data' => $organization, 'errors' => $e->getErrors()];
            return $this->getErrorResponse("Validation Errors", 404, $response);
        }
        if ($count == 0) {
            return $this->getErrorResponse("Entity not found for id - $id", 404);
        }
        return $this->getSuccessResponseDataWithPagination($count['data'], $count['total']);
    }

    public function getListofAdminUsersAction()
    {
        $data = $this->params()->fromRoute();
        $filterParams = $this->params()->fromQuery();
        $orgId = isset($data['orgId']) ? $data['orgId'] : null;
        try {
            $result = $this->orgService->getAdminUsers($filterParams, $orgId);
        } catch (AccessDeniedException $e) {
            return $this->getErrorResponse($e->getMessage(), 403);
        }
        return $this->getSuccessResponseDataWithPagination($result['data'], $result['total']);
    }

    /**
     * GET Organization Groups API
     * @api
     * @link /organization/:orgId/groups
     * @method GET
     **/
    public function getListofOrgGroupsAction()
    {
        $params = $this->params()->fromRoute();
        $filterParams = $this->params()->fromQuery();
        $orgId = isset($params['orgId']) ? $params['orgId'] : null;
        try {
            $result = $this->orgService->getOrgGroupsList($orgId, $filterParams);
            if (!$result) {
                return $this->getErrorResponse("Organization not found", 404);
            }
        } catch (Exception $e) {
            return $this->getErrorResponse($e->getMessage(), 404);
        }
        return $this->getSuccessResponseDataWithPagination($result['data'], $result['total']);
    }

    /**
     * GET Organization Projects API
     * @api
     * @link /organization/:orgId/projects
     * @method GET
     **/
    public function getListofOrgProjectsAction()
    {
        $params = $this->params()->fromRoute();
        $filterParams = $this->params()->fromQuery();
        $orgId = isset($params['orgId']) ? $params['orgId'] : null;
        try {
            $result = $this->orgService->getOrgProjectsList($orgId, $filterParams);
            if (!$result) {
                return $this->getErrorResponse("Organization not found", 404);
            }
        } catch (Exception $e) {
            return $this->getErrorResponse($e->getMessage(), 404);
        }
        return $this->getSuccessResponseDataWithPagination($result['data'], $result['total']);
    }

    /**
     * GET Organization Announcements API
     * @api
     * @link /organization/:orgId/announcements
     * @method GET
     **/
    public function getListofOrgAnnouncementsAction()
    {
        $params = $this->params()->fromRoute();
        $filterParams = $this->params()->fromQuery();
        $orgId = isset($params['orgId']) ? $params['orgId'] : null;
        try {
            $result = $this->orgService->getOrgAnnouncementsList($orgId, $filterParams);
            if (!$result) {
                return $this->getErrorResponse("Organization not found", 404);
            }
        } catch (Exception $e) {
            return $this->getErrorResponse($e->getMessage(), 404);
        }
        return $this->getSuccessResponseDataWithPagination($result['data'], $result['total']);
    }

    /**
     * GET Organization Roles API
     * @api
     * @link /organization/:orgId/roles
     * @method GET
     **/
    public function getListofOrgRolesAction()
    {
        $params = $this->params()->fromRoute();
        $filterParams = $this->params()->fromQuery();
        $orgId = isset($params['orgId']) ? $params['orgId'] : null;
        try {
            $result = $this->orgService->getOrgRolesList($orgId, $filterParams);
            if (!$result) {
                return $this->getErrorResponse("Organization not found", 404);
            }
        } catch (Exception $e) {
            return $this->getErrorResponse($e->getMessage(), 404);
        }
        return $this->getSuccessResponseDataWithPagination($result['data'], $result['total']);
    }
}
