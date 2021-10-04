<?php

use Oxzion\AppDelegate\AbstractAppDelegate;
use Oxzion\Db\Persistence\Persistence;
use Oxzion\AppDelegate\AccountTrait;
use Oxzion\AppDelegate\UserContextTrait;
//use Oxzion\DelegateException;
use Oxzion\Utils\UuidUtil;

class DriverRegister extends AbstractAppDelegate
{
    use AccountTrait;
    use UserContextTrait;
    const APPID = 'a4b1f073-fc20-477f-a804-1aa206938c42';

    public function __construct()
    {
        parent::__construct();
    }

    public function execute(array $data, Persistence $persistenceService)
    {
        $this->logger->info("Executing Driver Registration with data- " . print_r($data, true));
        // Add logs for created by id and producer name who triggered submission
        if(!isset($data['isDriverRegisterationOver']) || ($data['isDriverRegisterationOver'] === false || $data['isDriverRegisterationOver'] === 'false')) {
            if(isset($data['driverDataGrid']) && !is_array($data['driverDataGrid'])){
                $data['driverDataGrid'] = json_decode($data['driverDataGrid'],true);
            }
            if(isset($data['driverDataGrid']) && !empty($data['driverDataGrid'])){

                foreach ($data['driverDataGrid'] as $k=>$driver) {
                    $dataForDriver = array();

                    if(!isset($driver['driverFirstName']) || $driver['driverFirstName']==''){
                        $this->logger->info("Driver not Registered. Missing First Name" . $driver['driverFirstName']);
                        continue;
                    }
                        
                    if(!isset($driver['driverLastName']) || $driver['driverLastName']==''){
                        $this->logger->info("Driver not Registered. Missing Last Name" . $driver['driverLastName']);
                        continue;
                    }

                    if(!isset($driver['driverEmail']) || $driver['driverEmail']==''){
                        $this->logger->info("Driver not Registered. Missing email" . $driver['driverEmail']);
                        continue;
                    }
                        


                    if (!isset($dataForDriver['uuid'])) {
                        $dataForDriver['uuid'] = UuidUtil::uuid();
                    }
                    /*
                    if (!isset($dataForDriver['preferences'])) {
                        $dataForDriver['preferences'] = '{}';
                    }*/
                    $dataForDriver['name'] = $driver['driverFirstName']." ".$driver['driverLastName'];
                    $dataForDriver['email'] = $driver['driverEmail'];
                    $dataForDriver['firstname'] = $driver['driverFirstName'];
                    $dataForDriver['lastname'] = $driver['driverLastName'];
                    $dataForDriver['username'] = str_replace('@', '.', $driver['driverEmail']);;
                    if (!isset($dataForDriver['contact'])) {
                        $dataForDriver['contact'] = array();
                        $dataForDriver['contact']['username'] = str_replace('@', '.', $driver['driverEmail']);
                        $dataForDriver['contact']['firstname'] = $driver['driverFirstName'];
                        $dataForDriver['contact']['lastname'] = $driver['driverLastName'];
                        $dataForDriver['contact']['email'] = $driver['driverEmail'];
                    }
                    $dataForDriver['app_id'] = self::APPID;
                    $dataForDriver['type'] = 'INDIVIDUAL';
                    $params['accountId'] = $data['buyerAccountId'];
                    $response = $this->createUser($params, $dataForDriver);
                    $driver['driveruuid'] = $dataForDriver['uuid'];
                    $data['driverDataGrid'][$k] = $driver;
                    $this->logger->info("After driver registration---".print_r($driver,true));
                }
            }
        }
        $data['isDriverRegisterationOver'] = true;
        return $data;
    }
}
