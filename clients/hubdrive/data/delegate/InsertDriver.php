<?php

use Oxzion\AppDelegate\AbstractAppDelegate;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use Oxzion\Db\Persistence\Persistence;
use Oxzion\AppDelegate\UserContextTrait;
use Oxzion\DelegateException;
use Oxzion\AppDelegate\FileTrait;
use Oxzion\Utils\UuidUtil;
use Oxzion\AppDelegate\InsuranceTrait;

use Zend\Code\Scanner\FunctionScanner;

use function Matrix\trace;

class InsertDriver extends AbstractAppDelegate
{
    use UserContextTrait;
    use InsuranceTrait;
    use FileTrait;
    const APPID = 'a4b1f073-fc20-477f-a804-1aa206938c42';

    private $persistenceService;

    public function __construct()
    {
        parent::__construct();
        include_once(__DIR__ . '/../zendriveintegration/ZenDriveClient.php');
        $this->apicall = new ApiCall();
    }

    public function execute(array $data, Persistence $persistenceService)
    {
        try {
            $data['formType'] = isset($data['formType']) ? $data['formType'] : "driverManagementScreen";
            $icFileData = array();
            $dataForDriver = array();
            $icUserId = $data['icusername']['uuid'];
            $filterParams = array();
            $pageSize = 1;
            $filterParams['filter'][0]['take'] = $pageSize;
            $skip =  0;
            $filterParams['filter'][0]['skip'] = $skip;
            $filterParams['filter'][0]['filter']['filters'][] = array('field' => 'ICUserId', 'operator' => 'eq', 'value' => $icUserId);
            $fileList = $this->getFileList($data, $filterParams);
            $this->logger->info("file list " . print_r(json_encode($fileList), true));
            if (isset($fileList['data']) && sizeof($fileList['data']) > 0) {
                $fileData = is_string($fileList['data'][0]['data']) ? json_decode($fileList['data'][0]['data'], true) : $fileList['data'][0]['data'];
                $fileUuid = $fileList['data'][0]['uuid'];
                $companyName = $fileList['data'][0]['companyName'];
                $zendDriveSubscription = isset($fileList['data'][0]['zenDriveIntegration']) ? $fileList['data'][0]['zenDriveIntegration'] : 'false';
                $this->logger->info("company name " . print_r(json_encode($companyName), true));
            } else {
                return $data;
            }
            if ($data['formType'] == "driveSafeSubscriptionForm") {
                $fileData['zenDriveIntegration'] = "Yes";
                $icFileData['zenDriveIntegration'] = "Yes";
                $zendDriveSubscription = "YES";
            }
            // $zendDriveSubscription = "YES";
            // $zendDriveSubscription = $fileList['data'][0]['zenDriveIntegration'];
            // $this->logger->info("ic subscription information " . print_r($zendDriveSubscription, true));
            if (isset($zendDriveSubscription) && strtoupper($zendDriveSubscription) == "YES") {
                $selectQuery = "SELECT * FROM `ic_info` WHERE email = '" . $data['icusername']['email'] . "'";
                $ICrrecord = $persistenceService->selectQuery($selectQuery);
                if (count($ICrrecord) >= 1) {
                    $details = array();
                    while ($ICrrecord->next()) {
                        $details[] = $ICrrecord->current();
                    }
                    $this->logger->info("ic information " . print_r($details, true));
                    $ic_id = $details[0]['id'];
                    $fleet_id = $details[0]['uuid'];
                    $icFileData['fleet_api_key'] = $details[0]['zendrive_fleet_api_key'];
                    $this->saveFile($icFileData, $fileUuid);
                } else {
                    $fleet_name = $data['icusername']['name'];
                    $fleet_email = $data['icusername']['email'];
                    $fleet_id = $data['icusername']['accountId'];
                    $fleet_phonenumber = $data['icusername']['phone'];
                    $endpoint = 'fleet/';
                    $requesttype = 'POST';
                    $params = array('name' => $fleet_name, 'fleet_id' => $fleet_id, 'phone_number' => $fleet_phonenumber);
                    $this->logger->info("in zendrive delegate params- " . json_encode($params, JSON_UNESCAPED_SLASHES));
                    $response = $this->apicall->getApiResponse($endpoint, $params, $requesttype);
                    $this->logger->info("in zendrive delegate api response- " . $response);
                    $parsedResponse = json_decode($response, true);
                    $finalresponse = json_decode($parsedResponse['body'], true);
                    $data['fleet_api_key'] = $icFileData['fleet_api_key'] = $fleet_api_key = $finalresponse['data']['fleet_api_key'];
                    $this->saveFile($icFileData, $fileUuid);
                    //create a table called ic_info in hubdrive db and save ic name, email, phone, uuid, fleet_api_key
                    $fleetArr = array('name' => $fleet_name, 'fleet_id' => $fleet_id, 'phone_number' => $fleet_phonenumber, 'fleet_api_key' => $fleet_api_key, 'email' => $fleet_email);

                    $columns = "(`ic_name`,`email`,`ph_number`,`uuid`,`zendrive_fleet_api_key`)";
                    $values = "VALUES (:name,:email,:phone_number,:fleet_id,:fleet_api_key)";
                    $insertQuery = "INSERT INTO ic_info " . $columns . $values;
                    $this->logger->info("in zendrive delegate insert query- " . print_r($insertQuery, true));
                    $icInsert = $persistenceService->insertQuery($insertQuery, $fleetArr, 'yes');
                    $ic_id = $icInsert->getGeneratedValue();
                }
            } else {
                // return $data;
                $fleet_id = $data['icusername']['accountId'];
                $this->logger->info("fleet id " . print_r($fleet_id, true));
            }

            if ($data['formType'] == "driveSafeSubscriptionForm") {
                $filterParams = array();
                $pageSize = 1000;
                $filterParams['filter'][0]['take'] = $pageSize;
                $skip =  0;
                $filterParams['filter'][0]['skip'] = $skip;
                $filterParams['filter'][0]['filter']['filters'][] = array('field' => 'entity_name', 'operator' => 'eq', 'value' => 'Driver');
                $fileList = $this->getFileList($data, $filterParams);
                $this->logger->info("driver data in drive safe " . print_r($fileList['data'][0], true));
                if (isset($fileList['data']) && sizeof($fileList['data']) > 0) {
                    foreach ($fileList['data'] as $key => $val) {
                        $driverZendDriveResponse = $this->addDriver($fleet_id, $fileList['data'][0], $ic_id, $fleet_id, $persistenceService);
                    }
                } else {
                    return $data;
                }
            } else {
                if (isset($data['formOptions']) && $data['formOptions'] == 'excelUpload') {
                    $datavalidate = $this->checkArrayEmpty($data['driverDataFileUpload']);
                    if ($datavalidate === "0") {
                        return array("status" => "Error", "data" => $data);
                    }
                    $driverex = array();
                    $driverex =  $this->uploadCSVDataForDrivers($data);
                    $this->logger->info("driver data " . print_r($driverex, true));
                    $line = array_chunk($driverex, 10);
                    foreach ($line as $key => $val) {
                        $data = array();
                        for ($y = 0; $y < sizeof($val); $y++) {
                            if (isset($val[$y][0]) && isset($val[$y][1]) && isset($val[$y][2]) && isset($val[$y][3])) {
                                $username = $val[$y][3];
                                $this->logger->info("driver username " . print_r($username, true));
                                $userDataIfExists = $this->userService->getUserByUsername($username);
                                $this->logger->info("driver existance data " . print_r($userDataIfExists, true));
                                $dataForDriver['name'] = $val[$y][0] . " " . $val[$y][1] . " " . $val[$y][2];
                                $dataForDriver['email'] = $val[$y][3];
                                $dataForDriver['firstname'] = $val[$y][0];
                                $dataForDriver['lastname'] = $val[$y][2];
                                $dataForDriver['middlename'] = $dataForDriver['email'];
                                $dataForDriver['SSN'] = $val[$y][4];
                                $dataForDriver['driverLincence'] = $val[$y][5];
                                $dataForDriver['driverType'] =  $val[$y][6];
                                $dataForDriver['paidOption'] = $val[$y][7];
                                $dataForDriver['username'] = $dataForDriver['email'];
                                if (count($userDataIfExists) == 0) {
                                    $dataForDriver['app_id'] = self::APPID;
                                    $dataForDriver['type'] = 'INDIVIDUAL';
                                    $params['accountId'] = $fleet_id;
                                    $params['ic_id'] = $ic_id;
                                    $this->logger->info("account id in params " . print_r($params['accountId'], true));
                                    if (!isset($dataForDriver['uuid'])) {
                                        $dataForDriver['uuid'] = UuidUtil::uuid();
                                    }
                                    if (!isset($dataForDriver['contact'])) {
                                        $dataForDriver['contact'] = array();
                                        $dataForDriver['contact']['username'] = $dataForDriver['email'];
                                        $dataForDriver['contact']['firstname'] = $dataForDriver['name'];
                                        $dataForDriver['contact']['lastname'] = $dataForDriver['lastname'];
                                        $dataForDriver['contact']['email'] = $dataForDriver['email'];
                                    }
                                    $dataForDriver['entity_name'] = 'Driver';
                                    $response = $this->createUser($params, $dataForDriver);
                                    $driverUuid = $this->getUserByUsername($dataForDriver['username']);
                                    $dataForDriver['userUUID'] = $driverUuid[0]['uuid'];
                                    $dataForDriver['status'] = 'Active';
                                    $response1 = $this->createFile($dataForDriver);
                                    $this->logger->info("driver file data " . print_r(json_encode($dataForDriver['uuid']), true));
                                    $this->logger->info("company name before " . print_r($companyName, true));
                                    $dataForDriver['companyName'] = $companyName;
                                    $response2 = $this->Insurelearn($dataForDriver);
                                    $this->logger->info("driver registration data " . print_r($response, true));
                                    $this->logger->info("driver file creation data " . print_r($response1, true));
                                    $this->logger->info("driver insurelearn data " . print_r($response2, true));
                                    if (isset($zendDriveSubscription) && strtoupper($zendDriveSubscription) == "YES") {
                                        $driverZendDriveResponse = $this->addDriver($fleet_id, $dataForDriver, $ic_id, $fleet_id, $persistenceService);
                                        $this->logger->info("zenddriveIntegration response " . print_r($driverZendDriveResponse, true));
                                    }
                                } else {
                                    $dataForDriver['companyName'] = $companyName;
                                    $this->logger->info("driver already exists");
                                    $this->logger->info("company name before " . print_r($companyName, true));
                                    $response2 = $this->Insurelearn($dataForDriver);
                                    $this->logger->info("driver insurelearn data " . print_r($response2, true));
                                    if (isset($zendDriveSubscription) && strtoupper($zendDriveSubscription) == "YES") {
                                        $driverZendDriveResponse = $this->addDriver($fleet_id, $dataForDriver, $ic_id, $fleet_id, $persistenceService);
                                        $this->logger->info("zenddriveIntegration response " . print_r($driverZendDriveResponse, true));
                                    }
                                }
                            } else {
                                $errorData = array();
                                $errorData = $val[$y];
                                $this->logger->info("driver response error data " . print_r($errorData, true));
                            }
                        }
                    }
                } else {
                    $this->logger->info("Drivers Form Data..." . print_r($data, true));
                    $username = $data['driverEmail'];
                    $this->logger->info("driver username " . print_r($username, true));
                    $userDataIfExists = $this->userService->getUserByUsername($username);
                    $this->logger->info("driver existance data " . print_r($userDataIfExists, true));
                    $dataForDriver['name'] = $data['driverFirstName'] . " " . $data['driverMiddleName'] . " " . $data['driverLastName'];
                    $dataForDriver['email'] = $data['driverEmail'];
                    $dataForDriver['firstname'] = $data['driverFirstName'];
                    $dataForDriver['lastname'] = $data['driverLastName'];
                    $dataForDriver['middlename'] = $data['driverMiddleName'];
                    $dataForDriver['SSN'] = $data['driverSsn'];
                    $dataForDriver['driverLincence'] = $data['driverLicense'];
                    $dataForDriver['driverType'] = isset($data['driverType']) ? $data['driverType'] : "";
                    $dataForDriver['paidOption'] = isset($data['paidOption']) ? $data['paidOption'] : "";
                    $dataForDriver['username'] = $username;
                    $dataForDriver['companyName'] = $companyName;
                    if (count($userDataIfExists) == 0) {
                        $dataForDriver['app_id'] = self::APPID;
                        $dataForDriver['type'] = 'INDIVIDUAL';
                        $params['accountId'] = $fleet_id;
                        $params['ic_id'] = $ic_id;
                        $params['persistence'] = $persistenceService;
                        $this->logger->info("account id in params " . print_r($params['accountId'], true));
                        if (!isset($dataForDriver['uuid'])) {
                            $dataForDriver['uuid'] = UuidUtil::uuid();
                        }
                        if (!isset($dataForDriver['contact'])) {
                            $dataForDriver['contact'] = array();
                            $dataForDriver['contact']['username'] = $dataForDriver['email'];
                            $dataForDriver['contact']['firstname'] = $dataForDriver['name'];
                            $dataForDriver['contact']['lastname'] = $dataForDriver['lastname'];
                            $dataForDriver['contact']['email'] = $dataForDriver['email'];
                        }
                        $dataForDriver['zenddriveIntegration'] = $zendDriveSubscription;
                        $this->saveDrivers($params, $dataForDriver);
                    } else {
                        $this->logger->info("driver already exists");
                        $this->logger->info("company name before " . print_r($companyName, true));
                        $response2 = $this->Insurelearn($dataForDriver);
                        $this->logger->info("driver insurelearn data " . print_r($response2, true));
                        if (isset($zendDriveSubscription) && strtoupper($zendDriveSubscription) == "YES") {
                            $driverZendDriveResponse = $this->addDriver($fleet_id, $dataForDriver, $ic_id, $fleet_id, $persistenceService);
                            $this->logger->info("zenddriveIntegration response " . print_r($driverZendDriveResponse, true));
                        }
                    }
                }
            }
        } catch (Exception $e) {
            $this->logger->error($e->getMessage(), $e);
            throw $e;
        }
    }

    public function saveDrivers($params, $dataForDriver)
    {
        $dataForDriver['entity_name'] = 'Driver';
        $dataForDriver['status'] = 'Active';
        $response = $this->createUser($params, $dataForDriver);
        $driverUuid = $this->getUserByUsername($dataForDriver['username']);
        $dataForDriver['userUUID'] = $driverUuid[0]['uuid'];
        $dataForDriver['status'] = 'Active';
        $response1 = $this->createFile($dataForDriver);
        $response2 = $this->Insurelearn($dataForDriver);
        $this->logger->info("driver insurelearn data " . print_r($response2, true));
        $this->logger->info("driver registration data " . print_r($response, true));
        $this->logger->info("driver file creation data " . print_r($response1, true));
        if (isset($dataForDriver['zenddriveIntegration']) && strtoupper($dataForDriver['zenddriveIntegration']) == "YES") {
            $driverZendDriveResponse = $this->addDriver($params['accountId'], $dataForDriver,  $params['ic_id'], $params['accountId'],  $params['persistence']);
            $this->logger->info("zenddriveIntegration response " . print_r($driverZendDriveResponse, true));
        }
    }

    public function getParentFileId($data)
    {
        $userData = $this->userService->getUserByUsername(null, $data['username']); //print_r($userData);
        if ($userData[0]['uuid']) {
            $filterParams = array();
            $filterParams['filter'][0]['filter']['filters'][] = array('field' => 'ICUserId', 'operator' => 'eq', 'value' => $userData[0]['uuid']);
            $data['filterParams'] = $filterParams;

            $files = $this->getFileList($data, $filterParams);
            echo $assocId = $files['data'][0]['uuid'];
            echo $buyerAccountId = $userData[0]['account_id'];
        }
    }

    public function uploadCSVDataForDrivers($data)
    {
        $file_mimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

        if (isset($data['driverDataFileUpload'][0]['name']) && in_array($data['driverDataFileUpload'][0]['type'], $file_mimes)) {

            $arr_file = explode('.', $data['driverDataFileUpload'][0]['name']);
            $extension = end($arr_file);

            if ('csv' == $extension) {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
            } else {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            }

            $spreadsheet = $reader->load($data['driverDataFileUpload'][0]['path']);
            $driversData = $spreadsheet->getActiveSheet()->toArray();
            $driverinfo = array();
            if (!empty($driversData)) {
                for ($i = 1; $i < count($driversData); $i++) {
                    array_push($driverinfo, $driversData[$i]); //skipping headers here
                }
                return $driverinfo;
            }
        }
    }

    public function checkArrayEmpty($array = array())
    {
        foreach ($array as $element) {
            if (empty($element)) {
                return "0";
            }
        }
        return "1";
    }
    public function addDriver($fleet_account_id, $driverData, $ic_id, $fleet_id, Persistence $persistenceService)
    {
        $dataForDriver = array();
        $dataForDriver = $driverData;
        $this->logger->info("Drivers Data..." . print_r($dataForDriver, true));
        $selectQuery = "SELECT * FROM `driver` WHERE email = '" . $dataForDriver['email'] . "'";
        $driverrecord = $persistenceService->selectQuery($selectQuery);
        $Driverdetails = array();
        while ($driverrecord->next()) {
            $Driverdetails[] = $driverrecord->current();
        }
        $this->logger->info("driver information " . print_r($Driverdetails, true));
        if (count($driverrecord) == 0) {
            $this->logger->info("driver information if not exist " . print_r($driverData, true));
            $endpoint = 'fleet/' . $fleet_id . '/driver/';
            $email = $dataForDriver['email'];
            $username = $dataForDriver['email'];
            $driveruuid = UuidUtil::uuid();
            $requesttype = 'POST';
            $params = array('first_name' => $dataForDriver['firstname'], 'last_name' => $dataForDriver['lastname'], 'email' => $email);
            $result = $this->apicall->getApiResponse($endpoint, $params, $requesttype);
            $this->logger->info("in zendrive delegate driver api response- " . $result);
            $parsedResponse = json_decode($result, true);
            $finalresponse = json_decode($parsedResponse['body'], true);
            if (!isset($finalresponse['data']['driver_id'])) {
                $this->logger->info("Zendrive Driver Addition Failed For" . $dataForDriver['firstname']);
            }
            $dataForDriver['zendrive_driver_id'] = $finalresponse['data']['driver_id'];
            $this->saveFile($dataForDriver, $dataForDriver['uuid']);
            $driverArr = [
                'uuid' => $driveruuid,
                'firstName'    => $dataForDriver['firstname'],
                'middleName'   => $dataForDriver['middlename'],
                'lastName'     => $dataForDriver['lastname'],
                'email'         => $email,
                'ssn'           => $dataForDriver['SSN'],
                'licenseNum'    => $dataForDriver['driverLincence'],
                'driverType'   => $dataForDriver['driverType'],
                'paidByOption' => $dataForDriver['paidOption'],
                'zendrive_driver_id' => $finalresponse['data']['driver_id'],
            ];
            $drivercolumns = "(`uuid`, `first_name`,`middle_name`,`last_name`,`email`,`ssn`,`license_num`,`driver_type`,`paid_by_option`,`zendrive_driver_id`) ";
            $drivervalues = "VALUES (:uuid,:firstName,:middleName,:lastName,:email,:ssn,:licenseNum,:driverType,:paidByOption,:zendrive_driver_id)";
            $driverinsertQuery = "INSERT INTO driver " . $drivercolumns . $drivervalues;
            $driverSelect = $persistenceService->insertQuery($driverinsertQuery, $driverArr, 'yes');
            $driver_id = $driverSelect->getGeneratedValue();

            $mappingArr = ['driver_id' => $driver_id, 'ic_id' => $ic_id];
            $mappingcolumns = "(`ic_id`,`driver_id`)";
            $mappingvalues = "VALUES (:ic_id,:driver_id)";
            $insert = "INSERT INTO ic_driver_mapping " . $mappingcolumns . $mappingvalues;
            $this->logger->info("mapping query- " . print_r($insert, true));
            $insertQuery = $persistenceService->insertQuery($insert, $mappingArr, 'yes');
        }
        $this->logger->info("driver record " . print_r($driverrecord, true));
    }

    private function Insurelearn($data)
    {
        $this->logger->info("HERE INSURE LEARN" . print_r($data, true));
        $this->setInsuranceConfig([
            "client" => "InsureLearn"
        ]);
        // if (!empty($data['debug'])) $data = $this->getSampleData();
        $this->logger->info("HERE INSURE LEARN AFTER");
        $contractorGroup = $this->insuranceService->search('groupName', [
            'groupName' => $data['companyName']
        ]);
        $this->logger->info("contractorGroup info" . print_r($contractorGroup, true));
        if (empty($contractorGroup['group'])) {
            $this->insuranceService->create('group', [
                'groupName' => $data['companyName'],
                'groupTypeID' => 2,
                'groupDescription' => 'created by eox vantage',
                'userData' => $data['companyName']
            ]);
            $contractorGroup = $this->insuranceService->search('groupName', [
                'groupName' => $data['companyName']
            ]);
            $this->logger->info("newly created contractorGroup info" . print_r($contractorGroup, true));
        }
        $this->checkDriver($data, $contractorGroup['group']);
        $this->logger->info("HERE DRIVER INSURE LEARN ADDEDD");
        return $data;
    }
    private function checkDriver($driver, $group)
    {
        $driver['email'] = strtolower($driver['email']);
        $driverUser = $this->insuranceService->search('userData', [
            'loginID' => $driver['email']
        ]);
        $this->logger->info("driver info" . print_r($driverUser, true));
        $driverUserData = [
            'loginID' => $driver['email'],
            'userData' => $driver['email'],
            'passwd' => 'Welcome2Hdol!',
            'firstName' => $driver['firstname'],
            'middleName' => $driver['middlename'],
            'lastName' => $driver['lastname'],
            'email' => $driver['email']
        ];
        if (empty($driverUser['userProfiles'])) {
            $this->insuranceService->create('user', $driverUserData + [
                'accessLevel' => 1,
                'accessMode' => 0,
                'roleName' => 'IC Driver - English',
                'locationName' => $group['@attributes']['groupName']
            ]);
            $driverUser = $this->insuranceService->search('userData', [
                'loginID' => $driver['email']
            ]);
            $this->logger->info("created driver info" . print_r($driverUser, true));
        } else {
            $this->insuranceService->create('user', $driverUserData);
            $driverUser = $driverUser['userProfiles']['userProfile'];
            $this->insuranceService->perform('group', [
                'groupID' => 12,
                'groupTypeID' => 1,
                'userProfileID' => $driverUser['@attributes']['userProfileID']
            ], 'adduser');
            $groupDrivers[] = $driverUser['@attributes']['userProfileID'];
            $this->logger->info("group drivers" . print_r($groupDrivers, true));
        }
        if (!empty($groupDrivers)) {
            $this->insuranceService->perform('group', [
                'groupID' => $group['@attributes']['groupID'],
                'groupTypeID' => 2,
                'userProfileID' => implode('|', $groupDrivers)
            ], 'adduser');
        }
    }
}
