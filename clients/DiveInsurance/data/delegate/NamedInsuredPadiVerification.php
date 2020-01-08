<?php

use Oxzion\AppDelegate\AbstractAppDelegate;
use Oxzion\Db\Persistence\Persistence;
use Oxzion\Utils\Country;

class NamedInsuredPadiVerification extends AbstractAppDelegate
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
        $select = "Select firstname, MI as initial, lastname,insurance_type as status FROM padi_data WHERE member_number ='".$data['member_number']."'";
        $result = $persistenceService->selectQuery($select);
        if($result->count() > 0){
            $response = array();
            while ($result->next()) {
                $response[] = $result->current();
            }
            $response[0]['name'] = $response[0]['initial']." ".$response[0]['firstname'] ." ".$response[0]['lastname'];
            $returnArray = array_merge($data,$response[0]);
            $returnArray['Verified'] = true;
            return $returnArray;
        } else {
            $returnArray['Verified'] = false;
            $data = array_merge($data,$returnArray);
            return $data;
        }
    }
}
