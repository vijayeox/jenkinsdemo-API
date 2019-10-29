<?php

use Oxzion\AppDelegate\AbstractAppDelegate;
use Oxzion\Db\Persistence\Persistence;

class Ratecard extends AbstractAppDelegate
{
    public function __construct(){
        parent::__construct();
    }

    // Premium Calculation values are fetched here
    public function execute(array $data,Persistence $persistenceService)
    {  
        $this->logger->info("Executing Rate Card");
        $select = "Select * FROM premium_rate_card WHERE product ='".$data['product']."' AND start_date <= '".$data['start_date']."' AND end_date >= '".$data['start_date']."'";
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
