<?php

namespace Analytics\Controller;

use Zend\Log\Logger;
use Analytics\Model\Visualization;
use Oxzion\Controller\AbstractApiController;
use Oxzion\ValidationException;
use Oxzion\VersionMismatchException;

class VisualizationController extends AbstractApiController
{

    private $visualizationService;

    /**
     * @ignore __construct
     */
    public function __construct($visualizationService, Logger $log)
    {
        parent::__construct(null, $log, __class__, Visualization::class);
        $this->setIdentifierName('visualizationUuid');
        $this->visualizationService = $visualizationService;
    }

    /**
     * Create Visualization API
     * @api
     * @link /analytics/visualization
     * @method POST
     * @param array $data Array of elements as shown
     * <code> {
     *               type : string
     *   } </code>
     * @return array Returns a JSON Response with Status Code and Created Visualization.
     */
    public function create($data)
    {
        $data = $this->params()->fromPost();
        try {
            $count = $this->visualizationService->createVisualization($data);
        }
        catch (ValidationException $e) {
            $response = ['data' => $data, 'errors' => $e->getErrors()];
            return $this->getErrorResponse("Validation Errors", 404, $response);
        }
        if ($count == 0) {
            return $this->getFailureResponse("Failed to create a new entity", $data);
        }
        return $this->getSuccessResponseWithData($data, 201);
    }

    /**
     * Update Visualization API
     * @api
     * @link /analytics/visualization/:visualizationUuid
     * @method PUT
     * @param array $uuid ID of Visualization to update
     * @param array $data
     * @return array Returns a JSON Response with Status Code and Created Visualization.
     */
    public function update($uuid, $data)
    {
        try {
            $count = $this->visualizationService->updateVisualization($uuid, $data);
        }
        catch (ValidationException $e) {
            $response = ['data' => $data, 'errors' => $e->getErrors()];
            return $this->getErrorResponse("Validation Errors", 404, $response);
        }
        catch (VersionMismatchException $e) {
            return $this->getErrorResponse('Version changed', 404, ['reason' => 'Version changed', 'reasonCode' => 'VERSION_CHANGED']);
        }
        if ($count == 0) {
            return $this->getErrorResponse("Visualization not found for uuid - $uuid", 404);
        }
        return $this->getSuccessResponseWithData($data, 200);
    }

    public function delete($uuid) {
        throw new Exception('Deleting without version number is not allowed. Use */deleteWithVersion?version=<version> URL.');
    }

    /**
     * Delete Visualization API
     * @api
     * @link /analytics/visualization/:visualizationUuid
     * @method DELETE
     * @param $uuid ID of Visualization to Delete
     * @return array success|failure response
     */
    public function deleteWithVersion($uuid)
    {
        try {
            $response = $this->visualizationService->deleteVisualization($uuid. $data['version']);
        }
        catch (VersionMismatchException $e) {
            return $this->getErrorResponse('Version changed', 404, ['reason' => 'Version changed', 'reasonCode' => 'VERSION_CHANGED']);
        }
        if ($response == 0) {
            return $this->getErrorResponse("Visualization not found for uuid - $uuid", 404, ['uuid' => $uuid]);
        }
        return $this->getSuccessResponse();
    }

    /**
     * GET Visualization API
     * @api
     * @link /analytics/visualization/:visualizationUuid
     * @method GET
     * @param array $dataget of Visualization
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
        $result = $this->visualizationService->getVisualization($uuid);
        if ($result == 0) {
            return $this->getErrorResponse("Visualization not found", 404, ['uuid' => $uuid]);
        }
        return $this->getSuccessResponseWithData($result);
    }

    /**
     * GET Visualization API
     * @api
     * @link /analytics/visualization
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
        $result = $this->visualizationService->getVisualizationList($params);
        return $this->getSuccessResponseWithData($result);
    }
}

