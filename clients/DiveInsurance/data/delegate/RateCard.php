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
        $this->logger->info("Executing Rate Card -STart".print_r($data,true));
        $select = "Select * FROM premium_rate_card WHERE product ='".$data['product']."' AND start_date <= '".$data['start_date']."' AND is_upgrade = 0 AND end_date >= '".$data['start_date']."'";
        $result = $persistenceService->selectQuery($select);
        $this->logger->info("Rate Card query -> $select");
       
        while ($result->next()) {
            $rate = $result->current();
            if(isset($rate['key'])){
                if(isset($rate['total'])){
                    $premiumRateCardDetails[$rate['key']] = $rate['total'];
                } else {
                    if(isset($rate['tax'])){
                        $total = $rate['tax'] + $rate['premium'];
                        if(isset($rate['padi_fee'])){
                            $total = $rate['padi_fee'] + $total;
                        }
                        $premiumRateCardDetails[$rate['key']] = $total;
                    } else {
                        $premiumRateCardDetails[$rate['key']] = $rate['premium'];
                    }
                }
            }
            unset($rate);
        }

        foreach ($data as $key => $value) {
            if(is_string($value))
            {
                $result = json_decode($value, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $data[$key] = $result;
                }
            }
        }

        if($data['product'] == 'Dive Boat' || $data['product'] == 'Dive Store'){
            $premiumRateCardDetails['stateTaxData'] = $this->getStateTaxData($data,$persistenceService);
        }
            
        if(isset($data["quote_due_date"]) || isset($data['quoteRequirement'])){
            $data['quote_due_date'] = '';
            $data['quoteInfo'] = "";
            $data['quoteInfoOther'] = array();
            $data['marineX'] = isset($data['marineX']) ? "" : "";
            $data['captainX'] = isset($data['captainX']) ? "" : "";
        }
        if(isset($premiumRateCardDetails)){
            $this->logger->info("Rate Card ENd");
            $returnArray = array_merge($data,$premiumRateCardDetails);
            return $returnArray;
        } else {
            return $data;
        }
    }


    private function getStateTaxData($data,$persistenceService){
        $year = date('Y');
        if($data['product'] == 'Dive Boat'){
            $selectTax = "Select state, coverage, percentage FROM state_tax WHERE coverage = 'group' AND start_date <= '".$data['start_date']."' AND end_date >= '".$data['start_date']."' and `year` = ".$year;
        }else if($data['product'] == 'Dive Store'){
            $selectTax = "Select state, coverage, percentage FROM state_tax WHERE start_date <= '".$data['start_date']."' AND end_date >= '".$data['start_date']."' and `year` = ".$year;
        }
        $stateTaxResult = $persistenceService->selectQuery($selectTax);

        $stateTaxData = [];
        while ($stateTaxResult->next()) {
            $rate = $stateTaxResult->current();
            array_push($stateTaxData, $rate);
        }
        return $stateTaxData;
    }
}
