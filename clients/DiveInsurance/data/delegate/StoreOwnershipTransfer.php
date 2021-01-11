<?php

use Oxzion\Db\Persistence\Persistence;
use Oxzion\AppDelegate\FileTrait;
require_once __DIR__."/PolicyDocument.php";


class StoreOwnershipTransfer extends PolicyDocument
{
    use FileTrait;

    public function __construct(){
        parent::__construct();
    }

    public function execute(array $data,Persistence $persistenceService)
    {
        $this->logger->info("Executing StoreOwnershipTransfer with data- ".json_encode($data));
        if(isset($data['data'])) {
            $fileData = json_decode($data['data'],true);
            unset($data['data']);
            $data = array_merge($fileData,$data);
        }

        $data['assocId'] = $data['fileId'];
        unset($data['fileId']);
        unset($data['workflowInstanceId']);

        //$data['iterations'] = isset($data['iterations']) ? $data['iterations'] + 1 : 1;
        if(isset($data['iterations'])){
            if(isset($data['transfer']) && ($data['transfer'] === true || $data['transfer'] === 'true')) {
                $data['iterations'] = $data['iterations'] + 1;
            } else {
                $data['iterations'] = 1;
            }
        }else {
            $data['iterations'] = 1;
        }

        //Flag for change of ownership
        $data['transfer'] = true;

        //Dynamic flag for new account creation
        $data['CreateNewUser'] = true;

        //New account name suffixed with R + iteration
        if(isset($data['username'])) {
            $partsArray = explode("R", $data['username']);
            $data['username'] = $partsArray[0]."R".$data['iterations'];
        } else {
            $data['username'] = null;
        }
        $data['data'] = json_encode($data);

        return $data;
    }
}