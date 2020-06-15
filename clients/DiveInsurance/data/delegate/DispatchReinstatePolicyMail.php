<?php
use Oxzion\AppDelegate\MailDelegate;
use Oxzion\Db\Persistence\Persistence;
use Oxzion\Messaging\MessageProducer;
use Oxzion\Encryption\Crypto;
use Oxzion\Utils\FileUtils;
use Oxzion\Utils\ArtifactUtils;
require_once __DIR__."/DispatchDocument.php";

class DispatchReinstatePolicyMail extends DispatchDocument
{ 
    public $template;
    
    public function __construct(){
        $this->template = array(
            'Individual Professional Liability' => 'ReinstatePolicyMailTemplate',
            'Dive Boat' => 'ReinstatePolicyMailTemplate',
            'Emergency First Response' => 'ReinstatePolicyMailTemplate',
            'Dive Store' => 'ReinstatePolicyMailTemplate');
        parent::__construct();
    }

    public function execute(array $data, Persistence $persistenceService)
    {
        $this->logger->info("Dispatch reinstate policy mail notification ".json_encode($data));
        $temp = $data['reinstateDocuments'];
        if(!is_array($temp)){
            $temp = json_decode($data['reinstateDocuments'], true);
        }
        $dest = ArtifactUtils::getDocumentFilePath($this->destination,$data['fileId'],array('orgUuid' => $data['orgUuid']));
        $data['documents_history'] = array();
        array_push($data['documents_history'], $data['reinstateDocuments']);
        array_push($data['documents_history'], $data['documents']);
        if(isset($data['documents'])){
            unset($data['documents']);
        }
        $this->logger->info("Dispatch reinstate policy mail notification - data consists of:".json_encode($temp));
        if(is_array($temp)){
            foreach($temp as $key => $value){
                if (is_array($value)) {
                    $data['documents'][$key] = $value;
                }
                else {
                    $fileName = basename($value);
                    $this->logger->info("the fileName is: ".print_r($fileName, true));
                    $this->logger->info("the new path is : ".json_encode($dest));
                    $data['documents'][$key] = $dest['relativePath'].$fileName;
                    $this->logger->info("The destination value is : ".print_r($this->destination.$value, true));
                    $this->logger->info("The destination relative path is : ".print_r($this->destination.$dest['relativePath'], true));
                    FileUtils::copy($this->destination.$value, $fileName, $this->destination.$dest['relativePath']);
                }
            }
        }
        if(isset($data[$data['jobName']])){
            $data[$data['jobName']] = "";
        }
        if(isset($data['documents'])){
            $this->logger->info("the document array consists of : ".print_r($data['documents'], true));
        }
        if(isset($data['reinstateDocuments'])){
            $data['reinstateDocuments'] = '';
        }
        if(isset($data['reasonforRejection'])){
            $data['reasonforRejection'] = '';
        }
        if(isset($data['userCancellationReason'])){
            $data['userCancellationReason'] = '';
        }
        if(isset($data['othersCsr'])){
            $data['othersCsr'] = '';
        }
        if(isset($data['reinstateAmount'])){
            $data['reinstateAmount'] = '';
        }
        if(isset($data['reasonforCsrCancellation'])){
            $data['reasonforCsrCancellation'] = '';
        }
        if(isset($data['cancellationStatus'])){
            $data['cancellationStatus'] = '';
        }
        if(isset($data['csrCancellationReason'])){
            $data['csrCancellationReason'] = '';
        }
        if(isset($data['othersUser'])){
            $data['othersUser'] = '';
        }
        if(isset($data['reasonForUserCancellation'])){
            $data['reasonForUserCancellation'] = '';
        }
        if(isset($data['userAgreement'])){
            $data['userAgreement'] = '';
        }
        if(isset($data['state'])){
            $data['state_in_short'] = $this->getStateInShort($data['state'],$persistenceService);
        }
        $temp = $data;
        $temp['template'] = $this->template[$data['product']];
        if($data['product'] == 'Dive Store'){
            $subject = 'PADI Endorsed Dive Store Insurance Reinstatement - '.$data['padi'];
        }else if($data['product'] == 'Dive Boat'){
            $subject = 'PADI Endorsed Dive Boat Insurance Reinstatement - '.$data['padi'];
        }else{
            $subject = 'PADI Endorsed Insurance Reinstatement - '.$data['padi'];
        }
        $temp['subject'] = $subject;
        $response = $this->dispatch($temp);
        $this->logger->info("Dispatch reinstate policy returning data --- ".json_encode($data));
        return $data;
    }
    protected function getStateInShort($state,$persistenceService){
        $selectQuery = "Select state_in_short FROM state_license WHERE state ='".$state."'";
        $resultSet = $persistenceService->selectQuery($selectQuery);
        if($resultSet->count() == 0){
            return $state;
        }else{
            while ($resultSet->next()) {
                $stateDetails[] = $resultSet->current();
            }       
            if(isset($stateDetails) && count($stateDetails)>0){
                 $state = $stateDetails[0]['state_in_short'];
            } 
        }
        return $state;
    }
}
?>