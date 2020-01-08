<?php
use Oxzion\AppDelegate\AbstractDocumentAppDelegate;
use Oxzion\Db\Persistence\Persistence;
use Oxzion\Utils\UuidUtil;

class DocumentFetchDelegate extends AbstractDocumentAppDelegate
{
    public function __construct()
    {
        parent::__construct();
    }

    public function execute(array $data,Persistence $persistenceService)
    {
        $this->logger->info("DocumentFetchDelegate".print_r($data,true));
        if(isset($data['groupPL']))
            {
                $group = $data['groupPL'];
                for($i = 0;$i < sizeof($group);$i++){
                    if(isset($group[$i]['document'][0]['file'])){
                        $file = $this->destination.$group[$i]['document'][0]['file'];
                        $data['groupPL'][$i]['document'][0]['url'] = file_get_contents($file);
                        unset($data['groupPL'][$i]['document'][0]['file']); 
                    }
                }
            }
        $this->logger->info("DocumentFetchDelegate1".print_r($data,true));
        return $data;
    }
}
