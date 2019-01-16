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

class SearchController extends AbstractApiController
{
	private $dbAdapter;
	private $searchFactory;
    /**
    * @ignore __construct
    */
	public function __construct(SearchFactory $searchFactory,Logger $log){
		parent::__construct(null, $log, __CLASS__, null);
		$this->searchFactory=$searchFactory;
	}


	public function create($data){
        try{
            $searchEngine = $this->searchFactory->getSearchEngine();
            $app_id = (isset($data['app_id']))?$data['app_id']:null;
            $result = $searchEngine->search($data,$app_id);
        }catch(ValidationException $e){
            $response = ['data' => $data, 'errors' => $e->getErrors()];
            return $this->getErrorResponse("Validation Errors",404, $response);
        }
        return $this->getSuccessResponseWithData(array("result"=>$result),201);
    }
  

}
