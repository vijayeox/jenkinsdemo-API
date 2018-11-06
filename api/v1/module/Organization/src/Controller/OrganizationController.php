<?php
namespace Organization\Controller;

use Zend\Log\Logger;
use Organization\Model\Organization;
use Organization\Model\OrganizationTable;
use Organization\Service\OrganizationService;
use Oxzion\Controller\AbstractApiController;
use Oxzion\ValidationException;
use Zend\Db\Adapter\AdapterInterface;

class OrganizationController extends AbstractApiController {

	private $orgService;

	public function __construct(OrganizationTable $table, OrganizationService $orgService, Logger $log, AdapterInterface $dbAdapter) {
		parent::__construct($table, $log, __CLASS__, Organization::class);
		$this->setIdentifierName('orgId');
		$this->orgService = $orgService;
	}
	/**
    *   $data should be in the following JSON format
    *   {

    *   }
    *
    *
    */
	public function create($data){
		try{
			$count = $this->orgService->createOrganization($data);
		} catch (ValidationException $e){
			$response = ['data' => $data, 'errors' => $e->getErrors()];
			return $this->getErrorResponse("Validation Errors",404, $response);
		}
		if($count == 0){
			return $this->getFailureResponse("Failed to create a new entity", $data);
		}
		return $this->getSuccessResponseWithData($data,201);
	}

	public function getList() {
		return $this->getErrorResponse("Method Not Found",405);
	}
	public function update($id, $data){
		try{
			$count = $this->orgService->updateOrganization($id,$data);
		}catch(ValidationException $e){
			$response = ['data' => $data, 'errors' => $e->getErrors()];
			return $this->getErrorResponse("Validation Errors",404, $response);
		}
		if($count == 0){
			return $this->getErrorResponse("Entity not found for id - $id", 404);
		}
		return $this->getSuccessResponseWithData($data,200);
	}
	public function delete($id){
		$response = $this->orgService->deleteOrganization($id);
		if($response == 0){
			return $this->getErrorResponse("Organization not found", 404, ['id' => $id]);
		}
		return $this->getSuccessResponse();
	}
	public function get($id){
		$result = $this->orgService->getOrganization($id);
		if($result == 0){
			return $this->getErrorResponse("Organization not found", 404, ['id' => $id]);
		}
		return $this->getSuccessResponseWithData($result);
	}
}