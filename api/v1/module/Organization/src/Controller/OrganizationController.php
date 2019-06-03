<?php
namespace Organization\Controller;

use Zend\Log\Logger;
use Oxzion\Controller\AbstractApiController;
use Bos\ValidationException;
use Zend\Db\Adapter\AdapterInterface;
use Oxzion\Model\Organization;
use Oxzion\Model\OrganizationTable;
use Oxzion\Service\OrganizationService;

class OrganizationController extends AbstractApiController
{

    private $orgService;

    /**
     * @ignore __construct
     */
    public function __construct(OrganizationTable $table, OrganizationService $orgService, Logger $log, AdapterInterface $dbAdapter)
    {
        parent::__construct($table, $log, __CLASS__, Organization::class);
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
        $files = $this->params()->fromFiles('logo');
        $id=$this->params()->fromRoute();
        try {
            if(!isset($id['orgId'])){
                $count = $this->orgService->createOrganization($data,$files);
            }else{
                $count = $this->orgService->updateOrganization($id['orgId'],$data,$files);
            }
         } catch (ValidationException $e) {
            $response = ['data' => $data, 'errors' => $e->getErrors()];
            return $this->getErrorResponse("Validation Errors", 404, $response);
        }
        if ($count == 0) {
            return $this->getFailureResponse("Failed to create a new entity", $data);
        }
        if ($count == 2) {
            return $this->getFailureResponse("Entity not found for UUID", $id);
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
        $result = $this->orgService->getOrganizations($filterParams);
        return $this->getSuccessResponseDataWithPagination($result['data'],$result['total']);
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
                $count = $this->orgService->updateOrganization($id,$data,$files);    
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
        $response = $this->orgService->deleteOrganization($id);
        if ($response == 0) {
            return $this->getErrorResponse("Organization not found", 404, ['id' => $id]);
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
        $result = $this->orgService->getOrganizationByUuid($id);
        
        if ($result == 0) {
            return $this->getErrorResponse("Organization not found", 404, ['id' => $id]);
        }

         if ($result) {
                $baseUrl =$this->getBaseUrl();
                $logo = $result['logo'];
                $result['logo'] = $baseUrl . "/organization/" . $result["uuid"];
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

        $id=$params['orgId'];
        $data = $this->params()->fromPost();
        try {
            $count = $this->orgService->saveUser($id,$data);
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
}