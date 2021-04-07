<?php

use Oxzion\AppDelegate\AbstractDocumentAppDelegate;
use Oxzion\AppDelegate\UserContextTrait;
use Oxzion\Db\Persistence\Persistence;
class GetProducerCode extends AbstractDocumentAppDelegate
{   protected $template;
    use UserContextTrait;
    public function __construct()
    {
        parent::__construct();
    }

    public function execute(array $data, Persistence $persistenceService)
    {
        $userId =  $this->getUserId();
        $userId = "a1efb171-bd10-482b-a835-94f6ee7ef648";
        $selectQuery = "SELECT * FROM user WHERE uuid='$userId'";
        $result = $persistenceService->selectQuery($selectQuery);
        $resultArr = array();       
        array_push($resultArr, $result->current());  
        $data["producerCode"] = $resultArr[0]['producer_code'];
        return $data;
    }
}
