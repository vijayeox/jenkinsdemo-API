<?php

use Oxzion\AppDelegate\MailDelegate;
use Oxzion\Db\Persistence\Persistence;
use Oxzion\Messaging\MessageProducer;
use Oxzion\DelegateException;

require_once __DIR__ . "/DispatchDocument.php";


class NewPolicyDocumentDispatch extends DispatchDocument
{

    public function __construct()
    {
        $this->template = array(
            'Dive Boat' => 'diveBoatPolicyMailTemplate',
            'Dive Store' => 'diveStorePolicyMailTemplate',
            'Group Professional Liability' => 'groupProfessionalLiabilityPolicyMailTemplate',
        );
        $this->newPolicyDoc = array(
            'Property' => array('cover_letter', 'property_coi_document', 'property_policy_document', 'loss_payee_document', 'premium_summary_document'),
            'Group' => array('cover_letter', 'group_coi_document', 'group_named_insured_document', 'group_additional_named_insured_document', 'group_additional_insured_document', 'group_policy_document', 'premium_summary_document', 'group_blanket_document', 'PocketCard')
        );
        parent::__construct();
    }


    public function execute(array $data, Persistence $persistenceService)
    {
        $this->logger->info("New Policy Document" . json_encode($data));
        $data['template'] = $this->template[$data['product']];
        $documents = array();
        $docType = "";
        $documents = isset($data['documents']) ? (is_string($data['documents']) ? json_decode($data['documents'], true) : $data['documents']) : array();
        if (isset($data['previous_policy_data'])) {
            $documents = isset($data['mailDocuments']) ? (is_string($data['mailDocuments']) ? json_decode($data['mailDocuments'], true) : $data['mailDocuments']) : array();
            if (isset($documents['endorsement_coi_document'])) {
                $endoDoc = end($documents['endorsement_coi_document']);
                $documents['endorsement_coi_document'] = $endoDoc;
            }
        } else if ($data['product'] == 'Dive Store') {
            if ($data['propertyCoverageSelect'] == "yes") {
                $docType = "Property";
                $mailDoc = array();
                $mailDoc = $this->getDocumentList($documents, $docType);
                $this->dispatchDocument($data, $mailDoc, $docType);
            }
            if ($data['groupProfessionalLiabilitySelect'] == "yes" && $data['product'] == 'Dive Store') {
                $docType = "Group";
                $mailDoc = array();
                $mailDoc = $this->getDocumentList($documents, $docType);
                $this->dispatchDocument($data, $mailDoc, $docType);
            }
            $docType = "General Liability";
        }
        if(isset($data['csrmailDocuments']) && $data['csrmailDocuments'] !== ""){
            $documents =$this->getSelectedDocuments($data['csrmailDocuments'],$documents);
        }
        $this->dispatchDocument($data, $documents, $docType);
    }

    private function getDocumentList(&$documents, $docType)
    {
        $document = array();
        foreach ($documents as $key => $doc) {
            if (in_array($key, $this->newPolicyDoc[$docType])) {
                array_push($document, $doc);
                if ($key != 'premium_summary_document' || $key != 'cover_letter') {
                    unset($documents[$key]);
                }
            }
        }
        return $document;
    }

    private function dispatchDocument($data, $documents, $docType)
    {
        if (sizeof($documents) == 0) {
            return;
        }
        if (isset($data['csrApprovalAttachments']) && is_string($data['csrApprovalAttachments'])) {
            $data['csrApprovalAttachments'] = json_decode($data['csrApprovalAttachments'], true);
        }
        $fileData = array();
        $errorFile = array();
        foreach ($documents as $doc) {
            if (is_array($doc)) {
                $doc = end($doc);
            }
            $file = $this->destination . $doc;
            if (file_exists($file)) {
                array_push($fileData, $file);
            } else {
                $this->logger->error("File Not Found" . $file);
                array_push($errorFile, $file);
            }
        }


        if (isset($data['csrApprovalAttachments'])) {
            foreach ($data['csrApprovalAttachments'] as $doc) {
                if (isset($doc['file'])) {
                    $file = $this->destination . $doc['file'];
                    if (file_exists($file)) {
                        array_push($fileData, $file);
                    } else {
                        $this->logger->error("File Not Found" . $file);
                        array_push($errorFile, $file);
                    }
                }
            }
            $data['csrApprovalAttachments'] = array();
        }
        if ($data['product'] == 'Dive Store') {
            $subject = 'PADI Endorsed Dive Store Insurance ' . $docType . ' Documents – ' . $data['business_padi'];
        } else if ($data['product'] == 'Dive Boat') {
            $subject = 'PADI Endorsed Dive Boat Insurance Documents – ' . $data[$data['identifier_field']];
        } else {
            $subject = 'Certificate of Insurance';
        }
        if (count($errorFile) > 0) {
            $error = json_encode($errorFile);
            $this->logger->error("Documents Not Found" . $error);
            throw new DelegateException('Documents Not Found', 'file.not.found', 0, $errorFile);
        }
        $data['document'] = $fileData;
        $data['subject'] = $subject;
        $this->dispatch($data);
    }
}
