<?php

use Oxzion\AppDelegate\AbstractAppDelegate;
use Oxzion\Db\Persistence\Persistence;

class CoverageList extends AbstractAppDelegate
{
    public function __construct()
    {
        parent::__construct();
    }

    // Premium Calculation values are fetched here
    public function execute(array $data, Persistence $persistenceService)
    {
        $coverageList = array();
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
        } else if ($data['product'] == 'Dive Store - Endorsement' || $data['product'] == 'Dive Store - Group PL Endorsement') {
            $is_upgrade = 1;
            $product = 'Dive Store';
        } else if ($data['product'] == 'Group Professional Liability - New Policy' || $data['product'] == 'Group Professional Liability - Group PL') {
            $is_upgrade = 0;
            $product = 'Group Professional Liability';
        } else if ($data['product'] == 'Group Professional Liability - Endorsement' || $data['product'] == 'Group Professional Liability - Group PL Endorsement') {
            $is_upgrade = 1;
            $product = 'Group Professional Liability';
        }
        $selectQuery  = "SELECT DISTINCT coverage from premium_rate_card WHERE product = '" . $product . "' AND is_upgrade = 0 AND `year` = " . $data['year']." AND coverage_category = '".$data['coverage_category']."' AND coverage NOT IN ('".$data['coverage']."') ORDER BY coverage";
        $result = $persistenceService->selectQuery($selectQuery);
        while ($result->next()) {
            $coverage = $result->current();
            array_push($coverageList, $coverage['coverage']);
        }

        return $coverageList;
    }
}