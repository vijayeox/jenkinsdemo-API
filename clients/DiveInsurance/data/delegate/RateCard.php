<?php

use Oxzion\AppDelegate\AbstractAppDelegate;
use Oxzion\Db\Persistence\Persistence;

class Ratecard extends AbstractAppDelegate
{
    public function __construct()
    {
        parent::__construct();
    }

    // Premium Calculation values are fetched here
    public function execute(array $data, Persistence $persistenceService)
    {
        $this->logger->info("Executing Rate Card -STart" . print_r($data, true));
        $data['start_date'] = ($data['start_date'] == "null") ? date("Y-m-d") : $data['start_date'];
        $operation = " <= ";
        $select = "Select * FROM premium_rate_card WHERE product ='" . $data['product'] . "' AND start_date " . $operation . " '" . $data['start_date'] . "' AND is_upgrade = 0 AND end_date >= '" . $data['start_date'] . "'";
        $result = $persistenceService->selectQuery($select);
        $this->logger->info("Rate Card query -> $select");
        while ($result->next()) {
            $rate = $result->current();
            if (isset($rate['key'])) {
                if (isset($rate['total'])) {
                    $premiumRateCardDetails[$rate['key']] = $rate['total'];
                } else {
                    if (isset($rate['tax'])) {
                        $total = $rate['tax'] + $rate['premium'];
                        if (isset($rate['padi_fee'])) {
                            $total = $rate['padi_fee'] + $total;
                        }
                        $premiumRateCardDetails[$rate['key']] = $total;
                    } else {
                        $premiumRateCardDetails[$rate['key']] = $rate['premium'];
                    }
                }
                if (isset($rate['downpayment'])) {
                    $premiumRateCardDetails[$rate['key'] . "_downpayment"] = $rate['downpayment'];
                } else {
                    $premiumRateCardDetails[$rate['key'] . "_downpayment"] = 0;
                }
                if (isset($rate['installment_count'])) {
                    $premiumRateCardDetails[$rate['key'] . "_installments"] = $rate['installment_count'];
                } else {
                    $premiumRateCardDetails[$rate['key'] . "_installments"] = 0;
                }
                if (isset($rate['installment_amount'])) {
                    $premiumRateCardDetails[$rate['key'] . "_installment_amount"] = $rate['installment_amount'];
                } else {
                    $premiumRateCardDetails[$rate['key'] . "_installment_amount"] = 0;
                }
            }
            unset($rate);
        }

        if ($data['product'] == "Individual Professional Liability") {
            $this->getCoverageOptions($data, $persistenceService);
        }
        foreach ($data as $key => $value) {
            if (is_string($value)) {
                $result = json_decode($value, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $data[$key] = $result;
                }
            }
        }

        if ($data['product'] == 'Dive Boat' || $data['product'] == 'Dive Store' || $data['product'] == 'Group Professional Liability') {
            $premiumRateCardDetails['stateTaxData'] = $this->getStateTaxData($data, $persistenceService);
        }

        if (isset($data["quote_due_date"]) || isset($data['quoteRequirement'])) {
            $data['quote_due_date'] = '';
            $data['quoteInfo'] = "";
            $data['quoteInfoOther'] = array();
            $data['marineX'] = isset($data['marineX']) ? "" : "";
            $data['captainX'] = isset($data['captainX']) ? "" : "";
        }
        if (isset($premiumRateCardDetails)) {
            $this->logger->info("Rate Card ENd");
            $returnArray = array_merge($data, $premiumRateCardDetails);
            $this->logger->info("Rate Card Response : updated".print_r($returnArray,true));
            return $returnArray;
        } else {
            $this->logger->info("Rate Card Response : Not updated".print_r($data,true));
            return $data;
        }
    }


    private function getStateTaxData($data, $persistenceService)
    {
        $year = date('Y');
        if ($data['product'] == 'Dive Boat') {
            $selectTax = "Select state, coverage, percentage FROM state_tax WHERE coverage = 'group' and `year` = " . $year;
        } else if ($data['product'] == 'Dive Store' || $data['product'] == 'Group Professional Liability') {
            $selectTax = "Select state, coverage, percentage FROM state_tax WHERE `year` = " . $year;
        }
        $stateTaxResult = $persistenceService->selectQuery($selectTax);

        $stateTaxData = [];
        while ($stateTaxResult->next()) {
            $rate = $stateTaxResult->current();
            array_push($stateTaxData, $rate);
        }
        return $stateTaxData;
    }

    private function getCoverageOptions(&$data, $persistenceService)
    {
        if (!isset($data['padi'])) {
            return $data;
        } else if ($data['padi'] == "") {
            return $data;
        }
        $coverageOptions = array();
        $select = "Select firstname, MI as initial, lastname, business_name,rating FROM padi_data WHERE member_number ='" . $data['padi'] . "'";
        $result = $persistenceService->selectQuery($select);
        if ($result->count() > 0) {
            $response = array();
            while ($result->next()) {
                $response[] = $result->current();
            }
            $ratingApplicable = implode('","', array_column($response, 'rating'));
            $returnArray['rating'] = $ratingApplicable;
            $coverageSelect = 'Select DISTINCT coverage_level,coverage_name FROM coverage_options WHERE padi_rating  in ("' . $ratingApplicable . '") and category IS NULL';
            $this->logger->info("coverage select" . $coverageSelect);
            $coverageLevels = $persistenceService->selectQuery($coverageSelect);
            if ($coverageLevels->count() > 0) {
                while ($coverageLevels->next()) {
                    $coverage = $coverageLevels->current();
                    $coverageOptions[0][] = array('label' => $coverage['coverage_name'], 'value' => $coverage['coverage_level']);
                }
            }
        }
        $coverageSelect = "Select DISTINCT coverage_name,coverage_level FROM coverage_options WHERE category IS NULL";
        $coverageLevels = $persistenceService->selectQuery($coverageSelect);
        while ($coverageLevels->next()) {
            $coverage = $coverageLevels->current();
            $coverageOptions[1][] = array('label' => $coverage['coverage_name'], 'value' => $coverage['coverage_level']);
        }
        if(empty($coverageOptions[0])){
            $coverageOptions[0] = $coverageOptions[1];
        }
        unset($data['careerCoverageOptions']);
        $data['careerCoverageOptions'] = $coverageOptions;
    }
}
