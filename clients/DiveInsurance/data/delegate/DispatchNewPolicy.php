<?php
use Oxzion\AppDelegate\MailDelegate;
use Oxzion\Db\Persistence\Persistence;
use Oxzion\Messaging\MessageProducer;
use Oxzion\DelegateException;

require_once __DIR__."/DispatchDocument.php";


class DispatchNewPolicy extends DispatchDocument {

    public $template = array();
    public $document = array();
 
    public function __construct(){
        $this->template = array(
            'Individual Professional Liability' => 'COIPolicyMailTemplate',
            'Dive Boat' => 'diveBoatPolicyMailTemplate',
            'Dive Store' => 'diveStorePolicyMailTemplate',
            'Emergency First Response' => 'EFRMailTemplate');
        $this->document = array(
            'Individual Professional Liability' => array('docs' => ['coi_document','slWording','blanket_document','additionalInsured_document','scuba_fit_document','cylinder_document','equipment_liability_document']),
            'Dive Boat' => array('docs' => ['coi_document','cover_letter']),
            'Dive Store' => array('docs' => ['property_coi_document','liability_coi_document','cover_letter']),
            'Emergency First Response' => array('docs' => ['coi_document','additionalInsured_document']));
        $this->required = array(
            'Individual Professional Liability' => array('docs' => ['coi_document','blanket_document']),
            'Dive Boat' => array('docs' => ['coi_document','cover_letter']),
            'Dive Store' => array('docs' => ['Dive_Store_Liability_Policy.pdf','Dive_Store_Property_Policy.pdf','DiveStore_Property_COI','DiveStore_Liability_COI','cover_letter']),
            'Emergency First Response' => array('docs' => ['coi_document']));
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
        $document = array_keys($data['documents']);
        $this->logger->info("ARRAY DOCUMENT --- ".json_encode($document));
        $this->logger->info("REQUIRED DOCUMENT --- ".json_encode($this->required[$data['product']]['docs']));
        $document = array_intersect($this->required[$data['product']]['docs'], $document);
        $this->logger->info("INTERSECT DOCUMENT --- ".json_encode($document));
        if(count($this->required[$data['product']]['docs']) == count($document)){
            foreach($this->document[$data['product']]['docs'] as $file){
                if(array_key_exists($file,$data['documents'])){
                    if(is_array($data['documents'][$file])){
                            $doc = $data['documents'][$file][0];
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
        $data['subject'] = 'PADI Endorsed Insurance Documents - '.$data['padi'];
        $response = $this->dispatch($data);
        return $response;
    }
}
?>