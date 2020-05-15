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


        //PARAMETER

//         {
//     "year": 2019,
//     "product": "Individual Professional Liability - Upgrade",
//     "start_date": "2019-09-01",
//     "end_date": "2019-09-30",
//     "premium": 600.00,
//     "tax": 40.00,
//     "padi_fee": 12.0,
//     "coverage": "Instructor",
//     "previous_coverage": "Dive Master"
// }


        
        if(AuthContext::isPrivileged('MANAGE_ADMIN_WRITE')){
             if($data['product'] == 'Individual Professional Liability - New Policy'){
                $is_upgrade = 0;
                $product = 'Individual Professional Liability';
            }else if($data['product'] == 'Individual Professional Liability - Upgrade'){
                $is_upgrade = 1;
                $product = 'Individual Professional Liability';
            }else if($data['product'] == 'Emergency First Response - New Policy'){
                $is_upgrade = 0;
                $product = 'Emergency First Response';
            }else if($data['product'] == 'Emergency First Response - Upgrade'){
                $is_upgrade = 1;
                $product = 'Emergency First Response';
            }else if($data['product'] == 'Dive Boat - New Policy'){
                $is_upgrade = 0;
                $product = 'Dive Boat';
            }else if($data['product'] == 'Dive Boat - Upgrade'){
                $is_upgrade = 1;
                $product = 'Dive Boat';
            }else if($data['product'] == 'Dive Store - New Policy'){
                $is_upgrade = 0;
                $product = 'Dive Store';
            }else if($data['product'] == 'Dive Store - Upgrade'){
                $is_upgrade = 1;
                $product = 'Dive Store';
            }  
           
            if($is_upgrade == 0){
                $data = $this->updateNewPolicyRates($data,$product,$is_upgrade,$persistenceService);
            }else if($is_upgrade == 1){
                $data = $this->updateUpgradePolicyRates($data,$product,$is_upgrade,$persistenceService);
            }
            return $data;
        }else{
            throw new DelegateException("You do not access to this API",'no_access');
        }
        
    }


    private function updateNewPolicyRates($data,$product,$is_upgrade,$persistenceService){
        $total = (float)$data['premium'] + (float)$data['tax'] + (float)$data['padi_fee'];
        $updateQuery = "UPDATE premium_rate_card SET `premium` = ".$data['premium'].",`tax` = ".$data['tax'].", padi_fee = ".$data['padi_fee'].",total = ".$total." WHERE coverage = '".$data['coverage']."' AND product = '".$product."' AND start_date = '".$data['start_date']."' AND end_date = '".$data['end_date']."' AND `is_upgrade` = ".$is_upgrade." AND `year` = ".$data['year'];
        $result = $persistenceService->updateQuery($updateQuery);
    }



    private function updateUpgradePolicyRates($data,$product,$is_upgrade,$persistenceService){
         $total = (float)$data['premium'] + (float)$data['tax'] + (float)$data['padi_fee'];
         $selectQuery = "SELECT DISTINCT `key` from premium_rate_card WHERE coverage = '".$data['previous_coverage']."'";
         $result = $persistenceService->selectQuery($selectQuery);
         while ($result->next()) {
            $previous_key = $result->current();
            $previous_key = $previous_key['key'];
         }
         $updateQuery = "UPDATE premium_rate_card SET premium = ".$data['premium'].",tax = ".$data['tax'].",padi_fee = ".$data['padi_fee'].",total = ".$total." WHERE coverage = '".$data['coverage']."' AND previous_key = '".$previous_key."' AND product = '".$product."' AND start_date = '".$data['start_date']."' AND end_date = '".$data['end_date']."' AND is_upgrade = ".$is_upgrade." AND `year` = ".$data['year'];
         $result = $persistenceService->updateQuery($updateQuery);
    }
    
}
