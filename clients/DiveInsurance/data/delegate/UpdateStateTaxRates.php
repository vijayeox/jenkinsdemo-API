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


    //PARAMETERs
    // {
    // "year": 2020,
    // "state": "Alabama",
    // "coverage": "liability",
    // "percentage": 2
    // }

    
    // State Tax values are fetched here
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
        $updateQuery = "UPDATE state_tax SET percentage = ".$data['percentage']." WHERE `year` = ".$data['year']." and `coverage` = '".$data['coverage']."' AND state = '".$data['state']."'";
        $result = $persistenceService->updateQuery($updateQuery);
    }

}
