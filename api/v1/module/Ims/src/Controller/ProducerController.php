<?php
namespace Ims\Controller;

use Exception;
use Ims\Controller\AbstractController;
use Oxzion\Service\ImsService;

class ProducerController extends AbstractController
{
    protected $imsService;
    public function __construct(ImsService $imsService)
    {
        parent::__construct($imsService, 'ProducerFunctions');
        $this->imsService = $imsService;
    }

    public function producerFunctionAction()
    {
        $params = $this->params()->fromRoute(); // This will capture the operation that we are going to perform
        $data = $this->extractPostData(); //This will extract the POST parameters
        try {
            $this->log->info(__CLASS__ . "-> Add Producer with Location - " . print_r($params, true));
            $response = $this->imsService->producerFunctionAction($params['operation'], $data);
        } catch (Exception $e) {
            $this->log->error($e->getMessage(), $e);
            return $this->exceptionToResponse($e);
        }
        return $this->getSuccessResponseWithData($response, 201);
    }
}
