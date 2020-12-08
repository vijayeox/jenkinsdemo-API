<?php
use Oxzion\AppDelegate\MailDelegate;
use Oxzion\Db\Persistence\Persistence;
use Oxzion\Messaging\MessageProducer;
use Oxzion\Encryption\Crypto;
use Oxzion\Utils\FileUtils;
use Oxzion\Utils\ArtifactUtils;
use Oxzion\AppDelegate\FileTrait;
use Oxzion\DelegateException;
require_once __DIR__."/DispatchDocument.php";

class DispatchReinstatePolicyMail extends DispatchDocument
{ 
    use FileTrait;
    public $template;
    
    public function __construct(){
        $this->template = array(
            'Individual Professional Liability' => 'ReinstatePolicyMailTemplate',
            'Dive Boat' => 'ReinstatePolicyMailTemplate',
            'Emergency First Response' => 'ReinstatePolicyMailTemplate',
            'Group Professional Liability' => 'ReinstatePolicyMailTemplate',
            'Dive Store' => 'ReinstatePolicyMailTemplate');
           
        parent::__construct();
    }

    public function execute(array $data, Persistence $persistenceService)
    {
        $this->logger->info("Dispatch reinstate policy mail notification ".json_encode($data));
        $fileData = $this->getFile($data['fileId'], false, $data['orgId']);
        $data = array_merge($data, $fileData['data']);
        $mailData = $data;
        if (isset($mailData['documents']) && is_string($mailData['documents'])) {
            $mailData['documents'] = json_decode($mailData['documents'], true);
        }
        $fileData = array();
        if(isset($mailData['documents']['reinstate_doc'])){
            if(sizeof($mailData['documents']['reinstate_doc']) > 0){
                $file = $this->destination.end($mailData['documents']['reinstate_doc']);
                if(file_exists($file)){
                    array_push($fileData, $file);
                }else{
                    $this->logger->error("Reinstate Document Not Found - ".$file);
                    throw new DelegateException('Reinstate Document Not Found','file.not.found',0,array($file));
                }
            }else{
                $this->logger->error("Reinstate Document Not Found");
                    throw new DelegateException('Reinstate Document Not Found','file.not.found');
            }
        }else{
            $this->logger->error("Reinstate Document Not Found");
            throw new DelegateException('Reinstate Document Not Found','file.not.found');
        }
        if($data['product'] == 'Dive Store'){
            $subject = 'PADI Endorsed Dive Store Insurance Reinstatement - '.$data['business_padi'];
        }
        if($data['product'] == 'Group Professional Liability') {
            $subject = 'PADI Endorsed Group Professional Liability Insurance Reinstatement - '.$data['business_padi'];
        }
        else if($data['product'] == 'Dive Boat'){
            $subject = 'PADI Endorsed Dive Boat Insurance Reinstatement - '.$data['padi'];
        }
        else if($data['product'] == 'Group Professional Liability'){
            $subject = 'PADI Endorsed Insurance Reinstatement - '.$data['business_padi'];
        }else{
            $subject = 'PADI Endorsed Insurance Reinstatement - '.$data['padi'];
        }
        $mailData['subject'] = $subject;
        $mailData['template'] = $this->template[$data['product']];
        $mailData['document'] = $fileData;
        $response = $this->dispatch($mailData);
        $this->logger->info("Dispatch reinstate policy returning data --- ".json_encode($data));
        return $response;
    }
}
?>