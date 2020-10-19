<?php

namespace Analytics\Controller;

use Exception;
use Analytics\Model\DataSource;
use Oxzion\Controller\AbstractApiController;
use Oxzion\ValidationException;
use Oxzion\VersionMismatchException;

class DataSourceController extends AbstractApiController
{

    private $dataSourceService;

    /**
     * @ignore __construct
     */
    public function __construct($dataSourceService)
    {
        parent::__construct(null, __class__, DataSource::class);
        $this->setIdentifierName('dataSourceUuid');
        $this->dataSourceService = $dataSourceService;
    }

    /**
     * Create DataSource API
     * @api
     * @link /analytics/dataSource
     * @method POST
     * @param array $data Array of elements as shown
     * <code> {
     *               name : string,
     *               type : string,
     *               connection_string : string
     *   } </code>
     * @return array Returns a JSON Response with Status Code and Created DataSource.
     */
    public function create($data)
    {
        $data = $this->params()->fromPost();
        try {
            $this->dataSourceService->createDataSource($data);
            return $this->getSuccessResponseWithData($data, 201);
        }
        catch (Exception $e) {
            $this->log->error($e->getMessage(), $e);
            return $this->exceptionToResponse($e);
        }
    }

    /**
     * Update DataSource API
     * @api
     * @link /analytics/dataSource/:dataSourceUuid
     * @method PUT
     * @param array $uuid ID of DataSource to update
     * @param array $data
     * @return array Returns a JSON Response with Status Code and Created DataSource.
     */
    public function update($uuid, $data)
    {
        try {
            $this->dataSourceService->updateDataSource($uuid, $data);
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
            $this->dataSourceService->deleteDataSource($uuid, $params['version']);
            return $this->getSuccessResponse();
        }
        catch (Exception $e) {
            $this->log->error($e->getMessage(), $e);
            return $this->exceptionToResponse($e);
        }
    }

    /**
     * GET DataSource API
     * @api
     * @link /analytics/datasource/:dataSourceUuid
     * @method GET
     * @param array $dataget of DataSource
     * @return array $data
     * {
     *              id: integer,
     *              uuid: string,
     *              name : string,
     *              type : string,
     *              connection_string : string,
     *              created_by: integer,
     *              date_created: date,
     *              org_id: integer,
     *              isdeleted: tinyint
     *   }
     * @return array Returns a JSON Response with Status Code and Created Group.
     */
    public function get($id)
    {
        $result = $this->dataSourceService->getDataSource($id);
        if ($result == 0) {
            return $this->getErrorResponse("DataSource not found", 404, ['id' => $id]);
        }
        return $this->getSuccessResponseWithData($result);
    }

    /**
     * GET DataSource API
     * @api
     * @link /analytics/datasource
     * @method GET
     * @param      integer      $limit   (number of rows to fetch)
     * @param      integer      $skip    (number of rows to skip)
     * @param      array[json]  $sort    (sort based on field and dir json)
     * @param      array[json]  $filter  (filter with logic and filters)
     * @return array $dataget list of Datasource
     * <code>status : "success|error",
     *              id: integer,
     *              uuid: string,
     *              name : string,
     *              type : string,
     *              connection_string : string,
     *              created_by: integer,
     *              date_created: date,
     *              isdeleted: tinyint
     * </code>
     */

    public function getList()
    {
        $params = $this->params()->fromQuery();
        $result = $this->dataSourceService->getDataSourceList($params);
        return $this->getSuccessResponseDataWithPagination($result['data'], $result['total']);
    }

    /**
     * GET DataSource API
     * @api
     * @link /analytics/datasource
     * @method GET
     * @param      array[json]  $dataEntity    (Index or Table for fields)
     * @param      array[json]  $field  (field for values)
     * @return array $dataget Get Details
     * <code>status : "success|error",
     *              id: integer,
     *              data: string,
     * </code>
     */
    public function getDetailsAction()
    {
        $routeParams = $this->params()->fromRoute();
        $uuid = $routeParams['datasourceUuid'];
        $params = $this->params()->fromQuery();
        $result = $this->dataSourceService->getDataStructureDetails($uuid,  $params);
        return $this->getSuccessResponseWithData($result);
    }
}
