<?php
namespace Search\Controller;

use Zend\Log\Logger;
use Search\Service\SearchService;
use Oxzion\Controller\AbstractApiController;
use Oxzion\Utils\ValidationResult;
use Zend\Db\Adapter\AdapterInterface;
use Zend\View\Model\JsonModel;
use Zend\Db\Sql\Sql;
use Zend\Db\Adapter\Adapter;
use Oxzion\Search\SearchFactory;
use Oxzion\Search\SearchEngine;

class SearchController extends AbstractApiController
{
	private $dbAdapter;
	private $searchEngine;
    /**
    * @ignore __construct
    */
	public function __construct(SearchEngine $searchEngine,Logger $log){
		parent::__construct(null, $log, __CLASS__, null);
		$this->searchEngine=$searchEngine;
	}


	public function create($data){
        try{
            $app_id = (isset($data['app_id']))?$data['app_id']:null;
            $result = $this->searchEngine->search($data,$app_id);
        }catch(ValidationException $e){
            $response = ['data' => $data, 'errors' => $e->getErrors()];
            return $this->getErrorResponse("Validation Errors",404, $response);
        }
        return $this->getSuccessResponseWithData(array("result"=>$result),201);
    }
  

}
