<?php

use Oxzion\AppDelegate\AbstractAppDelegate;
use Oxzion\Db\Persistence\Persistence;
use Oxzion\Auth\AuthConstants;
use Oxzion\Auth\AuthContext;
use Oxzion\DelegateException;
use Oxzion\AppDelegate\UserContextTrait;

class AddOrRemovePolicyRates extends AbstractAppDelegate
{
    use UserContextTrait;
    public function __construct()
    {
        parent::__construct();
    }

    // Premium Calculation values are fetched here
    public function execute(array $data, Persistence $persistenceService)
    {

        if (AuthContext::isPrivileged('MANAGE_ADMIN_WRITE')) {
            if ($data['type'] == 'add') {
                $leap = false;
                if ((0 == $data['year'] % 4) and (0 != $data['year'] % 100) or (0 == $data['year'] % 400)) {
                    $leap = true;
                }
                $start_date = '01 ' . $data['month'] . ' ' . $data['year'];
                $month = date_format(date_create($start_date), 'm');
                $year = $data['year'];
                if ($month < 7) {
                    $year = $data['year'] + 1;
                }
                $data['start_date'] = $year . '-' . $month . '-01';

                if ($data['product'] == 'Individual Professional Liability - Upgrade') {
                    $is_upgrade = 1;
                    $product = 'Individual Professional Liability';
                    if($month == 7){
                        $data['start_date'] = $year . '-06-30';
                    }
                    $days = date('t', mktime(0, 0, 0, $month, 1, $year));
                    $data['end_date'] = $year . '-' . $month . '-' . $days;
                } else if ($data['product'] == 'Emergency First Response - Upgrade') {
                    $is_upgrade = 1;
                    $product = 'Emergency First Response';
                    $data['end_date'] = $year . '-06-30';
                } else if ($data['product'] == 'Dive Boat - Upgrade') {
                    $is_upgrade = 1;
                    $product = 'Dive Boat';
                    $data['end_date'] = $year . '-07-22';
                } else if ($data['product'] == 'Dive Store - Upgrade') {
                    $is_upgrade = 1;
                    $product = 'Dive Store';
                }

                if ($data['coverage_category'] == 'GROUP_COVERAGE' || $data['coverage_category'] == 'GROUP_EXCESS_LIABILITY') {
                    $days = date('t', mktime(0, 0, 0, $month, 1, $year));
                    $data['end_date'] = $year . '-' . $month . '-' . $days;
                }
                $data = $this->addNewPolicyRate($data, $product, $is_upgrade, $persistenceService);
            } else if ($data['type'] == 'remove') {
                $data = $this->removePolicyRates($data,$persistenceService);
            }
            return $data;
        } else {
            throw new DelegateException("You do not access to this API", 'no_access');
        }
    }


    private function addNewPolicyRate($data, $product, $is_upgrade, $persistenceService)
    {
        $previous_key = $this->getCoverageName($data['previous_coverage'], $persistenceService);
        $coverage_key = $this->getCoverageName($data['coverage'], $persistenceService);
        $selectQuery = "SELECT * FROM premium_rate_card WHERE product = '".$product."' AND coverage = '".$data['coverage']."' AND previous_key = '".$previous_key."' AND year = ".$data['year']." AND start_date = '".$data['start_date']."' AND `is_upgrade` = ".$is_upgrade;
        $result = $persistenceService->selectQuery($selectQuery);
        while ($result->next()) {
            throw new DelegateException("Record already exists","record.exists");
        }
        $insertQuery = "INSERT INTO premium_rate_card (`product`,`coverage`,`key`,`start_date`,`end_date`,`premium`,`tax`,`padi_fee`,`total`,`is_upgrade`,`previous_key`,`coverage_category`,`year`) VALUES ('" . $product . "','" . $data['coverage'] . "','" . $coverage_key . "','" . $data['start_date'] . "','" . $data['end_date'] . "','" . $data['premium'] . "','" . $data['tax'] . "','" . $data['padi_fee'] . "','" . $data['total'] . "'," . $is_upgrade . ",'" . $previous_key . "','" . $data['coverage_category'] . "'," . $data['year'];
        $persistenceService->insertQuery($insertQuery);
    }

    private function removePolicyRates($data,$persistenceService)
    {
        $deleteQuery = "DELETE FROM premium_rate_card WHERE id = " . $data['id'];
        $persistenceService->deleteQuery($deleteQuery);
    }

    private function getCoverageName($previousKey, $persistenceService)
    {
        $selectQuery = "SELECT DISTINCT `key` from premium_rate_card WHERE coverage = '" . $previousKey . "'";
        $result = $persistenceService->selectQuery($selectQuery);
        while ($result->next()) {
            $previous_key = $result->current();
            $previous_key = $previous_key['key'];
        }

        return $previous_key;
    }
}
