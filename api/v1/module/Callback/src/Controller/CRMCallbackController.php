<?php
namespace Callback\Controller;

use Callback\Service\CRMService;
use Exception;
use Oxzion\Controller\AbstractApiControllerHelper;

class CRMCallbackController extends AbstractApiControllerHelper
{
    private $crmService;
    private $log;
    /**
     * @ignore __construct
     */
    public function __construct(CRMService $crmService)
    {
        $this->crmService = $crmService;
        $this->log = $this->getLogger();
    }

    public function addContactAction()
    {
        $params = $this->extractPostData();
        $this->log->info(__CLASS__ . "-> Add contact to CRM - " . json_encode($params, true));
        try {
            $response = $this->crmService->addContact($params);
            if ($response) {
                return $this->getSuccessResponseWithData($response['body'], 201);
            }
        } catch (Exception $e) {
            $this->log->error($e->getMessage(), $e);
            return $this->getErrorResponse($e->getMessage(), 500);
        }
        return $this->getErrorResponse("Contact Creation Failed", 404);
    }

    /**
     * @ignore
     * Make Call API
     * ! This function is the exact replica of addContactAction(). Not sure why we need this one. We dont even have the routes mentioned in the config.
     */
    public function addCampaignAction()
    {
        $params = $this->extractPostData();
        $this->log->info(__CLASS__ . "-> Add Campaign to CRM - " . json_encode($params, true));
        $response = $this->crmService->addContact($params);
        if ($response) {
            return $this->getSuccessResponseWithData($response['body'], 201);
        }
        return $this->getErrorResponse("Contact Creation Failed", 404);
    }
}
