<?php

use Oxzion\AppDelegate\AbstractAppDelegate;
use Oxzion\Db\Persistence\Persistence;
use Oxzion\Auth\AuthConstants;
use Oxzion\Auth\AuthContext;
use Oxzion\DelegateException;
use Oxzion\AppDelegate\UserContextTrait;

class UpdateCarrierandPolicyNumber extends AbstractAppDelegate
{
    use UserContextTrait;
    public function __construct(){
        parent::__construct();
    }

    // State Tax values are fetched here
    public function execute(array $data,Persistence $persistenceService)
    {  
        if(AuthContext::isPrivileged('MANAGE_ADMIN_WRITE')){ 
            $data = $this->updateCarrierPolicyNumber($data,$persistenceService);
            return $data;
        }else{
            throw new DelegateException("You do not access to this API",'no_access');
        }
    }


    private function updateCarrierPolicyNumber(&$data,$persistenceService){
        $updateQuery = "UPDATE carrier_policy SET carrier = '".$data['carrier']."',policy_number = '".$data['policy_number']."' WHERE id = ".$data['id'];
        $result = $persistenceService->updateQuery($updateQuery);
    }

}
