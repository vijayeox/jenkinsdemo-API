<?php

use Oxzion\Db\Persistence\Persistence;
use Oxzion\Utils\ArtifactUtils;
use Oxzion\AppDelegate\FileTrait;
use Oxzion\AppDelegate\UserContextTrait;
use Oxzion\Auth\AuthConstants;
use Oxzion\Auth\AuthContext;
require_once __DIR__."/PolicyDocument.php";

class PocketCard extends PolicyDocument
{
	use FileTrait;
    protected $type;
    protected $template;

    public function __construct(){
        parent::__construct();
        $this->type = 'cancel';
        $this->template = array('template' => 'PocketCard');
    }

    public function execute(array $data, Persistence $persistenceService) 
    {
        $this->logger->info("Executing Pocket Card Generation with data- ".json_encode($data));
        if(isset($data['boatStoreNumber']) && !empty($data['boatStoreNumber'])){
            $data['padiNumber'] = $data['boatStoreNumber'];
        }
        if(isset($data['storeNumber']) && !empty($data['storeNumber'])){
            $data['padiNumber'] = $data['storeNumber'];
        }
        if(isset($data['padiNumber'])){
            $this->logger->info("generating individual pocket card");
            $params = array();
            $currentDate = date_create()->format("Y-m-d");
            $filter[] = array("field" => "end_date", "operator" => "gte", "value" => $currentDate);
            $filter[] = array("field" => "policyStatus", "operator" => "eq", "value" => "In Force");
            $filter[] = array("field" => "padi", "operator" => "eq", "value" => $data['padiNumber']);
            if($data['padiProductType'] == 'individualProfessionalLiability'){
                $filter[] = array("field" => "product", "operator" => "eq", "value" => "Individual Professional Liability");
            }
            if($data['padiProductType'] == 'emergencyFirstResponse'){
                $filter[] = array("field" => "product", "operator" => "eq", "value" => "Emergency First Response");
            }
            if($data['padiProductType'] == 'diveBoat'){
                $filter[] = array("field" => "product", "operator" => "eq", "value" => "Dive Boat");
            }
            if($data['padiProductType'] == 'diveStore'){
                $filter[] = array("field" => "product", "operator" => "eq", "value" => "Dive Store");
            }
            $filterParams = array(array("filter" => array("logic" => "AND", "filters" => $filter)));
            $this->logger->info("filter params is : ". json_encode($filterParams));
            $params['workflowStatus'] = 'Completed';
            $files = $this->getFileList($params, $filterParams);
            $data['product'] = implode(", ", array_unique(array_column($files['data'], 'product')));
            $this->logger->info("the product is: ". print_r($data['product'], true));
            if($data['product'] == 'Dive Boat' || $data['product'] == 'Dive Store'){
                $result = $this->newDataArray($files);
                $files = $result;
            }
        }
        else
        {
            $this->logger->info("Generating batch pocket cards");
            $params = array();
            if(isset($data['pocketCardStartDate'])){
                $data['pocketCardStartDate'] = substr($data['pocketCardStartDate'], 0, -6);
                $params['gtCreatedDate'] =  $data['pocketCardStartDate'];
            }
            if(isset($data['pocketCardEndDate'])){
                $data['pocketCardEndDate'] = substr($data['pocketCardEndDate'], 0, -6);
                $params['ltCreatedDate'] =  $data['pocketCardEndDate'];
            }
            $params['workflowStatus'] = 'Completed';
            $filter = array();
            $data['pocketCardProductType'] = json_decode($data['pocketCardProductType'], true);
            if($data['pocketCardProductType']['individualProfessionalLiability'] || $data['pocketCardProductType']['emergencyFirstResponse']){
                if($data['pocketCardProductType']['individualProfessionalLiability']){
                    $filter[] = array("field" => "product", "operator" => "eq", "value" => "Individual Professional Liability");
                }
                if($data['pocketCardProductType']['emergencyFirstResponse']){
                    $filter[] = array("field" => "product", "operator" => "eq", "value" => "Emergency First Response");
                }
                $filterParams = array(array("filter" => array("logic" => "OR", "filters" => $filter), "skip" => 0, "take" => 1000));
                $files = $this->getFileList($params, $filterParams);
                $this->logger->info("pocket card - the total number of files fetched for IPL/ EFR : ".print_r($files['total'], true));
            }
            if($data['pocketCardProductType']['diveBoat'] || $data['pocketCardProductType']['diveStore']){
                $filter = array();
                if($data['pocketCardProductType']['diveBoat']){
                    $filter[] = array("field" => "product", "operator" => "eq", "value" => "Dive Boat");
                }
                if($data['pocketCardProductType']['diveStore']){
                    $filter[] = array("field" => "product", "operator" => "eq", "value" => "Dive Store");
                }
                $filterParams = array(array("filter" => array("logic" => "OR", "filters" => $filter), "skip" => 0, "take" => 1000));
                $files2 = $this->getFileList($params, $filterParams);

                $this->logger->info("pocket card - the total number of files fetched for DB/ DS: ".print_r($files2['total'], true));
                $result = $this->newDataArray($files2);
                $this->logger->info("pocket card - the file details of get file after data clean up: ".print_r($result, true));

                if((!isset($files)) || empty($files)){
                    $files['total'] = 0;
                    $files['data'] = array();
                    $total = -1;
                }
                else{
                    $files['total'] = $files['total'] + $result['total'];
                    $total = $files['total'];
                }
                
                if((isset($result)) && !empty($result)){
                    foreach ($result['data'] as $key => $value) {
                        ++$total;
                        $files['data'][$total] = $value;
                    }
                    $files['total'] = $files['total'] + $result['total'];
                }
            }
        }

        $this->logger->info("the total number of files fetched is : ".print_r($files['total'], true));
        $totalfiles = $files['total'];
        if($totalfiles == 0){
            $data['jobStatus'] = 'No Records Found';
        } 
        $this->logger->info("pocket card - the file details of get file is : ".print_r($files['data'], true));
        $options = array();
        $newData = array();
        foreach($files['data'] as $key => &$value){
            $this->logger->info("key is : ".$key);
            $this->logger->info("value is : ".print_r($value, true));
            if(!isset($value['policyStatus']) || $value['policyStatus'] != 'In Force'){
                continue;
            }
            $newData[$key]['product'] = $value['product'];
            $newData[$key]['email'] = $value['email'];
            $newData[$key]['padi'] = $value['padi'];
            $newData[$key]['certificate_no'] = $value['certificate_no'];
            $newData[$key]['start_date'] = $value['start_date'];
            $newData[$key]['end_date'] = $value['end_date'];
            $newData[$key]['firstname'] = $value['firstname'];
            $newData[$key]['lastname'] = $value['lastname'];
            $newData[$key]['address1'] = $value['address1'];
            $newData[$key]['address2'] = isset($value['address2']) ? $value['address2'] : '';
            $newData[$key]['city'] = $value['city'];
            $newData[$key]['state'] = $value['state'];
            $newData[$key]['zip'] = $value['zip'];
            $newData[$key]['entity_name'] = 'Pocket Card Job';
            if(isset($value['business_name']) && $value['business_name']){
                $newData[$key]['business_name'] = $value['business_name'];
            }
        }
        $this->logger->info("New array data is : ".print_r($newData,true));

        $orgUuid = isset($data['orgUuid']) ? $data['orgUuid'] : ( isset($data['orgId']) ? $data['orgId'] : AuthContext::get(AuthConstants::ORG_UUID));
        $dest = ArtifactUtils::getDocumentFilePath($this->destination, $data['uuid'], array('orgUuid' => $orgUuid));
        $this->logger->info("The destination folder is : ".print_r($dest, true));        
        $template = $this->template['template'];
        $this->logger->info("Template is: ".print_r($template, true));
        $options = array();        
        $docDest = $dest['absolutePath'].$template.'.pdf';
        $data['documents']['PocketCard'] = $dest['relativePath'].$template.'.pdf';
        $this->logger->info("template path is: ".print_r($docDest, true));
        $newData = array('data' => json_encode($newData));
        $this->logger->info("The file data after encode is : ");
        $this->logger->info($newData);
        if(!file_exists($docDest)){
            $this->logger->info("Execute generate document ---------");
            $this->documentBuilder->generateDocument($template, $newData, $docDest);
        }
        if(isset($data['jobStatus']) && ($data['jobStatus']=='In Force')){
            $data['jobStatus'] = 'Completed';
        }        
        $this->logger->info("The data returned from pocket card is : ". print_r($data, true));
        return $data;
    }

    private function newDataArray($data){
        $this->logger->info('pocket card - padi data to be formatted: '.print_r($data, true));
        $i = 0;
        foreach ($data['data'] as $key => $value) {
            if(isset($value['groupPL']) && !empty($value['groupPL'])){
                $this->logger->info('group PL members need to be formatted to a new array');
                $groupData = json_decode($value['groupPL'], true);
                $this->logger->info('group data is: '.print_r($groupData, true));
                $this->logger->info('value data is: '.print_r($value, true));
                $total = count($groupData);
                foreach ($groupData as $key2 => $value2) {
                    $response[$i]['padi'] = $value2['padi'];
                    $response[$i]['firstname'] = $value2['firstname'];
                    $response[$i]['lastname'] = $value2['lastname'];
                    $response[$i]['start_date'] = $value2['start_date'];
                    $response[$i]['product'] = $value['product'];
                    $response[$i]['email'] = $value['email'];
                    $response[$i]['certificate_no'] = $value['certificate_no'];
                    $response[$i]['end_date'] = $value['end_date'];
                    $response[$i]['address1'] = $value['address1'];
                    $response[$i]['address2'] = isset($value['address2']) ? $value['address2'] : '';
                    $response[$i]['city'] = $value['city'];
                    $response[$i]['state'] = $value['state'];
                    $response[$i]['zip'] = $value['zip'];
                    $response[$i]['business_name'] = $value['business_name'];
                    $response[$i]['policyStatus'] = $value['policyStatus'];
                    $responseData['data'] = $response;
                    $i += 1;
                }                
            }
            else{
                $responseData['total'] = -1;
                $responseData['data'] = '';
                return $responseData;
            }
        }
        $responseData['total'] = $i;
        $this->logger->info('the response data is : '.print_r($responseData, true));
        return $responseData;
    }
}
?>