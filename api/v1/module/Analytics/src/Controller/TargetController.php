<?php

namespace Analytics\Controller;

use Analytics\Model\Target;
use Oxzion\Controller\AbstractApiController;
use Oxzion\ValidationException;
use Oxzion\VersionMismatchException;

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
            $count = $this->targetService->createTarget($data);
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
            $count = $this->targetService->updateTarget($uuid, $data);
        } catch (ValidationException $e) {
            $response = ['data' => $data, 'errors' => $e->getErrors()];
            return $this->getErrorResponse("Validation Errors", 404, $response);
        } catch (VersionMismatchException $e) {
            return $this->getErrorResponse('Version changed', 404, ['reason' => 'Version changed', 'reasonCode' => 'VERSION_CHANGED', 'new record' => $e->getReturnObject()]);
        }
        if ($count == 0) {
            return $this->getErrorResponse("Target not found for uuid - $uuid", 404);
        }
        return $this->getSuccessResponseWithData($data, 200);
    }

    public function delete($uuid)
    {
        $params = $this->params()->fromQuery();
        if (isset($params['version'])) {
            try {
                $response = $this->targetService->deleteTarget($uuid, $params['version']);
            } catch (VersionMismatchException $e) {
                return $this->getErrorResponse('Version changed', 404, ['reason' => 'Version changed', 'reasonCode' => 'VERSION_CHANGED', 'new record' => $e->getReturnObject()]);
            }
            if ($response == 0) {
                return $this->getErrorResponse("Target for uuid - $uuid", 404, ['uuid' => $uuid]);
            }
            return $this->getSuccessResponse();
        } else {
            return $this->getErrorResponse("Deleting without version number is not allowed. Use */delete?version=<version> URL.", 404, ['uuid' => $uuid]);
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
