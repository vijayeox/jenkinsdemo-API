<?php

use Oxzion\Db\Persistence\Persistence;
use Oxzion\Utils\ArtifactUtils;
use Oxzion\AppDelegate\FileTrait;
use Oxzion\AppDelegate\UserContextTrait;
use Oxzion\Auth\AuthConstants;
use Oxzion\Auth\AuthContext;
use Oxzion\Document\DocumentGenerator;
require_once __DIR__."/PolicyDocument.php";

class BatchReport extends PolicyDocument
{
    use FileTrait;
    protected $type;
    protected $template;
    public function __construct(){
        parent::__construct();
        $this->type = 'cancel';
        $this->template = array('template' => 'ProfessionalLiabilityCOI');
    }
    public function execute(array $data, Persistence $persistenceService) 
    { 
        $this->logger->info("Executing Batch Report Generation with data- ".json_encode($data));
        $params = array();
        if(isset($data['reportStartDate'])){
            $params['gtCreatedDate'] =  $data['reportStartDate'];
        }
        if(isset($data['reportEndDate'])){
            $params['ltCreatedDate'] =  $data['reportEndDate'];
        }
        if(isset($data['country'])){
            $params['country'] =  $data['country'];
            $filter[] = array("field" => "country", "operator" => "eq", "value" => $params['country']);
        }
        
            $params['state'] =  isset($data['state'])  ? $data['state'] : (is_null($data['state'])? "": $data['state']);
            $filter[] = array("field" => "state", "operator" => "eq", "value" => $params['state']);
            $this->logger->info("state".json_encode($params));
        $params['workflowStatus'] = 'Completed';
        if($data['reportProductType'] == 'individualProfessionalLiability' || $data['reportProductType'] =='emergencyFirstResponse'){
            if($data['reportProductType'] == 'individualProfessionalLiability'){
                $params['entityName'] = 'Individual Professional Liability';
            }
            if($data['reportProductType'] == 'emergencyFirstResponse'){
                $params['entityName'] = 'Emergency First Response';
            }
        }
        if($data['reportProductType'] == 'diveStore'){
            $params['entityName'] = 'Dive Store';
        }
        $filterParams = array(array("filter" => array("logic" => "AND", "filters" => $filter), "skip" => 0, "take" => 1000));
        $files = $this->getFileList($params, $filterParams);     
        $this->logger->info("The data returned from COI batch generation is : ". print_r($files['data'], true));
        if(empty($files['data'])){
            $data['jobStatus'] = 'No Records Found';
            $this->saveFile($data,$data['uuid']);      
            return $data;
        }   
        $COIdocument = array();
        $fileData = $files['data'];
        foreach ($fileData as $key => $value) {
            if(is_string($fileData[$key]['documents'])) { 
                $docs = json_decode($fileData[$key]['documents'],true); 
                if($data['reportProductType'] == 'individualProfessionalLiability' || $data['reportProductType'] =='emergencyFirstResponse'){ 
                   if(isset($docs['coi_document'])) {
                     $COIdocument[] = $this->destination.$docs['coi_document'][0];   
                   }
                }
                if($data['reportProductType'] == 'diveStore'){ 
                    $COIdocument[] = $this->destination.$docs['liability_coi_document'];
                    if(isset($docs['property_coi_document'])){
                        $COIdocument[] = $this->destination.$docs['property_coi_document'];
                    }
                    if(isset($docs['group_coi_document'])) {
                        $COIdocument[] = $this->destination.$docs['group_coi_document'];
                    }
                    if(isset($docs['endorsement_coi_document'])){
                        $totalendorsements = sizeOf($docs['endorsement_coi_document']);
                        $COIdocument[] = $this->destination.$docs['endorsement_coi_document'][$totalendorsements-1];
                    }
                }
                $this->logger->info("The list of all the documents : ". print_r($COIdocument, true)); 
            }
        }
        $orgUuid = isset($data['orgUuid']) ? $data['orgUuid'] : ( isset($data['orgId']) ? $data['orgId'] : AuthContext::get(AuthConstants::ORG_UUID));
        $dest = ArtifactUtils::getDocumentFilePath($this->destination, $data['uuid'], array('orgUuid' => $orgUuid));
        $docDest = $dest['absolutePath']."COIbatchReport.pdf";
        if(!empty($COIdocument)){
            $this->documentBuilder->mergePDF($COIdocument,$docDest);
            $data['documents']['BatchReport'] = $dest['relativePath']."COIbatchReport.pdf";
            if(isset($data['jobStatus']) && ($data['jobStatus']=='In Progress')){
                $data['jobStatus'] = 'Completed';
            }  
        }
        $this->saveFile($data,$data['uuid']); 
        return $data;
    }
}

?>