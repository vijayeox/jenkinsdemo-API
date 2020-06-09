<?php

use Oxzion\AppDelegate\AbstractAppDelegate;
use Oxzion\Db\Persistence\Persistence;
use Oxzion\Auth\AuthConstants;
use Oxzion\Auth\AuthContext;
use Oxzion\DelegateException;
use Oxzion\AppDelegate\UserContextTrait;

class UpdatePolicyRates extends AbstractAppDelegate
{
    use UserContextTrait;
    public function __construct(){
        parent::__construct();
    }

    // Premium Calculation values are fetched here
    public function execute(array $data,Persistence $persistenceService)
    {  

        if(AuthContext::isPrivileged('MANAGE_ADMIN_WRITE')){
            $data = $this->updatePolicyRates($data,$persistenceService);
            return $data;
        }else{
            throw new DelegateException("You do not access to this API",'no_access');
        }
        
    }
    private function updatePolicyRates($data,$persistenceService){
        $data['padi_fee'] = isset($data['padi_fee']) ? $data['padi_fee'] : 0;
        $data['premium'] = isset($data['premium']) ? $data['premium'] : 0;
        $data['tax'] = isset($data['tax']) ? $data['tax'] : 0;
        $total = ((float)$data['premium'] || 0) + ((float)$data['tax'] || 0) + ((float)$data['padi_fee'] || 0);
        $updateQuery = "UPDATE premium_rate_card SET `premium` = ".$data['premium'].",`tax` = ".$data['tax'].", padi_fee = ".$data['padi_fee'].",total = ".$total." WHERE id = ".$data['id'];
        $result = $persistenceService->updateQuery($updateQuery);
    }
}