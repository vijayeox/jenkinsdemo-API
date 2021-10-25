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
        $fileData['data']['hubNote'] = "";
        $fileData['data']['avantNote'] = "";
        $this->saveFile($fileData['data'], $data['fileId']);
        // Add logs for created by id and producer name who triggered submission
        $mailOptions = [];
        $fileData = array();
        if($data['mailType'] == "QuoteMailtoHub" || $data['mailType'] == "RequestForMoreInfoMail" || $data['mailType'] == "PolicyMailtoHub"){
            $mailOptions['to'] = $data['mailAddress'];
        }else{
            $mailOptions['to'] = $this->getMailToAddress($data['mailAddress'],$persistenceService);
        }
        
        $mailOptions['attachments'] = $this->getMailDocuments($file,$data['documentType'],$data['mailAttachments']);
        $template = $data['mailTemplate'];
        
        $temp = $file;
        $this->processData($temp);
        $mailOptions['subject'] = $this->getSubjectLine($data['mailType'],$temp);
        $temp['avantImageUrl'] = $this->applicationUrl . '/public/img/avant.png';
        if($file['mailType'] == "ExcessMail"){
            if(isset($file['desiredPolicyEffectiveDate'])) {
                $temp['desiredPolicyEffectiveDateFormatted'] = isset($data['desiredPolicyEffectiveDate']) ? explode('T',$data['desiredPolicyEffectiveDate'])[0] : null;
            }
        }
        if($data['mailType'] == "SubmissionMailToGenre"){
            if($file['limitsNeededinExcessLayer'] == '1M'){
                $temp['limitsNeededExcess'] = '1,000,000.00';
            }else if($file['limitsNeededinExcessLayer'] == '2M'){
                $temp['limitsNeededExcess'] = '2,000,000.00';
            }else if($file['limitsNeededinExcessLayer'] == '3M'){
                $temp['limitsNeededExcess'] = '3,000,000.00';
            }else if($file['limitsNeededinExcessLayer'] == '4M'){
                $temp['limitsNeededExcess'] = '4,000,000.00';
            }else if($file['limitsNeededinExcessLayer'] == '5M'){
                $temp['limitsNeededExcess'] = '5,000,000.00';
            }else if($file['limitsNeededinExcessLayer'] == 'all'){
                $temp['limitsNeededExcess'] = 'ALL';
            } 
            if($file['excessCovCgl'] == true){
                $temp['ExcessCvrg'] = 'GL only';
            }else if($file['commercialAutoLiability'] == true){
                $temp['ExcessCvrg'] = 'AL only';
            }else if($file['employersLiability'] == true){
                $temp['ExcessCvrg'] = 'GL,AL & EL';
            }else if($file['excessCovGlAl'] == true){
                $temp['ExcessCvrg'] = 'GL & AL';
            }
        } 
        if($data['mailType'] == "PolicyMailtoHub"){
            $mailOptions['cc'] = $this->getMailToAddress('excessLiabilityMail',$persistenceService);
        }
        $response = $this->sendMail($temp, $template, $mailOptions);
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
            $subjectLine = "Request to Bind Policy - ".$data['policyNumber'];
        }else if($mailType == "SubmissionMailToGenre"){
            $subjectLine = "Gen Re Quote -".$data['insuredName'];
        }else if($mailType == "PolicyMailtoHub"){
            $subjectLine = "Policy Document - ".$data['policyNumber'];
        }else if($mailType == "RequestForMoreInfoMail"){
            $subjectLine = "Request For More Information - ".$data['insuredName'];
        }else if($mailType == "HubRejectedQuote"){
            $subjectLine = "Quote Rejected";
        }
        return $subjectLine;
    }

    protected function processData(&$temp)
    {
        foreach ($temp as $key => $value) {
            if (is_array($temp[$key])) {
                $temp[$key] = json_encode($value);
            }
        }
    }
}
