<?php
namespace App\Controller;

/**
* Entity Api
*/
use App\Model\Entity;
use App\Model\EntityTable;
use App\Service\EntityService;
use App\Service\EntityContentService;
use Zend\Db\Adapter\AdapterInterface;
use Oxzion\Controller\AbstractApiController;
use Oxzion\ValidationException;
use Oxzion\ServiceException;

class EntityController extends AbstractApiController
{
    private $entityService;
    private $entityContentService;
    /**
    * @ignore __construct
    */
    public function __construct(EntityTable $table, EntityService $entityService,  AdapterInterface $dbAdapter)
    {
        parent::__construct($table, Entity::class);
        $this->setIdentifierName('entityId');
        $this->entityService = $entityService;
    }
    /**
    * Create Entity API
    * @api
    * @link /app/appId/entity
    * @method POST
    * @param array $data Array of elements as shown
    * <code> {
    *               id : integer,
    *               name : string,
    *               Fields from Entity
    *   } </code>
    * @return array Returns a JSON Response with Status Code and Created Entity.
    */
    public function create($data)
    {
        $appUuid = $this->params()->fromRoute()['appId'];
        try {
            $count = $this->entityService->saveEntity($appUuid, $data);
        } catch (ValidationException $e) {
            $response = ['data' => $data, 'errors' => $e->getErrors()];
            return $this->getErrorResponse("Validation Errors", 404, $response);
        }
        if ($count == 0) {
            return $this->getFailureResponse("Failed to create a new entity", $data);
        }
        return $this->getSuccessResponseWithData($data, 201);
    }
    
    /**
    * GET List Entitys API
    * @api
    * @link /app/appId/entity
    * @method GET
    * @return array Returns a JSON Response list of Entitys based on Access.
    */
    public function getList()
    {
        $appUuid = $this->params()->fromRoute()['appId'];
        $result = $this->entityService->getEntitys($appUuid);
        return $this->getSuccessResponseWithData($result);
    }
    /**
    * Update Entity API
    * @api
    * @link /app/appId/entity[/:id]
    * @method PUT
    * @param array $id ID of Entity to update
    * @param array $data
    * @return array Returns a JSON Response with Status Code and Created Entity.
    */
    public function update($id, $data)
    {
        $appUuid = $this->params()->fromRoute()['appId'];
        if($id){
            $data['id'] = $id;
        }
        try {
            $count = $this->entityService->saveEntity($appUuid, $data, $id);
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
    * Delete Entity API
    * @api
    * @link /app/appId/entity[/:id]
    * @method DELETE
    * @param $id ID of Entity to Delete
    * @return array success|failure response
    */
    public function delete($id)
    {
        $appUuid = $this->params()->fromRoute()['appId'];
        try{
        $response = $this->entityService->deleteEntity($appUuid, $id);
        } catch(ServiceException $e){
            return $this->getErrorResponse($e->getMessage(),404);
        }
        return $this->getSuccessResponse();
    }
    /**
    * GET Entity API
    * @api
    * @link /app/appId/entity[/:id]
    * @method GET
    * @param $id ID of Entity
    * @return array $data
    * @return array Returns a JSON Response with Status Code and Created Entity.
    */
    public function get($entityId)
    {
        $appUuid = $this->params()->fromRoute()['appId'];
        $result = $this->entityService->getEntity($appUuid, $entityId);
        if ($result == 0) {
            return $this->getErrorResponse("Entity not found", 404, ['id' => $entityId]);
        }
        return $this->getSuccessResponseWithData($result);
    }
    /**
     * Upload the app from the UI and extracting the zip file in a folder that will start the installation of app.
     * @api
     * @link /app/:appId/entity/entityId/deployworkflow
     * @method POST
     * @param null </br>
     * <code>
     * </code>
     * @return array Returns a JSON Response with Status Code.</br>
     * <code> status : "success|error"
     * </code>
     */
    public function workflowDeployAction()
    {
        $data=$this->extractPostData();
        $params = array_merge($data, $this->params()->fromRoute());
        $files = isset($_FILES['files']) ? $_FILES['files'] : null;
        try {
            if ($files&&isset($params['name'])) {
                $response = $this->entityService->deployWorkflow($params['appId'],$params['entityId'], $params, $files);
                if ($response == 0) {
                    return $this->getErrorResponse("Error Creating workflow");
                }
                if ($response == 1) {
                    return $this->getErrorResponse("Error Parsing BPMN");
                }
                if ($response == 2) {
                    return $this->getErrorResponse("More Than 1 Process Found in BPMN Please Define only one Process per BPMN");
                }
                return $this->getSuccessResponse($response);
            } else {
                return $this->getErrorResponse("Files cannot be uploaded");
            }
        } catch (Exception $e) {
            return $this->getErrorResponse("Files cannot be uploaded!");
        }
    }
}
