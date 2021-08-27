<?php
namespace Oxzion\AppDelegate;

use Oxzion\Service\AccountService;
use Oxzion\Service\BusinessParticipantService;
use Oxzion\Service\ApiCallService;
use Logger;

trait AccountTrait
{
    protected $logger;
    private $accountService;
    private $businessParticipantService;
    private $apiCallService;

    public function __construct()
    {
        $this->logger = Logger::getLogger(__CLASS__);
    }
    
    public function setAccountService(AccountService $accountService)
    {
        $this->logger->info("SET ACCOUNT SERVICE");
        $this->accountService = $accountService;
    }

    public function setBusinessParticipantService(BusinessParticipantService $businessParticipantService)
    {
        $this->logger->info("SET BUSINESS PARTICIPANT SERVICE");
        $this->businessParticipantService = $businessParticipantService;
    }

    public function setApiCallService(ApiCallService $apiCallService)
    {
        $this->logger->info("SET VENDOR API CALL SERVICE");
        $this->apiCallService = $apiCallService;
    }

    protected function registerAccount(&$data)
    {
        return $this->accountService->registerAccount($data);
    }

    protected function checkIfBusinessRelationshipExists($businessRole,$appId,$accountId){
        return $this->businessParticipantService->checkIfBusinessRelationshipExists($businessRole,$appId,$accountId);
    }

    protected function getAccountByName($name) {
        return $this->accountService->getAccountByName($name);
    }

    protected function getVendorApiResponse($endpoint, $params) {
        return $this->apiCallService->getVendorApiResponse($endpoint, $params);
    }
}
