<?php
use Oxzion\Db\Persistence\Persistence;
use Oxzion\Messaging\MessageProducer;
use Oxzion\AppDelegate\AbstractAppDelegate;


class StateList extends AbstractAppDelegate{

    public function execute(array $data,Persistence $persistenceService)
    {
        $this->logger->info("State Delegate");
        $select  = "Select DISTINCT state from state where country = '".$data['country']."'";
        $result = $persistenceService->selectQuery($select);
        $this->logger->info(print_r($result,true));
        $data['stateList'] = array();
        unset($data['state']);
        while ($result->next()) {
            $rate = $result->current();
            array_push($data['stateList'],$rate['state']);
        }
        return $data;
    }
}
?>