<?php

use Oxzion\Db\Persistence\Persistence;
use Oxzion\AppDelegate\TemplateAppDelegate;
use Oxzion\Auth\AuthConstants;
use Oxzion\Auth\AuthContext;


class YearList extends TemplateAppDelegate
{
    public function __construct()
    {
        parent::__construct();
    }

    // Premium Calculation values are fetched here
    public function execute(array $data, Persistence $persistenceService)
    {
        $yearList = array();
        if (isset($data['product'])) {
            if ($data['product'] == 'Individual Professional Liability - New Policy') {
                $is_upgrade = 0;
                $product = 'Individual Professional Liability';
            } else if ($data['product'] == 'Individual Professional Liability - Endorsement') {
                $is_upgrade = 1;
                $product = 'Individual Professional Liability';
            } else if ($data['product'] == 'Emergency First Response - New Policy') {
                $is_upgrade = 0;
                $product = 'Emergency First Response';
            } else if ($data['product'] == 'Emergency First Response - Endorsement') {
                $is_upgrade = 1;
                $product = 'Emergency First Response';
            } else if ($data['product'] == 'Dive Boat - New Policy' || $data['product'] == 'Dive Boat - Group PL') {
                $is_upgrade = 0;
                $product = 'Dive Boat';
            } else if ($data['product'] == 'Dive Boat - Endorsement' || $data['product'] == "Dive Boat - Group PL Endorsement") {
                $is_upgrade = 1;
                $product = 'Dive Boat';
            } else if ($data['product'] == 'Dive Store - New Policy' || $data['product'] == 'Dive Store - Group PL') {
                $is_upgrade = 0;
                $product = 'Dive Store';
            } else if ($data['product'] == 'Dive Store - Endorsement' || $data['product'] == 'Dive Store - Group PL Endorsement'){
                $is_upgrade = 1;
                $product = 'Dive Store';
            } else {
                $is_upgrade = 0;
                $product = $data['product'];
            }
            if ($data['type'] == 'PremiumRates') {
                $andClause1 = " ";
                if($data['product'] == 'Dive Boat - Group PL' || $data['product'] == 'Dive Store - Group PL'){
                    $andClause1  = " AND coverage_category IN ('GROUP_COVERAGE','GROUP_EXCESS_LIABILITY') ";
                }
                if($data['product'] == 'Dive Boat - Group PL Endorsement' || $data['product'] == 'Dive Store - Group PL Endorsement'){
                    $andClause1 = " AND coverage_category IN ('GROUP_EXCESS_LIABILITY')";
                }

                $selectQuery  = "SELECT DISTINCT year from premium_rate_card WHERE 
                                product = '" . $product . "' AND
                                 is_upgrade = " . $is_upgrade." ".$andClause1;
            } else if ($data['type'] == 'SurplusLines') {
                if($product == 'Individual Professional Liability'){
                    $product = 'IPL';
                }else if($product == 'Emergency First Response'){
                    $product = 'EFR';
                }else if($product == 'Dive Boat'){
                    $product = 'DiveBoat';
                }else if($product == 'Dive Store'){
                    $product = 'DiveStore';
                }else if($product == 'Group Professional Liability'){
                    $product = 'Group';
                } 
                $destinationPath = $this->destination . AuthContext::get(AuthConstants::ORG_UUID) . '/SurplusLines/' . $product;
                $yearList = scandir($destinationPath);
                array_shift($yearList);
                array_shift($yearList);
                return $yearList;
            }else if ($data['type'] == 'CarrierPolicy') {
                $selectQuery  = "SELECT DISTINCT year from carrier_policy WHERE product = '" . $product . "'";
            }
        } else {
            if ($data['type'] == 'StateTax') {
                $selectQuery  = "SELECT DISTINCT year from state_tax WHERE coverage = '" . $data['coverage'] . "'";
            } 
        }
        $result = $persistenceService->selectQuery($selectQuery);
        while ($result->next()) {
            $year = $result->current();
            array_push($yearList, $year['year']);
        }
        return $yearList;
    }
}