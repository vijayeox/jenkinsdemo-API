<?php

use Oxzion\AppDelegate\AbstractAppDelegate;
use Oxzion\Db\Persistence\Persistence;
use Oxzion\Utils\Country;
use Oxzion\AppDelegate\UserContextTrait;

class BusinessPadiVerification extends AbstractAppDelegate
{
    use UserContextTrait;
    public function __construct(){
        parent::__construct();
    }

    // Padi Verification is performed here
    public function execute(array $data,Persistence $persistenceService)
    {
        $this->logger->info("Padi Verification new".json_encode($data)); 
        $returnArray = array();
        if(isset($data['business_padi']) && $data['business_padi'] != ''){
            $data['member_number'] = $data['business_padi'];
        }
        if(!isset($data['member_number'])){
            $data['businessPadiEmpty'] = true;
            return $data;
        }
        $select = "Select business_name FROM padi_data WHERE member_number ='".$data['member_number']."'";
        
        $result = $persistenceService->selectQuery($select);
        if($result->count() > 0){
            $response = array();
            while ($result->next()) {
                $response[] = $result->current();
            }
            unset($data['member_number']);
            $returnArray = array_merge($data,$response[0]);
            if(isset($response[0]['business_name']) && $response[0]['business_name'] != ''){
                $returnArray['businessPadiValidated'] = true;
                $returnArray['businessPadiEmpty'] = false;
            }else{
                $returnArray['businessPadiValidated'] = false;
                $returnArray['businessPadiEmpty'] = false;
            }
            return $returnArray;
        } else {
            $returnArray = array();
            unset($data['member_number']);
            $returnArray['businessPadiValidated'] = false;
            $returnArray['businessPadiEmpty'] = false;
            $data = array_merge($data,$returnArray);
            return $data;
        }
    }
}