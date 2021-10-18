<?php

use Oxzion\AppDelegate\AbstractAppDelegate;
use Oxzion\Db\Persistence\Persistence;
//use Oxzion\AppDelegate\AccountTrait;
use Oxzion\DelegateException;
//use Zend\Log\Logger;

use function GuzzleHttp\json_decode;

class DeleteDriver extends AbstractAppDelegate
{
    //use AccountTrait;
    const APPID = 'a4b1f073-fc20-477f-a804-1aa206938c42';

    private $apicall;
    public function __construct()
    {
        parent::__construct();
        include_once(__DIR__.'/../zendriveintegration/ZenDriveClient.php');
        $this->apicall = new ApiCall();
    }

    public function execute(array $data, Persistence $persistenceService)
    {   
        // $this->logger->info("inside delete delegate - ". $data);
        if(isset($data['zenDriveIntegration']) && strtoupper($data['zenDriveIntegration']) == "YES"){
            $this->logger->info("in zendrive delegate- " . json_encode($data, JSON_UNESCAPED_SLASHES));
            if(!isset($data['buyerAccountId']) || $data['buyerAccountId']=='')
                throw new DelegateException("Account Not Found.","no.user.record.exists");
            $driver_info = '81da5804-98e6-45bb-a1c2-9259a9157d27';
            $selectQuery = "SELECT * FROM `driver` WHERE uuid = :uuid";
            $resultArr = $persistenceService->selectQuery($selectQuery,[
                    "uuid"=>$driver_info
                ],true);
            
            $this->logger->info("in zendrive delegate api response data- " . json_encode($resultArr));
            if(isset($resultArr) && !empty($resultArr)){
                $driver_id = $resultArr[0]['id'];
                $driver_uuid = $resultArr[0]['zendrive_driver_id'];
                $this->logger->info("return data from select query - ".$resultArr[0]['zendrive_driver_id']);
                $selectQuery1 = 'SELECT * FROM `ic_driver_mapping` WHERE id = :id';
                $resultArr1 = $persistenceService->selectQuery($selectQuery1,[
                "id"=>$driver_id
                ],true);
            }
            if(isset($resultArr1) && !empty($resultArr1)){
                $ic_id = $resultArr1[0]['ic_id'];
                $selectQuery2 = 'SELECT * FROM `ic_info` WHERE id = :id';
                $resultArr2 = $persistenceService->selectQuery($selectQuery2,[
                    "id"=>$ic_id
                ],true);
            }

            if(isset($resultArr2) && !empty($resultArr2)){
                $fleet_id = $resultArr2[0]['uuid'];
            }
            // $this->logger->info("fleet uuid - ".$resultArr1[0]['zendrive_driver_id']);
            if(isset($fleet_id) && isset($driver_uuid)){
            $endpoint = 'fleet/'.$fleet_id.'/driver/'.$driver_uuid;
            $params = array('fleet_id' => $fleet_id , 'driver_id' => $driver_uuid);
            $this->logger->info("in zendrive delete delegate params- " . json_encode($params, JSON_UNESCAPED_SLASHES));
            $requesttype = 'DEL';
            try{
            $response = $this->apicall->getApiResponse($endpoint,$params,$requesttype);
            $this->logger->info("in zendrive delegate api response delete- " . $response);
            $parsedResponse = json_decode($response,true);
            $finalresponse = json_decode($parsedResponse['body'],true);
            throw new Exception("Error in api", 1);
            }catch(Exception $e){
                // throw new Exception("Zendrive Integration Failed.", 1, $e);
            }
            return $data;
            }
        }
        
    
    }
}