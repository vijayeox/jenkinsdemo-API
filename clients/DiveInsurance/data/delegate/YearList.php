<?php

use Oxzion\AppDelegate\AbstractAppDelegate;
use Oxzion\Db\Persistence\Persistence;

class YearList extends AbstractAppDelegate
{
    public function __construct(){
        parent::__construct();
    }

    // Premium Calculation values are fetched here
    public function execute(array $data,Persistence $persistenceService)
    {  
        $yearList = array();
        if($data['type'] == 'rates'){
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
            $selectQuery  = "SELECT DISTINCT year from premium_rate_card WHERE product = '".$product."' AND is_upgrade = ".$is_upgrade;
        }else if($data['type'] == 'stateTax'){
            $selectQuery  = "SELECT DISTINCT year from state_tax WHERE coverage = '".$data['coverage']."'";
        }else if($data['type'] == 'carrierpolicy'){
            $selectQuery  = "SELECT DISTINCT year from carrier_policy";
        }
        
        $result = $persistenceService->selectQuery($selectQuery);
        while ($result->next()) {
            $year = $result->current();
            array_push($yearList,$year['year']);
        }

        return $yearList;

    }

}
