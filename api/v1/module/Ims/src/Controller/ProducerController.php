<?php
namespace Ims\Controller;

use Oxzion\Controller\AbstractApiController;
use Oxzion\Service\ImsService;
use Zend\Db\Adapter\AdapterInterface;
use Exception;

class ProducerController extends AbstractApiController
{
    private $imsService;
    public function __construct(ImsService $imsService)
    {
        parent::__construct();
        $this->imsService = $imsService;
        $this->imsService->setSoapClient('ProducerFunctions');
    }

    public function getFunctionStructureAction()
    {
        try {
            $params = $this->params()->fromRoute();
            $response = $this->imsService->getFunctionStructure($params['operation']);
        } catch (Exception $e) {
            $this->log->error($e->getMessage(), $e);
            return $this->exceptionToResponse($e);
        }
        return $this->getSuccessResponseWithData($response, 201);
    }

    public function create($data)
    {
        try {
            $params = $this->params()->fromRoute();
            $response = $this->imsService->createProducer($params, $data);
        } catch (Exception $e) {
            $this->log->error($e->getMessage(), $e);
            return $this->exceptionToResponse($e);
        }
        return $this->getSuccessResponseWithData($response, 201);
    }

}