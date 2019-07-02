<?php

namespace Analytics\Controller;

use Zend\Log\Logger;
use Analytics\Model\QueryTable;
use Analytics\Model\Query;
use Analytics\Service\QueryService;
use Oxzion\Controller\AbstractApiController;
use Zend\Db\Adapter\AdapterInterface;
use Oxzion\ValidationException;
use Zend\InputFilter\Input;


class QueryController extends AbstractApiController
{

    private $queryService;

    /**
     * @ignore __construct
     */
    public function __construct(QueryTable $table, QueryService $queryService, Logger $log, AdapterInterface $dbAdapter)
    {
        parent::__construct($table, $log, __class__, Query::class);
        $this->setIdentifierName('queryId');
        $this->queryService = $queryService;
    }

    /**
     * Create Query API
     * @api
     * @link /analytics/query
     * @method POST
     * @param array $data Array of elements as shown
     * <code> {
     *               name : string,
     *               datasource_id : integer,
     *               query_json : string,
     *               ispublic : integer,
     *   } </code>
     * @return array Returns a JSON Response with Status Code and Created Query.
     */
    public function create($data)
    {
        $data = $this->params()->fromPost();
        try {
            $count = $this->queryService->createQuery($data);
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
     * Update Query API
     * @api
     * @link /analytics/query/:queryId
     * @method PUT
     * @param array $id ID of Query to update
     * @param array $data
     * @return array Returns a JSON Response with Status Code and Created Query.
     */
    public function update($id, $data)
    {
        try {
            $count = $this->queryService->updateQuery($id, $data);
        } catch (ValidationException $e) {
            $response = ['data' => $data, 'errors' => $e->getErrors()];
            print_r($response);exit;
            return $this->getErrorResponse("Validation Errors", 404, $response);
        }
        if ($count == 0) {
            return $this->getErrorResponse("Query not found for id - $id", 404);
        }
        return $this->getSuccessResponseWithData($data, 200);
    }

    /**
     * Delete Query API
     * @api
     * @link /analytics/query/:queryId
     * @method DELETE
     * @param $id ID of Query to Delete
     * @return array success|failure response
     */
    public function delete($id)
    {
        $response = $this->queryService->deleteQuery($id);
        if ($response == 0) {
            return $this->getErrorResponse("Query not found for id - $id", 404, ['id' => $id]);
        }
        return $this->getSuccessResponse();
    }

    /**
     * GET Query API
     * @api
     * @link /analytics/query/:queryId
     * @method GET
     * @param array $dataget of Query
     * @return array $data
     * {
     *              id: integer,
     *              uuid: string,
     *              name : string,
     *              datasource_id : integer,
     *              query_json : string,
     *              ispublic : integer,
     *              created_by: integer,
     *              date_created: date,
     *              org_id: integer
     * }
     * @return array Returns a JSON Response with Status Code and Created Group.
     */
    public function get($id)
    {
        $result = $this->queryService->getQuery($id);
        if ($result == 0) {
            return $this->getErrorResponse("Query not found", 404, ['id' => $id]);
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
     *              id: integer
     *              name : string,
     *              type : string,
     *              connection_string : string
     *              created_by: integer
     *              date_created: date
     * </code>
     */
    public function getList()
    {
        $params = $this->params()->fromQuery();
        $result = $this->queryService->getQueryList($params);
        if ($result == 0) {
            return $this->getErrorResponse("No records found",404);
        }
        return $this->getSuccessResponseWithData($result);
    }
}

