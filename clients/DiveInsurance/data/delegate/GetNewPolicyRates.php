<?php

use Oxzion\AppDelegate\AbstractAppDelegate;
use Oxzion\Db\Persistence\Persistence;
use Oxzion\Auth\AuthConstants;
use Oxzion\Auth\AuthContext;
use Oxzion\DelegateException;
use Oxzion\AppDelegate\UserContextTrait;

class GetNewPolicyRates extends AbstractAppDelegate
{
    use UserContextTrait;
    public function __construct(){
        parent::__construct();
    }

    // Premium Calculation values are fetched here
    public function execute(array $data,Persistence $persistenceService)
    {  
        if(AuthContext::isPrivileged('MANAGE_ADMIN_WRITE')){
            if($data['product'] == 'Individual Professional Liability - New Policy'){
                $product = 'Individual Professional Liability';
            }else if($data['product'] == 'Emergency First Response - New Policy'){
                $product = 'Emergency First Response';
            }else if($data['product'] == 'Dive Boat - New Policy'){
                $product = 'Dive Boat';
            }else if($data['product'] == 'Dive Store - New Policy'){
                $product = 'Dive Store';
            }  
            if($data['year'] == ""){
                $data['year'] = $this->getMaxYear($data,$product,$persistenceService);
            }
            $data = $this->getRates($data,$product,$persistenceService);
            return $data;
        }else{
            throw new DelegateException("You do not access to this API",'no_access');
        }
        
    }


    private function getRates(&$data,$product,$persistenceService){
        $data['rates'] = array();

        $yearSelect = "SELECT * FROM premium_rate_card WHERE product = '".$product."' and `year` = ".$data['year']." and `is_upgrade` = 0";
        $yearResult = $persistenceService->selectQuery($yearSelect);
        
        if(count($yearResult) == 0){
             $this->addNewRecord($data,$product,$persistenceService);
        }

        $selectQuery = "SELECT coverage,CASE WHEN DAY(start_date) = '30' AND MONTH(start_date) = 6 AND product = 'Individual Professional Liability' THEN
                'July' ELSE 
                MONTHNAME(start_date) END as `month`,start_date,end_date,premium,tax,padi_fee,total FROM premium_rate_card WHERE product = '".$product."' and `year` = ".$data['year']." and `is_upgrade` = 0";
        $result = $persistenceService->selectQuery($selectQuery);
        while ($result->next()) {
            $rate = $result->current();
            array_push($data['rates'],$rate);
        }
        return $data['rates'];
    }

    private function getMaxYear($data,$product,$persistenceService){
        $yearSelect = "SELECT max(`year`) as `year` FROM premium_rate_card WHERE product = '".$product."' and `is_upgrade` = 0";
        $result = $persistenceService->selectQuery($yearSelect);
        while ($result->next()) {
            $year = $result->current();
            $maxYear = $year['year'];
        }
        return $maxYear;
    }

    private function addNewRecord($data,$product,$persistenceService){
        $year = $this->getMaxYear($data,$product,$persistenceService);
        $persistenceService->beginTransaction();
        try{
            $query = "INSERT INTO premium_rate_card (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`type`,`tax`,`padi_fee`,`total`,`is_upgrade`,`coverage_category`,`year`) SELECT product,coverage,`key`,DATE_ADD(start_date, INTERVAL 1 year) as start_date,CASE WHEN ((YEAR(end_date) + 1) % 4 = 0 AND MONTH(end_date) = 2) THEN 
                                DATE_ADD(DATE_ADD(end_date, INTERVAL 1 YEAR),INTERVAL 1 dAY)
                            ELSE
                                DATE_ADD(end_date, INTERVAL 1 year) 
                            END as end_date,premium,`type`,tax,padi_fee,total,is_upgrade,coverage_category,".$data['year']." as `year` FROM premium_rate_card WHERE product = '".$product."' and `year` = ".$year." and `is_upgrade` = 0";
            $insert = $persistenceService->insertQuery($query);
            $persistenceService->commit();
        }catch(Exception $e){
            print_r($e->getMessage());
            $persistenceService->rollback();
            throw $e;
        }
       
    }
}
