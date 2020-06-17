<?php

use Oxzion\AppDelegate\AbstractAppDelegate;
use Oxzion\Db\Persistence\Persistence;
use Oxzion\Auth\AuthConstants;
use Oxzion\Auth\AuthContext;
use Oxzion\DelegateException;
use Oxzion\AppDelegate\UserContextTrait;

class UpdateStateTaxRates extends AbstractAppDelegate
{
    use UserContextTrait;
    public function __construct(){
        parent::__construct();
    }

    public function execute(array $data,Persistence $persistenceService)
    {  
        if(AuthContext::isPrivileged('MANAGE_ADMIN_WRITE')){ 
            $data = $this->updateStateTaxRates($data,$persistenceService);
            return $data;
        }else{
            throw new DelegateException("You do not access to this API",'no_access');
        }
        
    }

    private function updateStateTaxRates(&$data,$persistenceService){
        $updateQuery = "UPDATE state_tax SET percentage = ".$data['percentage']." WHERE `id` = ".$data['id'];
        $persistenceService->updateQuery($updateQuery);
    }

}
