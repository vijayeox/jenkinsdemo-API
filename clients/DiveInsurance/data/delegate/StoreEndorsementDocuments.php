<?php


require_once __DIR__."/EndorsementDocument.php";
use Oxzion\Db\Persistence\Persistence;
use Oxzion\Utils\ArtifactUtils;
use Oxzion\Utils\FileUtils;

class StoreEndorsementDocuments extends EndorsementDocument
{

    public function execute(array $data,Persistence $persistenceService)
    {
        $originalData = $data;
        $this->processSurplusYear($data);
        $data['state_in_short'] = $this->getStateInShort($data['state'],$persistenceService);
        $data['license_number'] = $this->getLicenseNumber($data,$persistenceService);
        $options = array();
        if(isset($data['endorsement_options'])){
            $endorsementOptions = is_array($data['endorsement_options']) ?  $data['endorsement_options'] : json_decode($data['endorsement_options'],true);
        }else{
            $endorsementOptions = null;
        }

        if(isset($data['documents'])){
            if(is_string($data['documents'])){
                $data['documents'] = json_decode($data['documents'],true);
            }
        }
        if(isset($data['documents']['endorsement_coi_document'])){
            $length = sizeof($data['documents']['endorsement_coi_document']) + 1;
        }else{
            $length = 1;
        }
        $certificate_no = explode("-",$data['certificate_no']);
        $data['certificate_no'] = $certificate_no[0].' - '.$length;
        
        $orgUuid = isset($data['orgUuid']) ? $data['orgUuid'] : ( isset($data['orgId']) ? $data['orgId'] :AuthContext::get(AuthConstants::ORG_UUID));
        $data['orgUuid'] = $orgUuid;
        $liabilityPolicyDetails = $this->getPolicyDetails($data,$persistenceService,$data['product'],'LIABILITY');
        if($liabilityPolicyDetails){
            $data['liability_policy_id'] = $liabilityPolicyDetails['policy_number'];
            $data['liability_carrier'] = $liabilityPolicyDetails['carrier'];
        }

        $propertyPolicyDetails = $this->getPolicyDetails($data,$persistenceService,$data['product'],'PROPERTY');
        if($propertyPolicyDetails){
            $data['property_policy_id'] = $propertyPolicyDetails['policy_number'];
            $data['property_carrier'] = $propertyPolicyDetails['carrier'];
        }
        if(isset($data['groupPL'])){
            if($data['groupProfessionalLiabilitySelect'] == 'yes'){
                $policyDetails = $this->getPolicyDetails($data,$persistenceService,'Group Professional Liability');
                if($policyDetails){
                    $data['group_policy_id'] = $policyDetails['policy_number'];
                    $data['group_carrier'] = $policyDetails['carrier'];
                }
            }
        }
        $dest = ArtifactUtils::getDocumentFilePath($this->destination,$data['fileId'],array('orgUuid' => $orgUuid));

        if(!is_null($endorsementOptions)){
            $workflowInstUuid = $this->getWorkflowInstanceByFileId($data['fileId'],'In Progress');
            if( count($workflowInstUuid) > 0 && (isset($workflowInstUuid[0]['process_instance_id']))){
                $dest['absolutePath'] .= $workflowInstUuid[0]['process_instance_id']."/";
                $dest['relativePath'] .= $workflowInstUuid[0]['process_instance_id']."/";
                FileUtils::createDirectory($dest['absolutePath']);
            }
        }

        $data['dest'] = $dest;
        FileUtils::deleteDirectoryContents($dest['absolutePath'].'Preview/');
        $dest['relativePath'] = $dest['relativePath'].'Preview/';
        $dest['absolutePath'] = $dest['absolutePath'].'Preview/';
        $documents = array();
        $temp = $data;
        foreach ($temp as $key => $value) {
            if(is_array($temp[$key])){
                $temp[$key] = json_encode($value);
            }
        }
        if(isset($data['previous_policy_data'])){
            $previous_data = array();
            $previous_data = is_string($data['previous_policy_data']) ? json_decode($data['previous_policy_data'],true) : $data['previous_policy_data'];
            $length = sizeof($previous_data);
        }else{
            $previous_data = array();
            $length = 0;
        }

        $this->diveStoreEndorsement($data,$temp,$persistenceService);
        if(isset($this->template[$temp['product']]['cover_letter'])){
            $this->logger->info("DOCUMENT cover_letter");
            $documents['cover_letter'] = $this->generateDocuments($temp,$dest,$options,'cover_letter','lheader','lfooter');
        }
        if(isset($this->template[$data['product']]['instruct'])){
            $this->logger->info("DOCUMENT instruct");
            $documents['instruct'] = $this->copyDocuments($data,$dest['relativePath'],'instruct');
        }

        if(isset($this->template[$temp['product']]['businessIncomeWorksheet']))   {
            $documents['businessIncomeWorksheet'] = $this->copyDocuments($temp,$dest['relativePath'],'businessIncomeWorksheet');
        }
        

        if(isset($temp['lossPayees']) && $temp['lossPayeesSelect']=="yes"){
            $this->logger->info("DOCUMENT lossPayees");
            $documents['loss_payee_document'] = $this->generateDocuments($temp,$dest,$options,'lpTemplate','lpheader','lpfooter');
        }

        if(isset($temp['additionalLocations']) && $temp['additionalLocationsSelect']=="yes"){
          $addLocations = $temp['additionalLocations'];
          unset($temp['additionalLocations']);
            if(is_string($addLocations)){
                $additionalLocations = json_decode($addLocations,true);
            } else {
                $additionalLocations = $addLocations;
            }
            for($i=0; $i<sizeof($additionalLocations);$i++){
                $this->logger->info("DOCUMENT additionalLocations (additional named insuredes");
                $temp["additionalLocationData"] = json_encode($additionalLocations[$i]);
                $documents['additionalLocations_document_'.$i] = $this->generateDocuments($temp,$dest,$options,'alTemplate','alheader','alfooter',$i,0,true);
                unset($temp["additionalLocationData"]);
            }
        }

        if(isset($this->template[$temp['product']]['blanketForm'])){
            $this->logger->info("DOCUMENT blanketForm");
            $documents['blanket_document'] = $this->copyDocuments($temp,$dest['relativePath'],'blanketForm');
        }
        if($this->type == 'endorsement') {
            $documents['endorsement_coi_document'] = $this->generateDocuments($temp,$dest,$options,'template','header','footer');
        }

        if($endorsementOptions['modify_groupProfessionalLiability'] == true){
            if(isset($data['groupPL'])){
                if($data['groupProfessionalLiabilitySelect'] == 'yes'){
                    if(isset($data['group_certificate_no'])){
                        $grp_certificate_no = explode("-",$data['group_certificate_no']);
                        $temp['group_certificate_no'] = $data['group_certificate_no'] = $grp_certificate_no[0];
                    }else{
                        $temp['group_certificate_no'] = $data['group_certificate_no'] = 'S123456789';
                    }
                }
            }
            if(isset($temp['groupPL']) && $temp['groupProfessionalLiabilitySelect'] == 'yes'){
                $this->generateGroupDocuments($data,$temp,$documents,$previous_data,$endorsementOptions,$dest,$options,$length);
            }
        }
        if(isset($data['documents']['premium_summary_document'])){
            $documents['premium_summary_document'] = $data['documents']['premium_summary_document'];    
        }
        $originalData['finalDocuments'] = $documents;
        return $originalData;
    }
}
