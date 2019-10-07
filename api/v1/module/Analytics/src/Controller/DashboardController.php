<?php

namespace Analytics\Controller;

use Zend\Log\Logger;
use Analytics\Model\Dashboard;
use Oxzion\Controller\AbstractApiController;
use Oxzion\ValidationException;
use Oxzion\VersionMismatchException;

class DashboardController extends AbstractApiController
{

    private $dashboardService;

    /**
     * @ignore __construct
     */
    public function __construct($dashboardService, Logger $log)
    {
        parent::__construct(null, $log, __class__, Dashboard::class);
        $this->setIdentifierName('dashboardUuid');
        $this->dashboardService = $dashboardService;
    }

    /**
     * Create Dashboard API
     * @api
     * @link /analytics/dashboard
     * @method POST
     * @param array $data Array of elements as shown
     * <code> {
     *               name : string
     *               dashboard_type : string
     *               ispublic : integer(binary)
     *               description : string
     *   } </code>
     * @return array Returns a JSON Response with Status Code and Created Dashboard.
     */
    public function create($data)
    {
        $data = $this->params()->fromPost();
        try {
            $count = $this->dashboardService->createDashboard($data);
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
     * Update Dashboard API
     * @api
     * @link /analytics/dashboard/:dashboardUuid
     * @method PUT
     * @param array $uuid ID of Dashboard to update
     * @param array $data
     * @return array Returns a JSON Response with Status Code and Created Dashboard.
     */
    public function update($uuid, $data)
    {
        try {
            $result = $this->dashboardService->updateDashboard($uuid, $data);
        }
        catch (ValidationException $e) {
            $response = ['data' => $data, 'errors' => $e->getErrors()];
            return $this->getErrorResponse("Validation Errors", 404, $response);
        }
        catch (VersionMismatchException $e) {
            return $this->getErrorResponse('Version changed', 404, ['reason' => 'Version changed', 'reasonCode' => 'VERSION_CHANGED']);
        }
        if ($result == 0) {
            return $this->getErrorResponse("Dashboard update failed for uuid - $uuid", 404);
        }
        return $this->getSuccessResponseWithData($result, 200);
    }

    /**
     * Delete Dashboard API
     * @api
     * @link /analytics/dashboard/:dashboardUuid
     * @method DELETE
     * @param $uuid ID of Dashboard to Delete
     * @param $version Version number of the dashboard to delete.
     * @return array success|failure response
     */
    public function delete($uuid, $version)
    {
        try {
            $response = $this->dashboardService->deleteDashboard($uuid, $version);
        }
        catch (VersionMismatchException $e) {
            return $this->getErrorResponse('Version changed', 404, ['reason' => 'Version changed', 'reasonCode' => 'VERSION_CHANGED']);
        }
        if ($response == 0) {
            return $this->getErrorResponse("Dashboard not found for uuid - $uuid", 404, ['uuid' => $uuid]);
        }
        return $this->getSuccessResponse();
    }

    /**
     * GET Dashboard API
     * @api
     * @link /analytics/dashboard/:dashboardUuid
     * @method GET
     * @param array $dataget of Dashboard
     * @return array $data
     * {
     *              id: integer,
     *              uuid : string,
     *              name : string,
     *              ispublic : integer,
     *              description : string,
     *              dashboard_type : string,
     *              created_by: integer,
     *              date_created: date,
     *              org_id: integer,
     *              isdeleted: tinyint
     *   }
     * @return array Returns a JSON Response with Status Code and Created Group.
     */
    public function get($uuid)
    {
        $result = $this->dashboardService->getDashboard($uuid);
        if ($result == 0) {
            return $this->getErrorResponse("Dashboard not found", 404, ['uuid' => $uuid]);
        }
        return $this->getSuccessResponseWithData($result);
    }

    /**
     * GET Dashboard API
     * @api
     * @link /analytics/dashboard
     * @method GET
     * @param      integer      $limit   (number of rows to fetch)
     * @param      integer      $skip    (number of rows to skip)
     * @param      array[json]  $sort    (sort based on field and dir json)
     * @param      array[json]  $filter  (filter with logic and filters)
     * @return array $dataget list of Datasource
     * <code>status : "success|error",
     *              id: integer,
     *              uuid : string,
     *              name : string,
     *              ispublic : integer,
     *              description : string,
     *              dashboard_type : string,
     *              created_by: integer,
     *              date_created: date,
     *              org_id: integer,
     *              isdeleted: tinyint
     * </code>
     */
    public function getList()
    {
        $params = $this->params()->fromQuery();
        $result = $this->dashboardService->getDashboardList($params);
        return $this->getSuccessResponseWithData($result);
    }
}

