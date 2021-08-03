<?php

use Oxzion\AppDelegate\AbstractAppDelegate;
use Oxzion\Db\Persistence\Persistence;
//use Oxzion\AppDelegate\AccountTrait;
use Oxzion\DelegateException;
//use Zend\Log\Logger;

use function GuzzleHttp\json_decode;

class ZenDriveFleetIntegration extends AbstractAppDelegate
{
    //use AccountTrait;
    const APPID = 'a4b1f073-fc20-477f-a804-1aa206938c42';

    private $apicall;
    public function __construct()
    {
        parent::__construct();
        include(__DIR__.'/../zendriveintegration/ZenDriveClient.php');
        $this->apicall = new ApiCall();
    }

    public function execute(array $data, Persistence $persistenceService)
    {   
        if(isset($data['zenDriveIntegration']) && strtoupper($data['zenDriveIntegration']) == "YES"){
            $this->logger->info("in zendrive delegate- " . json_encode($data, JSON_UNESCAPED_SLASHES));
            if(!isset($data['buyerAccountId']) || $data['buyerAccountId']=='')
                throw new DelegateException("Account Not Found.","no.user.record.exists");
            
            $fleet_name = $data['name'];
            $fleet_email = $data['email'];
            $fleet_id = $data['buyerAccountId'];
            $fleet_phonenumber = $data['phone'];
            $endpoint = 'fleet/';

            $params = array('name' => $fleet_name, 'fleet_id' => $fleet_id , 'phonenumber' => $fleet_phonenumber);
            $this->logger->info("in zendrive delegate params- " . json_encode($params, JSON_UNESCAPED_SLASHES));
            $response = $this->apicall->getApiResponse($endpoint,$params);
            $this->logger->info("in zendrive delegate api response- " . $response);
            $parsedResponse = json_decode($response,true);
            $finalresponse = json_decode($parsedResponse['body'],true);
            $data['fleet_api_key'] = $fleet_api_key = $finalresponse['data']['fleet_api_key'];
            
            //create a table called ic_info in hubdrive db and save ic name, email, phone, uuid, fleet_api_key
            $fleetArr = array('name' => $fleet_name, 'fleet_id' => $fleet_id , 'phonenumber' => $fleet_phonenumber,'fleet_api_key'=> $fleet_api_key,'email'=>$fleet_email);
            
            $columns = "(`ic_name`,`email`,`ph_number`,`uuid`,`fleet_api_key`)";
            $values = "VALUES (:name,:email,:phonenumber,:fleet_id,:fleet_api_key)";
            $insertQuery = "INSERT INTO ic_info ".$columns.$values;
            $this->logger->info("in zendrive delegate api response3- " . print_r($insertQuery,true));
            $unitSelect = $persistenceService->insertQuery($insertQuery, $fleetArr);
            $this->logger->info("in zendrive delegate api response4- " . print_r($unitSelect,true));
            
            
            $this->addDriver($data, $persistenceService);
            return $data;
        }
        
    
    }

    private function addDriver($datafordriver, Persistence $persistenceService){
        $fleet_id = $datafordriver['buyerAccountId'];
        $endpoint = 'fleet/'.$fleet_id.'/driver/';
        $this->logger->info("in zendrive delegate driver api request- " . print_r($datafordriver['driverDataGrid'],true));
        if(!isset($datafordriver['driverDataGrid']))
            throw new DelegateException("Driver Data Does Not Exist","no.user.record.exists");
        $driverData = json_decode($datafordriver['driverDataGrid'], true);
        if(!is_array($driverData) || count($driverData) < 1)
            throw new DelegateException("Driver Data Invalid Format","no.user.record.exists");

        foreach ($driverData as $k=>$driver) {
            $email  = isset($driver['driverEmail']) ? $driver['driverEmail'] : $driver['driverFirstName']."@abc.com";
            $driveruuid = $driver['driveruuid'];
            
            $params = array('first_name'=>$driver['driverFirstName'] , 'last_name'=>$driver['driverLastName'], 'email'=>$email);
            $result = $this->apicall->getApiResponse($endpoint,$params);
            $this->logger->info("in zendrive delegate driver api response- " . $result);
            $parsedResponse = json_decode($result,true);
            $finalresponse = json_decode($parsedResponse['body'],true);
            //$datafordriver['driver'.$k.'key'] = $finalresponse['data']['driver_id'];
            
            //create a table called ic_driver_mapping in hubdrive db and save driver name, email, uuid, fleet_id,zendrive_driver_id
            $driverName = $driver['driverFirstName'].' '.$driver['driverLastName'];
            $driverArr = ['name'=> $driverName,'uuid'=> $driveruuid, 'email'=>$email, 'fleet_id'=>$fleet_id, 'zendrive_driver_id'=>$finalresponse['data']['driver_id']];
            
            $columns = "(`driver_name`,`uuid`,`fleet_id`,`email`,`zendrive_driver_id`)";
            $values = "VALUES (:name,:uuid,:fleet_id,:email,:zendrive_driver_id)";
            $insert = "INSERT INTO ic_driver_mapping ".$columns.$values;
            $this->logger->info("in query- " . print_r($insert,true));
            $insertQuery = $persistenceService->insertQuery($insert,$driverArr);

        }
    }

}