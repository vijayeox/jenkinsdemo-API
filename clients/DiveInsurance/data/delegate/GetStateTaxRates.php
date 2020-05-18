<?php

use Oxzion\AppDelegate\AbstractAppDelegate;
use Oxzion\Db\Persistence\Persistence;
use Oxzion\Auth\AuthConstants;
use Oxzion\Auth\AuthContext;
use Oxzion\DelegateException;
use Oxzion\AppDelegate\UserContextTrait;

class GetStateTaxRates extends AbstractAppDelegate
{
    use UserContextTrait;
    public function __construct(){
        parent::__construct();
    }

    // State Tax values are fetched here
    public function execute(array $data,Persistence $persistenceService)
    {  
        if(AuthContext::isPrivileged('MANAGE_ADMIN_WRITE')){ 
            if($data['year'] == ""){
                $data['year'] = $this->getMaxYear($data,$persistenceService);
            }
            $data = $this->getStateTaxRates($data,$persistenceService);
            return $data;
        }else{
            throw new DelegateException("You do not access to this API",'no_access');
        }
        
    }


    private function getStateTaxRates(&$data,$persistenceService){
        $data['stateTax'] = array();

        $stateTax = "SELECT * FROM state_tax WHERE `year` = ".$data['year']." and `coverage` = '".$data['coverage']."'";
        $stateTaxResult = $persistenceService->selectQuery($stateTax);
        
        if(count($stateTaxResult) == 0){
             $this->addNewRecord($data,$persistenceService);
        }

        $selectQuery = "SELECT CONCAT(UCASE(MID(coverage,1,1)),MID(coverage,2)) as coverage,state,percentage,year FROM state_tax WHERE `year` = ".$data['year']." and `coverage` = '".$data['coverage']."'";
        $result = $persistenceService->selectQuery($selectQuery);
        while ($result->next()) {
            $rate = $result->current();
            array_push($data['stateTax'],$rate);
        }
        return $data['stateTax'];
    }

    private function getMaxYear($data,$persistenceService){
        $yearSelect = "SELECT max(`year`) as `year` FROM state_tax WHERE coverage = '".$data['coverage']."'";
        $result = $persistenceService->selectQuery($yearSelect);
        while ($result->next()) {
            $year = $result->current();
            $maxYear = $year['year'];
        }
        return $maxYear;
    }

    private function addNewRecord($data,$persistenceService){
        $year = $this->getMaxYear($data,$persistenceService);
        $persistenceService->beginTransaction();
        try{
            $query = "INSERT INTO state_tax (`state`,`coverage`,`percentage`,`start_date`,`end_date`,`year`) SELECT state,coverage,percentage,DATE_ADD(start_date, INTERVAL 1 year) as start_date,DATE_ADD(end_date, INTERVAL 1 year) as end_date,".$data['year']." as `year` FROM state_tax WHERE `year` = ".$year." and coverage = '".$data['coverage']."'";
            $insert = $persistenceService->insertQuery($query);
            $persistenceService->commit();
        }catch(Exception $e){
            print_r($e->getMessage());
            $persistenceService->rollback();
            throw $e;
        }
       
    }
}
