<?php

use Oxzion\AppDelegate\AbstractAppDelegate;
use Oxzion\Db\Persistence\Persistence;
use Oxzion\Utils\Country;

class PadiRatingList extends AbstractAppDelegate
{
    public function __construct(){
        parent::__construct();
    }

    // Padi rating list
    public function execute(array $data,Persistence $persistenceService)
    {
        $this->logger->info("Padi Rating Data List");
        $properties[] = array("name" => "group_concat_max_len","value" => 10000);
        $persistenceService->setSessionProperties($properties);
        $certificateLevelList = array();
        $select = "Select DISTINCT padi_rating,concat('[',group_concat('{\"label\":\"',coverage_name,'\",','\"value\":\"',coverage_level,'\"}'),']') as statusList FROM coverage_options WHERE category = 'GROUP' GROUP BY padi_rating";   
        $result = $persistenceService->selectQuery($select);
        if($result->count() > 0){
            $response = array();
            while ($result->next()) {
                $response = $result->current();
                $certificateLevelList[$response['padi_rating']] = json_decode($response['statusList'],true);
            }
        }

        $selectAll = "SELECT concat('[',GROUP_CONCAT(DISTINCT concat('{\"label\":\"',coverage_name,'\",\"value\":\"',coverage_level,'\"}')),']') as statusList WHERE category = 'GROUP' FROM coverage_options";
        $result = $persistenceService->selectQuery($selectAll);
        if($result->count() > 0){
            $response = array();
            while ($result->next()) {
                $response = $result->current();
                $certificateLevelList['ratingList'] = json_decode($response['statusList'],true);
            }
        }
        $data['certificateLevelList'] = $certificateLevelList;
        return $data;
    }
}
