<?php

namespace Oxzion\Service;
use Exception;
use Oxzion\Auth\AuthConstants;
use Oxzion\Auth\AuthContext;
use Oxzion\ServiceException;
use Oxzion\Service\AbstractService;
use Oxzion\Service\AccountService;
use Oxzion\Service\AppService;

class RegistrationService extends AbstractService{
    private $accountService;
    private $appService;

    public function __construct($config, $dbAdapter, AccountService $accountService, AppService $appService){
        parent::__construct($config, $dbAdapter);
        $this->accountService = $accountService;
        $this->appService = $appService;
    }

    public function registerAccount(&$data) {
        $success = $this->accountService->registerAccount($data);
        if(isset($data['app_id'])){
            $this->appService->createAppRegistry($data['app_id'], $data['accountId']);
        }

        return $success;
    }
}