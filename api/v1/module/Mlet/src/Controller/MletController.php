<?php
namespace Mlet\Controller;

use Zend\Log\Logger;
use Mlet\Model\Mlet;
use Mlet\Model\MletTable;
use Mlet\Service\MletService;
use Oxzion\Controller\AbstractApiController;
use Oxzion\Utils\ValidationResult;
use Zend\Db\Adapter\AdapterInterface;
use Zend\View\Model\JsonModel;
use Zend\Db\Sql\Sql;
use Zend\Db\Adapter\Adapter;

class MletController extends AbstractApiController
{
	private $dbAdapter;
	private $mletService;
    /**
    * @ignore __construct
    */
	public function __construct(MletTable $table,MletService $mletService,Logger $log){
		parent::__construct($table, $log, __CLASS__, Mlet::class);
		$this->mletService=$mletService;
		$this->setIdentifierName('mletId');
	}
 	/**
    * GET List Mlet API
    * @api
    * @link /mlet
    * @method GET
    * @return array Returns a JSON Response list of Mlet based on Access.
    */
	public function getList() {
		$params = $this->params()->fromRoute();
		$result = $this->mletService->getMlets();
		return $this->getSuccessResponseWithData($result);
	}

	public function getResultAction() {
		try{
			$params = $this->params()->fromRoute();
			$id=$params[$this->getIdentifierName()];
			$data = $this->params()->fromPost();
			$result= $this->mletService->getResult($id,$data);
		}catch(ValidationException $e){
            $response = ['data' => $data, 'errors' => $e->getErrors()];
            return $this->getErrorResponse("Validation Errors",404, $response);
        }
        return $this->getSuccessResponseWithData(array("result"=>$result),201);
	}
  

}
