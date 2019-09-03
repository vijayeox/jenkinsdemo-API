<?php

use Oxzion\AppDelegate\AppDelegate;
use Oxzion\Db\Persistence\Persistence;

class Ratecard implements AppDelegate
{
    private $logger;
    public function setLogger($logger){
        $this->logger = $logger;
    }

    // Premium Calculation values are fetched here
    public function execute(array $data,Persistence $persistenceService)
    {  
        $this->logger->info("Executing Rate Card");
        $select = "Select * FROM premium_rate_card WHERE key ='".$data['key']."' AND start_date <= '".$data['start_date']."' AND end_date >= '".$data['start_date']."'";
        $result = $persistenceService->selectQuery($select);
        while ($result->next()) {
            $premiumRateCardDetails[] = $result->current();
        }
        return $premiumRateCardDetails;
    }
}
