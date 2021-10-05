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
        include_once(__DIR__.'/../zendriveintegration/ZenDriveClient.php');
        $this->apicall = new ApiCall();
    }

    public function execute(array $data, Persistence $persistenceService)
    {   
        if(isset($data['zenDriveIntegration']) && strtoupper($data['zenDriveIntegration']) == "YES"){
            $this->logger->info("in zendrive delegate- " . json_encode($data, JSON_UNESCAPED_SLASHES));
            if(!isset($data['buyerAccountId']) || $data['buyerAccountId']=='')
                throw new DelegateException("Account Not Found.","no.user.record.exists");
                
            $selectQuery = "SELECT * FROM `ic_info` WHERE email = :email";
            $resultArr = $persistenceService->selectQuery($selectQuery,[
                    "email"=>$data['email']
                ],true);
            if(count($resultArr) >= 1){
                $this->logger->info("Skipping the Zendrive Integration As IC Is Already Registered With Zendrive. Key - ". $resultArr[0]['zendrive_fleet_api_key']);
                return $data;
                
            }

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
            
            $columns = "(`ic_name`,`email`,`ph_number`,`uuid`,`zendrive_fleet_api_key`)";
            $values = "VALUES (:name,:email,:phonenumber,:fleet_id,:fleet_api_key)";
            $insertQuery = "INSERT INTO ic_info ".$columns.$values;
            $this->logger->info("in zendrive delegate insert query- " . print_r($insertQuery,true));
            $icInsert = $persistenceService->insertQuery($insertQuery, $fleetArr);
            $ic_id = $icInsert->getGeneratedValue();

            if(isset($data['driverDataGrid'])){
                $this->logger->info("driver data grid is set- ");
                if(is_array($data['driverDataGrid'])){
                    $this->logger->info("driver data grid is array- ");
                    $this->addDriver($data['buyerAccountId'], $data['driverDataGrid'], $ic_id, $fleet_id, $persistenceService);
                }else{
                    $this->logger->info("driver data grid is json object- ");
                    $driverData = json_decode($data['driverDataGrid'], true);
                    $this->logger->info("driver data grid expanded- ".print_r($driverData,true));
                    if(is_array($driverData) && count($driverData) >= 1)
                        $this->addDriver($data['buyerAccountId'],$driverData, $ic_id, $fleet_id, $persistenceService);
                }
            }else{
                $this->logger->info("driver data grid not found- ");
            }
            
            return $data;
        }
        
    
    }

    private function addDriver($fleet_account_id,$driverData, $ic_id, $fleet_id, Persistence $persistenceService){
        //$fleet_id = $driverData['buyerAccountId'];
        $endpoint = 'fleet/'.$fleet_id.'/driver/';
        $this->logger->info("in zendrive delegate driver api request- " . $endpoint."  ".print_r($driverData,true));
        /*if(!isset($datafordriver['driverDataGrid']))
            throw new DelegateException("Driver Data Does Not Exist","no.user.record.exists");
        $driverData = json_decode($datafordriver['driverDataGrid'], true);
        if(!is_array($driverData) || count($driverData) < 1)
            throw new DelegateException("Driver Data Invalid Format","no.user.record.exists");*/

        foreach ($driverData as $k=>$driver) {
            $email  = $driver['driverEmail'] ;

            $username = str_replace('@', '.', $driver['driverEmail']);
            $uuidresponse = $this->getUserByUsername($fleet_account_id, $username);
            $this->logger->info("in zendrive delegate uuid ".print_r($uuidresponse,true));

            if(count($uuidresponse) < 1)
                continue;

            $driveruuid = $driver['driveruuid'];
            
            $params = array('first_name'=>$driver['driverFirstName'] , 'last_name'=>$driver['driverLastName'], 'email'=>$email);
            $result = $this->apicall->getApiResponse($endpoint,$params);
            $this->logger->info("in zendrive delegate driver api response- " . $result);
            $parsedResponse = json_decode($result,true);
            $finalresponse = json_decode($parsedResponse['body'],true);
            //$datafordriver['driver'.$k.'key'] = $finalresponse['data']['driver_id'];
            if(!isset($finalresponse['data']['driver_id'])){
                $this->logger->info("Zendrive Driver Addition Failed For".$driver['driverFirstName']);
                continue;
            }
                
            
                //entry in driver table and ic_driver_mapping
            $driverArr = ['uuid'=> $driveruuid,
                'firstName'    => $driver['driverFirstName'],
                'middleName'   =>$driver['driverMiddleName'],
                'lastName'     =>$driver['driverLastName'],
                'email'         =>$email,
                //'dateOfBirth'   => (isset($driver['driverDateofBirth']) && $driver['driverDateofBirth']!='')? explode("T",$driver['driverDateofBirth'])[0] :'' ,
                'ssn'           =>$driver['driverSsn'],
                'licenseNum'    =>$driver['driverLicense'],
                //'hasExperience'=>$driver['doesthedriverhave2YearsofcommercialdrivingexperienceinNorthAmerica'],
                'driverType'   =>$driver['pleaseindicatetypeofdriver'],
                'paidByOption'=>$driver['pleaseselectthepaidbyoption'],
                'zendrive_driver_id'=>$finalresponse['data']['driver_id'],
            ];
            

            $drivercolumns = "(`uuid`, `first_name`,`middle_name`,`last_name`,`email`,`ssn`,`license_num`,`driver_type`,`paid_by_option`,`zendrive_driver_id`) ";
            $drivervalues = "VALUES (:uuid,:firstName,:middleName,:lastName,:email,:ssn,:licenseNum,:driverType,:paidByOption,:zendrive_driver_id)";
            $driverinsertQuery = "INSERT INTO driver ".$drivercolumns.$drivervalues;
            $driverSelect = $persistenceService->insertQuery($driverinsertQuery, $driverArr);   
            $driver_id= $driverSelect->getGeneratedValue();

            $mappingArr = ['driver_id'=>$driver_id, 'ic_id'=>$ic_id];
            $mappingcolumns = "(`ic_id`,`driver_id`)";
            $mappingvalues = "VALUES (:ic_id,:driver_id)";
            $insert = "INSERT INTO ic_driver_mapping ".$mappingcolumns.$mappingvalues;
            $this->logger->info("mapping query- " . print_r($insert,true));
            $insertQuery = $persistenceService->insertQuery($insert,$mappingArr);

        }
    }

}