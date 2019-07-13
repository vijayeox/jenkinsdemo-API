<?php

namespace Analytics\Controller;

use Zend\Log\Logger;
use Analytics\Model\VisualizationTable;
use Analytics\Model\Visualization;
use Analytics\Service\VisualizationService;
use Oxzion\Controller\AbstractApiController;
use Zend\Db\Adapter\AdapterInterface;
use Oxzion\ValidationException;
use Zend\InputFilter\Input;


class VisualizationController extends AbstractApiController
{

    private $visualizationService;

    /**
     * @ignore __construct
     */
    public function __construct(VisualizationTable $table, VisualizationService $visualizationService, Logger $log, AdapterInterface $dbAdapter)
    {
        parent::__construct($table, $log, __class__, Visualization::class);
        $this->setIdentifierName('visualizationId');
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
     * Update Visualization API
     * @api
     * @link /analytics/visualization/:visualizationId
     * @method PUT
     * @param array $id ID of Visualization to update
     * @param array $data
     * @return array Returns a JSON Response with Status Code and Created Visualization.
     */
    public function update($id, $data)
    {
        try {
            $count = $this->visualizationService->updateVisualization($id, $data);
        } catch (ValidationException $e) {
            $response = ['data' => $data, 'errors' => $e->getErrors()];
            return $this->getErrorResponse("Validation Errors", 404, $response);
        }
        if ($count == 0) {
            return $this->getErrorResponse("Visualization not found for id - $id", 404);
        }
        return $this->getSuccessResponseWithData($data, 200);
    }

    /**
     * Delete Visualization API
     * @api
     * @link /analytics/visualization/:visualizationId
     * @method DELETE
     * @param $id ID of Visualization to Delete
     * @return array success|failure response
     */
    public function delete($id)
    {
        $response = $this->visualizationService->deleteVisualization($id);
        if ($response == 0) {
            return $this->getErrorResponse("Visualization not found for id - $id", 404, ['id' => $id]);
        }
        return $this->getSuccessResponse();
    }

    /**
     * GET Visualization API
     * @api
     * @link /analytics/visualization/:visualizationId
     * @method GET
     * @param array $dataget of Visualization
     * @return array $data
     * {
     *              id: integer,
     *              uuid : string,
     *              type : string,
     *              created_by: integer,
     *              date_created: date,
     *              org_id: integer
     *   }
     * @return array Returns a JSON Response with Status Code and Created Group.
     */
    public function get($id)
    {
        $result = $this->visualizationService->getVisualization($id);
        if ($result == 0) {
            return $this->getErrorResponse("Visualization not found", 404, ['id' => $id]);
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
     *              id: integer
     *              name : string,
     *              type : string,
     *              created_by: integer,
     *              date_created: date,
     *              org_id: integer
     * </code>
     */
    public function getList()
    {
        $params = $this->params()->fromQuery();
        $result = $this->visualizationService->getVisualizationList($params);
        if ($result == 0) {
            return $this->getErrorResponse("No records found",404);
        }
        return $this->getSuccessResponseWithData($result);
    }
}

