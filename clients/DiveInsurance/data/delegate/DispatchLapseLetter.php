<?php
use Oxzion\AppDelegate\MailDelegate;
use Oxzion\Db\Persistence\Persistence;
use Oxzion\Messaging\MessageProducer;
use Oxzion\Encryption\Crypto;
require_once __DIR__."/DispatchDocument.php";


class DispatchLapseLetter extends DispatchDocument {

    public $template;

    public function __construct(){
        parent::__construct();
    }

    public function execute(array $data,Persistence $persistenceService)
    {
        $this->logger->info("Dispatch Lapse Letter");
        $data['template'] = 'Individual_PL_Lapse_Letter';
        $fileData = array();
        if(isset($data['documents']['lapseLetter'])){
            $file = $this->destination.$data['documents']['lapseLetter'];
            if(file_exists($file)){
                 array_push($fileData, $file);
            }else{
                $this->logger->error("Lapse Letter Not Found - ".$file);
                throw new DelegateException('Lapse Letter Not Found','file.not.found',0,array($file));
            }
        }else{
            $this->logger->error("Lapse Letter Not Found");
            throw new DelegateException('Lapse Letter Not Found','file.not.found');
        }
        $mailData = array();
        $mailData = $data;
        $mailData['email'] = $data['email'];
        if(isset($data['padi'])){
            $mailData['subject'] = 'PADI Endorsed Insurance Lapse – '.$data['padi'];
        } else {
            if(isset($data['business_padi'])){
                $mailData['subject'] = 'PADI Endorsed Insurance Lapse – '.$data['business_padi'];
            }
        }
        $mailData['template'] = $data['template'];
        $mailData['document'] = $fileData;
        $response = $this->dispatch($mailData);
        return $data;
    }
}
?>
