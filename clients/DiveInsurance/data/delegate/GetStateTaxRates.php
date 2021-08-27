<?php

use Oxzion\AppDelegate\AbstractAppDelegate;
use Oxzion\Db\Persistence\Persistence;
use Oxzion\Auth\AuthConstants;
use Oxzion\Auth\AuthContext;
use Oxzion\DelegateException;
use Oxzion\AppDelegate\UserContextTrait;

class GetStateTaxRates extends AbstractAppDelegate
{
    use UserContextTrait;
    public function __construct()
    {
        parent::__construct();
    }

    // State Tax values are fetched here
    public function execute(array $data, Persistence $persistenceService)
    {
        $this->logger->info("Get State Tax ------" . print_r($data, true));
        if (AuthContext::isPrivileged('MANAGE_ADMIN_WRITE')) {
            if ($data['year'] == "") {
                $data['year'] = $this->getMaxYear($data, $persistenceService);
            }
            $data = $this->getStateTaxRates($data, $persistenceService);
            return $data;
        } else {
            throw new DelegateException("You do not have access to this API", 'no_access');
        }
    }


    private function getStateTaxRates(&$data, $persistenceService)
    {
        $this->logger->info("Get State Tax Rates ------" . print_r($data, true));
        $data['stateTax'] = array();

        $stateTax = "SELECT * FROM state_tax WHERE `year` = " . $data['year'] . " and `coverage` = '" . $data['coverage'] . "'";
        $stateTaxResult = $persistenceService->selectQuery($stateTax);

        if (count($stateTaxResult) == 0) {
            $this->addNewRecord($data, $persistenceService);
        }

        $selectQuery = "SELECT id,CONCAT(UCASE(MID(coverage,1,1)),MID(coverage,2)) as coverage,state,percentage,year FROM state_tax WHERE `year` = " . $data['year'] . " and `coverage` = '" . $data['coverage'] . "'";
        $this->logger->info("Get State Tax Rates Query ------" . print_r($selectQuery, true));
        $result = $persistenceService->selectQuery($selectQuery);
        while ($result->next()) {
            $rate = $result->current();
            array_push($data['stateTax'], $rate);
        }
        return $data['stateTax'];
    }

    private function getMaxYear($data, $persistenceService)
    {
        $yearSelect = "SELECT max(`year`) as `year` FROM state_tax WHERE coverage = '" . $data['coverage'] . "'";
        $result = $persistenceService->selectQuery($yearSelect);
        while ($result->next()) {
            $year = $result->current();
            $maxYear = $year['year'];
        }
        return $maxYear;
    }

    private function addNewRecord($data, $persistenceService)
    {
        $this->logger->info("Add New Record State Tax Rates ------" . print_r($data, true));
        $year = $this->getMaxYear($data, $persistenceService);
        $persistenceService->beginTransaction();
        try {
            $query = "INSERT INTO state_tax (`state`,`coverage`,`percentage`,`start_date`,`end_date`,`year`) SELECT state,coverage,percentage,DATE_ADD(start_date, INTERVAL 1 year) as start_date,DATE_ADD(end_date, INTERVAL 1 year) as end_date," . $data['year'] . " as `year` FROM state_tax WHERE `year` = " . $year . " and coverage = '" . $data['coverage'] . "'";
            $this->logger->info("Add New Record State Tax Rates Query ------" . print_r($query, true));
            $insert = $persistenceService->insertQuery($query);
            $persistenceService->commit();
        } catch (Exception $e) {
            print_r($e->getMessage());
            $persistenceService->rollback();
            throw $e;
        }
    }
}
