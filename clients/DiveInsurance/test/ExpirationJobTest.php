<?php
use Oxzion\Test\DelegateTest;
use Oxzion\AppDelegate\AppDelegateService;
use Oxzion\Auth\AuthContext;
use Oxzion\Auth\AuthConstants;
use PHPUnit\DbUnit\DataSet\YamlDataSet;
use Oxzion\Utils\FileUtils;
use PHPUnit\DbUnit\DataSet\DefaultDataSet;
use Oxzion\Utils\ArtifactUtils;
use Oxzion\Encryption\Crypto;
use Oxzion\Service\FileService;
use Oxzion\Db\Persistence\Persistence;

class ExpirationJobTest extends DelegateTest
{

    public function setUp() : void
    {
        $this->loadConfig();
        // $this->data = array(
        //     "appName" => 'ox_client_app',
        //     'UUID' => 8765765,
        //     'description' => 'FirstAppOfTheClient',
        //     'orgUuid' => '53012471-2863-4949-afb1-e69b0891c98a'
        // );
        // $path = __DIR__ . '/../../../api/v1/data/delegate/' . $this->data['UUID'];
        // if (!is_link($path)) {
        //     symlink(__DIR__ . '/../data/delegate/', $path);
        // }
        // $this->config = $this->getApplicationConfig();
        // $this->persistence = new Persistence($this->config, $this->data['UUID'], $this->data['appName']);
        // $this->delegateService = $this->getApplicationServiceLocator()->get(AppDelegateService::class);
        // $this->fileService = $this->getApplicationServiceLocator()->get(FileService::class);
        parent::setUp();
    }

    public function getDataSet()
    {
        return new DefaultDataSet();
    }

    public function tearDown() : void
    {
        parent::tearDown();
        // $path = __DIR__ . '/../../../api/v1/data/delegate/' . $this->data['UUID'];
        // if (is_link($path)) {
        //     unlink($path);
        // }
        // $this->delegateService->setFileService($this->fileService);   
    } 

    // private function setUpFileServiceMock()
    // {
    //     $fileServiceMock = Mockery::mock('\Oxzion\Service\FileService');
    //     $this->delegateService->setFileService($fileServiceMock);
    //     return $fileServiceMock;
    // }


    // public function testStatusToExpiredNotEqualToDiveBoat()
    // {
    //     $appId = $this->data['UUID'];
    //     $data = ['flag'=>'notEqualTo','orgId'=>$this->data['orgUuid'],'appId'=>$appId];
    //     $filterParams = array();
    //     $filterParams['filter'][0]['filter']['filters'][] = array('field'=>'product','operator'=>'neq','value'=> 'Dive Boat');
    //     $params = array();
    //     $params['orgId'] = $this->data['orgUuid'];
    //     $today = date('Y-m-d');
    //     $filterParams['filter'][0]['filter']['filters'][] = array('field'=>'end_date','operator'=>'lte','value'=>$today);
    //     $filterParams['filter'][0]['filter']['filters'][] = array('field'=>'policyStatus','operator'=>'eq','value'=> 'In Force');
    //     $pageSize = 1000;
    //     $filterParams['filter'][0]['take'] = $pageSize;
    //     $skip =  0;
    //     $filterParams['filter'][0]['skip'] = $skip;
    //     $saveParams = array("data" => array(array("policyStatus" => "In Force","uuid" => '53012471-2863-4949-afb1-e69b0891cad4'),array("policyStatus" => "In Force","uuid" => '53012471-9783-4949-afb1-e69b0891cad4')));
    //     $fileServiceMock = $this->setUpFileServiceMock();
    //     $getFileListResponse = array($saveParams,'total' => 3680);
    //     for($i=0;$i<4;$i++){
    //         $this->setUpFileParams($appId,$fileServiceMock,$params,$filterParams,$saveParams,$getFileListResponse);   
    //         $filterParams['filter'][0]['skip'] += 1000;
    //     }
    //     $this->delegateService->setPersistence($appId, $this->persistence);
    //     $content = $this->delegateService->execute($appId, 'UpdatePolicyStatusOnExpiration', $data);
    //     $this->assertEquals($content, $data);
    // }

    // public function testStatusToExpiredEqualToDiveBoat()
    // {
    //     $appId = $this->data['UUID'];
    //     $data = ['flag'=>'equalTo','orgId'=>$this->data['orgUuid'],'appId'=>$appId];
    //     $filterParams = array();
    //     $filterParams['filter'][0]['filter']['filters'][] = array('field'=>'product','operator'=>'eq','value'=> 'Dive Boat');
    //     $params = array();
    //     $params['orgId'] = $this->data['orgUuid'];
    //     $today = date('Y-m-d');
    //     $filterParams['filter'][0]['filter']['filters'][] = array('field'=>'end_date','operator'=>'lte','value'=>$today);
    //     $filterParams['filter'][0]['filter']['filters'][] = array('field'=>'policyStatus','operator'=>'eq','value'=> 'In Force');
    //     $pageSize = 1000;
    //     $filterParams['filter'][0]['take'] = $pageSize;
    //     $skip =  0;
    //     $filterParams['filter'][0]['skip'] = $skip;
    //     $saveParams = array("data" => array(array("policyStatus" => "In Force","uuid" => '53012471-2863-4949-afb1-e69b0891cad4'),array("policyStatus" => "In Force","uuid" => '53012471-9783-4949-afb1-e69b0891cad4')));
    //     $fileServiceMock = $this->setUpFileServiceMock();
    //     $getFileListResponse = array($saveParams,'total' => 3680);
    //     for($i=0;$i<4;$i++){
    //         $this->setUpFileParams($appId,$fileServiceMock,$params,$filterParams,$saveParams,$getFileListResponse);   
    //         $filterParams['filter'][0]['skip'] += 1000;
    //     }
    //     $this->delegateService->setPersistence($appId, $this->persistence);
    //     $content = $this->delegateService->execute($appId, 'UpdatePolicyStatusOnExpiration', $data);
    //     $this->assertEquals($content, $data);
    // }

    // public function testLessThanThousandRecord()
    // {
    //     $appId = $this->data['UUID'];
    //     $data = ['flag'=>'notEqualTo','orgId'=>$this->data['orgUuid'],'appId'=>$appId];
    //     $filterParams = array();
    //     $filterParams['filter'][0]['filter']['filters'][] = array('field'=>'product','operator'=>'neq','value'=> 'Dive Boat');
    //     $params = array();
    //     $params['orgId'] = $this->data['orgUuid'];
    //     $today = date('Y-m-d');
    //     $filterParams['filter'][0]['filter']['filters'][] = array('field'=>'end_date','operator'=>'lte','value'=>$today);
    //     $filterParams['filter'][0]['filter']['filters'][] = array('field'=>'policyStatus','operator'=>'eq','value'=> 'In Force');
    //     $pageSize = 1000;
    //     $filterParams['filter'][0]['take'] = $pageSize;
    //     $skip =  0;
    //     $filterParams['filter'][0]['skip'] = $skip;
    //     $saveParams = array("data" => array(array("policyStatus" => "In Force","uuid" => '53012471-2863-4949-afb1-e69b0891cad4'),array("policyStatus" => "In Force","uuid" => '53012471-9783-4949-afb1-e69b0891cad4')));
    //     $fileServiceMock = $this->setUpFileServiceMock();
    //     $getFileListResponse = array($saveParams,'total' => 600);
    //     $this->setUpFileParams($appId,$fileServiceMock,$params,$filterParams,$saveParams,$getFileListResponse);
    //     $this->delegateService->setPersistence($appId, $this->persistence);
    //     $content = $this->delegateService->execute($appId, 'UpdatePolicyStatusOnExpiration', $data);
    //     $this->assertEquals($content, $data);
    // }

    // public function testEqualToThousandRecord()
    // {
    //     $appId = $this->data['UUID'];
    //     $data = ['flag'=>'notEqualTo','orgId'=>$this->data['orgUuid'],'appId'=>$appId];
    //     $filterParams = array();
    //     $filterParams['filter'][0]['filter']['filters'][] = array('field'=>'product','operator'=>'neq','value'=> 'Dive Boat');
    //     $params = array();
    //     $params['orgId'] = $this->data['orgUuid'];
    //     $today = date('Y-m-d');
    //     $filterParams['filter'][0]['filter']['filters'][] = array('field'=>'end_date','operator'=>'lte','value'=>$today);
    //     $filterParams['filter'][0]['filter']['filters'][] = array('field'=>'policyStatus','operator'=>'eq','value'=> 'In Force');
    //     $pageSize = 1000;
    //     $filterParams['filter'][0]['take'] = $pageSize;
    //     $skip =  0;
    //     $filterParams['filter'][0]['skip'] = $skip;
    //     $saveParams = array("data" => array(array("policyStatus" => "In Force","uuid" => '53012471-2863-4949-afb1-e69b0891cad4'),array("policyStatus" => "In Force","uuid" => '53012471-9783-4949-afb1-e69b0891cad4')));
    //     $fileServiceMock = $this->setUpFileServiceMock();
    //     $getFileListResponse = array($saveParams,'total' => 1000);
    //     $this->setUpFileParams($appId,$fileServiceMock,$params,$filterParams,$saveParams,$getFileListResponse);
    //     $this->delegateService->setPersistence($appId, $this->persistence);
    //     $content = $this->delegateService->execute($appId, 'UpdatePolicyStatusOnExpiration', $data);
    //     $this->assertEquals($content, $data);
    // }

    // public function testMoreToThousandRecord()
    // {
    //     $appId = $this->data['UUID'];
    //     $data = ['flag'=>'notEqualTo','orgId'=>$this->data['orgUuid'],'appId'=>$appId];
    //     $filterParams = array();
    //     $filterParams['filter'][0]['filter']['filters'][] = array('field'=>'product','operator'=>'neq','value'=> 'Dive Boat');
    //     $params = array();
    //     $params['orgId'] = $this->data['orgUuid'];
    //     $today = date('Y-m-d');
    //     $filterParams['filter'][0]['filter']['filters'][] = array('field'=>'end_date','operator'=>'lte','value'=>$today);
    //     $filterParams['filter'][0]['filter']['filters'][] = array('field'=>'policyStatus','operator'=>'eq','value'=> 'In Force');
    //     $pageSize = 1000;
    //     $filterParams['filter'][0]['take'] = $pageSize;
    //     $skip =  0;
    //     $filterParams['filter'][0]['skip'] = $skip;
    //     $saveParams = array("data" => array(array("policyStatus" => "In Force","uuid" => '53012471-2863-4949-afb1-e69b0891cad4'),array("policyStatus" => "In Force","uuid" => '53012471-9783-4949-afb1-e69b0891cad4')));
    //     $fileServiceMock = $this->setUpFileServiceMock();
    //     $getFileListResponse = array($saveParams,'total' => 1560);
    //     for($i=0;$i<1;$i++){
    //         $this->setUpFileParams($appId,$fileServiceMock,$params,$filterParams,$saveParams,$getFileListResponse);   
    //         $filterParams['filter'][0]['skip'] += 1000;
    //     }
    //     $this->setUpFileParams($appId,$fileServiceMock,$params,$filterParams,$saveParams,$getFileListResponse);
    //     $this->delegateService->setPersistence($appId, $this->persistence);
    //     $content = $this->delegateService->execute($appId, 'UpdatePolicyStatusOnExpiration', $data);
    //     $this->assertEquals($content, $data);
    // }

    // private function setUpFileParams($appId,$fileServiceMock,$params,$filterParams,$saveParams,$getFileListResponse){
    //     $fileServiceMock->expects('getFileList')->with($appId,$params,$filterParams)->once()->andReturn($getFileListResponse);
    //     foreach($saveParams['data'] as $id => $param){
    //         $param['policyStatus'] = 'Expired';
    //         $fileServiceMock->expects('updateFile')->with($param,$param['uuid'])->once()->andReturn($id + 10);
    //     }
    // }



    // if(enableMock == 0){
    //         $filterParams = array();
    //         $params = array();
    //         $params['orgId'] = $this->data['orgUuid'];
    //         $today = date('Y-m-d');
    //         $filterParams['filter'][0]['filter']['filters'][] = array('field'=>'end_date','operator'=>'lte','value'=>$today);
    //         $filterParams['filter'][0]['filter']['filters'][] = array('field'=>'policyStatus','operator'=>'eq','value'=> 'In Force');
    //         $fileServiceMock = $this->setUpFileServiceMock();
    //         $entityName = ['Individual Professional Liability','Emergency First Response','Dive Store'];
    //         for($i = 0;$i < sizeof($entityName);$i++){
    //             $params['entityName'] = $entityName[$i];
    //             print_r($params);
    //             print_r($filterParams);
    //             $fileServiceMock->expects('updateFieldValueOnFile')->with($params,'policyStatus','In Force','Expired',$filterParams)->once()->andReturn($data);    
    //         }
    //     }

}