<?php

namespace Analytics\Controller;

use Analytics\Model\Target;
use Oxzion\Controller\AbstractApiController;
use Oxzion\ValidationException;
use Oxzion\VersionMismatchException;
use Exception;

class TargetController extends AbstractApiController
{

    private $targetService;

    /**
     * @ignore __construct
     */
    public function __construct($targetService)
    {
        parent::__construct(null, __class__, Target::class);
        $this->setIdentifierName('targetUuid');
        $this->targetService = $targetService;
    }

    /**
     * Create Target API
     * @api
     * @link /analytics/Target
     * @method POST
     * @param array $data Array of elements as shown
     * <code> {
     *               type : string
     *   } </code>
     * @return array Returns a JSON Response with Status Code and Created Target.
     */
    public function create($data)
    {
        $data = $this->params()->fromPost();
        try {
            $generated = $this->targetService->createTarget($data);
            return $this->getSuccessResponseWithData($generated, 201);
        }
        catch (Exception $e) {
            $this->log->error($e->getMessage(), $e);
            return $this->exceptionToResponse($e);
        }
    }

    /**
     * Update Target API
     * @api
     * @link /analytics/Target/:TargetUuid
     * @method PUT
     * @param array $uuid ID of Target to update
     * @param array $data
     * @return array Returns a JSON Response with Status Code and Created Target.
     */
    public function update($uuid, $data)
    {
        try {
            $version = $this->targetService->updateTarget($uuid, $data);
            return $this->getSuccessResponseWithData(['version' => $version], 200);
        } 
        catch (Exception $e) {
            $this->log->error($e->getMessage(), $e);
            return $this->exceptionToResponse($e);
        }
    }

    public function delete($uuid)
    {
        $params = $this->params()->fromQuery();
        try {
            $this->targetService->deleteTarget($uuid, $params['version']);
            return $this->getSuccessResponse();
        }
        catch (Exception $e) {
            $this->log->error($e->getMessage(), $e);
            return $this->exceptionToResponse($e);
        }
    }

    /**
     * GET Target API
     * @api
     * @link /analytics/Target/:TargetUuid
     * @method GET
     * @param array $dataget of Target
     * @return array $data
     * {
     *              uuid : string,
     *              type : string,
     *              created_by: integer,
     *              date_created: date,
     *              org_id: integer,
     *              isdeleted: tinyint
     *   }
     * @return array Returns a JSON Response with Status Code and Created Group.
     */
    public function get($uuid)
    {
        $result = $this->targetService->getTarget($uuid);
        if ($result == 0) {
            return $this->getErrorResponse("Target not found", 404, ['uuid' => $uuid]);
        }
        return $this->getSuccessResponseWithData($result);
    }

    /**
     * GET Target API
     * @api
     * @link /analytics/Target
     * @method GET
     * @param      integer      $limit   (number of rows to fetch)
     * @param      integer      $skip    (number of rows to skip)
     * @param      array[json]  $sort    (sort based on field and dir json)
     * @param      array[json]  $filter  (filter with logic and filters)
     * @return array $dataget list of Datasource
     * <code>status : "success|error",
     *              name : string,
     *              type : string,
     *              created_by: integer,
     *              date_created: date,
     *              org_id: integer,
     *              isdeleted: tinyint
     * </code>
     */
    public function getList()
    {
        $params = $this->params()->fromQuery();
        $result = $this->targetService->getTargetList($params);
        return $this->getSuccessResponseWithData($result);
    }

    public function getKRAResultAction() 
    {
        $params = $this->params()->fromQuery();
        $result = $this->targetService->getKRAResult($params);
        return $this->getSuccessResponseWithData($result);
    }
}
