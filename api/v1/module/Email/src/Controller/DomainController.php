<?php

namespace Email\Controller;

use Email\Model\Domain;
use Email\Model\DomainTable;
use Email\Service\DomainService;
use Exception;
use Oxzion\Controller\AbstractApiController;
use Oxzion\ValidationException;
use Zend\Db\Adapter\AdapterInterface;

class DomainController extends AbstractApiController
{
    /**
     * @var DomainService Instance of Domain Service
     */
    private $domainService;

    /**
     * @ignore __construct
     */
    public function __construct(DomainTable $table, DomainService $domainService, AdapterInterface $dbAdapter)
    {
        parent::__construct($table, Domain::class);
        $this->setIdentifierName('domainId');
        $this->domainService = $domainService;
    }

    public function create($data)
    {
        $this->log->info(__CLASS__ . "->create new Domain - " . json_encode($data, true));
        try {
            $count = $this->domainService->createDomain($data);
            if ($count == 0) {
                return $this->getFailureResponse("Failed to create a new entity", $data);
            }
        } catch (ValidationException $e) {
            $response = ['data' => $data, 'errors' => $e->getErrors()];
            return $this->getErrorResponse("Validation Errors", 404, $response);
        } catch (Exception $e) {
            $this->log->error($e->getMessage(), $e);
            return $this->getErrorResponse($e->getMessage(), 500);
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
        $this->log->info(__CLASS__ . "-> Update Domain - " . json_encode($data, true));
        try {
            $count = $this->domainService->updateDomain($id, $data);
            if ($count == 0) {
                return $this->getErrorResponse("Entity not found for id - $id", 404);
            }
        } catch (ValidationException $e) {
            $response = ['data' => $data, 'errors' => $e->getErrors()];
            return $this->getErrorResponse("Validation Errors", 404, $response);
        } catch (Exception $e) {
            $this->log->error($e->getMessage(), $e);
            return $this->getErrorResponse($e->getMessage(), 500);
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
        $this->log->info(__CLASS__ . "-> Update Domain - " . json_encode($domain, true));
        try {
            $responseData = $this->domainService->deleteDomain($domain);
        } catch (ValidationException $e) {
            $response = ['data' => $domain, 'errors' => $e->getErrors()];
            return $this->getErrorResponse("Validation Errors", 404, $response);
        } catch (Exception $e) {
            $this->log->error($e->getMessage(), $e);
            return $this->getErrorResponse($e->getMessage(), 500);
        }
        if ($responseData == 0) {
            return $this->getErrorResponse("Entity not found", 404);
        }
        return $this->getSuccessResponseWithData($responseData, 200);
    }
}
