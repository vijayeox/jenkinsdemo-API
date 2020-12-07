<?php
namespace App\Controller;

/**
 * Entity Api
 */
use Oxzion\Model\App\Entity;
use Oxzion\Model\App\EntityTable;
use Oxzion\Service\EntityService;
use Exception;
use Oxzion\Controller\AbstractApiController;
use Zend\Db\Adapter\AdapterInterface;

class EntityController extends AbstractApiController
{
    private $entityService;
    private $entityContentService;
    /**
     * @ignore __construct
     */
    public function __construct(EntityTable $table, EntityService $entityService, AdapterInterface $dbAdapter)
    {
        parent::__construct($table, Entity::class);
        $this->setIdentifierName('entityId');
        $this->entityService = $entityService;
        $this->log = $this->getLogger();
    }

    /**
     * Create Entity API
     * @api
     * @link /app/appId/entity
     * @method POST
     * @param array $data Array of elements as shown
     * <code> {
     *      id : integer,
     *      name : string,
     *      Fields from Entity
     *   } </code>
     * @return array Returns a JSON Response with Status Code and Created Entity.
     */
    public function create($data)
    {
        $appUuid = $this->params()->fromRoute()['appId'];
        $this->log->info(__CLASS__ . "-> \n Create Entity - " . print_r($data, true));
        try {
            $this->entityService->saveEntity($appUuid, $data);
            unset($data['id']);
            return $this->getSuccessResponseWithData($data, 201);
        }catch (Exception $e) {
            $this->log->error($e->getMessage(), $e);
            return $this->exceptionToResponse($e);
        }
        
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
        $data = $this->params()->fromRoute();
        $this->log->info(__CLASS__ . "-> \n Get Entity List- " . print_r($data, true));
        try {
            $appUuid = $this->params()->fromRoute()['appId'];
            $result = $this->entityService->getEntitys($appUuid);
            return $this->getSuccessResponseWithData($result);
        }catch (Exception $e) {
            $this->log->error($e->getMessage(), $e);
            return $this->exceptionToResponse($e);
        }
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
        print($id."\n");
        $appUuid = $this->params()->fromRoute()['appId'];
        $this->log->info(__CLASS__ . "-> \n Update- " . print_r($data, true) . "AppUUID - " . $appUuid);
        if ($id) {
            $data['uuid'] = $id;
        }
        try {
            $this->entityService->saveEntity($appUuid, $data, false);
            return $this->getSuccessResponseWithData($data, 200);
        }catch (Exception $e) {
            $this->log->error($e->getMessage(), $e);
            return $this->exceptionToResponse($e);
        }
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
        $this->log->info(__CLASS__ . "-> \n Delete Entity - " . print_r($id, true) . "AppUUID - " . $appUuid);
        try {
            $response = $this->entityService->deleteEntity($appUuid, $id);
        }catch (Exception $e) {
            $this->log->error($e->getMessage(), $e);
            return $this->exceptionToResponse($e);
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
        try{
            $result = $this->entityService->getEntity($entityId, $appUuid);
        }catch (Exception $e) {
            $this->log->error($e->getMessage(), $e);
            return $this->exceptionToResponse($e);
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
    // public function workflowDeployAction()
    // {
    //     $data = $this->extractPostData();
    //     $params = array_merge($data, $this->params()->fromRoute());
    //     $this->log->info(__CLASS__ . "-> \n Deploy Workflow - " . print_r($params, true));
    //     $files = isset($_FILES['files']) ? $_FILES['files'] : null;
    //     try {
    //         if ($files && isset($params['name'])) {
    //             $response = $this->entityService->deployWorkflow($params['appId'], $params['entityId'], $params, $files);
    //             if ($response == 0) {
    //                 return $this->getErrorResponse("Error Creating workflow");
    //             }
    //             if ($response == 1) {
    //                 return $this->getErrorResponse("Error Parsing BPMN");
    //             }
    //             if ($response == 2) {
    //                 return $this->getErrorResponse("More Than 1 Process Found in BPMN Please Define only one Process per BPMN");
    //             }
    //             return $this->getSuccessResponse($response);
    //         } else {
    //             return $this->getErrorResponse("Files cannot be uploaded");
    //         }
    //     } catch (Exception $e) {
    //         $this->log->error($e->getMessage(), $e);
    //         return $this->getErrorResponse($e->getMessage(), 417);
    //     }
    // }

    /**
     * GET Entity API
     * @api
     * @link /app/appId/entity[/:id]
     * @method GET
     * @param $id ID of Entity
     * @return array $data
     * @return array Returns a JSON Response with Status Code and Created Entity.
     */
    public function pageAction()
    {
        $appUuid = $this->params()->fromRoute()['appId'];
        $entityId = $this->params()->fromRoute()['entityId'];
        try{
            $result = $this->entityService->getEntityPage($entityId, $appUuid);
        }catch (Exception $e) {
            $this->log->error($e->getMessage(), $e);
            return $this->exceptionToResponse($e);
        }
        return $this->getSuccessResponseWithData($result);
    }
}
