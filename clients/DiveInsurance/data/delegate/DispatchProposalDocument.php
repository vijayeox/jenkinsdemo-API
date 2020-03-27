<?php
use Oxzion\AppDelegate\MailDelegate;
use Oxzion\Db\Persistence\Persistence;
use Oxzion\Messaging\MessageProducer;
use Oxzion\DelegateException;

require_once __DIR__."/DispatchDocument.php";


class DispatchProposalDocument extends DispatchDocument {

    public $template = array();
    public $document = array();
 
    public function __construct(){
        $this->template = array(
            'Dive Boat' => 'diveBoatProposalMailTemplate',
            'Dive Store' => 'diveStoreProposalMailTemplate');
        $this->document = array(
            'Dive Boat' => array('docs' => ['coi_document','cover_letter','group_coi_document','slWording','loss_payee_document','additionalInsured_document','additionalNamedInsured_document','additionalLocations_document','named_insured_document']),
            'Dive Store' => array('docs' => ['coi_document','cover_letter']));
        $this->required = array(
            'Dive Boat' => array('docs' => ['coi_document','cover_letter']),
            'Dive Store' => array('docs' => ['coi_document','cover_letter']));
        parent::__construct();
    }

    
    public function execute(array $data,Persistence $persistenceService)
    {
        $fileData = array();
        $errorFile = array();
        $data['template'] = $this->template[$data['product']];
        if(isset($data['documents']) && is_string($data['documents'])){
            $data['documents'] = json_decode($data['documents'],true);
        }
        if(isset($data['documents'])){
            $document = array_keys($data['documents']);
        }
        $this->logger->info("ARRAY DOCUMENT --- ".print_r($document,true));
        $this->logger->info("REQUIRED DOCUMENT --- ".print_r($this->required[$data['product']]['docs'],true));
        $document = array_intersect($this->required[$data['product']]['docs'], $document);
        $this->logger->info("INTERSECT DOCUMENT --- ".print_r($document,true));
        if(count($this->required[$data['product']]['docs']) == count($document)){
            foreach($this->document[$data['product']]['docs'] as $file){
                if(array_key_exists($file,$data['documents'])){
                    if($file == 'coi_document'){
                        if(is_array($data['documents'][$file])){
                            $doc = end($data['documents'][$file]);
                        }else{
                            $doc = $data['documents'][$file];
                        }
                    }else{
                        $doc = $data['documents'][$file];
                    }
                    $file = $this->destination.$doc;
                    if(file_exists($file)){
                         array_push($fileData, $file);         
                    }else{
                        $this->logger->error("File Not Found".$file);
                        array_push($errorFile,$file);
                    }
                }
            }


            if(count($errorFile) > 0){
                $error = json_encode($errorFile);
                $this->logger->error("Documents Not Found".$error);
                throw new DelegateException('Documents Not Found','file.not.found',0,$errorFile);
            }
        }else{
           $this->logger->error("Required Documents are not Found");     
           throw new DelegateException('Required Documents are not Found','file.not.found');
        }
        $data['document'] =$fileData;
        $data['subject'] = 'Proposal Document';
        $data['url'] = $this->baseUrl. '?app=DiveInsurance&params={"type":"Form","activityInstanceId":"'.$data['activityInstanceId'].'","workflowInstanceId":"'.$data['workflowInstanceId'].'"}';
        $response = $this->dispatch($data);
        return $response;
    }
}
?>