<?php
namespace Search\Controller;

use Oxzion\Controller\AbstractApiController;
use Oxzion\Search\SearchEngine;

class SearchController extends AbstractApiController
{
    private $dbAdapter;
    private $searchEngine;
    /**
     * @ignore __construct
     */
    public function __construct(SearchEngine $searchEngine)
    {
        parent::__construct(null, null);
        $this->searchEngine = $searchEngine;
    }

    public function create($data)
    {
        try {
            $app_id = (isset($data['app_id'])) ? $data['app_id'] : null;
            $result = $this->searchEngine->search($data, $app_id);
        } catch (ValidationException $e) {
            $response = ['data' => $data, 'errors' => $e->getErrors()];
            return $this->getErrorResponse("Validation Errors", 404, $response);
        }
        return $this->getSuccessResponseWithData(array("result" => $result), 201);
    }
}
