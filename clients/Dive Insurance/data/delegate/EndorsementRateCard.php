<?php

use Oxzion\AppDelegate\AbstractAppDelegate;
use Oxzion\Db\Persistence\Persistence;

class EndorsementRatecard extends AbstractAppDelegate
{
    public function __construct(){
        parent::__construct();
    }

    public function execute(array $data,Persistence $persistenceService)
    {
        $this->logger->info("Executing Endorsement Rate Card");
        if(!isset($data['previous_careerCoverage'])){
            $data['previous_careerCoverage'] = $data['careerCoverage'];
        }
        $select = "Select * FROM premium_rate_card WHERE product ='".$data['product']."' AND is_upgrade = 1 AND previous_key = '".$data['previous_careerCoverage']."' AND start_date <= '".$data['update_date']."'";
        $result = $persistenceService->selectQuery($select);
        while ($result->next()) {
            $rate = $result->current();
            if(isset($rate['key'])){
                if(isset($rate['total'])){
                    $premiumRateCardDetails[$rate['key']] = $rate['total'];
                } else {
                    $premiumRateCardDetails[$rate['key']] = $rate['premium'];
                }
            }
            unset($rate);
        }
        if(isset($premiumRateCardDetails)){
            $returnArray = array_merge($data,$premiumRateCardDetails);
            return $returnArray;
        } else {
            return $data;
        }
    }
}
