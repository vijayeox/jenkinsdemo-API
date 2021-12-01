<?php

use Oxzion\AppDelegate\AbstractAppDelegate;
use Oxzion\Db\Persistence\Persistence;
use Oxzion\AppDelegate\UserContextTrait;
use Oxzion\DelegateException;
use Oxzion\AppDelegate\FileTrait;
use Oxzion\Utils\UuidUtil;


class ELDSubscription extends AbstractAppDelegate
{
    use UserContextTrait;
    use FileTrait;
    const APPID = 'a4b1f073-fc20-477f-a804-1aa206938c42';

    private $persistenceService;

    public function __construct()
    {
        parent::__construct();
    }

    public function execute(array $data, Persistence $persistenceService)
    {
        $icUserId = $data['icusername']['uuid'];
        $filterParams = array();
        $pageSize = 1;
        $filterParams['filter'][0]['take'] = $pageSize;
        $skip =  0;
        $filterParams['filter'][0]['skip'] = $skip;
        $filterParams['filter'][0]['filter']['filters'][] = array('field' => 'ICUserId', 'operator' => 'eq', 'value' => $icUserId);
        $fileList = $this->getFileList($data, $filterParams);
        $this->logger->info("file list " . print_r(json_encode($fileList), true));
        if (isset($fileList['data']) && sizeof($fileList['data']) > 0) {
            $fileData = is_string($fileList['data'][0]['data']) ? json_decode($fileList['data'][0]['data'], true) : $fileList['data'][0]['data'];
            $fileUuid = $fileList['data'][0]['uuid'];
            $fileData['ELDSubscription'] = "Yes";
            $this->saveFile($fileData, $fileUuid);
        } else {
            return $data;
        }
    }
}
