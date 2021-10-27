<?php
use Oxzion\Test\DelegateTest;
use Oxzion\AppDelegate\AppDelegateService;
use Oxzion\Auth\AuthContext;
use Oxzion\Auth\AuthConstants;
use PHPUnit\DbUnit\DataSet\DefaultDataSet;


class ZenDriveFleetIntegrationTest extends DelegateTest
{  
    
	public function setUp() : void
    {
        print_r("inside zenddrive test");
        $this->loadConfig();
        print_r("inside zenddrive test after loadconfig");
        $config = $this->getApplicationConfig();
        print_r("inside zenddrive test after config");
        $this->data = array(
            "appName" => 'HubDrive',
            'UUID' => 'a4b1f073-fc20-477f-a804-1aa206938c42',
            'description' => 'Hubdrive Online Client Application',
            'accountUuid' => '6b88905a-fa7b-47a4-af18-a5eed6ade5c5',
            'accountId' =>'1836',
            'buyerAccountId'=>'90913287801',
        );
        // $migrationFolder = __DIR__  . "/../data/migrations/";
        // $this->doMigration($this->data,$migrationFolder);
        // $path = __DIR__.'/../../../api/data/delegate/'.$this->data['UUID'];
        // if (!is_link($path)) {
        //     symlink(__DIR__.'/../data/delegate/',$path);
        // }
        parent::setUp();
        print_r("inside zenddrive test after setup");
    }

    public function getDataSet()
    {
        return new DefaultDataSet();
    }

    /*public function tearDown() : void
    {
        parent::tearDown();
        $path = __DIR__.'/../../../api/v1/data/delegate/'.$this->data['UUID'];
        if (is_link($path)) {
            unlink($path);
        }

        $query = "DROP DATABASE " . $this->database;//comment
        $statement = $this->getDbAdapter()->query($query);
        $result = $statement->execute();

    }*/


    public function  testFleetAddition(){

    $accountId=AuthContext::put(AuthConstants::ACCOUNT_ID, $this->data['accountId']);
    $accountUuid = AuthContext::put(AuthConstants::ACCOUNT_UUID,$this->data['accountUuid']);
    
    $data = array();
    $config = $this->getApplicationConfig();
    // print_r($config);exit;

    $appId = $this->data['UUID'];
    $testdata = Array
    (
        'buyerAccountId'=>$this->data['buyerAccountId'],
        'name'=>'Stella Lang',
        'email'=>'sadubipy@mailinator.com',
        'phone'=>'9876567890',
        'zenDriveIntegration' => 'yes'  
    );
    
    print_r("inside testcase".AppDelegateService::class);exit;
    $delegateService = $this->getApplicationServiceLocator()->get(AppDelegateService::class);
    $delegateService->setPersistence($appId, $this->persistence);
    $content = $delegateService->execute($appId, 'ZenDriveFleetIntegration', $testdata);
    

    $select = "SELECT * FROM `ic_info` WHERE ox_app_account_id=:accountId AND uuid=:buyerAccountId ";
    $result = $this->persistence->selectQuery($select,[
        "accountId"=>$this->data['accountId'],
        "buyerAccountId" => $this->data['buyerAccountId']
    ],true);

    $this->assertEquals($result[0]['ic_name'],"Stella Lang");
    $this->assertEquals($result[0]['uuid'],$this->data['buyerAccountId']);
    $this->assertEquals($result[0]['zendrive_fleet_api_key'],$content['fleet_api_key']);
}


public function  testDriverAddition(){
    $accountId=AuthContext::put(AuthConstants::ACCOUNT_ID, $this->data['accountId']);
    $accountUuid = AuthContext::put(AuthConstants::ACCOUNT_UUID,$this->data['accountUuid']);
    
    $data = array();
    $config = $this->getApplicationConfig();
    $appId = $this->data['UUID'];
    $testdata = Array
    (
        'buyerAccountId'=>$this->data['buyerAccountId']+1,
        'name'=>'Stella Lang',
        'email'=>'sadubipy44@mailinator.com',
        'phone'=>'9876567890',
        'zenDriveIntegration' => 'yes',
        "driverDataGrid" => '[{"driverFirstName":"Walker11","driverId":1,"driverMiddleName":"J","driverLastName":"Jack","driverDateofBirth":"","driverSsn":"XXX8293057","driverLicense":"12355667","doesthedriverhave2YearsofcommercialdrivingexperienceinNorthAmerica":"yes","pleaseindicatetypeofdriver":"coDriver","pleaseselectthepaidbyoption":1099,"driveruuid":"6ed45560-26df-41d5-b8e3-ebd9cf6a7e15"},{"driverFirstName":"John11","driverId":2,"driverMiddleName":"D","driverLastName":"Daniels","driverDateofBirth":"","driverSsn":"XXX8584958","driverLicense":"1235590","doesthedriverhave2YearsofcommercialdrivingexperienceinNorthAmerica":"yes","pleaseindicatetypeofdriver":"coDriver","pleaseselectthepaidbyoption":"w2","driveruuid":"6ed45560-26df-41d5-b8e3-ebd9cf6a8e29"}]',
    );
    

    $delegateService = $this->getApplicationServiceLocator()->get(AppDelegateService::class);
    $delegateService->setPersistence($appId, $this->persistence);
    $content = $delegateService->execute($appId, 'ZenDriveFleetIntegration', $testdata);

    $getIC = "SELECT * FROM `ic_info` WHERE ox_app_account_id=:accountId AND uuid=:buyerAccountId ";
    $resultgetIC = $this->persistence->selectQuery($getIC,[
        "accountId"=>$this->data['accountId'],
        "buyerAccountId" => $testdata['buyerAccountId']
    ],true);

    echo $ic_id = $resultgetIC[0]['id'];

    $selectMapping = "SELECT * FROM `ic_driver_mapping` WHERE ic_id=:ic_id";
    $resultMapping = $this->persistence->selectQuery($selectMapping,[
       "ic_id" => $ic_id
    ],true);

    //print_r($resultMapping);exit;

    $this->assertNotEmpty($resultMapping,"Mapping Array is empty.");
    
    //if(!empty($resultMapping)){
        //foreach($resultMapping as $driver){
            $selectDriver = "SELECT * FROM `driver` WHERE id=:driver_id";
            $resultDriver = $this->persistence->selectQuery($selectDriver,[
           "driver_id" => $resultMapping[0]['driver_id']],true);
        //}
    //}
    $this->assertEquals($resultDriver[0]['first_name'],"Walker11");
    //$this->assertEquals($resultDriver[1]['first_name'],"John11");
    


    
}
public function  testDriverDeletion(){
    $accountId=AuthContext::put(AuthConstants::ACCOUNT_ID, $this->data['accountId']);
    $accountUuid = AuthContext::put(AuthConstants::ACCOUNT_UUID,$this->data['accountUuid']);
    
    $data = array();
    $config = $this->getApplicationConfig();
    $appId = $this->data['UUID'];
    $testdata = Array
    (
        'buyerAccountId'=>$this->data['buyerAccountId']+1,
        "driveruuid" => "6ed45560-26df-41d5-b8e3-ebd9cf6a7e15"
    );

    $delegateService = $this->getApplicationServiceLocator()->get(AppDelegateService::class);
    $delegateService->setPersistence($appId, $this->persistence);
    $content = $delegateService->execute($appId, 'ZenDriveFleetIntegration', $testdata);

    $driver_info = $testdata['driveruuid'];
            $selectQuery = "SELECT * FROM `driver` WHERE uuid = :uuid";
            $resultArr = $this->persistence->selectQuery($selectQuery,[
                "uuid"=>$driver_info
            ],true);
    $this->assertEquals($resultArr[0]['first_name'],"Walker11");
}



/*public function testifICRegister($data){
    // assert function 
    $this->assertArrayHasKey('buyerAccountId', $data, "IC Account Is Not Yet Registered.");
  
}

public function testifICRegisterWithZendrive($data){
    $this->assertArrayHasKey('fleet_api_key', $data, "IC Is Not Yet Registered With Zendrive.");
}
*/

}