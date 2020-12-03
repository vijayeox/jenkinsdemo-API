<?php

use Oxzion\Db\Persistence\Persistence;
use Oxzion\Utils\ArtifactUtils;
use Oxzion\Utils\FileUtils;
use Oxzion\Auth\AuthContext;
use Oxzion\Auth\AuthConstants;

require_once __DIR__ . "/PolicyDocument.php";


class ReinstatePolicy extends PolicyDocument
{
    protected $documentBuilder;
    protected $type;
    protected $template;

    public function __construct()
    {
        parent::__construct();
        $this->type = 'reinstate';
        $this->template = array(
            'Individual Professional Liability'
            => array(
                'template' => 'Reinstatement_Approval',
                'header' => 'Reinstate_header.html',
                'footer' => 'Cancellation_footer.html'
            ),
            'Emergency First Response'
            => array(
                'template' => 'Reinstatement_Approval',
                'header' => 'Reinstate_header.html',
                'footer' => 'Cancellation_footer.html'
            ),
            'Dive Boat'
            => array(
                'template' => 'Reinstatement_Approval',
                'header' => 'Reinstate_header.html',
                'footer' => 'Cancellation_footer.html'
            ),
            'Dive Store'
            => array(
                'template' => 'Reinstatement_Approval',
                'header' => 'Reinstate_header.html',
                'footer' => 'Cancellation_footer.html'
            ),
            'Group Professional Liability'
            => array(
                'template' => 'Reinstatement_Approval',
                'header' => 'Reinstate_header.html',
                'footer' => 'Cancellation_footer.html'
            )
        );
    }

    public function execute(array $data, Persistence $persistenceService)
    {
        $this->logger->info("Executing Reinstate Policy with data- " . json_encode($data));
        $options = array();
        foreach ($data as $key => $row) {
            if (is_array($row)) {
                $data[$key] = json_encode($row);
            }
        }
        $data['carrierName'] = "";
        $data['policyId'] = "";
        $data['propPolicyId'] = "";
        $data['groupPolicyId'] = "";
        $data['propCarrierName'] = "";
        $data['groupCarrierName'] = "";
        $data['coverageTitle'] = "";
        $data['multiplePolicy'] = "no";
        if ($data['product'] == "Dive Store") {
            $data['carrierName'] = "Liability Policy issued by " . $data['liability_carrier'];
            $data['policyId'] = "Liability Policy #:" . $data['liability_policy_id'];
            $data['coverageTitle'] = "GENERAL LIABILITY";
            if ($data['propertyCoverageSelect'] == "yes") {
                $data['multiplePolicy'] = "yes";
                $data['propCarrierName'] = "Property Policy issued by " . $data['property_carrier'];
                $data['propPolicyId'] = "Property Policy #:" . $data['property_policy_id'];
                $data['coverageTitle'] = "GENERAL LIABILITY,PROPERTY";
            }
            if ($data['groupProfessionalLiabilitySelect'] == "yes") {
                $data['multiplePolicy'] = "yes";
                $data['groupCarrierName'] = "Group Policy issued by " . $data['group_carrier'];
                $data['groupPolicyId'] = "Group Policy #:" . $data['group_policy_id'];
                $data['coverageTitle'] = "GENERAL LIABILITY,PROPERTY,GROUP PROFESSIONAL LIABILITY";
            }
        } else if ($data['product'] == "Group Professional Liability") {
            $data['carrierName'] = "Policy issued by " . $data['group_carrier'];
            $data['policyId'] = "Policy #:" . $data['group_policy_id'];
            $data['coverageTitle'] = "GROUP PROFESSIONAL LIABILITY";
        } else if($data['product'] == "Individual Professional Liability"){
            $data['carrierName'] = "Policy issued by " . $data['carrier'];
            $data['policyId'] = "Policy #:" . $data['policy_id'];
            $data['coverageTitle'] = "INDIVIDUAL PROFESSIONAL LIABILITY";
        }else if($data['product'] == "Emergency First Response"){
            $data['carrierName'] = "Policy issued by " . $data['carrier'];
            $data['policyId'] = "Policy #:" . $data['policy_id'];
            $data['coverageTitle'] = "EMERGENCY FIRST RESPONSE";
        }
        if (isset($data['state'])) {
            $data['state_in_short'] = $this->getStateInShort($data['state'], $persistenceService);
        }
        if(isset($data[$data['jobName']])){
            $data[$data['jobName']] = "";
        }
        if(isset($data['reasonforRejection'])){
            $data['reasonforRejection'] = '';
        }
        if(isset($data['userCancellationReason'])){
            $data['userCancellationReason'] = '';
        }
        if(isset($data['othersCsr'])){
            $data['othersCsr'] = '';
        }
        if(isset($data['reinstateAmount'])){
            $data['reinstateAmount'] = '';
        }
        if(isset($data['reasonforCsrCancellation'])){
            $data['reasonforCsrCancellation'] = '';
        }
        if(isset($data['cancellationStatus'])){
            $data['cancellationStatus'] = '';
        }
        if(isset($data['csrCancellationReason'])){
            $data['csrCancellationReason'] = '';
        }
        if(isset($data['othersUser'])){
            $data['othersUser'] = '';
        }
        if(isset($data['reasonForUserCancellation'])){
            $data['reasonForUserCancellation'] = '';
        }
        if(isset($data['userAgreement'])){
            $data['userAgreement'] = '';
        }
        $data['policyStatus'] = "In Force";
        
        $orgUuid = isset($data['orgUuid']) ? $data['orgUuid'] : (isset($data['orgId']) ? $data['orgId'] : AuthContext::get(AuthConstants::ORG_UUID));
        $dest = ArtifactUtils::getDocumentFilePath($this->destination, $data['fileId'], array('orgUuid' => $orgUuid));
        $this->logger->info('the  destination consists of : ' . print_r($dest, true));
        if(isset($data['previous_policy_data'])){
            $previous_policy = is_string($data['previous_policy_data']) ? json_decode($data['previous_policy_data'],true) : $data['previous_policy_data'];
            if(sizeof($previous_policy) > 0){
                $workflowInstUuid = $this->getWorkflowInstanceByFileId($data['fileId'], 'In Progress');
                if (count($workflowInstUuid) > 0 && (isset($workflowInstUuid[0]['process_instance_id']))) {
                    $dest['absolutePath'] .= $workflowInstUuid[0]['process_instance_id'] . "/";
                    $dest['relativePath'] .= $workflowInstUuid[0]['process_instance_id'] . "/";
                    FileUtils::createDirectory($dest['absolutePath']);
                }
            }
        }
        $this->logger->info("execute generate documents");
        $reinstateDoc = $this->generateDocuments($data, $dest, $options, 'template', 'header', 'footer');
        $data['documents'] = isset($data['documents']) ? (is_string($data['documents']) ?  json_decode($data['documents'],true) : $data['documents']) : array();
        if (isset($data['documents']['reinstate_doc'])) {
            $data['documents']['reinstate_doc'] = is_string($data['documents']['reinstate_doc']) ? json_decode($data['documents']['reinstate_doc'], true) : $data['documents']['reinstate_doc'];
        }else{
            $data['documents']['reinstate_doc'] = array();
        }
        array_push($data['documents']['reinstate_doc'], $reinstateDoc);
        unset($data['carrierName']);
        unset($data['policyId']);
        unset($data['propPolicyId']);
        unset($data['groupPolicyId']);
        unset($data['multiplePolicy']);
        unset($data['coverageTitle']);
        return $data;
    }
}