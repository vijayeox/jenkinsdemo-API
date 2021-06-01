<?php
namespace Ims\Controller;

use Exception;
use Ims\Controller\AbstractController;
use Oxzion\Service\ImsService;

class InsuredController extends AbstractController
{
    public function __construct(ImsService $imsService)
    {
        parent::__construct($imsService, 'InsuredFunctions');
    }

    public function createInsuredFunctionAction()
    {
        $params = $this->params()->fromRoute(); // This will capture the operation that we are going to perform
        $data = $this->extractPostData(); //This will extract the POST parameters
        try {
            $this->log->info(__CLASS__ . "-> Add Insured with Location - " . print_r($params, true) . "\n Data: " . print_r($data, true));
            $response = $this->imsService->createAPI($params, $data);
        } catch (Exception $e) {
            $this->log->error($e->getMessage(), $e);
            return $this->exceptionToResponse($e);
        }
        return $this->getSuccessResponseWithData($response, 201);
    }
}
