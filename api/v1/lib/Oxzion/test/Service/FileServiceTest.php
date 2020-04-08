<?php
namespace Oxzion\Service;

// use Oxzion\Service\FormService;
use Oxzion\Test\AbstractServiceTest;
// use Oxzion\Transaction\TransactionManager;
// use Zend\Db\Adapter\Adapter;
use PHPUnit\DbUnit\DataSet\YamlDataSet;
use Oxzion\Auth\AuthContext;
use Oxzion\Auth\AuthConstants;
use Symfony\Component\Yaml\Yaml;
use Oxzion\ServiceException;

class FileServiceTest extends AbstractServiceTest
{
    public $dataset = null;

    protected function setUp(): void
    {
        $this->loadConfig();
        parent::setUp();
        $this->fileService = $this->getApplicationServiceLocator()->get(\Oxzion\Service\FileService::class);
        AuthContext::put(AuthConstants::ORG_ID, 1);
        AuthContext::put(AuthConstants::USER_ID, 1);
        $this->dataset = $this->parseYaml();
    }

    public function getDataSet()
    {
        $dataset = new YamlDataSet(dirname(__FILE__)."/Dataset/File.yml");
        $dataset->addYamlFile(dirname(__FILE__) . "/../../../../module/User/test/Dataset/User.yml");
        return $dataset;
    }

    private function parseYaml(){
        $dataset = Yaml::parseFile(dirname(__FILE__)."/Dataset/File.yml");
        return $dataset;
    }

    public function testGetFileListWithNoWorkflowOrUserIdInRoute() {
        $orgId = AuthContext::get(AuthConstants::ORG_ID);
        $dataset = $this->dataset;
        $appUuid = $dataset['ox_app'][0]['uuid'];
        $params = null;
        $filterParams = null;
        $result = $this->fileService->getFileList($appUuid,$params,$filterParams);
        $this->assertEquals('d13d0c68-98c9-11e9-adc5-308d99c9145b', $result['data'][0]['uuid']);
        $this->assertEquals('d13d0c68-98c9-11e9-adc5-308d99c9145c', $result['data'][1]['uuid']);
        $this->assertEquals('d13d0c68-98c9-11e9-adc5-308d99c9145d', $result['data'][2]['uuid']);
        $this->assertEquals('d13d0c68-98c9-11e9-adc5-308d99c9146d', $result['data'][3]['uuid']);
        $this->assertEquals(7, $result['total']);
    }

    public function testGetFileListWithWorkflowButNoUserIdInRoute() {
        $orgId = AuthContext::get(AuthConstants::ORG_ID);
        $dataset = $this->dataset;
        $appUuid = $dataset['ox_app'][0]['uuid'];
        $params = array('workflowId' => '1141cd2e-cb14-11e9-a32f-2a2ae2dbcce4');
        $filterParams = null;
        $result = $this->fileService->getFileList($appUuid,$params,$filterParams);
        $this->assertEquals('d13d0c68-98c9-11e9-adc5-308d99c9145b', $result['data'][0]['uuid']);
        $this->assertEquals('3f20b5c5-0124-11ea-a8a0-22e8105c0778', $result['data'][0]['workflowInstanceId']);
        $this->assertEquals('d13d0c68-98c9-11e9-adc5-308d99c9145c', $result['data'][1]['uuid']);
        $this->assertEquals('entity1', $result['data'][1]['entity_name']);
        $this->assertEquals(6, $result['total']);
    }

    public function testGetFileListWithWorkflowButNoUserIdInRoute2() {
        $orgId = AuthContext::get(AuthConstants::ORG_ID);
        $dataset = $this->dataset;
        $appUuid = $dataset['ox_app'][0]['uuid'];
        //Change in workflowId
        $params = array('workflowId' => '1141cd2e-cb14-11e9-a32f-2a2ae2dbccpo');
        $filterParams = null;
        $result = $this->fileService->getFileList($appUuid,$params,$filterParams);
        $this->assertEquals('New File Data with different Workflow- Latest', $result['data'][0]['data']);
        $this->assertEquals('39bcde37-1c2a-4461-800d-a5ab4b801491', $result['data'][0]['uuid']);
        $this->assertEquals(1, $result['total']);
    }

    public function testGetFileListWithoutWorkflowButWithUserIdInRoute() {
        $orgId = AuthContext::get(AuthConstants::ORG_ID);
        $dataset = $this->parseYaml();
        $appUuid = $dataset['ox_app'][0]['uuid'];
        $params = array('userId' => '4fd99e8e-758f-11e9-b2d5-68ecc57cde45');
        $filterParams = null;
        $result = $this->fileService->getFileList($appUuid,$params,$filterParams);
        $this->assertEquals('d13d0c68-98c9-11e9-adc5-308d99c9145d', $result['data'][2]['uuid']);
        $this->assertEquals('3f20b5c5-0124-11ea-a8a0-22e8105c0790', $result['data'][2]['workflowInstanceId']);
        $this->assertEquals('d13d0c68-98c9-11e9-adc5-308d99c9145c', $result['data'][1]['uuid']);
        $this->assertEquals('entity1', $result['data'][1]['entity_name']);
        $this->assertEquals(4, $result['total']);
    }

    public function testGetFileListWithWorkflowAndUserIdInRoute() {
        $orgId = AuthContext::get(AuthConstants::ORG_ID);
        $dataset = $this->dataset;
        $appUuid = $dataset['ox_app'][0]['uuid'];
        //workflow 1 user 1
        $params = array('workflowId' => '1141cd2e-cb14-11e9-a32f-2a2ae2dbcce4','userId' => '4fd9ce37-758f-11e9-b2d5-68ecc57cde45');
        $filterParams = null;
        $result = $this->fileService->getFileList($appUuid,$params,$filterParams);
        $this->assertEquals('New File Data - Latest In Progress', $result['data'][0]['data']);
        $this->assertEquals('f13d0c68-98c9-11e9-adc5-308d99c91478', $result['data'][0]['uuid']);
        $this->assertEquals('New File Data - Latest Completed', $result['data'][1]['data']);
        $this->assertEquals('d13d0c68-98c9-11e9-adc5-308d99c91478', $result['data'][1]['uuid']);
        $this->assertEquals(2, $result['total']);
    }

    public function testGetFileListWithWorkflowAndUserIdInRoute2() {
        $orgId = AuthContext::get(AuthConstants::ORG_ID);
        $dataset = $this->dataset;
        $appUuid = $dataset['ox_app'][0]['uuid'];
        //workflow 2 user 2
        $params = array('workflowId' => '1141cd2e-cb14-11e9-a32f-2a2ae2dbccpo','userId' => '4fd9ce37-758f-11e9-b2d5-68ecc57cde45');
        $filterParams = null;
        $result = $this->fileService->getFileList($appUuid,$params,$filterParams);
        $this->assertEquals('New File Data with different Workflow- Latest', $result['data'][0]['data']);
        $this->assertEquals('39bcde37-1c2a-4461-800d-a5ab4b801491', $result['data'][0]['uuid']);
        $this->assertEquals(1, $result['total']);
    }

    public function testGetFileListWithAppRegistryCheck() {
        $orgId = AuthContext::get(AuthConstants::ORG_ID);
        //Random uuid
        $appUuid = '02d8ae56-8da4-4162-a628-ab10b9514641';
        $params = array('workflowId' => '1141cd2e-cb14-11e9-a32f-2a2ae2dbccpo','userId' => '4fd9ce37-758f-11e9-b2d5-68ecc57cde45');
        $filterParams = null;
        try {
            $result = $this->fileService->getFileList($appUuid,$params,$filterParams);
            $this->fail("ServiceException should have been thrown with message \'App Does not belong to the org\'");
        }
        catch (ServiceException $e) {
            $this->assertEquals("App Does not belong to the org", $e->getMessage());
            $this->assertEquals("app.fororgnot.found", $e->getMessageCode());
        }
    }

    public function testGetFileListWithWorkflowStatusCheckPositive() {
        $orgId = AuthContext::get(AuthConstants::ORG_ID);
        $dataset = $this->dataset;
        $appUuid = $dataset['ox_app'][0]['uuid'];
        $params = array('workflowStatus' => 'Completed');
        $filterParams = null;
        $result = $this->fileService->getFileList($appUuid,$params,$filterParams);
        $this->assertEquals("Completed",$result['data'][0]['status']);
        $this->assertEquals("Completed",$result['data'][1]['status']);
        $this->assertEquals("Completed",$result['data'][2]['status']);
        $this->assertEquals("Completed",$result['data'][3]['status']);
        $this->assertEquals(4,$result['total']);
    }

    public function testGetFileListWithWorkflowStatusCheckNegative() {
        $orgId = AuthContext::get(AuthConstants::ORG_ID);
        $dataset = $this->dataset;
        $appUuid = $dataset['ox_app'][0]['uuid'];
        $params = array('workflowStatus' => 'Random');
        $filterParams = null;
        $result = $this->fileService->getFileList($appUuid,$params,$filterParams);
        $this->assertEmpty($result['data']);
    }

    public function testGetFileListWithEntityNameCheckPositive() {
        $orgId = AuthContext::get(AuthConstants::ORG_ID);
        $dataset = $this->dataset;
        $appUuid = $dataset['ox_app'][0]['uuid'];
        $params = array('entityName' => 'entity1');
        $filterParams = null;
        $result = $this->fileService->getFileList($appUuid,$params,$filterParams);
        $this->assertEquals("d13d0c68-98c9-11e9-adc5-308d99c9145b",$result['data'][0]['uuid']);
        $this->assertEquals("entity1",$result['data'][0]['entity_name']);
        $this->assertEquals("39bcde37-1c2a-4461-800d-a5ab4b801491",$result['data'][6]['uuid']);
        $this->assertEquals("entity1",$result['data'][6]['entity_name']);
        $this->assertEquals(7,$result['total']);
    }

    public function testGetFileListWithEntityNameCheckNegative() {
        $orgId = AuthContext::get(AuthConstants::ORG_ID);
        $dataset = $this->dataset;
        $appUuid = $dataset['ox_app'][0]['uuid'];
        $params = array('entityName' => 'random');
        $filterParams = null;
        $result = $this->fileService->getFileList($appUuid,$params,$filterParams);
        $this->assertEmpty($result['data']);
    }

    public function testGetFileListWithEntityNameCheckAndAssocIdNegative(){
        $orgId = AuthContext::get(AuthConstants::ORG_ID);
        $dataset = $this->dataset;
        $appUuid = $dataset['ox_app'][0]['uuid'];
        $params = array('entityName' => 'random', 'assocId' => 'd13d0c68-98c9-11e9-adc5-308d99c9145b');
        $filterParams = null;
        $result = $this->fileService->getFileList($appUuid,$params,$filterParams);
        $this->assertEmpty($result['data']);
    }

    public function testGetFileListWithEntityNameCheckAndAssocIdNegative2(){
        $orgId = AuthContext::get(AuthConstants::ORG_ID);
        $dataset = $this->dataset;
        $appUuid = $dataset['ox_app'][0]['uuid'];
        $params = array('entityName' => 'entity1', 'assocId' => '2');
        $filterParams = null;
        try{
            $result = $this->fileService->getFileList($appUuid,$params,$filterParams);
            $this->fail("ServiceException should have been thrown with code \'app.mysql.error\'");
        }
        catch (ServiceException $e){
            $this->assertEquals("app.mysql.error", $e->getMessageCode());
        }
    }

    public function testGetFileListWithEntityNameCheckAndAssocIdPositive() {
        $orgId = AuthContext::get(AuthConstants::ORG_ID);
        $dataset = $this->dataset;
        $appUuid = $dataset['ox_app'][0]['uuid'];
        $params = array('entityName' => 'entity1', 'assocId' => 'd13d0c68-98c9-11e9-adc5-308d99c9145b');
        $filterParams = null;
        $result = $this->fileService->getFileList($appUuid,$params,$filterParams);
        $this->assertEquals("d13d0c68-98c9-11e9-adc5-308d99c9145c",$result['data'][0]['uuid']);
        $this->assertEquals("entity1",$result['data'][0]['entity_name']);
        $this->assertEquals(1,$result['total']);
    }

    public function testGetFileListWithGreaterThanOrEqualCreatedDateCheck() {
        $orgId = AuthContext::get(AuthConstants::ORG_ID);
        $dataset = $this->dataset;
        $appUuid = $dataset['ox_app'][0]['uuid'];
        $params = array('gtCreatedDate' => '2019-06-27');
        $filterParams = null;
        $result = $this->fileService->getFileList($appUuid,$params,$filterParams);
        $this->assertEquals("d13d0c68-98c9-11e9-adc5-308d99c9145c",$result['data'][0]['uuid']);
        $this->assertEquals("entity1",$result['data'][0]['entity_name']);
        $this->assertEquals(1,$result['total']);
    }

    public function testGetFileListWithLesserThanOrEqualCreatedDateCheck() {
        $orgId = AuthContext::get(AuthConstants::ORG_ID);
        $dataset = $this->dataset;
        $appUuid = $dataset['ox_app'][0]['uuid'];
        $params = array('ltCreatedDate' => '2019-06-25');
        $filterParams = null;
        $result = $this->fileService->getFileList($appUuid,$params,$filterParams);
        $this->assertEquals('d13d0c68-98c9-11e9-adc5-308d99c9145b', $result['data'][0]['uuid']);
        $this->assertEquals('3f20b5c5-0124-11ea-a8a0-22e8105c0778', $result['data'][0]['workflowInstanceId']);
        $this->assertEquals(1, $result['total']);
    }

    public function testGetFileListWithUserMe() {
        $orgId = AuthContext::get(AuthConstants::ORG_ID);
        $dataset = $this->dataset;
        $appUuid = $dataset['ox_app'][0]['uuid'];
        $params = array('userId' => 'me');
        $filterParams = null;
        $result = $this->fileService->getFileList($appUuid,$params,$filterParams);
        $this->assertEquals('d13d0c68-98c9-11e9-adc5-308d99c9145c', $result['data'][1]['uuid']);
        $this->assertEquals('Completed', $result['data'][1]['status']);
        $this->assertEquals('3f20b5c5-0124-11ea-a8a0-22e8105c0790', $result['data'][2]['workflowInstanceId']);
        $this->assertEquals(4, $result['total']);
    }

    public function testGetFileListWithInvalidUser() {
        $orgId = AuthContext::get(AuthConstants::ORG_ID);
        $dataset = $this->dataset;
        $appUuid = $dataset['ox_app'][0]['uuid'];
        $params = array('userId' => '2b897411-ce40-481f-ae93-e004c1e63859');
        $filterParams = null;
        try {
            $result = $this->fileService->getFileList($appUuid,$params,$filterParams);
            $this->fail("ServiceException should have been thrown with message \'User Does not Exist\'");
        }
        catch (ServiceException $e) {
            $this->assertEquals("User Does not Exist", $e->getMessage());
            $this->assertEquals("app.forusernot.found", $e->getMessageCode());
        }
    }

}
