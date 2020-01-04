<?php

use Oxzion\AppDelegate\AbstractDocumentAppDelegate;
use Oxzion\Db\Persistence\Persistence;
use Oxzion\Utils\UuidUtil;

class DocumentSaveDelegate extends AbstractDocumentAppDelegate
{
    public function __construct()
    {
        parent::__construct();
    }

    public function setDocumentBuilder($builder){
        
        $this->documentBuilder = $builder;
    }

    public function setTemplatePath($destination)
    {
        $this->destination = $destination;
    }

    public function execute(array $data,Persistence $persistenceService) 
    {
        $data['uuid'] = UuidUtil::uuid();
        $filepath = $this->destination.$data['orgId'].'/'.$data['uuid'].'/';
        if (!is_dir($filepath)) {
            mkdir($filepath, 0777, true);
        }
        for($j = 0;$j < sizeof($data['groupPL']);$j++){
            $this->logger->info("INSIDE FOR Loop");
            if(isset($data['groupPL'][$j]['document'])){
                $group = $data['groupPL'][$j]['document'];
                for($i = 0 ;$i < sizeof($group);$i++){
                    $this->logger->info("INSIDE FOR2 Loop");
                    $docFile = fopen($filepath.$group[$i]['originalName'].'.txt','wb');
                    fwrite($docFile,$group[$i]['url']);
                    fclose($docFile);
                    unset($data['groupPL'][$j]['document'][$i]['url']);
                    $data['groupPL'][$j]['document'][$i]['file'] = $filepath.$group[$i]['originalName'].'.txt';
                }
            }
        }
        return $data;
    }
}
