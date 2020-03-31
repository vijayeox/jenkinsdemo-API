<?php
use Oxzion\AppDelegate\MailDelegate;
use Oxzion\Db\Persistence\Persistence;
use Oxzion\Messaging\MessageProducer;
use Oxzion\DelegateException;

require_once __DIR__."/DispatchDocument.php";


class DocumentValidation extends DispatchDocument {

    public $template = array();
    public $document = array();
    
    protected function validateDocuments(array $data,array $requiredDocuments)
    {
        $this->logger->info("DOCUMENT Validation ---");
        $fileData = array();
        $errorFile = array();
        if(isset($data['documents']) && is_string($data['documents'])){
            $data['documents'] = json_decode($data['documents'],true);
        }
        $document = array_keys($data['documents']);
        $this->logger->info("ARRAY DOCUMENT --- ".print_r($document,true));
        $this->logger->info("REQUIRED DOCUMENT --- ".print_r($requiredDocuments,true));
        $document = array_intersect($requiredDocuments, $document);
        $this->logger->info("INTERSECT DOCUMENT --- ".print_r($document,true));
        if(count($requiredDocuments) == count($document)){
            foreach($requiredDocuments as $file){
                if(array_key_exists($file,$data['documents'])){

                    $file = $this->destination.$data['documents'][$file];
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
        return $fileData;
    }
}
?>