<?php

namespace Email\Controller;

use Zend\Log\Logger;
use Oxzion\Controller\AbstractApiController;
use Email\Model\DomainTable;
use Email\Model\Domain;
use Email\Service\DomainService;
use Zend\Db\Adapter\AdapterInterface;
use Oxzion\ValidationException;
use Oxzion\Auth\AuthContext;
use Oxzion\Auth\AuthConstants;

class DomainController extends AbstractApiController
{
    /**
     * @var DomainService Instance of Domain Service
     */
    private $domainService;

    /**
     * @ignore __construct
     */
    public function __construct(DomainTable $table, DomainService $domainService, Logger $log, AdapterInterface $dbAdapter)
    {
        parent::__construct($table, $log, __CLASS__, Domain::class);
        $this->setIdentifierName('domainId');
        $this->domainService = $domainService;
    }

    public function create($data)
    {
        try {
            $count = $this->domainService->createDomain($data);
        } catch (ValidationException $e) {
            $response = ['data' => $data, 'errors' => $e->getErrors()];
            return $this->getErrorResponse("Validation Errors", 404, $response);
        }
        if ($count == 0) {
            return $this->getFailureResponse("Failed to create a new entity", $data);
        }
        unset($data['password']);
        return $this->getSuccessResponseWithData($data, 201);
    }


    /**
     * Update Domain API
     * @api
     * @link /domain[/:domainId]
     * @method PUT
     * @param array $id ID of Domain to update
     * @param array $data
     * @return array Returns a JSON Response with Status Code and Created Domain.
     */
    public function update($id, $data)
    {
        try {
            $count = $this->domainService->updateDomain($id, $data);
        } catch (ValidationException $e) {
            $response = ['data' => $data, 'errors' => $e->getErrors()];
            return $this->getErrorResponse("Validation Errors", 404, $response);
        }
        if ($count == 0) {
            return $this->getErrorResponse("Entity not found for id - $id", 404);
        }
        return $this->getSuccessResponseWithData($data, 200);
    }

    /**
     * Delete Domain API
     * @api
     * @link /domain/delete/:name
     * @method DELETE
     * @param $id ID of Domain to Delete
     * @return array success|failure response
     */
    public function deleteDomainAction()
    {
        $domain = $this->params()->fromRoute()['name'];
        try {
            $responseData = $this->domainService->deleteDomain($domain);
        } catch (ValidationException $e) {
            $response = ['data' => $domain, 'errors' => $e->getErrors()];
            return $this->getErrorResponse("Validation Errors", 404, $response);
        }
        if ($responseData == 0) {
            return $this->getErrorResponse("Entity not found", 404);
        }
        return $this->getSuccessResponseWithData($responseData, 200);
    }

}