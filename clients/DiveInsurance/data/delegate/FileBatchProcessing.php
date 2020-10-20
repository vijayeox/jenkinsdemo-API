<?php
use Oxzion\Db\Persistence\Persistence;
use Oxzion\AppDelegate\FileTrait;
// use Oxzion\AppDelegate\AbstractAppDelegate;
use Oxzion\AppDelegate\AppDelegateTrait;
require_once __DIR__ . "/DispatchNotification.php";
 
abstract class FileBatchProcessing extends DispatchNotification{

	use FileTrait;
    use AppDelegateTrait;
    public $template;
 
    public function __construct(){
         $this->renewalTemplate = array(
            'Individual Professional Liability' => 'IPL_Renewal_Reminder',
            'Dive Boat' => 'DB_Renewal_Reminder',
            'Dive Store' => 'DS_Renewal_Reminder',
            'Emergency First Response' => 'EFR_Renewal_Reminder',
        );
        parent::__construct();
    }

    protected function processNotification(array $data,Persistence $persistenceService)
    {
        $this->logger->info("RenewalBatchProcessing Delegate ---".print_r($data,true));
        $filterParams = $data['filterParams'];
        unset($data['filterParams']);
        $pageSize = 1000;
        $filterParams['filter'][0]['take'] = $pageSize;
        $skip =  0;
        $filterParams['filter'][0]['skip'] = $skip;
       	$policyList = $this->getFileList($data,$filterParams);
        $total = $policyList['total'];
    
        $this->logger->info("Policy List".print_r($policyList,true));
        if($total > 0){
            for($skip = 0;$skip < $total;){
                if(isset($policyList['data'])){
                    foreach($policyList['data'] as $file){
                        if(isset($file['data'])){
                            $fileData = json_decode($file['data'],true);
                            unset($file['data']);
                            array_merge($file,$fileData);
                        }
                        $file['orgId'] = $data['orgId'];
                        $this->logger->info("FILE DATA -----".print_r($file,true));
                        $this->processDataForRenewal($file,$data);
                    }
                }
                $skip += $pageSize;
                if($skip < $total){
                    $filterParams['filter'][0]['skip'] = $skip;
                    $policyList = $this->getFileList($data,$filterParams);    
                }              
            }
        } 
    }

    private function processDataForRenewal($fileData,$data){
        if($data['flag'] == 'Renewal Notification'){
            $this->processRenewals($fileData,$data);
        }else if($data['flag'] == 'Lapse Letter'){
            $responseData = $this->executeDelegate('LapseLetter',$fileData);
            if(isset($responseData['documents']['lapseLetter'])){
                $this->executeDelegate('DispatchLapseLetter',$responseData);    
            }
        }else if($data['flag'] == 'Expired Policy Notification'){
            $this->executeDelegate('DispatchExpiredNotification',$fileData);
        }else if($data['flag'] == 'AutoRenewal Notification'){
            $responseData = $this->executeDelegate('AutoRenewalRateCard',$fileData);
            $this->executeDelegate('DispatchAutoRenewalNotification',$responseData);
        }else if($data['flag'] == 'Schedule AutoRenewal'){
            print("jdgjg");
        }
    }


    private function processRenewals($fileData,$data){
        $val = array();
        $val['workflowInstanceId'] = $fileData['workflowInstanceId'];
        $val['template'] = $this->renewalTemplate[$fileData['product']];
        $val['orgUuid'] = $fileData['orgId'];
        $val['orgId'] = $fileData['orgId'];
        $val['to'] = $fileData['email'];
        $val['subject'] = "PADI Endorsed Insurance Renewal - ".$fileData['padi'];   
        $val['url'] = $this->baseUrl. '?app=DiveInsurance&params={"name":"","detail":[{"type":"Form","url":"pipeline","urlPostParams":{"workflowInstanceId":"'.$fileData['workflowInstanceId'].'","workflow_id":"'.$fileData['workflowId'].'","commands":[{"command":"file"},{"command":"delegate","delegate":"RenewalRateCard"},{"command":"startform"}]}}]}';
        $response[] = $this->dispatch($val);
    }
}
?>