<?php

use Oxzion\AppDelegate\AbstractAppDelegate;
use Oxzion\Db\Persistence\Persistence;
use Oxzion\Auth\AuthConstants;
use Oxzion\Auth\AuthContext;
use Oxzion\DelegateException;
use Oxzion\AppDelegate\UserContextTrait;

class GetPremiumRates extends AbstractAppDelegate
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
            }else if($data['product'] == 'Individual Professional Liability - Endorsement'){
                $is_upgrade = 1;
                $product = 'Individual Professional Liability';
            }else if($data['product'] == 'Emergency First Response - New Policy'){
                $is_upgrade = 0;
                $product = 'Emergency First Response';
            }else if($data['product'] == 'Emergency First Response - Endorsement'){
                $is_upgrade = 1;
                $product = 'Emergency First Response';
            }else if ($data['product'] == 'Dive Boat - New Policy' || $data['product'] == 'Dive Boat - Group PL') {
                $is_upgrade = 0;
                $product = 'Dive Boat';
            } else if ($data['product'] == 'Dive Boat - Endorsement' || $data['product'] == "Dive Boat - Group PL Endorsement") {
                $is_upgrade = 1;
                $product = 'Dive Boat';
            }else if ($data['product'] == 'Dive Store - New Policy' || $data['product'] == 'Dive Store - Group PL') {
                $is_upgrade = 0;
                $product = 'Dive Store';
            } else if ($data['product'] == 'Dive Store - Endorsement' || $data['product'] == 'Dive Store - Group PL Endorsement'){
                $is_upgrade = 1;
                $product = 'Dive Store';
            }  
            if($data['year'] == ""){
                $data['year'] = $this->getMaxYear($data,$product,$is_upgrade,$persistenceService);
            }
            if($data['type'] == 'coverage') {
                $data = $this->getCoverageList($data,$product,$is_upgrade,$persistenceService);
            }else if($data['type'] == 'subcoverage'){
                $data = $this->getSubCoverageList($data,$product,$is_upgrade,$persistenceService);
            }else if($data['type'] == 'addNew'){
                $data = $this->addNewRecord($data,$product,$is_upgrade,$persistenceService);
            }
   
            return $data;
        }else{
            throw new DelegateException("You do not access to this API",'no_access');
        }
        
    }


    private function getCoverageList(&$data,$product,$is_upgrade,$persistenceService){
        $coverageList = array();
        $andClause = " ";
        if($is_upgrade == 0){
            
            if($data['product'] == 'Dive Boat - Group PL' || $data['product'] == 'Dive Store - Group PL'){
                $andClause = " AND coverage_category IN ('GROUP_COVERAGE','GROUP_EXCESS_LIABILITY') ";
            }

            $select = "SELECT DISTINCT coverage,coverage_category from premium_rate_card 
                            WHERE product = '".$product."' 
                              AND is_upgrade = 0 
                              AND `year` = ".$data['year']." ".$andClause." ORDER BY coverage";

        }else if($is_upgrade == 1){
            if($product == 'Individual Professional Liability'){
                 $andClause = " AND prc1.coverage_category NOT IN ('EXCESS_LIABILITY') ";
            }
            if($data['product'] == 'Dive Boat - Group PL Endorsement' || $data['product'] == 'Dive Store - Group PL Endorsement'){
                $andClause = " AND prc1.coverage_category IN ('GROUP_EXCESS_LIABILITY') ";
            }        

            $select = "SELECT DISTINCT prc2.coverage as coverage,prc2.coverage_category FROM premium_rate_card as prc1 
                INNER JOIN (SELECT DISTINCT `key`,coverage,coverage_category FROM premium_rate_card 
                WHERE product = '".$product."' 
                    AND `is_upgrade` = 0 
                    AND `year` = ".$data['year'].") as prc2 on  prc1.previous_key = prc2.key 
                    WHERE prc1.product = '".$product."' 
                        AND prc1.year = ".$data['year']." 
                        AND prc1.is_upgrade = 1 AND prc1.coverage_category IS NOT NULL ".$andClause." ORDER BY prc2.coverage";   
        }
        $result = $persistenceService->selectQuery($select);
        while ($result->next()) {
            $rate = $result->current();
            array_push($coverageList,$rate);
        }
        return $coverageList;
    }

    private function getSubCoverageList(&$data,$product,$is_upgrade,$persistenceService){
        $subCoverageList = array();
        $andClause = " ";
        if($is_upgrade == 0){
            if($data['product'] == 'Dive Boat - Group PL' || $data['product'] == 'Dive Store - Group PL'){
                $andClause = " AND coverage_category IN ('GROUP_COVERAGE','GROUP_EXCESS_LIABILITY') ";
            }

            $select = "SELECT id,coverage,CASE 
                        WHEN DAY(start_date) = '30' AND MONTH(start_date) = 6 AND product = 'Individual Professional Liability' 
                        THEN 'July' 
                        ELSE MONTHNAME(start_date) END as `month`,start_date,end_date,premium,tax,padi_fee,total,year,coverage_category 
                        FROM premium_rate_card WHERE product = '".$product."' 
                        AND `year` = '".$data['year']."' 
                        AND `is_upgrade` = 0 
                        AND coverage = '".$data['coverage']."' ".$andClause." ORDER BY coverage";       
        }else if($is_upgrade == 1){
            if($data['product'] == 'Dive Boat - Group PL Endorsement' || $data['product'] == 'Dive Store - Group PL Endorsement'){
                $andClause = " AND coverage_category IN ('GROUP_EXCESS_LIABILITY') ";
            }  
            $coverage = $this->getCoverageName($data['coverage'], $persistenceService);
            $select = "SELECT id,coverage,`key`,CASE WHEN DAY(start_date) = '30' AND MONTH(start_date) = 6 AND product = 'Individual Professional Liability' THEN
                'July' ELSE MONTHNAME(start_date) END as `month`,start_date,end_date,premium,tax,padi_fee,coverage_category FROM premium_rate_card 
                 WHERE product = '".$product."' and `year` = '".$data['year']."' and is_upgrade = 1 AND previous_key = '".$coverage."' ".$andClause." ORDER BY coverage";     
        }
        $result = $persistenceService->selectQuery($select);
        while ($result->next()) {
            $rate = $result->current();
            array_push($subCoverageList,$rate);
        }
        return $subCoverageList;
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
            $persistenceService->insertQuery($query);
            $persistenceService->commit();
        }catch(Exception $e){
            print_r($e->getMessage());
            $persistenceService->rollback();
            throw $e;
        }
       
    }

   
    private function getCoverageName($previousKey, $persistenceService)
    {
        $selectQuery = "SELECT `key` from premium_rate_card WHERE coverage = '" . $previousKey . "'";
        $result = $persistenceService->selectQuery($selectQuery);
        while ($result->next()) {
            $previous_key = $result->current();
            $previous_key = $previous_key['key'];
        }
        return $previous_key;
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
}
