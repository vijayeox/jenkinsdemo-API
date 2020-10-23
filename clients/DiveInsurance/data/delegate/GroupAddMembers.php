<?php

use Oxzion\AppDelegate\AbstractAppDelegate;
use Oxzion\Db\Persistence\Persistence;
use Oxzion\Utils\Country;

class GroupAddMembers extends AbstractAppDelegate
{
    public function __construct(){
        parent::__construct();
    }

    // Padi Verification is performed here
    public function execute(array $data,Persistence $persistenceService)
    {
        $this->logger->info("Padi Verification");
        if(isset($data['padi'])){
            $data['member_number'] = $data['padi'];
        }
        if(!isset($data['member_number'])){
            return;
        }
        $select = "Select firstname, MI as initial, lastname,address1,address2,state,zip,country_code,rating FROM padi_data WHERE member_number ='".$data['member_number']."'";
        $result = $persistenceService->selectQuery($select);
        if($result->count() > 0){
            $response = array();
            while ($result->next()) {
                $response[] = $result->current();
            }
            if($response[0]['firstname'] == ''){
                $returnArray['membersPadiVerified'] = false;
                $data = array_merge($data,$returnArray);
                return $data;      
            }
            $response[0]['nameOfInstitution'] = 'PADI';
            if(isset($response[0]['rating']) && $response[0]['rating'] != ''){
                if($response[0]['rating'] == 'PM' || $response[0]['firstname'] == ''){
                    $returnArray['membersPadiVerified'] = false;
                    $data = array_merge($data,$returnArray);
                    return $data;        
                }
            }else{
                $response[0]['rating'] = " ";
            }
            if(count($response) > 0){
                $response[0]['rating'] = implode(",",array_column($response, 'rating'));
            }
            $returnArray = array_merge($data,$response[0]);
            $returnArray['membersPadiVerified'] = true;
            return $returnArray;
        } else {
            $returnArray['membersPadiVerified'] = false;
            $data = array_merge($data,$returnArray);
            return $data;
        }
    }
}
