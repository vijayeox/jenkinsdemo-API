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
        
        $params = array();
        if(isset($data['pocketCardStartDate'])){
        	$params['gtCreatedDate'] = 	$data['pocketCardStartDate'];
        }
        if(isset($data['pocketCardEndDate'])){
        	$params['ltCreatedDate'] = 	$data['pocketCardEndDate'];
        }
        $params['workflowStatus'] = 'Completed';
        $filter = array();
        if($data['pocketCardProductType']['individualProfessionalLiability']){
        	$filter[] = array("field" => "product", "operator" => "eq", "value" => "Individual Professional Liability");
        }
        if($data['pocketCardProductType']['emergencyFirstResponse']){
        	$filter[] = array("field" => "product", "operator" => "eq", "value" => "Emergency First Response");
        }
        $filterParams = array("logic" => "OR", "filters" => $filter);
        $files = $this->getFileList($params, $filterParams);
        $this->logger->info("the total number of files fetched is : ".print_r($files['total'], true));
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
        }
        $this->logger->info("new array data is : ".print_r($newData,true));
        
        $orgUuid = isset($data['orgUuid']) ? $data['orgUuid'] : ( isset($data['orgId']) ? $data['orgId'] : AuthContext::get(AuthConstants::ORG_UUID));
        $dest = ArtifactUtils::getDocumentFilePath($this->destination, $data['uuid'], array('orgUuid' => $orgUuid));
        $this->logger->info("the destination folder is : ".print_r($dest, true));        
        $template = $this->template['template'];
        $this->logger->info("template is: ".print_r($template, true));
        $options = array();        
        $docDest = $dest['absolutePath'].$template.'.pdf';
        $data['documents']['PocketCard'] = $dest['relativePath'].$template.'.pdf';
        $this->logger->info("template path is: ".print_r($docDest, true));
        $newData = array('data' => json_encode($newData));
        $this->logger->info(" The file data after encode is : ");
        $this->logger->info($newData);
        if(!file_exists($docDest)){
            $this->logger->info("execute generate document ---------");
            $this->documentBuilder->generateDocument($template, $newData, $docDest);
        }
        if(isset($data['jobStatus']) && ($data['jobStatus']=='In Force')){
            $data['jobStatus'] = 'Completed';
        }        
        $this->logger->info("the data returned from pocket card is : ", print_r($data, true));        
        return $data;
    }
}


?>