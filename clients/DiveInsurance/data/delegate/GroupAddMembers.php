<?php

use Oxzion\AppDelegate\AbstractAppDelegate;
use Oxzion\Db\Persistence\Persistence;
use Oxzion\Utils\Country;

class GroupAddMembers extends AbstractAppDelegate
{
    public function __construct(){
        parent::__construct();
        $this->status = array('OWSI' => 'Instructor','MI' => 'Instructor','AL' => 'Instructor','EFR' => 'Instructor','MSDT' => 'Instructor','UI' => 'Instructor','DM' => 'Dive Master','AI' => 'Assistant Instructor','AIN' => 'Assistant Instructor','LFSI' => 'Swim Instructor','FDIC' => 'Freedive Instructor');
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
        $select = "Select firstname, MI as initial, lastname,insurance_type as status FROM padi_data WHERE member_number ='".$data['member_number']."'";
        $result = $persistenceService->selectQuery($select);
        if($result->count() > 0){
            $response = array();
            while ($result->next()) {
                $response[] = $result->current();
            }
            $response[0]['nameOfInstitution'] = 'PADI';
            if(isset($response[0]['status']) && $response[0]['status'] != ''){
                if($response[0]['status'] == 'PM'){
                    $returnArray['padi_Verified'] = false;
                    $data = array_merge($data,$returnArray);
                    return $data;        
                }else if(array_key_exists($response[0]['status'], $this->status)){
                    $response[0]['status'] = $this->status[$response[0]['status']];    
                }else{
                    $response[0]['status'] = " ";
                }
            }else{
                $response[0]['status'] = " ";
            }
            $returnArray = array_merge($data,$response[0]);
            $returnArray['padi_Verified'] = true;
            return $returnArray;
        } else {
            $returnArray['padi_Verified'] = false;
            $data = array_merge($data,$returnArray);
            return $data;
        }
    }
}
