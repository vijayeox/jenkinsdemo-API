<?php

use Oxzion\AppDelegate\AbstractAppDelegate;
use Oxzion\Db\Persistence\Persistence;
use Oxzion\Auth\AuthConstants;
use Oxzion\Auth\AuthContext;
use Oxzion\DelegateException;
use Oxzion\AppDelegate\UserContextTrait;

class GetCarrierandPolicyNumber extends AbstractAppDelegate
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
            $data = $this->getCarrierPolicyNumber($data,$persistenceService);
            return $data;
        }else{
            throw new DelegateException("You do not access to this API",'no_access');
        }
        
    }


    private function getCarrierPolicyNumber(&$data,$persistenceService){
        $data['carrier'] = array();

        $stateTax = "SELECT * FROM carrier_policy WHERE `year` = ".$data['year'];
        $carrierResult = $persistenceService->selectQuery($stateTax);
        
        if(count($carrierResult) == 0){
             $this->addNewRecord($data,$persistenceService);
        }

        $selectQuery = "SELECT product,carrier,policy_number,year FROM carrier_policy WHERE `year` = ".$data['year'];
        $result = $persistenceService->selectQuery($selectQuery);
        while ($result->next()) {
            $rate = $result->current();
            array_push($data['carrier'],$rate);
        }
        return $data['carrier'];
    }

    private function getMaxYear($data,$persistenceService){
        $yearSelect = "SELECT max(`year`) as `year` FROM carrier_policy";
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
            $query = "INSERT INTO carrier_policy (`product`,`carrier`,`policy_number`,`start_date`,`end_date`,`year`) SELECT product,carrier,policy_number,DATE_ADD(start_date, INTERVAL 1 year) as start_date,DATE_ADD(end_date, INTERVAL 1 year) as end_date,".$data['year']." as `year` FROM carrier_policy WHERE `year` = ".$year;
            $insert = $persistenceService->insertQuery($query);
            $persistenceService->commit();
        }catch(Exception $e){
            print_r($e->getMessage());
            $persistenceService->rollback();
            throw $e;
        }
       
    }
}
