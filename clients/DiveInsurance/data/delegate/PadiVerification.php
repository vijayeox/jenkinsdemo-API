<?php

use Oxzion\AppDelegate\AbstractAppDelegate;
use Oxzion\Db\Persistence\Persistence;
use Oxzion\Utils\Country;

class PadiVerification extends AbstractAppDelegate
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
        if(isset($data['business_padi'])){
            $data['member_number'] = $data['business_padi'];
        }
        if(!isset($data['member_number'])){
            $data['padi_empty'] = true;
            return $data;
        }
        if(isset($data['padi']) && !isset($data['business_padi'])){
            $select = "Select firstname, MI as initial, lastname  FROM padi_data WHERE member_number ='".$data['member_number']."'";

        }else if(isset($data['business_padi'])){
            $select = "Select business_name FROM padi_data WHERE member_number ='".$data['member_number']."'";
        }
        $result = $persistenceService->selectQuery($select);
        if($result->count() > 0){
            $response = array();
            while ($result->next()) {
                $response[] = $result->current();
            }

            $returnArray = array_merge($data,$response[0]);
            if(isset($data['padi']) && !isset($data['business_padi'])){
               $returnArray['padiVerified'] = true;
            }else if(isset($data['business_padi'])){
               if(isset($response[0]['business_name'])){
                    $returnArray['businessPadiVerified'] = true;
               }else{
                        $returnArray['businessPadiVerified'] = true;
               }
            }
            if(isset($data['product'])){
                if(($data['product'] == 'Individual Professional Liability' || $data['product'] == 'Emergency First Response' ) && (!isset($response[0]['firstname']) || $response[0]['firstname'] == '')){
                    $returnArray['padiVerified'] = false;
                }
            }
            if(isset($data['business_padi'])){
                if($data['product'] == 'Dive Store' && (!isset($response[0]['business_name']) || empty($response[0]['business_name']))){
                    $returnArray['businessPadiVerified'] = false;
                }
                if($data['product'] == 'Dive Boat' && (!isset($response[0]['business_name']) || empty($response[0]['business_name']))){
                    $returnArray['businessPadiVerified'] = false;
                }
            }
            $returnArray['padiNotFound'] = false;
            $returnArray['verified'] = true;
            $returnArray['padi_empty'] = false;
            return $returnArray;
        } else {
            if(isset($data['padi']) && !isset($data['business_padi'])){
                $returnArray['padiVerified'] = false;
            }else if(isset($data['business_padi'])){
                $returnArray['businessPadiVerified'] = false;
            }
            $returnArray['verified'] = true;
            $returnArray['padi_empty'] = false;
            $returnArray['padiNotFound'] = true;
            $data = array_merge($data,$returnArray);
            return $data;
        }
    }
}
