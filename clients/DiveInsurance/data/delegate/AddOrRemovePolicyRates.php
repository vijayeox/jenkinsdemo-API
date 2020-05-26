<?php

use Oxzion\AppDelegate\AbstractAppDelegate;
use Oxzion\Db\Persistence\Persistence;
use Oxzion\Auth\AuthConstants;
use Oxzion\Auth\AuthContext;
use Oxzion\DelegateException;
use Oxzion\AppDelegate\UserContextTrait;

class AddOrRemovePolicyRates extends AbstractAppDelegate
{
    use UserContextTrait;
    public function __construct(){
        parent::__construct();
    }

    // Premium Calculation values are fetched here
    public function execute(array $data,Persistence $persistenceService)
    {  

        if(AuthContext::isPrivileged('MANAGE_ADMIN_WRITE')){
            if($data['product'] == 'Individual Professional Liability - Upgrade'){
                $is_upgrade = 1;
                $product = 'Individual Professional Liability';
            }else if($data['product'] == 'Emergency First Response - Upgrade'){
                $is_upgrade = 1;
                $product = 'Emergency First Response';
            }else if($data['product'] == 'Dive Boat - Upgrade'){
                $is_upgrade = 1;
                $product = 'Dive Boat';
            }else if($data['product'] == 'Dive Store - Upgrade'){
                $is_upgrade = 1;
                $product = 'Dive Store';
            }  
           
            if($data['type'] == 'add'){
                $data = $this->addNewPolicyRate($data,$product,$is_upgrade,$persistenceService);
            }else if($data['type'] == 'remove'){
                $data = $this->removePolicyRates($data,$product,$is_upgrade,$persistenceService);
            }
            return $data;
        }else{
            throw new DelegateException("You do not access to this API",'no_access');
        }
        
    }


    private function addNewPolicyRate($data,$product,$is_upgrade,$persistenceService){
        $previous_key = $this->getCoverageName($data['previous_key']);
        $insertQuery = "INSERT INTO premium_rate_card (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`type`,`tax`,`padi_fee`,`total`,`is_upgrade`,`previous_key`,`coverage_category`,`year`) VALUES ('".$data['product']."','".$data['coverage']."','".$data['key']."','".$data['start_date']."','".$data['end_date']."','".$data['premium']."','VALUE','".$data['tax']."','".$data['padi_fee']."','".$data['total']."',".$is_upgrade.",'".$previous_key."','".$data['coverage_category']."',".$data['year'];
        $persistenceService->insertQuery($insertQuery);
    }

    private function removePolicyRates($data,$product,$is_upgrade,$persistenceService){
        $previous_key = $this->getCoverageName($data['previous_key']);
        $deleteQuery = "DELETE FROM premium_rate_card WHERE coverage = '".$data['coverage']."' AND start_date = '".$data['start_date']."' AND end_date = '".$data['end_date']."' AND premium = '".$data['premium']."' AND tax = '".$data['tax']."' AND padi_fee = '".$data['padi_fee']."' AND previou_coverage = '".$previous_key."' AND is_upgrade = ".$is_upgrade;
        $persistenceService->deleteQuery($deleteQuery);
    }

    private function getCoverageName($previousKey){
        $selectQuery = "SELECT DISTINCT `key` from premium_rate_card WHERE coverage = '".$previousKey."'";
        $result = $persistenceService->selectQuery($selectQuery);
        while ($result->next()) {
            $previous_key = $result->current();
            $previous_key = $previous_key['key'];
        }

        return $previous_key;
    }
    
}
