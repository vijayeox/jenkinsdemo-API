<?php

use Oxzion\Db\Persistence\Persistence;
use Oxzion\Utils\ArtifactUtils;
use Oxzion\Utils\FileUtils;

require_once __DIR__ . "/PolicyDocument.php";


class CancelPolicy extends PolicyDocument
{
    protected $documentBuilder;
    protected $type;
    protected $template;

    public function __construct()
    {
        parent::__construct();
        $this->type = 'cancel';
        $this->template = array(
            'Individual Professional Liability'
            => array(
                'template' => 'Cancellation_Approval',
                'header' => 'Cancellation_header.html',
                'footer' => 'Cancellation_footer.html'
            ),
            'Emergency First Response'
            => array(
                'template' => 'Cancellation_Approval',
                'header' => 'Cancellation_header.html',
                'footer' => 'Cancellation_footer.html'
            ),
            'Dive Boat'
            => array(
                'template' => 'Cancellation_Approval',
                'header' => 'Cancellation_header.html',
                'footer' => 'Cancellation_footer.html'
            ),
            'Dive Store'
            => array(
                'template' => 'Cancellation_Approval',
                'header' => 'Cancellation_header.html',
                'footer' => 'Cancellation_footer.html'
            ),
            'Group Professional Liability'
            => array(
                'template' => 'Cancellation_Approval',
                'header' => 'Cancellation_header.html',
                'footer' => 'Cancellation_footer.html'
            )
        );
    }

    public function execute(array $data, Persistence $persistenceService)
    {
        $this->logger->info("Executing Cancel Policy with data- " . json_encode($data));
        if (isset($data['cancellationDate'])) {
            $data['cancellationDate'] = "";
        }
        $options = array();
        foreach ($data as $key => $row) {
            if (is_array($row)) {
                $data[$key] = json_encode($row);
            }
        }
        $value = json_decode($data['reasonforCsrCancellation'], true);
        $data['reasonforCsrCancellation'] = $value['value'];
        $data['reinstateDocuments'] = $data['documents'];
        $data['reasonforRejection'] = isset($data['reasonforRejection']) ? $data['reasonforRejection'] : "Not Specified";
        $data['cancelDate'] = isset($data['cancelDate']) ? $data['cancelDate'] : date_create()->format("Y-m-d");
        $data['policyStatus'] = "Cancelled";
        $data['carrierName'] = "";
        $data['policyId'] = "";
        $data['propPolicyId'] = "";
        $data['groupPolicyId'] = "";

        if ($data['product'] == "Dive Store") {
            $data['carrierName'] = "Policy issued by " . $data['liability_carrier'];
            $data['policyId'] = "Policy #:" . $data['liability_policy_id'];
            if ($data['propertyCoverageSelect'] == "yes") {
                $data['propPolicyId'] = "Policy #:" . $data['property_policy_id'];
            }
            if ($data['groupProfessionalLiabilitySelect'] == "yes") {
                $data['groupPolicyId'] = "Policy #:" . $data['group_policy_id'];
            }
        } else if ($data['product'] == "Group Professional Liability") {
            $data['carrierName'] = "Policy issued by " . $data['group_carrier'];
            $data['policyId'] = "Policy #:" . $data['group_policy_id'];
        } else {
            $data['carrierName'] = "Policy issued by " . $data['carrier'];
            $data['policyId'] = "Policy #:" . $data['policy_id'];
        }
        $data['confirmReinstatePolicy'] = '';
        if ($data['reasonforCsrCancellation'] == 'nonPaymentOfPremium') {
            $this->logger->info("Processing nonPaymentOfPremium");
            $temp = date_create();
            $data['ReinstatePolicyPeriod'] = $temp->add(new DateInterval("P10D"))->format("Y-m-d");
        } else if ($data['reasonforCsrCancellation'] == 'padiMembershipNotCurrent') {
            $this->logger->info("Processing padiMembershipNotCurrent");
            $temp = date_create();
            $data['ReinstatePolicyPeriod'] = $temp->add(new DateInterval("P45D"))->format("Y-m-d");
        }
        if (isset($data['state'])) {
            $data['state_in_short'] = $this->getStateInShort($data['state'], $persistenceService);
        }
        $orgUuid = isset($data['orgUuid']) ? $data['orgUuid'] : (isset($data['orgId']) ? $data['orgId'] : AuthContext::get(AuthConstants::ORG_UUID));
        $dest = ArtifactUtils::getDocumentFilePath($this->destination, $data['fileId'], array('orgUuid' => $orgUuid));
        $this->logger->info('the  destination consists of : ' . print_r($dest, true));
        if (file_exists($dest['absolutePath'] . 'Cancellation_Approval.pdf')) {
            $workflowInstUuid = $this->getWorkflowInstanceByFileId($data['fileId'], 'In Progress');
            if (count($workflowInstUuid) > 0 && (isset($workflowInstUuid[0]['process_instance_id']))) {
                $dest['absolutePath'] .= $workflowInstUuid[0]['process_instance_id'] . "/";
                $dest['relativePath'] .= $workflowInstUuid[0]['process_instance_id'] . "/";
                FileUtils::createDirectory($dest['absolutePath']);
            }
        }
        $this->logger->info("execute generate documents");
        $data['documents'] = array("cancel_doc" => $this->generateDocuments($data, $dest, $options, 'template', 'header', 'footer'));
        unset($data['carrierName']);
        unset($data['policyId']);
        unset($data['propPolicyId']);
        unset($data['groupPolicyId']);
        return $data;
    }
}