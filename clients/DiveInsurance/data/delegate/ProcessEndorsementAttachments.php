<?php

use Oxzion\AppDelegate\AbstractAppDelegate;
use Oxzion\Db\Persistence\Persistence;

class ProcessEndorsementAttachments extends AbstractAppDelegate
{
    public function __construct(){
        parent::__construct();
    }

    // Padi Verification is performed here
    public function execute(array $data,Persistence $persistenceService)
    {

        $this->processAttachments($data,'endor_cylinderInstructor_attachments','cylinderInstructor_attachments');
        $this->processAttachments($data,'endor_cylinderInspector_attachments','cylinderInspector_attachments');
        $this->processAttachments($data,'endor_techRec_attachments','TechRec_attachments');
        $this->processAttachments($data,'endor_scubaFit_attachments','scubaFit_attachments');
        $this->processAttachments($data,'endor_attachments','attachments');
        return $data;
    }

    private function processAttachments(array &$data, $srcAttach, $destAttach){
        $this->logger->info("processing attachment for ----".print_r($srcAttach,true));
        $this->logger->info("processing attachment destination ----".print_r($destAttach,true));
        if(isset($data[$srcAttach]))
            $attachment = $data[$srcAttach];

        if(!isset($attachment)){
            return;
        }
        if(is_string($attachment)){
            $attachment = json_decode($attachment,true);
        }
        if(isset($data[$destAttach])){
            $destinationAttachment = $data[$destAttach];
        } else {
            $data[$destAttach] = array();
            $destinationAttachment = array();
        }
        if(is_string($destinationAttachment)){
            $destinationAttachment = json_decode($destinationAttachment,true);
        }
        if(is_array($attachment) && !empty($attachment)){
            foreach ($attachment as $key => $value) {
                $this->logger->info("Inside the loop ----".print_r($value,true));
                $destinationAttachment[] = $value;
                $this->logger->info("Inside the loop destinationAttachment----".print_r($destinationAttachment,true));
            }
        }
        $data[$srcAttach] = array();
        $data[$destAttach] = $destinationAttachment;
        $this->logger->info("DATA PROCESS ATTACHMENT----".print_r($data,true));
    }
}