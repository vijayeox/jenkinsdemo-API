<?php

use Oxzion\Db\Persistence\Persistence;
use Oxzion\Utils\ArtifactUtils;
use Oxzion\AppDelegate\UserContextTrait;
use Oxzion\Auth\AuthConstants;
use Oxzion\Auth\AuthContext;
use Oxzion\AppDelegate\FileTrait;
use Oxzion\AppDelegate\AbstractAppDelegate;

class PocketCardMenu extends AbstractAppDelegate
{
    use FileTrait;

    public function __construct(){
        parent::__construct();
    }

    public function execute(array $data, Persistence $persistenceService) 
    {
        $this->logger->info("Executing Pocket Card Menu Generation with data- ".json_encode($data));
        $params['entityName'] = 'Pocket Card Job';
        $sortParams = array("field" => "date_created", "dir" => "desc");
        $filterParams = array("filters" => array());
        $finalFilterParams = array(array("filter" => $filterParams, "sort" => array($sortParams)));
        if(isset($data['filter'])){
            $data['filter'] = json_decode($data['filter'],true);
            $finalFilterParams = array(array("filter" => $filterParams, "sort" => array($sortParams),"skip" => $data['filter'][0]['skip'],"take" => $data['filter'][0]['take']));
        }
        
        $files = $this->getFileList($params, $finalFilterParams);
        $this->logger->info("the total number of files fetched is : ".print_r($files['total'], true));
        $this->logger->info("the file details of get file is : ".print_r($files['data'], true));
        foreach($files['data'] as $key => &$value){
            $this->logger->info("key is : ".$key);
            $this->logger->info("value is : ".print_r($value, true));
            unset($value['data']);
            if(isset($value['boatStoreNumber']) && !empty($value['boatStoreNumber'])){
                $value['padiNumber'] = $value['boatStoreNumber'];
            }
            if(isset($value['storeNumber']) && !empty($value['storeNumber'])){
                $value['padiNumber'] = $value['storeNumber'];
            }
            if(isset($value['padiNumber'])){
                $value['generationType'] = $value['padiNumber'];
                $value['pocketCardProductType'] = isset($value['padiProductType']) ? $value['padiProductType'] : "";
                if(isset($value['pocketCardProductType']) && !empty($value['pocketCardProductType']) && $value['pocketCardProductType'] == 'individualProfessionalLiability'){
                    $value['pocketCardProductType'] = 'Individual Professional Liability';
                }
                if(isset($value['pocketCardProductType']) && !empty($value['pocketCardProductType']) && $value['pocketCardProductType'] == 'emergencyFirstResponse'){
                    $value['pocketCardProductType'] = 'Emergency First Response';
                }
                if(isset($value['pocketCardProductType']) && !empty($value['pocketCardProductType']) && $value['pocketCardProductType'] == 'diveBoat'){
                    $value['pocketCardProductType'] = 'Dive Boat';
                }
                if(isset($value['pocketCardProductType']) && !empty($value['pocketCardProductType']) && $value['pocketCardProductType'] == 'diveStore'){
                    $value['pocketCardProductType'] = 'Dive Store';
                }
            }
            else {
                $time = strtotime($value['pocketCardStartDate']);
                $value['pocketCardStartDate'] = date('Y-m-d', $time);
                $time = strtotime($value['pocketCardEndDate']);
                unset($value['pocketCardEndDate']);
                $value['pocketCardEndDate'] = date('Y-m-d', $time);
                $value['generationType'] = $value['pocketCardStartDate'].' to '.$value['pocketCardEndDate'];
                if(isset($value['product']) && $value['product'] == 'No Records Found'){
                    $this->logger->info('hello123456');
                    $value['pocketCardProductType'] = $value['product'];
                }
                else{
                    if(isset($value['pocketCardProductType'])){
                        if(is_string($value['pocketCardProductType'])){
                            $productName = json_decode($value['pocketCardProductType'], true);
                        } else {
                            $productName = array();
                        }
                        unset($value['pocketCardProductType']);
                        $value['pocketCardProductType'] = '';
                        if(isset($productName['individualProfessionalLiability']) && ($productName['individualProfessionalLiability'] == 1)){
                            $value['pocketCardProductType'] .= 'Individual Professional Liability , ';
                        }
                        if(isset($productName['emergencyFirstResponse']) && ($productName['emergencyFirstResponse'] == 1)){
                            $value['pocketCardProductType'] .= 'Emergency First Response , ';
                        }
                        if(isset($productName['diveBoat']) && ($productName['diveBoat'] == 1)){
                            $value['pocketCardProductType'] .= 'Dive Boat , ';
                        }
                        if(isset($productName['diveStore']) && ($productName['diveStore'] == 1)){
                            $value['pocketCardProductType'] .= ' Dive Store , ';
                        }
                        $value['pocketCardProductType'] = rtrim($value['pocketCardProductType'], ", ");
                        $this->logger->info($value['pocketCardProductType']);
                    }
                }
            }
            
        }
        return $files;
    }
}


?>