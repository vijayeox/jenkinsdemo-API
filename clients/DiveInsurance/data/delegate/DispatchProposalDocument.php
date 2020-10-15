<?php
use Oxzion\AppDelegate\MailDelegate;
use Oxzion\Db\Persistence\Persistence;
use Oxzion\Messaging\MessageProducer;
use Oxzion\DelegateException;
use Oxzion\AppDelegate\FileTrait;
require_once __DIR__."/DispatchDocument.php";


class DispatchProposalDocument extends DispatchDocument {

    use FileTrait;
    public function __construct(){
        $this->template = array(
            'Dive Boat' => 'diveBoatProposalMailTemplate',
            'Dive Store' => 'diveStoreProposalMailTemplate',
            'Group Professional Liability' => 'diveStoreProposalMailTemplate');
        parent::__construct();
    }

    public function execute(array $data,Persistence $persistenceService)
    {
        $this->logger->info("Proposal DOCUMENT --- ".json_encode($data));
        $fileData = $this->getFile($data['fileId'],false,$data['orgId']);
        $data = array_merge($data,$fileData['data']);
        $data['template'] = $this->template[$data['product']];
        $documents = array();
        $documents = isset($data['documents']) ? (is_string($data['documents']) ? json_decode($data['documents'],true) : $data['documents']) : array();
        if(isset($data['previous_policy_data'])){
           $documents = isset($data['quoteDocuments']) ? (is_string($data['quoteDocuments']) ? json_decode($data['quoteDocuments'],true) : $data['quoteDocuments']) : array();
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
            } else {
                $this->logger->error("File Not Found".$file);
                array_push($errorFile,$file);
            }
        }

        if(isset($data['csrApprovalAttachments'])){
            foreach($data['csrApprovalAttachments'] as $doc){
                $file = $this->destination.$doc['file'];
                if(file_exists($file)){
                    array_push($fileData, $file);         
                } else {
                    $this->logger->error("File Not Found".$file);
                    array_push($errorFile,$file);
                }
            }
            $data['csrApprovalAttachments'] = array();
        }

        if(count($errorFile) > 0){
            $error = json_encode($errorFile);
            $this->logger->error("Documents Not Found".$error);
            throw new DelegateException('Documents Not Found','file.not.found',0,$errorFile);
        }
        if($data['product'] == 'Dive Store'){
            $subject = 'PADI Endorsed Dive Store Insurance Proposal - '.$data['business_padi'];
        }else if($data['product'] == 'Group Professional Liability'){
            $subject = 'PADI Endorsed Group Professional Liability Insurance Proposal - '.$data['business_padi'];
        }else if($data['product'] == 'Dive Boat'){
            $subject = 'PADI Endorsed Dive Boat Insurance Proposal - '.$data['padi'];
        }else{
            $subject = 'Proposal Document';
        }
        $data['document'] =$fileData;
        $data['subject'] = $subject;
        $data['url'] = $this->baseUrl. '?app=DiveInsurance&params={"name":"","detail":[{"type":"Form","url": "pipeline","urlPostParams":{"activityInstanceId":"'.$data['activityInstanceId'].'","workflowInstanceId":"'.$data['workflowInstanceId'].'","commands":[{"command":"claimForm"},{"command":"activityInstanceForm"}]}}]}';
        $response = $this->dispatch($data);
        return $response;
    }
}
?>