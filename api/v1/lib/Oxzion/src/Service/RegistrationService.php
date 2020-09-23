<?php

namespace Oxzion\Service;
use Exception;
use Oxzion\Auth\AuthConstants;
use Oxzion\Auth\AuthContext;
use Oxzion\ServiceException;
use Oxzion\Service\AbstractService;
use Oxzion\Service\OrganizationService;
use Oxzion\Service\AppService;

class RegistrationService extends AbstractService{
    private $organizationService;
    private $appService;

    public function __construct($config, $dbAdapter, OrganizationService $organizationService, AppService $appService){
        parent::__construct($config, $dbAdapter);
        $this->organizationService = $organizationService;
        $this->appService = $appService;
    }

    public function registerAccount(&$data) {
        $success = $this->organizationService->registerAccount($data);
        if(isset($data['app_id'])){
            $this->appService->createAppRegistry($data['app_id'], $data['orgId']);
        }

        return $success;
    }
}