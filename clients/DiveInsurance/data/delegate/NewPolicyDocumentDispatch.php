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
            'Dive Store' => 'diveStorePolicyMailTemplate');
        parent::__construct();
    }

    
    public function execute(array $data,Persistence $persistenceService)
    {
        $this->logger->info("New Policy Document".json_encode($data));
        $data['template'] = $this->template[$data['product']];
        if(isset($data['documents']) && is_string($data['documents'])){
            $data['documents'] = json_decode($data['documents'],true);
        }

        if(isset($data['csrApprovalAttachments']) && is_string($data['csrApprovalAttachments'])){
            $data['csrApprovalAttachments'] = json_decode($data['csrApprovalAttachments'],true);
        }

        $fileData =array();
        $errorFile = array();
        foreach($data['documents'] as $doc){
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
        $data['document'] =$fileData;
        $data['subject'] = 'Certificate Of Insurance';
        $response = $this->dispatch($data);
        return $response;
    }
}
?>