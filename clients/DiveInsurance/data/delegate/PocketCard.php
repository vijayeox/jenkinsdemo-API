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

        if(isset($data['padiNumber'])){
            $this->logger->info("generating individual pocket card");
            $result = $this->padiVerification($data['padiNumber'], $persistenceService);
            $isBusiness = '';
            if(!empty($result[0]['business_name']) && isset($result[0]['business_name'])){
                $isBusiness = true;
            }
            else{
                $isBusiness = false;
            }
            $params = array();
            $currentDate = date_create()->format("Y-m-d");
            $filter[] = array("field" => "end_date", "operator" => "gte", "value" => $currentDate);
            $filter[] = array("field" => "policyStatus", "operator" => "eq", "value" => "In Force");
            if(!empty($isBusiness) && $isBusiness){
                $filter[] = array("field" => "business_padi", "operator" => "eq", "value" => $data['padiNumber']);
            }
            else {
                $filter[] = array("field" => "padi", "operator" => "eq", "value" => $data['padiNumber']);
            }
            $filterParams = array(array("filter" => array("logic" => "AND", "filters" => $filter)));
            $this->logger->info("filter params is : ". json_encode($filterParams));
            $params['workflowStatus'] = 'Completed';
            $files = $this->getFileList($params, $filterParams);
            $data['product'] = implode(", ", array_unique(array_column($files['data'], 'product')));
            $this->logger->info("the product is: ". print_r($data['product'], true));
        }
        else
        {
            $this->logger->info("Generating batch pocket cards");
            $params = array();
            if(isset($data['pocketCardStartDate'])){
                $params['gtCreatedDate'] =  $data['pocketCardStartDate'];
            }
            if(isset($data['pocketCardEndDate'])){
                $params['ltCreatedDate'] =  $data['pocketCardEndDate'];
            }
            $params['workflowStatus'] = 'Completed';
            $filter = array();
            $data['pocketCardProductType'] = json_decode($data['pocketCardProductType'], true);
            if($data['pocketCardProductType']['individualProfessionalLiability']){
                $filter[] = array("field" => "product", "operator" => "eq", "value" => "Individual Professional Liability");
            }
            if($data['pocketCardProductType']['emergencyFirstResponse']){
                $filter[] = array("field" => "product", "operator" => "eq", "value" => "Emergency First Response");
            }
            if($data['pocketCardProductType']['diveBoat']){
                $filter[] = array("field" => "product", "operator" => "eq", "value" => "Dive Boat");
            }
            if($data['pocketCardProductType']['diveStore']){
                $filter[] = array("field" => "product", "operator" => "eq", "value" => "Dive Store");
            }
            $filterParams = array(array("filter" => array("logic" => "OR", "filters" => $filter)));
            $files = $this->getFileList($params, $filterParams);
        }
        $this->logger->info("he total number of files fetched is : ".print_r($files['total'], true));
        $totalfiles = json_decode($files['total']);
        if($totalfiles == 0){
            $data['jobStatus'] = 'No Records Found';
        } 
        $this->logger->info("the file details of get file is : ".print_r($files['data'], true));
        $options = array();
        $newData = array();
        foreach($files['data'] as $key => &$value){
            $this->logger->info("key is : ".$key);
            $this->logger->info("value is : ".print_r($value, true));
            if(!isset($value['policyStatus']) || $value['policyStatus'] != 'In Force'){
                continue;
            }            
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

    private function padiVerification($padi, Persistence $persistenceService){
        $this->logger->info('pocket card padi verification: ');
        $select = "Select firstname, MI as initial, lastname, business_name FROM padi_data WHERE member_number ='".$padi."'";
        $result = $persistenceService->selectQuery($select);
        $response = array();
        if($result->count() > 0){
            while ($result->next()) {
                $response[] = $result->current();
            }
        }
        return $response;
    }
}
?>