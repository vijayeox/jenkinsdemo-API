<?php
use Oxzion\AppDelegate\MailDelegate;
use Oxzion\Db\Persistence\Persistence;
use Oxzion\Messaging\MessageProducer;
use Oxzion\DelegateException;

require_once __DIR__."/DispatchDocument.php";


class NewPolicyDocumentDispatch extends DispatchDocument {

    public function __construct(){
        $this->template = array(
            'Dive Boat' => 'diveBoatPolicyMailTemplate',
            'Dive Store' => 'diveStorePolicyMailTemplate',
            'Group Professional Liability' => 'groupProfessionalLiabilityPolicyMailTemplate',
        );
        parent::__construct();
    }


    public function execute(array $data,Persistence $persistenceService)
    {
        $this->logger->info("New Policy Document".json_encode($data));
        $data['template'] = $this->template[$data['product']];
        $documents = array();
        $documents = isset($data['documents']) ? (is_string($data['documents']) ? json_decode($data['documents'],true) : $data['documents']) : array();
        if(isset($data['previous_policy_data'])){
            $documents = isset($data['mailDocuments']) ? (is_string($data['mailDocuments']) ? json_decode($data['mailDocuments'],true) : $data['mailDocuments']) : array();
            if(isset($documents['endorsement_coi_document'])){
                $endoDoc = end($documents['endorsement_coi_document']);
                $documents['endorsement_coi_document'] = $endoDoc;
            }
        }
        if(isset($data['csrApprovalAttachments']) && is_string($data['csrApprovalAttachments'])){
            $data['csrApprovalAttachments'] = json_decode($data['csrApprovalAttachments'],true);
        }
        $fileData =array();
        $errorFile = array();
        foreach($documents as $doc){
            if(is_array($doc)){
                $doc = end($doc);
            }
            $file = $this->destination.$doc;
            if(file_exists($file)){
                 array_push($fileData, $file);
            }else{
                $this->logger->error("File Not Found".$file);
                array_push($errorFile,$file);
            }
        }


        if(isset($data['csrApprovalAttachments'])){
            foreach($data['csrApprovalAttachments'] as $doc){
              if(isset($doc['file'])){
                  $file = $this->destination.$doc['file'];
                  if(file_exists($file)){
                      array_push($fileData, $file);
                  } else {
                      $this->logger->error("File Not Found".$file);
                      array_push($errorFile,$file);
                  }
              }
            }
            $data['csrApprovalAttachments'] = array();
        }
        if($data['product'] == 'Dive Store'){
            $subject = 'PADI Endorsed Dive Store Insurance Documents – '.$data['business_padi'];
        }else if($data['product'] == 'Dive Boat'){
            $subject = 'PADI Endorsed Dive Boat Insurance Documents – '.$data[$data['identifier_field']];
        }else{
            $subject = 'Certificate of Insurance';
        }
        if(count($errorFile) > 0){
            $error = json_encode($errorFile);
            $this->logger->error("Documents Not Found".$error);
            throw new DelegateException('Documents Not Found','file.not.found',0,$errorFile);
        }
        $data['document'] =$fileData;
        $data['subject'] = $subject;
        $response = $this->dispatch($data);
        return $response;
    }
}
?>
