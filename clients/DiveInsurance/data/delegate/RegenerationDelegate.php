<?php

use Oxzion\Db\Persistence\Persistence;
use Oxzion\AppDelegate\FileTrait;
require_once __DIR__."/PolicyDocument.php";


class RegenerationDelegate extends PolicyDocument
{
    use FileTrait;

    public function __construct(){
        parent::__construct();
    }

    public function execute(array $data,Persistence $persistenceService) 
    {
        $this->logger->info("Executing RegenerationDelegate with data- ".json_encode($data));
        $fileData = $this->getWorkflowInstanceStartDataFromFileId($data['fileId']);
        if (count($fileData) > 0) {
            $startData = is_array($fileData['start_data']) ?  $fileData['start_data'] : json_decode($fileData['start_data'],true);
            $data['endorsement_options']  = isset($startData['endorsement_options']) ? $startData['endorsement_options'] : null;
        }
        $data = parent::execute($data,$persistenceService);
        return $data;
    }
}