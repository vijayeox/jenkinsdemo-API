<?php
use Oxzion\Db\Persistence\Persistence;
use Oxzion\AppDelegate\AbstractAppDelegate;
use Oxzion\AppDelegate\FileTrait;
use Oxzion\AppDelegate\AppDelegateTrait;


class HandleOldOwnershipChanges extends AbstractAppDelegate
{
    use FileTrait;
    use AppDelegateTrait;

    public function __construct(){
        parent::__construct();
    }

    public function execute(array $data,Persistence $persistenceService)
    {
        $this->logger->info("Executing HandleOldOwnershipChanges with data- ".json_encode($data));
        $params = array('appId' => 'd77ea120-b028-479b-8c6e-60476b6a4456','workflowStatus' => 'Completed');
        $filterParams = array('filter' => '[{"filter":{"logic":"and","filters":[{"filter":{"logic":"or","filters":[{"field":"padi","operator":"contains","value":"R"},{"field":"business_padi","operator":"contains","value":"R"}]}},{"field":"product","operator":"eq","value":"Dive Store"}]},"sort":[{"field":"date_created","dir":"desc"}],"skip":0}]');
        $result = $this->getFileList($params,$filterParams);
        $fileList = $result['data'];
        $appDelegateService = $this->getAppDelegateService();
        foreach ($fileList as $key => $value) {
            $fileData = $value;
            unset($fileData['data']);
            $replace = array('R', 'r');
            $fileData['business_padi'] = isset($fileData['business_padi']) ? str_replace($replace, "", $fileData['business_padi']) : null;
            $fileData['entity_id'] = $appDelegateService->getIdFromUuid('ox_app_entity', $fileData['entity_id']);
            $fileData['transfer'] = true;
            $fileId = $fileData['fileId'] = $fileData['uuid'];
            $fileData['iterations'] = 1;
            $value = $fileData;
            $this->saveFile($fileData,$fileId);
        }
    }
}