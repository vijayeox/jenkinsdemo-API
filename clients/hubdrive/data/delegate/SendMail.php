<?php

use Oxzion\AppDelegate\MailDelegate;
use Oxzion\Db\Persistence\Persistence;
use Oxzion\AppDelegate\FileTrait;
class SendMail extends MailDelegate
{
    use FileTrait;
    public function setDocumentPath($destination)
    {
        $this->destination = $destination;
        $this->attachmentList = array('attachment1','attachment2','attachment3','attachment4','attachment5','attachment6','attachment7','attachment8','attachment9');
    }

    public function execute(array $data, Persistence $persistenceService)
    {
        $this->logger->info("Executing Excess Mail with data- " . print_r($data, true));
        $fileData = $this->getFile($data['fileId'],false,$data['accountId']);
        $file = array_merge($data,$fileData['data']);
        // Add logs for created by id and producer name who triggered submission
        $mailOptions = [];
        $fileData = array();
        if($data['mailType'] == "QuoteMailtoHub" || $data['mailType'] == "RequestForMoreInfoMail" || $data['mailType'] == "PolicyMailtoHub"){
            $mailOptions['to'] = $data['mailAddress'];
        }else{
            $mailOptions['to'] = $this->getMailToAddress($data['mailAddress'],$persistenceService);
        }
        $mailOptions['subject'] = $this->getSubjectLine($data['mailType'],$data);
        $mailOptions['attachments'] = $this->getMailDocuments($file,$data['documentType'],$data['mailAttachments']);
        $template = $data['mailTemplate'];
        
        if($data['mailType'] == "ExcessMail"){
            if(isset($data['desiredPolicyEffectiveDate'])) {
                $data['desiredPolicyEffectiveDateFormatted'] = isset($data['desiredPolicyEffectiveDate']) ? explode('T',$data['desiredPolicyEffectiveDate'])[0] : null;
            }
        }
        $response = $this->sendMail($data, $template, $mailOptions);
        $this->logger->info("Mail Response" . $response);
        return $data;
    }

    private function getMailToAddress($config,$persistenceService){
        $selectQuery = "Select value FROM applicationConfig WHERE type = '".$config."'";
        $mailTo = ($persistenceService->selectQuery($selectQuery))->current()["value"];
        return $mailTo;
    }

    private function getMailDocuments($data,$mailDocumentName,$mailAttachments = false){
        $attachList = array();
        if (!empty($mailDocumentName)) {
            $data['documents'] = is_string($data['documents']) ? json_decode($data['documents'], true) : $data['documents'];
            foreach ($data['documents'] as $key => $value) {
                if($key == $mailDocumentName){
                    $attachList[] = $value['fullPath'];
                }
            }
        }
        if($mailAttachments){
            foreach($this->attachmentList as $value){
                if(isset($data[$value])){
                    $attachments = is_string($data[$value]) ? json_decode($data[$value],true) : $data[$value];
                    foreach($attachments as $attach){
                        $attachList[] = $attach['path'];
                    }                                
                }
            }
        }
        return $attachList;
    }

    private function getSubjectLine($mailType,$data){
        $subjectLine = "";
        if($mailType == "ExcessMail"){
            $subjectLine = 'HUB Drive Excess Liability Document Submission -'.$data['SubmissionNumber'];
        }else if($mailType == "QuoteMailtoHub"){
            $subjectLine = 'Quote Document';
        }else if($mailType == "RequestForBind"){
            $subjectLine = "RequestForBind";
        }else if($mailType == "SubmissionMailToGenre"){
            $subjectLine = "SubmissionMailToGenre";
        }else if($mailType == "PolicyMailtoHub"){
            $subjectLine = "PolicyMailtoHub";
        }else if($mailType == "RequestForMoreInfoMail"){
            $subjectLine = "RequestForMoreInfoMail";
        }else if($mailType == "HubRejectedQuote"){
            $subjectLine = "HubRejectedQuote";
        }
        return $subjectLine;
    }
}
