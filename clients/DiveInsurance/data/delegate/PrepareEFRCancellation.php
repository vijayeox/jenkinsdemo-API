<?php

use Oxzion\AppDelegate\AbstractAppDelegate;
use Oxzion\Db\Persistence\Persistence;
use Oxzion\AppDelegate\FileTrait;


class PrepareEFRCancellation extends AbstractAppDelegate
{
	use FileTrait;

    public function __construct(){
        parent::__construct();
    }

    public function execute(array $data,Persistence $persistenceService)
    {  
        if(isset($data['form_props'])){
            $data['form_props'] = gettype($data['form_props']) == "string" ? json_decode($data['form_props'],true) : $data['form_props'];
            $fileData = $this->getFile($data['form_props']['fileId']);
            if($fileData['data']['product']=="Emergency First Response"){
                $entity_id = $fileData['entity_id'];
                $uuid = $fileData['uuid'];
                $fileId = $fileData['id'];
                $fileData = $fileData['data'];

                $fileData['reasonforCsrCancellation'] = array("value"=>"others");
                $fileData['othersCsr'] = "Upgrade To IPL";
                $fileData['cancellationStatus'] = "approved";
                $fileData['disableReinstate'] = true;
                $fileData['entity_id'] = $entity_id;
                $fileData['fileId'] = $uuid;
                $fileData['parent_id'] = $fileId;
                $fileData['iplPolicyData'] = $data;
                $this->logger->info("Start start PrepareEFRCancellation Data Sent ---".json_encode($fileData));
                return $fileData;
            }
        }
        return array();
    }
}
