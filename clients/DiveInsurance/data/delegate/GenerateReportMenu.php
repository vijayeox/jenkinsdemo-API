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
        $this->logger->info("Executing Batch Report Menu Generation with data- ".json_encode($data));
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
            $time = strtotime($value['reportStartDate']);
            $value['reportStartDate'] = date('Y-m-d', $time);
            $time = strtotime($value['reportEndDate']);
            unset($value['reportEndDate']);
            $value['reportEndDate'] = date('Y-m-d', $time);
            $value['generationType'] = $value['reportStartDate'].' to '.$value['reportEndDate'];
            if(isset($value['product']) && $value['product'] == 'No Records Found'){
                $value['reportProductType'] = $value['product'];
            }
            else{
                if(isset($value['reportProductType'])){
                    if(is_string($value['reportProductType'])){
                        $productName = json_decode($value['reportProductType'], true);
                    } else {
                        $productName = array();
                    }
                    if(isset($value['reportProductType']) && !empty($value['reportProductType']) && $value['reportProductType'] == 'individualProfessionalLiability'){
                        $value['reportProductType'] = 'Individual Professional Liability';
                    }
                    if(isset($value['reportProductType']) && !empty($value['reportProductType']) && $value['reportProductType'] == 'emergencyFirstResponse'){
                        $value['reportProductType'] = 'Emergency First Response';
                    }
                    if(isset($value['reportProductType']) && !empty($value['reportProductType']) && $value['reportProductType'] == 'diveBoat'){
                        $value['reportProductType'] = 'Dive Boat';
                    }
                    if(isset($value['reportProductType']) && !empty($value['reportProductType']) && $value['reportProductType'] == 'diveStore'){
                        $value['reportProductType'] = 'Dive Store';
                    }
                    $this->logger->info($value['reportProductType']);
                }
            }
        }
        return $files;
    }
}
?>