<?php

use Oxzion\AppDelegate\AbstractAppDelegate;
use Oxzion\Db\Persistence\Persistence;
use Oxzion\Auth\AuthConstants;
use Oxzion\Auth\AuthContext;
use Oxzion\DelegateException;
use Oxzion\AppDelegate\UserContextTrait;

class GetPolicyRates extends AbstractAppDelegate
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
            if($data['year'] == ""){
                $data['year'] = $this->getMaxYear($data,$product,$is_upgrade,$persistenceService);
            }
            $data = $this->getRates($data,$product,$is_upgrade,$persistenceService);

            return $data;
        }else{
            throw new DelegateException("You do not access to this API",'no_access');
        }
        
    }


    private function getRates(&$data,$product,$is_upgrade,$persistenceService){
        $data['rates'] = array();
        $yearResult = $this->checkRecordExists($product,$is_upgrade,$data['year'],$persistenceService);

        if(count($yearResult) == 0){
             $this->addNewRecord($data,$product,$is_upgrade,$persistenceService);
        }

        if($is_upgrade == 0){
            $selectQuery = "SELECT id,coverage,CASE WHEN DAY(start_date) = '30' AND MONTH(start_date) = 6 AND product = 'Individual Professional Liability' THEN
                'July' ELSE 
                MONTHNAME(start_date) END as `month`,start_date,end_date,premium,tax,padi_fee,total,year FROM premium_rate_card WHERE product = '".$product."' and `year` = ".$data['year']." and `is_upgrade` = 0";
        }else if($is_upgrade == 1){
            $selectQuery = "SELECT prc1.id,prc1.coverage,CASE WHEN DAY(prc1.start_date) = '30' AND MONTH(prc1.start_date) = 6 AND prc1.product = '".$product."' THEN
                'July' ELSE 
                MONTHNAME(prc1.start_date) END as `month`,prc1.start_date,prc1.end_date,prc1.premium,prc1.tax,prc1.padi_fee,prc1.total,prc2.coverage as previous_coverage,prc1.year FROM premium_rate_card as prc1 INNER JOIN (SELECT DISTINCT `key`,coverage FROM premium_rate_card WHERE product = '".$product."' AND `is_upgrade` = 0 AND `year` = ".$data['year'].") as prc2 on  prc1.previous_key = prc2.key WHERE prc1.product = '".$product."' and prc1.year = ".$data['year']." and prc1.is_upgrade = 1  ORDER BY prc1.coverage";
        }else{
            return $data['rates'];
        }
        $result = $persistenceService->selectQuery($selectQuery);
        while ($result->next()) {
            $rate = $result->current();
            array_push($data['rates'],$rate);
        }
        return $data['rates'];
    }

    private function getMaxYear($data,$product,$is_upgrade,$persistenceService){
        $yearSelect = "SELECT max(`year`) as `year` FROM premium_rate_card WHERE product = '".$product."' and `is_upgrade` = ".$is_upgrade;
        $result = $persistenceService->selectQuery($yearSelect);
        while ($result->next()) {
            $year = $result->current();
            $maxYear = $year['year'];
        }
        return $maxYear;
    }

    private function addNewRecord($data,$product,$is_upgrade,$persistenceService){
        $year = $this->getMaxYear($data,$product,$is_upgrade,$persistenceService);
        $persistenceService->beginTransaction();
        try{
            $query = "INSERT INTO premium_rate_card (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`type`,`tax`,`padi_fee`,`total`,`is_upgrade`,`previous_key`,`coverage_category`,`year`) SELECT product,coverage,`key`,DATE_ADD(start_date, INTERVAL 1 year) as start_date,CASE WHEN ((YEAR(end_date) + 1) % 4 = 0 AND MONTH(end_date) = 2) THEN 
                                DATE_ADD(DATE_ADD(end_date, INTERVAL 1 YEAR),INTERVAL 1 dAY)
                            ELSE
                                DATE_ADD(end_date, INTERVAL 1 year) 
                            END as end_date,premium,`type`,tax,padi_fee,total,is_upgrade,previous_key,coverage_category,".$data['year']." as `year` FROM premium_rate_card WHERE product = '".$product."' and `year` = ".$year." and `is_upgrade` = ".$is_upgrade;
            $insert = $persistenceService->insertQuery($query);
            $persistenceService->commit();
        }catch(Exception $e){
            print_r($e->getMessage());
            $persistenceService->rollback();
            throw $e;
        }
       
    }

    private function checkRecordExists($product,$is_upgrade,$year,$persistenceService){
      $yearSelect = "SELECT * FROM premium_rate_card WHERE product = '".$product."' and `year` = ".$year." and `is_upgrade` = ".$is_upgrade;
      $yearResult = $persistenceService->selectQuery($yearSelect);  
      return $yearResult;
    }
}
