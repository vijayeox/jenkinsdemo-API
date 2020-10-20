<?php
use Oxzion\Db\Persistence\Persistence;
use Oxzion\Utils\ArtifactUtils;
use Oxzion\AppDelegate\UserContextTrait;
use Oxzion\Auth\AuthConstants;
use Oxzion\Auth\AuthContext;
use Oxzion\AppDelegate\FileTrait;
use Oxzion\AppDelegate\AbstractAppDelegate;

class GenerateReportMenu extends AbstractAppDelegate
{
    use FileTrait;

    public function __construct(){
        parent::__construct();
    }

    public function execute(array $data, Persistence $persistenceService) 
    {
        $this->logger->info("Executing Generate Report Menu Generation with data- ".json_encode($data));
        $params['entityName'] = 'Generate Report Job';
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
            $time = strtotime($value['startDate']);
            $value['startDate'] = date('Y-m-d', $time);
            $time = strtotime($value['endDate']);
            unset($value['endDate']);
            $value['endDate'] = date('Y-m-d', $time);
            $value['generationType'] = $value['startDate'].' to '.$value['endDate'];
            if(isset($value['product']) && $value['product'] == 'No Records Found'){
                $value['productType'] = $value['product'];
            }
            else{
                if(isset($value['productType'])){
                    if(is_string($value['productType'])){
                        $productName = json_decode($value['productType'], true);
                    } else {
                        $productName = array();
                    }
                    if(isset($value['productType']) && !empty($value['productType']) && $value['productType'] == 'individualProfessionalLiability'){
                        $value['productType'] = 'Individual Professional Liability';
                    }
                    if(isset($value['productType']) && !empty($value['productType']) && $value['productType'] == 'emergencyFirstResponse'){
                        $value['productType'] = 'Emergency First Response';
                    }
                    if(isset($value['productType']) && !empty($value['productType']) && $value['productType'] == 'diveBoat'){
                        $value['productType'] = 'Dive Boat';
                    }
                    if(isset($value['productType']) && !empty($value['productType']) && $value['productType'] == 'diveStore'){
                        $value['productType'] = 'Dive Store';
                    }
                    $this->logger->info($value['productType']);
                }
            }
        }
        return $files;
    }
}
?>