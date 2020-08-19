<?php

namespace Analytics\Controller;

use Exception;
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
    public function __construct($visualizationService)
    {
        parent::__construct(null, __class__, Visualization::class);
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
            $this->visualizationService->createVisualization($data);
            return $this->getSuccessResponseWithData($data, 201);
        }
        catch (Exception $e) {
print($e);
            $this->log->error($e->getMessage(), $e);
            return $this->exceptionToResponse($e);
        }
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
            $this->visualizationService->updateVisualization($uuid, $data);
            return $this->getSuccessResponseWithData($data, 200);
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
            $this->visualizationService->deleteVisualization($uuid, $params['version']);
            return $this->getSuccessResponse();
        }
        catch (Exception $e) {
            $this->log->error($e->getMessage(), $e);
            return $this->exceptionToResponse($e);
        }
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
