<?php
namespace Oxzion\Service;

use Oxzion\Test\AbstractServiceTest;
use Zend\Db\Adapter\Adapter;
use PHPUnit\DbUnit\DataSet\YamlDataSet;
use Oxzion\Auth\AuthContext;
use Oxzion\Auth\AuthConstants;
use Symfony\Component\Yaml\Yaml;
use Oxzion\ServiceException;
use Oxzion\ValidationException;
use Oxzion\EntityNotFoundException;
use Zend\Db\Adapter\Exception\InvalidQueryException;
use \Exception;
use Zend\Db\ResultSet\ResultSet;
use Oxzion\Utils\FileUtils;

class FileServiceTest extends AbstractServiceTest
{
    public $dataset = null;

    public $adapter = null;

    protected function setUp(): void
    {
        $this->loadConfig();
        parent::setUp();
        $this->fileService = $this->getApplicationServiceLocator()->get(\Oxzion\Service\FileService::class);
        AuthContext::put(AuthConstants::ORG_ID, 1);
        AuthContext::put(AuthConstants::ORG_UUID, '53012471-2863-4949-afb1-e69b0891c98a');
        AuthContext::put(AuthConstants::USER_ID, 1);
        $this->dataset = $this->parseYaml();
        $this->adapter = $this->getDbAdapter();
        $this->adapter->getDriver()->getConnection()->setResource(static::$pdo);
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

    private function runQuery($query) {
        $statement = $this->adapter->query($query);
        $result = $statement->execute();
        $resultSet = new ResultSet();
        $result = $resultSet->initialize($result)->toArray();
        return $result;
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
        $this->assertEquals(10, $result['total']);
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
        $this->assertEquals(9, $result['total']);
    }

    public function testGetFileListWithWorkflowButNoUserIdInRoute2() {
        $orgId = AuthContext::get(AuthConstants::ORG_ID);
        $dataset = $this->dataset;
        $appUuid = $dataset['ox_app'][0]['uuid'];
        //Change in workflowId
        $params = array('workflowId' => '1141cd2e-cb14-11e9-a32f-2a2ae2dbccpo');
        $filterParams = null;
        $result = $this->fileService->getFileList($appUuid,$params,$filterParams);
        $this->assertEquals('New File Data - Not Latest', $result['data'][0]['data']);
        $this->assertEquals('37d94567-466a-46c1-8bce-9bdd4e0c0d97', $result['data'][0]['uuid']);
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
        $this->assertEquals('{"firstname":"brian","email":"brian@gmail.com"}', $result['data'][2]['data']);
        $this->assertEquals('39bcde37-1c2a-4461-800d-a5ab4b801491', $result['data'][2]['uuid']);
        $this->assertEquals(3, $result['total']);
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
        $this->assertEquals($dataset['ox_workflow_instance'][2]['status'],$result['data'][2]['status']);
        $this->assertEquals($dataset['ox_file'][2]['uuid'],$result['data'][2]['uuid']);
        $this->assertEquals($dataset['ox_workflow_instance'][2]['process_instance_id'],$result['data'][2]['workflowInstanceId']);
        $this->assertEquals("Completed",$result['data'][3]['status']);
        $this->assertEquals(8,$result['total']);
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
        $this->assertEquals("37d94567-466a-46c1-8bce-9bdd4e0c0d97",$result['data'][6]['uuid']);
        $this->assertEquals("entity1",$result['data'][6]['entity_name']);
        $this->assertEquals(9,$result['total']);
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

    public function testFileCreateWithoutEntityId() {
        $orgId = AuthContext::get(AuthConstants::ORG_ID);
        $dataset = $this->dataset;
        $appUuid = $dataset['ox_app'][0]['uuid'];
        $formId = $dataset['ox_form'][0]['uuid'];
        $data = array('field1' => 1, 'field2' => 2, 'app_id' => $appUuid, 'form_id' => $formId);
        try {
            $result = $this->fileService->createFile($data);
            $this->fail("Validation Exception should have been thrown with message \'Validation Errors\'");
        }
        catch (ValidationException $e) {
            $this->assertEquals(array('entity_id' => 'required'), $e->getErrors());
        }
    }

    public function testFileCreateWithEntityId() {
        $dataset = $this->dataset;
        $appUuid = $dataset['ox_app'][0]['uuid'];
        $formId = $dataset['ox_form'][0]['uuid'];
        $entityId = $dataset['ox_app_entity'][0]['id'];
        $sqlQuery = 'SELECT count(id) as count FROM ox_file';
        $queryResult = $this->runQuery($sqlQuery);
        if(isset($queryResult[0]['count'])) {
            $initialCount = $queryResult[0]['count'];
            $this->assertEquals(10,$initialCount);
            $data = array('field1' => 1, 'field2' => 2, 'entity_id' => 1 ,'app_id' => $appUuid, 'form_id' => $formId);
            $result = $this->fileService->createFile($data);
            $this->assertEquals(1,$result);
            $sqlQuery2 = 'SELECT entity_id FROM ox_file order by id DESC LIMIT 1';
            $newQueryResult = $this->runQuery($sqlQuery);
            $sqlQuery2Result = $this->runQuery($sqlQuery2);
            $entityId = $sqlQuery2Result[0]['entity_id'];
            $finalCount = $newQueryResult[0]['count'];
            if(isset($newQueryResult[0]['count'])) {
            $this->assertEquals(11,$finalCount);
            $this->assertEquals(1,$entityId);
        }
            else{
                $this->fail("Final count has not been generated");
            }
        }
        else{
            $this->fail("Initial count has not been generated");
        }
    }

    public function testFileCreateWithFormId() {
        $dataset = $this->dataset;
        $appUuid = $dataset['ox_app'][0]['uuid'];
        $formId = $dataset['ox_form'][0]['uuid'];
        $entityId = $dataset['ox_app_entity'][0]['id'];
        $sqlQuery = 'SELECT count(id) as count FROM ox_file';
        $queryResult = $this->runQuery($sqlQuery);
        if(isset($queryResult[0]['count'])) {
            $initialCount = $queryResult[0]['count'];
            $this->assertEquals(10,$initialCount);
            $data = array('field1' => 1, 'field2' => 2, 'entity_id' => 1 ,'app_id' => $appUuid, 'form_id' => $formId );
            $result = $this->fileService->createFile($data);
            $this->assertEquals(1,$result);
            $sqlQuery2 = 'SELECT form_id FROM ox_file order by id DESC LIMIT 1';
            $newQueryResult = $this->runQuery($sqlQuery);
            $sqlQuery2Result = $this->runQuery($sqlQuery2);
            $formId = $sqlQuery2Result[0]['form_id'];
            $finalCount = $newQueryResult[0]['count'];
            if(isset($newQueryResult[0]['count'])) {
                $this->assertEquals(11,$finalCount);
                $this->assertEquals(1,$formId);
            }
            else{
                $this->fail("Final count has not been generated");
            }
        }
        else{
            $this->fail("Initial count has not been generated");
        }
    }

    public function testFileCreateWithoutFormId() {
        $dataset = $this->dataset;
        $appUuid = $dataset['ox_app'][0]['uuid'];
        $formId = $dataset['ox_form'][0]['uuid'];
        $entityId = $dataset['ox_app_entity'][0]['id'];
        $sqlQuery = 'SELECT count(id) as count FROM ox_file';
        $queryResult = $this->runQuery($sqlQuery);
        if(isset($queryResult[0]['count'])) {
            $initialCount = $queryResult[0]['count'];
            $this->assertEquals(10,$initialCount);
            $data = array('field1' => 1, 'field2' => 2, 'entity_id' => 1 ,'app_id' => $appUuid);
            $result = $this->fileService->createFile($data);
            $this->assertEquals(1,$result);
            $sqlQuery2 = 'SELECT form_id FROM ox_file order by id DESC LIMIT 1';
            $newQueryResult = $this->runQuery($sqlQuery);
            $sqlQuery2Result = $this->runQuery($sqlQuery2);
            $formId = $sqlQuery2Result[0]['form_id'];
            $finalCount = $newQueryResult[0]['count'];
            if(isset($newQueryResult[0]['count'])) {
                $this->assertEquals(11,$finalCount);
                $this->assertEquals(null,$formId);
            }
            else{
                $this->fail("Final count has not been generated");
            }
        }
        else{
            $this->fail("Initial count has not been generated");
        }
    }

    public function testFileCreateWithRandomUuid() {
        $dataset = $this->dataset;
        $appUuid = $dataset['ox_app'][0]['uuid'];
        $formId = $dataset['ox_form'][0]['uuid'];
        $entityId = $dataset['ox_app_entity'][0]['id'];
        $sqlQuery = 'SELECT count(id) as count FROM ox_file';
        $queryResult = $this->runQuery($sqlQuery);
        if(isset($queryResult[0]['count'])) {
            $initialCount = $queryResult[0]['count'];
            $this->assertEquals(10,$initialCount);
            $data = array('field1' => 1, 'field2' => 2, 'entity_id' => 1 ,'app_id' => $appUuid, 'uuid' => '7369c4e9-90bf-41d7-b774-605469294aae');
            $result = $this->fileService->createFile($data);
            $this->assertEquals(1,$result);
            $sqlQuery2 = 'SELECT uuid FROM ox_file order by id DESC LIMIT 1';
            $newQueryResult = $this->runQuery($sqlQuery);
            $sqlQuery2Result = $this->runQuery($sqlQuery2);
            $uuid = $sqlQuery2Result[0]['uuid'];
            $finalCount = $newQueryResult[0]['count'];
            if(isset($newQueryResult[0]['count'])) {
                $this->assertEquals(11,$finalCount);
                $this->assertEquals('7369c4e9-90bf-41d7-b774-605469294aae',$uuid);
            }
            else{
                $this->fail("Final count has not been generated");
            }
        }
        else{
            $this->fail("Initial count has not been generated");
        }
    }

    public function testFileCreateWithValidUuid() {
        $dataset = $this->dataset;
        $appUuid = $dataset['ox_app'][0]['uuid'];
        $formId = $dataset['ox_form'][0]['uuid'];
        $entityId = $dataset['ox_app_entity'][0]['id'];
        $sqlQuery = 'SELECT count(id) as count FROM ox_file';
        $queryResult = $this->runQuery($sqlQuery);
        if(isset($queryResult[0]['count'])) {
            $initialCount = $queryResult[0]['count'];
            $this->assertEquals(10,$initialCount);
            $data = array('field1' => 1, 'field2' => 2, 'entity_id' => 1 ,'app_id' => $appUuid, 'uuid' => 'd13d0c68-98c9-11e9-adc5-308d99c9145b');
            $result = $this->fileService->createFile($data);
            $this->assertEquals(1,$result);
            $sqlQuery2 = 'SELECT uuid FROM ox_file order by id DESC LIMIT 1';
            $newQueryResult = $this->runQuery($sqlQuery);
            $sqlQuery2Result = $this->runQuery($sqlQuery2);
            $uuid = $sqlQuery2Result[0]['uuid'];
            $finalCount = $newQueryResult[0]['count'];
            if(isset($newQueryResult[0]['count'])) {
                $this->assertEquals(11,$finalCount);
                $this->assertNotEquals('d13d0c68-98c9-11e9-adc5-308d99c9145b',$uuid);
            }
            else{
                $this->fail("Final count has not been generated");
            }
        }
        else{
            $this->fail("Initial count has not been generated");
        }
    }

    public function testFileCreateWithoutUuid() {
        $dataset = $this->dataset;
        $appUuid = $dataset['ox_app'][0]['uuid'];
        $formId = $dataset['ox_form'][0]['uuid'];
        $entityId = $dataset['ox_app_entity'][0]['id'];
        $sqlQuery = 'SELECT count(id) as count FROM ox_file';
        $queryResult = $this->runQuery($sqlQuery);
        if(isset($queryResult[0]['count'])) {
            $initialCount = $queryResult[0]['count'];
            $this->assertEquals(10,$initialCount);
            $data = array('field1' => 1, 'field2' => 2, 'entity_id' => 1 ,'app_id' => $appUuid);
            $result = $this->fileService->createFile($data);
            $this->assertEquals(1,$result);
            $sqlQuery2 = 'SELECT uuid FROM ox_file order by id DESC LIMIT 1';
            $newQueryResult = $this->runQuery($sqlQuery);
            $sqlQuery2Result = $this->runQuery($sqlQuery2);
            $uuid = $sqlQuery2Result[0]['uuid'];
            $finalCount = $newQueryResult[0]['count'];
            if(isset($newQueryResult[0]['count'])) {
                $this->assertEquals(11,$finalCount);
                $this->assertNotEmpty($uuid);
            }
            else{
                $this->fail("Final count has not been generated");
            }
        }
        else{
            $this->fail("Initial count has not been generated");
        }
    }

    public function testFileCreateWithEntityName() {
        $dataset = $this->dataset;
        $appUuid = $dataset['ox_app'][0]['uuid'];
        $formId = $dataset['ox_form'][0]['uuid'];
        $entityId = $dataset['ox_app_entity'][0]['id'];
        $sqlQuery = 'SELECT count(id) as count FROM ox_file';
        $queryResult = $this->runQuery($sqlQuery);
        if(isset($queryResult[0]['count'])) {
            $initialCount = $queryResult[0]['count'];
            $this->assertEquals(10,$initialCount);
            $data = array('field1' => 1, 'field2' => 2, 'app_id' => $appUuid, 'entity_name' => 'entity1');
            $result = $this->fileService->createFile($data);
            $this->assertEquals(1,$result);
            $sqlQuery2 = 'SELECT entity_id FROM ox_file order by id DESC LIMIT 1';
            $newQueryResult = $this->runQuery($sqlQuery);
            $sqlQuery2Result = $this->runQuery($sqlQuery2);
            $entityId = $sqlQuery2Result[0]['entity_id'];
            $finalCount = $newQueryResult[0]['count'];
            if(isset($newQueryResult[0]['count'])) {
                $this->assertEquals(11,$finalCount);
                $this->assertEquals(1,$entityId);
            }
            else{
                $this->fail("Final count has not been generated");
            }
        }
        else{
            $this->fail("Initial count has not been generated");
        }
    }

    public function testFileCreateWithEntityNameAndEntityId() {
        $dataset = $this->dataset;
        $appUuid = $dataset['ox_app'][0]['uuid'];
        $formId = $dataset['ox_form'][0]['uuid'];
        $entityId = $dataset['ox_app_entity'][0]['id'];
        $sqlQuery = 'SELECT count(id) as count FROM ox_file';
        $queryResult = $this->runQuery($sqlQuery);
        if(isset($queryResult[0]['count'])) {
            $initialCount = $queryResult[0]['count'];
            $this->assertEquals(10,$initialCount);
            $data = array('field1' => 1, 'field2' => 2, 'entity_id' => 1 ,'app_id' => $appUuid, 'entity_name' => 'entity1');
            $result = $this->fileService->createFile($data);
            $this->assertEquals(1,$result);
            $sqlQuery2 = 'SELECT entity_id FROM ox_file order by id DESC LIMIT 1';
            $newQueryResult = $this->runQuery($sqlQuery);
            $sqlQuery2Result = $this->runQuery($sqlQuery2);
            $entityId = $sqlQuery2Result[0]['entity_id'];
            $finalCount = $newQueryResult[0]['count'];
            if(isset($newQueryResult[0]['count'])) {
                $this->assertEquals(11  ,$finalCount);
                $this->assertEquals(1,$entityId);
            }
            else{
                $this->fail("Final count has not been generated");
            }
        }
        else{
            $this->fail("Initial count has not been generated");
        }
    }

    public function testFileCreateWithCheckFields() {
        $dataset = $this->dataset;
        $appUuid = $dataset['ox_app'][0]['uuid'];
        $formId = $dataset['ox_form'][0]['uuid'];
        $entityId = $dataset['ox_app_entity'][0]['id'];
        $sqlQuery = 'SELECT count(id) as count FROM ox_file';
        $data = array('field1' => "Test Value", 'field2' => 2, 'field3' => 'invalid field3', 'field4' => 'invalid field4' ,'entity_id' => 1 ,'app_id' => $appUuid, 'entity_name' => 'entity1', 'policy_document' => ["file" => "file1.pdf"], "coi_attachment" => ["file" => "attachment.pdf"]);
        $data = array('field1' => "Test Value", 'field2' => 2, 'field3' => 'invalid field3', 'field4' => 'invalid field4' ,'entity_id' => 1 ,'app_id' => $appUuid, 'entity_name' => 'entity1', 'policy_document' => ["file" => "file1.pdf"], "coi_attachment" => [["name" => "SampleAttachment.txt", "extension" => "txt", "uuid" => "ef06bf97-b187-48b3-acba-83cf4348a61b", "path" => __DIR__."/Dataset/SampleAttachment.txt"]]);
        $result = $this->fileService->createFile($data);
        $this->assertEquals(1,$result);
        $this->assertEquals(true, $data['id'] > 0);
        $sqlQuery2 = 'SELECT entity_id, data FROM ox_file order by id DESC LIMIT 1';
        $sqlQuery3 = "SELECT * from ox_file_attribute where file_id = ".$data['id'];
        $newQueryResult = $this->runQuery($sqlQuery." where id = ".$data['id']);
        $sqlQuery2Result = $this->runQuery($sqlQuery2);
        $sqlQuery3Result = $this->runQuery($sqlQuery3);
        $sqlQuery4 = "SELECT * from ox_file_document where file_id = ".$data['id'];
        $sqlQuery4Result = $this->runQuery($sqlQuery4);
        $this->assertEquals(2, count($sqlQuery4Result));
        $this->assertEquals(5, $sqlQuery4Result[0]['field_id']);
        $this->assertEquals(null, $sqlQuery4Result[0]['sequence']);
        $this->assertEquals($data['policy_document'], json_decode($sqlQuery4Result[0]['field_value'], true));
        $this->assertEquals(6, $sqlQuery4Result[1]['field_id']);
        $this->assertEquals(null, $sqlQuery4Result[1]['sequence']);
        $doc = json_decode($sqlQuery4Result[1]['field_value'], true);
        $this->assertEquals($data['coi_attachment'][0]['name'], $doc[0]['originalName']);
        $this->assertEquals("SampleAttachment-".$data['coi_attachment'][0]['uuid'].".txt", $doc[0]['name']);
        $this->assertEquals(true, file_exists($doc[0]['path']));
        $this->assertEquals($doc[0]['path'], FileUtils::truepath($this->applicationConfig['APP_DOCUMENT_FOLDER'].$doc[0]['file']));
        
        $data1 = json_decode($sqlQuery2Result[0]['data'], true);
        $this->assertEquals(6, count($data1));
        $this->assertEquals($doc, $data1['coi_attachment']);
        $this->assertEquals(0,count($sqlQuery3Result)); 
        $sqlQuery4 = "SELECT * from ox_indexed_file_attribute where file_id = ".$data['id'];
        $sqlQuery3Result = $this->runQuery($sqlQuery4);
        $this->assertEquals(1,count($sqlQuery3Result)); 
        $this->assertEquals(1, $sqlQuery3Result[0]['field_id']); 
        $this->assertEquals('TEXT', $sqlQuery3Result[0]['field_value_type']); 
        $this->assertEquals($data['field1'], $sqlQuery3Result[0]['field_value_text']); 
        $this->assertEquals(1,$newQueryResult[0]['count']);
        $this->assertEquals(1,$entityId);
        $this->fileService->updateFileAttributes($data['id']);
        $sqlQuery2Result = $this->runQuery($sqlQuery2);
        $data1 = json_decode($sqlQuery2Result[0]['data'], true);
        $this->assertEquals(6, count($data1));
        $sqlQueryResult = $this->runQuery($sqlQuery3);
        $this->assertEquals(4, count($sqlQueryResult));
        $this->assertEquals($data['field1'], $sqlQueryResult[0]['field_value']);
        $this->assertEquals(1, $sqlQueryResult[0]['field_id']);
        $this->assertEquals($data['id'], $sqlQueryResult[0]['file_id']);
        $this->assertEquals($data['field2'], $sqlQueryResult[1]['field_value']);
        $this->assertEquals(2, $sqlQueryResult[1]['field_id']);
        $this->assertEquals($data['id'], $sqlQueryResult[1]['file_id']);
        $this->assertEquals($data['policy_document'], json_decode($sqlQueryResult[2]['field_value'], true));
        $this->assertEquals(5, $sqlQueryResult[2]['field_id']);
        $this->assertEquals($data['id'], $sqlQueryResult[2]['file_id']);
        $this->assertEquals($doc, json_decode($sqlQueryResult[3]['field_value'], true));
        $this->assertEquals(6, $sqlQueryResult[3]['field_id']);
        $this->assertEquals($data['id'], $sqlQueryResult[3]['file_id']);
    }

    public function testFileCreateWithCleanData() {
        $dataset = $this->dataset;
        $appUuid = $dataset['ox_app'][0]['uuid'];
        $formId = $dataset['ox_form'][0]['uuid'];
        $entityId = $dataset['ox_app_entity'][0]['id'];
        $sqlQuery = 'SELECT count(id) as count FROM ox_file';
        $queryResult = $this->runQuery($sqlQuery);
        if(isset($queryResult[0]['count'])) {
            $initialCount = $queryResult[0]['count'];
            $this->assertEquals(10,$initialCount);
            $data = array('field1' => 1, 'field2' => 2, 'form_id' => $formId,'workflowInstanceId' => 'something' ,'entity_id' => 1 ,'app_id' => $appUuid, 'entity_name' => 'entity1');
            $result = $this->fileService->createFile($data);
            $this->assertEquals(1,$result);
            $sqlQuery2 = 'SELECT data FROM ox_file order by id DESC LIMIT 1';
            $newQueryResult = $this->runQuery($sqlQuery);
            $sqlQuery2Result = $this->runQuery($sqlQuery2);
            $data = json_decode($sqlQuery2Result[0]['data'],true);
            $finalCount = $newQueryResult[0]['count'];
            $this->assertArrayHasKey('field1',$data);  //Fields that don't exist
            $this->assertArrayHasKey('field2',$data);
            $this->assertArrayNotHasKey('form_id',$data); //Fields that exist
            $this->assertArrayNotHasKey('workflowInstanceId',$data);
            if(isset($newQueryResult[0]['count'])) {
                $this->assertEquals(11,$finalCount);
                $this->assertEquals(1,$entityId);
            }
            else{
                $this->fail("Final count has not been generated");
            }
        }
        else{
            $this->fail("Initial count has not been generated");
        }
    }

    public function testFileCreateWithCheckFieldsWithoutPersistent () {
        $dataset = $this->dataset;
                                                                     $appUuid = $dataset['ox_app'][0]['uuid'];
        $formId = $dataset['ox_form'][0]['uuid'];
        $entityId = $dataset['ox_app_entity'][0]['id'];
        $sqlQuery = 'SELECT count(id) as count FROM ox_file';
        $queryResult = $this->runQuery($sqlQuery);
        if(isset($queryResult[0]['count'])) {
            $initialCount = $queryResult[0]['count'];
            $this->assertEquals(10,$initialCount);
            $data = array('field1' => 1, 'field2' => 2, 'field3' => 3, 'non_persistent_field' => 'something' ,'entity_id' => 1 ,'app_id' => $appUuid, 'entity_name' => 'entity1');
            $result = $this->fileService->createFile($data);
            $this->assertEquals(1,$result);
            $sqlQuery2 = 'SELECT entity_id FROM ox_file order by id DESC LIMIT 1';
            $sqlQuery3 = 'SELECT field_id,field_value from ox_file_attribute where field_id in (1,2,3,4,7) order by id DESC LIMIT 5';
            $newQueryResult = $this->runQuery($sqlQuery);
            $sqlQuery2Result = $this->runQuery($sqlQuery2);
            try{
                $sqlQuery3Result = $this->runQuery($sqlQuery3);
            }
            catch(InvalidQueryException $e)
            {
                print_r($e->getMessage());
            }
        }
        else{
            $this->fail("Initial count has not been generated");
        }
    }

    public function testUpdateFileWithWorkflow() {
        $dataset = $this->dataset;
        $fileId = $dataset['ox_file'][0]['uuid'];
        $appUuid = $dataset['ox_app'][0]['uuid'];
        $formId = $dataset['ox_form'][0]['uuid'];
        $entityId = $dataset['ox_app_entity'][0]['id'];
        $data = array('field1' => 1, 'field2' => 2, 'entity_id' => 1 ,'app_id' => $appUuid, 'form_id' => $formId, 'workflow_instance_id' => 1);
        $result = $this->fileService->updateFile($data, $fileId);
        $sqlQuery = "SELECT data FROM ox_file where uuid ='".$fileId."'";
        $queryResult = $this->runQuery($sqlQuery);
        $queryResult = json_decode($queryResult[0]['data'],true);
        $this->assertEquals('Bangalore', $queryResult['city']);
        $this->assertEquals(1, $queryResult['field1']);
    }

    public function testUpdateFileWithDifferentWorkflow() {
        $dataset = $this->dataset;
        $fileId = $dataset['ox_file'][0]['uuid'];
        $appUuid = $dataset['ox_app'][0]['uuid'];
        $formId = $dataset['ox_form'][0]['uuid'];
        $entityId = $dataset['ox_app_entity'][0]['id'];
        $data = array('field1' => 1, 'field2' => 2, 'entity_id' => 1 ,'app_id' => $appUuid, 'form_id' => $formId, 'workflow_instance_id' => 9);
        try{
            $result = $this->fileService->updateFile($data, $fileId);
            $this->fail("EntityNotFoundException should have been thrown with message \'File Id not found -- ".$fileId."\'");
        }
        catch (EntityNotFoundException $e)
        {
            $this->assertEquals("File Id not found -- ".$fileId, $e->getMessage());
        }
    }

    public function testUpdateFileWithInvalidWorkflow() {
        $dataset = $this->dataset;
        $fileId = $dataset['ox_file'][0]['uuid'];
        $appUuid = $dataset['ox_app'][0]['uuid'];
        $formId = $dataset['ox_form'][0]['uuid'];
        $entityId = $dataset['ox_app_entity'][0]['id'];
        $data = array('field1' => 1, 'field2' => 2, 'entity_id' => 1 ,'app_id' => $appUuid, 'form_id' => $formId, 'workflow_instance_id' => 32);
        try{
            $result = $this->fileService->updateFile($data, $fileId);
            $this->fail("EntityNotFoundException should have been thrown with message \'File Id not found -- ".$fileId."\'");
        }
        catch (EntityNotFoundException $e)
        {
            $this->assertEquals("File Id not found -- ".$fileId, $e->getMessage());
        }
    }

    public function testUpdateFileWithNonExistantFileId() {
        $dataset = $this->dataset;
        $fileId = 21;
        $appUuid = $dataset['ox_app'][0]['uuid'];
        $formId = $dataset['ox_form'][0]['uuid'];
        $entityId = $dataset['ox_app_entity'][0]['id'];
        $sqlQuery = 'SELECT count(id) as count FROM ox_file';
        $queryResult = $this->runQuery($sqlQuery);
        if(isset($queryResult[0]['count'])) {
            $initialCount = $queryResult[0]['count'];
            $this->assertEquals(10,$initialCount);
            $data = array('field1' => 1, 'field2' => 2, 'entity_id' => 1 ,'app_id' => $appUuid, 'form_id' => $formId);
            $result = $this->fileService->updateFile($data, $fileId);
            $this->assertEquals(1,$result);
            $sqlQuery2 = 'SELECT form_id FROM ox_file order by id DESC LIMIT 1';
            $newQueryResult = $this->runQuery($sqlQuery);
            $sqlQuery2Result = $this->runQuery($sqlQuery2);
            $finalCount = $newQueryResult[0]['count'];
            if(isset($newQueryResult[0]['count'])) {
                $this->assertEquals(11,$finalCount);
                $this->assertEquals(1, $sqlQuery2Result[0]['form_id']);
            }
            else{
                $this->fail("Final count has not been generated");
            }
        }
        else{
            $this->fail("Initial count has not been generated");
        }
    }

    public function testUpdateFileWithFileId() {
        $dataset = $this->dataset;
        $fileId = $dataset['ox_file'][0]['uuid'];
        $appUuid = $dataset['ox_app'][0]['uuid'];
        $formId = $dataset['ox_form'][0]['uuid'];
        $entityId = $dataset['ox_app_entity'][0]['id'];
        $data = array('field1' => 1, 'field2' => 2, 'entity_id' => 1 ,'app_id' => $appUuid, 'form_id' => $formId);
        $result = $this->fileService->updateFile($data, $fileId);
        $sqlQuery = 'SELECT uuid FROM ox_file where id = 11';
        $sqlQueryResult = $this->runQuery($sqlQuery);
        $this->assertEquals($fileId, $sqlQueryResult[0]['uuid']);
    }

    public function testUpdateFileWithCheckFieldsWithNewFields() {
        $dataset = $this->dataset;
        $sqlQuery = 'SELECT fa.*, f.name FROM ox_file_attribute fa inner join ox_field f on f.id = fa.field_id 
                        where file_id = 11';
        $sqlQueryResult = $this->runQuery($sqlQuery);
        $sqlQuery0 = 'SELECT fa.*, f.name FROM ox_file_document fa inner join ox_field f on f.id = fa.field_id 
                        where file_id = 11';
        $sqlQuery0Result = $this->runQuery($sqlQuery0);
        $this->assertEquals(32552, $sqlQueryResult[0]['field_value']);
        $this->assertEquals(1, $sqlQueryResult[0]['field_id']);
        $this->assertEquals(11, $sqlQueryResult[0]['file_id']);
        $fileId = $dataset['ox_file'][0]['uuid'];
        $appUuid = $dataset['ox_app'][0]['uuid'];
        $data = array('field1' => 'Updated Text', 'field2' => "Modified Text", 'entity_id' => 1 ,'app_id' => $appUuid, 'policy_document' =>["file" => 'doc1.pdf'], "coi_attachment" => ["file" => "attach1.png"]);
        $data = array('field1' => 'Updated Text', 'field2' => "Modified Text", 'entity_id' => 1 ,'app_id' => $appUuid, 'policy_document' =>["file" => 'doc1.pdf'], "coi_attachment" => [["file" => "SampleAttachment.txt", "extension" => "txt", "path" => __DIR__."/Dataset"]]);
        $result = $this->fileService->updateFile($data, $fileId);
        $sqlQuery1 = 'SELECT * FROM ox_file where id = 11';
        $sqlQueryResult = $this->runQuery($sqlQuery1);
        $data1 = json_decode($sqlQueryResult[0]['data'],true);
        $this->assertEquals($data['field1'], $data1['field1']);
        $this->assertEquals($data['field2'], $data1['field2']);
        $sqlQueryResult = $this->runQuery($sqlQuery);
        $this->assertEquals($dataset['ox_file_attribute'][0]['field_value'], $sqlQueryResult[0]['field_value']);
        $this->assertEquals(1, $sqlQueryResult[0]['field_id']);
        $this->assertEquals(11, $sqlQueryResult[0]['file_id']);
        $sqlQuery3 = "SELECT * from ox_indexed_file_attribute where file_id = 11";
        $sqlQuery3Result = $this->runQuery($sqlQuery3);
        $this->assertEquals($data['field1'], $sqlQuery3Result[0]['field_value_text']);
        $this->assertEquals(1, $sqlQuery3Result[0]['field_id']);
        $sqlQuery3Result = $this->runQuery($sqlQuery0);
        $this->assertEquals(count($sqlQuery0Result), count($sqlQuery3Result));
        $fields = ["policy_document", "coi_attachment"];
        foreach ($sqlQuery3Result as $key => $value) {
            $this->assertEquals($sqlQuery0Result[$key]['id'], $sqlQuery3Result[$key]['id']);
            $this->assertEquals($sqlQuery0Result[$key]['field_id'], $sqlQuery3Result[$key]['field_id']);
            $this->assertEquals($data[$fields[$key]], $sqlQuery3Result[$key]['field_value']);
            $this->assertEquals($sqlQuery0Result[$key]['sequence'], $sqlQuery3Result[$key]['sequence']);
        }
        
        $this->fileService->updateFileAttributes(11);
        $sqlQueryResult = $this->runQuery($sqlQuery);
        $this->assertEquals($data['field1'], $sqlQueryResult[0]['field_value']);
        $this->assertEquals(1, $sqlQueryResult[0]['field_id']);
        $this->assertEquals(11, $sqlQueryResult[0]['file_id']);
        $this->assertEquals($data['field2'], $sqlQueryResult[1]['field_value']);
        $this->assertEquals(2, $sqlQueryResult[1]['field_id']);
        $this->assertEquals(11, $sqlQueryResult[1]['file_id']);
        $this->assertEquals(json_decode($data['policy_document'], true), json_decode($sqlQueryResult[3]['field_value'], true));
        $this->assertEquals(5, $sqlQueryResult[3]['field_id']);
        $this->assertEquals(11, $sqlQueryResult[3]['file_id']);
        $this->assertEquals(json_decode($data['coi_attachment'], true), json_decode($sqlQueryResult[4]['field_value'], true));
        $this->assertEquals(6, $sqlQueryResult[4]['field_id']);
        $this->assertEquals(11, $sqlQueryResult[4]['file_id']);
    }

    public function testCleanData() {
        $dataset = $this->dataset;
        $fileId = $dataset['ox_file'][0]['uuid'];
        $formId = $dataset['ox_form'][0]['uuid'];
        $appUuid = $dataset['ox_app'][0]['uuid'];
        $data = array('field1' => 1, 'field2' => 2, 'entity_id' => 1 ,'form_id' => $formId,'workflowInstanceId' => 'something','app_id' => $appUuid);
        $result = $this->fileService->updateFile($data, $fileId);
        $sqlQuery = 'SELECT * FROM ox_file where id = 11';
        $sqlQueryResult = $this->runQuery($sqlQuery);
        $data = json_decode($sqlQueryResult[0]['data'],true);
        $this->assertEquals(1, $data['field1']);
        $this->assertEquals(2, $data['field2']);
        $this->assertArrayNotHasKey('form_id', $data);
        $this->assertArrayNotHasKey('workflowInstanceId', $data);
    }

    public function testUpdateIndexedFieldonFiles(){
        $dataset = $this->dataset;
        $appId = $dataset['ox_app'][0]['uuid'];
        $params = array("entityName" => 'entity1');
        $today = date('Y-m-d');
        $filterParams['filter'][0]['filter']['filters'][] = array('field'=>'expiry_date','operator'=>'lte','value'=>$today);
        $filterParams['filter'][0]['filter']['filters'][] = array('field'=>'policy_period','operator'=>'eq','value'=> '1year');

        $this->fileService->updateFieldValueOnFiles($appId,$params,'policy_period','1year','2year',$filterParams);

        $sqlQuery = "SELECT data FROM ox_file where id = 11";
        $sqlQueryResult = $this->runQuery($sqlQuery);
        $data = json_decode($sqlQueryResult[0]['data'],true);
      
        $sqlQuery = "SELECT field_value,field_value_text from ox_file_attribute where field_id = 8 and file_id = 11";
        $sqlFieldQueryResult = $this->runQuery($sqlQuery);
     
        $sqlQuery = "SELECT field_value_text from ox_indexed_file_attribute where field_id = 8 and file_id = 11";
        $sqlIndexedQueryResult = $this->runQuery($sqlQuery);
     
        $this->assertEquals($data['policy_period'],'2year');
        $this->assertEquals($sqlFieldQueryResult[0]['field_value'],'2year');
        $this->assertEquals($sqlFieldQueryResult[0]['field_value_text'],'2year');
        $this->assertEquals($sqlIndexedQueryResult[0]['field_value_text'],'2year');
    }

    public function testUpdateFieldonFiles(){
        $dataset = $this->dataset;
        $appId = $dataset['ox_app'][0]['uuid'];
        $params = array("entityName" => 'entity1');
        $today = date('Y-m-d');
        $filterParams['filter'][0]['filter']['filters'][] = array('field'=>'expiry_date','operator'=>'lte','value'=>$today);
        $filterParams['filter'][0]['filter']['filters'][] = array('field'=>'policy_period','operator'=>'eq','value'=> '1year');

        $this->fileService->updateFieldValueOnFiles($appId,$params,'coverage','100000','200000',$filterParams);

        $sqlQuery = "SELECT data FROM ox_file where id = 11";
        $sqlQueryResult = $this->runQuery($sqlQuery);
        $data = json_decode($sqlQueryResult[0]['data'],true);
      
        $sqlQuery = "SELECT * from ox_file_attribute where field_id = 9 and file_id = 11";
        $sqlFieldQueryResult = $this->runQuery($sqlQuery);
     
        $sqlQuery = "SELECT * from ox_indexed_file_attribute where field_id = 9 and file_id = 11";
        $sqlIndexedQueryResult = $this->runQuery($sqlQuery);
     
        $this->assertEquals($data['coverage'],'200000');
        $this->assertEquals($sqlFieldQueryResult[0]['field_value'],'200000');
        $this->assertEquals($sqlFieldQueryResult[0]['field_value_numeric'],'200000');
        $this->assertEquals($sqlIndexedQueryResult,array());
    }

    public function testRecursiveLogicOnCreate() {
        $dataset = $this->dataset;
        $appUuid = $dataset['ox_app'][0]['uuid'];
        $entityId = $dataset['ox_app_entity'][3]['id'];
        $sqlQuery = 'SELECT count(id) as count FROM ox_file';
        $queryResult = $this->runQuery($sqlQuery);
        $initialCount = $queryResult[0]['count'];
        $this->assertEquals(10,$initialCount);
        $data = array('datagrid' => array(0 => array('firstname' => 'Sagar','lastname' => 'lastname','padi' =>1700, 'id_document' => array(array("name" => "SampleAttachment.txt", "extension" => "txt", "uuid" => "a9cd8b0c-3218-4fd4-b323-e3b6ce8c7d25", "path" => __DIR__."/Dataset/SampleAttachment.txt" ))), 1 => array('firstname' => 'mark','lastname' => 'hamil', 'padi' => 322,'id_document' => array(array("file" => "SampleAttachment.txt", "path" => __DIR__."/Dataset" )))), 'entity_id' => $entityId, 'app_id' => $appUuid);
        $result = $this->fileService->createFile($data);
        $this->assertEquals(1,$result);
        $sqlQuery2 = 'SELECT entity_id, data FROM ox_file where id = '.$data['id'];
        $sqlQuery3 = 'SELECT * from ox_file_attribute where file_id = '.$data['id'];
        $newQueryResult = $this->runQuery($sqlQuery." where id = ".$data['id']);
        $sqlQuery2Result = $this->runQuery($sqlQuery2);
        $sqlQuery3Result = $this->runQuery($sqlQuery3);
        $sqlQuery4 = 'SELECT * from ox_file_document where file_id = '.$data['id'];
        $sqlQuery4Result = $this->runQuery($sqlQuery4);
        $data1 = json_decode($sqlQuery2Result[0]['data'], true);
        $this->assertEquals(1,$newQueryResult[0]['count']);
        $this->assertEquals($entityId, $sqlQuery2Result[0]['entity_id']);
        $this->assertEquals(1, count($data1));
        $this->assertEquals($data['datagrid'][0]['id_document'][0]['name'], $data1['datagrid'][0]['id_document'][0]['originalName']);
        $this->assertEquals("SampleAttachment-".$data['datagrid'][0]['id_document'][0]['uuid'].".txt", $data1['datagrid'][0]['id_document'][0]['name']);
        $this->assertEquals(true, file_exists($data1['datagrid'][0]['id_document'][0]['path']));
        $this->assertEquals($data1['datagrid'][0]['id_document'][0]['path'], FileUtils::truepath($this->applicationConfig['APP_DOCUMENT_FOLDER'].$data1['datagrid'][0]['id_document'][0]['file']));
        $datagrid1 = $data1['datagrid'];
        $datagrid = $data['datagrid'];
        unset($datagrid1[0]['id_document']);
        unset($datagrid[0]['id_document']);
        $this->assertEquals($datagrid, $datagrid1);
        $this->assertEquals(0,count($sqlQuery3Result)); 
        $this->assertEquals(2,count($sqlQuery4Result));
        $this->assertEquals(14,$sqlQuery4Result[0]['field_id']);
        $this->assertEquals(0,$sqlQuery4Result[0]['sequence']);
        $this->assertEquals($data1['datagrid'][0]['id_document'],json_decode($sqlQuery4Result[0]['field_value'], true));
        $this->assertEquals(14,$sqlQuery4Result[1]['field_id']);
        $this->assertEquals(1,$sqlQuery4Result[1]['sequence']);
        $this->assertEquals($data['datagrid'][1]['id_document'],json_decode($sqlQuery4Result[1]['field_value'], true));

        $sqlQuery4 = "SELECT * from ox_indexed_file_attribute where file_id = ".$data['id'];
        $sqlQueryResult = $this->runQuery($sqlQuery4);
        $this->assertEquals(0,count($sqlQueryResult)); 
        $this->fileService->updateFileAttributes($data['id']);
        $sqlQuery3Result = $this->runQuery($sqlQuery3);
        $this->assertEquals($data1['datagrid'],json_decode($sqlQuery3Result[0]['field_value'], true));
        $this->assertEquals(10,$sqlQuery3Result[0]['field_id']); 
        $this->assertEquals(null,$sqlQuery3Result[0]['sequence']);
        $this->assertEquals(11,$sqlQuery3Result[1]['field_id']); 
        $this->assertEquals($data1['datagrid'][0]['padi'],$sqlQuery3Result[1]['field_value']);
        $this->assertEquals($data1['datagrid'][0]['padi'],$sqlQuery3Result[1]['field_value_text']);
        $this->assertEquals(0,$sqlQuery3Result[1]['sequence']);
        $this->assertEquals(11,$sqlQuery3Result[2]['field_id']); 
        $this->assertEquals($data1['datagrid'][1]['padi'],$sqlQuery3Result[2]['field_value']);
        $this->assertEquals($data1['datagrid'][1]['padi'],$sqlQuery3Result[2]['field_value_text']);
        $this->assertEquals(1,$sqlQuery3Result[2]['sequence']);
        
        $this->assertEquals(12,$sqlQuery3Result[3]['field_id']); 
        $this->assertEquals($data1['datagrid'][0]['firstname'],$sqlQuery3Result[3]['field_value']);
        $this->assertEquals($data1['datagrid'][0]['firstname'],$sqlQuery3Result[3]['field_value_text']);
        $this->assertEquals(0,$sqlQuery3Result[3]['sequence']);
        $this->assertEquals(12,$sqlQuery3Result[4]['field_id']); 
        $this->assertEquals($data1['datagrid'][1]['firstname'],$sqlQuery3Result[4]['field_value']);
        $this->assertEquals($data1['datagrid'][1]['firstname'],$sqlQuery3Result[4]['field_value_text']);
        $this->assertEquals(1,$sqlQuery3Result[4]['sequence']);
        
        $this->assertEquals(13,$sqlQuery3Result[5]['field_id']); 
        $this->assertEquals($data1['datagrid'][0]['lastname'],$sqlQuery3Result[5]['field_value']);
        $this->assertEquals($data1['datagrid'][0]['lastname'],$sqlQuery3Result[5]['field_value_text']);
        $this->assertEquals(0,$sqlQuery3Result[5]['sequence']);
        $this->assertEquals(13,$sqlQuery3Result[6]['field_id']); 
        $this->assertEquals($data1['datagrid'][1]['lastname'],$sqlQuery3Result[6]['field_value']);
        $this->assertEquals($data1['datagrid'][1]['lastname'],$sqlQuery3Result[6]['field_value_text']);
        $this->assertEquals(1,$sqlQuery3Result[6]['sequence']);
    }

    public function testRecursiveLogicOnUpdate() {
        $dataset = $this->dataset;
        $fileId = $dataset['ox_file'][9]['uuid'];
        $sqlQuery = 'SELECT fa.*, f.name FROM ox_file_attribute fa inner join ox_field f on f.id = fa.field_id 
                        where file_id = '.$dataset['ox_file'][9]['id'];
        $queryResult = $this->runQuery($sqlQuery);
        $sqlQuery2 = 'SELECT fa.*, f.name FROM ox_file_document fa inner join ox_field f on f.id = fa.field_id 
                        where file_id = '.$dataset['ox_file'][9]['id'];
        $queryResult1 = $this->runQuery($sqlQuery2);
        $data = array('datagrid' => array(0 => array('firstname' => 'manduk','lastname' => 'lastname','padi' =>1700, 'id_document' => array(array("file" => "SampleAttachment.txt", "path" => __DIR__."/Dataset" ))), 1 => array('firstname' => 'marmade','lastname' => 'hamil', 'padi' => 322, 'id_document' => array(array("file" => "SampleAttachment1.txt", "path" => __DIR__."/Dataset")))));
        $result = $this->fileService->updateFile($data,$fileId);
        $dataSqlQuery = "SELECT data FROM ox_file where uuid ='".$fileId."'";
        $queryResult2 = $this->runQuery($dataSqlQuery);
        $data['datagrid'] = json_decode($data['datagrid'], true);
        $this->assertEquals($data,json_decode($queryResult2[0]['data'], true));
        $newQueryResult = $this->runQuery($sqlQuery);
        $this->assertEquals($queryResult, $newQueryResult);
        $queryResult3 = $this->runQuery($sqlQuery2);
        foreach ($queryResult3 as $key => $value) {
            $fieldData = $data['datagrid'][$key]['id_document'];
            $fieldData = is_array($fieldData) ? $fieldData : json_decode($fieldData, true);
            $this->assertEquals($fieldData,json_decode($queryResult3[$key]['field_value'], true));
            
            $this->assertEquals($queryResult1[$key]['id'],$queryResult3[$key]['id']);
            $this->assertEquals($queryResult1[$key]['file_id'],$queryResult3[$key]['file_id']);
            $this->assertEquals($queryResult1[$key]['field_id'],$queryResult3[$key]['field_id']);
            $this->assertEquals($queryResult1[$key]['created_by'],$queryResult3[$key]['created_by']);
            $this->assertEquals($queryResult1[$key]['date_created'],$queryResult3[$key]['date_created']);
            $this->assertEquals(1,$queryResult3[$key]['modified_by']);
            $this->assertEquals(true,$queryResult3[$key]['date_modified'] != null);
            $this->assertEquals($queryResult1[$key]['sequence'],$queryResult3[$key]['sequence']);
                
        }
        $this->fileService->updateFileAttributes($dataset['ox_file'][9]['id']);
        $sqlQueryResult = $this->runQuery($sqlQuery);
        $this->assertEquals(count($queryResult), count($sqlQueryResult));
        $fields = array('padi', 'firstname', 'lastname', 'id_document');
        
        foreach ($sqlQueryResult as $key => $value) {
            if($key == 0){
                $this->assertEquals($data['datagrid'],json_decode($sqlQueryResult[$key]['field_value'], true));
            }else{
                $fieldData = $data['datagrid'][fmod($key-1, 2)][$fields[intdiv($key-1, 2)]];
                if(!is_array($fieldData)){
                    $this->assertEquals($fieldData,$sqlQueryResult[$key]['field_value_text']);
                }
                $fieldData = is_array($fieldData) ? json_encode($fieldData) : $fieldData;
                $this->assertEquals($fieldData,$sqlQueryResult[$key]['field_value']);
                
            }
            
            $this->assertEquals($queryResult[$key]['id'],$sqlQueryResult[$key]['id']);
            $this->assertEquals($queryResult[$key]['file_id'],$sqlQueryResult[$key]['file_id']);
            $this->assertEquals($queryResult[$key]['field_id'],$sqlQueryResult[$key]['field_id']);
            $this->assertEquals($queryResult[$key]['field_value_type'],$sqlQueryResult[$key]['field_value_type']);
            $this->assertEquals($queryResult[$key]['created_by'],$sqlQueryResult[$key]['created_by']);
            $this->assertEquals($queryResult[$key]['date_created'],$sqlQueryResult[$key]['date_created']);
            $this->assertEquals(1,$sqlQueryResult[$key]['modified_by']);
            $this->assertEquals(true,$sqlQueryResult[$key]['date_modified'] != null);
            $this->assertEquals($queryResult[$key]['sequence'],$sqlQueryResult[$key]['sequence']);
                
        }
        
    }

    public function testUpdateFileAttributes(){
        $dataset = $this->dataset;
        $appUuid = $dataset['ox_app'][0]['uuid'];
        $fileId = $dataset['ox_file'][0]['id'];
        $sqlQuery  = "delete from ox_indexed_file_attribute where file_id = $fileId and field_id = 8";
        $this->executeUpdate($sqlQuery);
        $sqlQuery  = "delete from ox_file_document where file_id = $fileId and field_id = 6";
        $this->executeUpdate($sqlQuery);
        $data = array('field1' => 'Updated Text', 'field2' => "Modified Text", 'entity_id' => 1 ,'app_id' => $appUuid, 'policy_document' =>["file" => 'doc1.pdf'], "coi_attachment" => [["file" => "SampleAttachment.txt", "extension" => "txt", "path" => __DIR__."/Dataset"]], "policy_period" => "2 years", "expiry_date" => '2021-10-01 00:00:00', 'coverage' => 2000000);
        $sqlQuery = "update ox_file set data = '".json_encode($data)."' where id = $fileId";
        $this->executeUpdate($sqlQuery);
        
        $sqlQuery = 'SELECT fa.*, f.name, f.index FROM ox_indexed_file_attribute fa inner join ox_field f 
                        on f.id = fa.field_id where file_id = '.$fileId;
        $sqlQuery1 = 'SELECT fa.*, f.name, f.type FROM ox_file_document fa inner join ox_field f 
                        on f.id = fa.field_id where file_id = '.$fileId;
        $sqlQuery2 = 'SELECT fa.*, f.name FROM ox_file_attribute fa inner join ox_field f on f.id = fa.field_id 
                        where file_id = '.$fileId;
        $queryResult = $this->runQuery($sqlQuery2);
        $this->fileService->updateFileAttributes($fileId);
        $sqlQueryResult = $this->runQuery($sqlQuery);
        $sqlQuery1Result = $this->runQuery($sqlQuery1);
        $this->assertEquals(3, count($sqlQueryResult));
        $this->assertEquals($data['field1'], $sqlQueryResult[0]['field_value_text']);
        $this->assertEquals(1, $sqlQueryResult[0]['field_id']);
        $this->assertEquals(11, $sqlQueryResult[0]['file_id']);
        $this->assertEquals($data['expiry_date'], $sqlQueryResult[1]['field_value_date']);
        $this->assertEquals(3, $sqlQueryResult[1]['field_id']);
        $this->assertEquals(11, $sqlQueryResult[1]['file_id']);
        $this->assertEquals($data['policy_period'], $sqlQueryResult[2]['field_value_text']);
        $this->assertEquals(8, $sqlQueryResult[2]['field_id']);
        $this->assertEquals(11, $sqlQueryResult[2]['file_id']);
        $this->assertEquals(2, count($sqlQuery1Result));
        $this->assertEquals($data['policy_document'], json_decode($sqlQuery1Result[0]['field_value'], true));
        $this->assertEquals(5, $sqlQuery1Result[0]['field_id']);
        $this->assertEquals(11, $sqlQuery1Result[0]['file_id']);
        $this->assertEquals($data['coi_attachment'], json_decode($sqlQuery1Result[1]['field_value'], true));
        $this->assertEquals(6, $sqlQuery1Result[1]['field_id']);
        $this->assertEquals(11, $sqlQuery1Result[1]['file_id']);
        $sqlQueryResult = $this->runQuery($sqlQuery2);

        foreach ($sqlQueryResult as $key => $value) {
            $fieldValue = $sqlQueryResult[$key]['field_value'];
            if($sqlQueryResult[$key]['field_value_type'] == 'OTHER'){
                $fieldValue = json_decode($fieldValue, true);
            }
            $this->assertEquals($data[$sqlQueryResult[$key]['name']], $fieldValue);
            switch ($sqlQueryResult[$key]['field_value_type']) {
                case 'TEXT':
                    $this->assertEquals($sqlQueryResult[$key]['field_value_text'], $fieldValue);
                    break;
                case 'NUMERIC':
                    $this->assertEquals($sqlQueryResult[$key]['field_value_numeric'], $fieldValue);
                    break;
                case 'BOOLEAN':
                    $this->assertEquals($sqlQueryResult[$key]['field_value_boolean'], $fieldValue);
                    break;
                case 'DATE':
                    $this->assertEquals($sqlQueryResult[$key]['field_value_date'], $fieldValue);
                    break;
            }
            $this->assertEquals($queryResult[$key]['id'],$sqlQueryResult[$key]['id']);
            $this->assertEquals($queryResult[$key]['file_id'],$sqlQueryResult[$key]['file_id']);
            $this->assertEquals($queryResult[$key]['field_id'],$sqlQueryResult[$key]['field_id']);
            $this->assertEquals($queryResult[$key]['field_value_type'],$sqlQueryResult[$key]['field_value_type']);
            $this->assertEquals($queryResult[$key]['created_by'],$sqlQueryResult[$key]['created_by']);
            $this->assertEquals($queryResult[$key]['date_created'],$sqlQueryResult[$key]['date_created']);
            $this->assertEquals(1,$sqlQueryResult[$key]['modified_by']);
            $this->assertEquals(true,$sqlQueryResult[$key]['date_modified']!=null);
            $this->assertEquals($queryResult[$key]['sequence'],$sqlQueryResult[$key]['sequence']);
                
        }
    }

    public function testUpdateFileAttributesWithDatagridDocuments(){
        $dataset = $this->dataset;
        $appUuid = $dataset['ox_app'][0]['uuid'];
        $fileId = $dataset['ox_file'][9]['id'];
        $sqlQuery  = "delete from ox_file_document where file_id = $fileId and field_id = 14 and sequence = 1";
        $this->executeUpdate($sqlQuery);
        $data = array('datagrid' => array(0 => array('firstname' => 'manduk','lastname' => 'lastname','padi' =>1700, 'id_document' => array(array("file" => "SampleAttachment.txt", "path" => __DIR__."/Dataset"))), 1 => array('firstname' => 'marmade','lastname' => 'hamil', 'padi' => 322, 'id_document' => array(array("file" => "SampleAttachment1.txt", "path" => __DIR__."/Dataset")))));
        $sqlQuery = "update ox_file set data = '".json_encode($data)."' where id = $fileId";
        $this->executeUpdate($sqlQuery);
        
        $sqlQuery = 'SELECT fa.*, f.name, f.index FROM ox_indexed_file_attribute fa inner join ox_field f 
                        on f.id = fa.field_id where file_id = '.$fileId;
        $sqlQuery1 = 'SELECT fa.*, f.name, f.type FROM ox_file_document fa inner join ox_field f 
                        on f.id = fa.field_id where file_id = '.$fileId;
        $queryResult = $this->runQuery($sqlQuery1);
        $sqlQuery2 = 'SELECT fa.*, f.name FROM ox_file_attribute fa inner join ox_field f on f.id = fa.field_id 
                        where file_id = '.$fileId;
        $queryResult1 = $this->runQuery($sqlQuery2);
        $this->fileService->updateFileAttributes($fileId);
        $sqlQueryResult = $this->runQuery($sqlQuery);
        $sqlQuery1Result = $this->runQuery($sqlQuery1);
        $this->assertEquals(0, count($sqlQueryResult));
        $this->assertEquals(2, count($sqlQuery1Result));
        $this->assertEquals($data['datagrid'][0]['id_document'], json_decode($sqlQuery1Result[0]['field_value'], true));
        $this->assertEquals(14, $sqlQuery1Result[0]['field_id']);
        $this->assertEquals(0, $sqlQuery1Result[0]['sequence']);
        $this->assertEquals($queryResult[0]['id'], $sqlQuery1Result[0]['id']);
        $this->assertEquals($fileId, $sqlQuery1Result[0]['file_id']);
        $this->assertEquals($data['datagrid'][1]['id_document'], json_decode($sqlQuery1Result[1]['field_value'], true));
        $this->assertEquals(14, $sqlQuery1Result[1]['field_id']);
        $this->assertEquals($fileId, $sqlQuery1Result[1]['file_id']);
        $this->assertEquals(1, $sqlQuery1Result[1]['sequence']);
        $sqlQueryResult = $this->runQuery($sqlQuery2);
        foreach ($sqlQueryResult as $key => $value) {
            $fieldValue = $sqlQueryResult[$key]['field_value'];
            if($sqlQueryResult[$key]['field_value_type'] == 'OTHER'){
                $fieldValue = json_decode($fieldValue, true);
            }
            if($key == 0){
                $this->assertEquals($data['datagrid'], $fieldValue);
            }else{
                $this->assertEquals($data['datagrid'][fmod($key-1, 2)][$sqlQueryResult[$key]['name']], $fieldValue);
            }
            switch ($sqlQueryResult[$key]['field_value_type']) {
                case 'TEXT':
                    $this->assertEquals($sqlQueryResult[$key]['field_value_text'], $fieldValue);
                    break;
                case 'NUMERIC':
                    $this->assertEquals($sqlQueryResult[$key]['field_value_numeric'], $fieldValue);
                    break;
                case 'BOOLEAN':
                    $this->assertEquals($sqlQueryResult[$key]['field_value_boolean'], $fieldValue);
                    break;
                case 'DATE':
                    $this->assertEquals($sqlQueryResult[$key]['field_value_date'], $fieldValue);
                    break;
            }
            $this->assertEquals($queryResult1[$key]['id'],$sqlQueryResult[$key]['id']);
            $this->assertEquals($queryResult1[$key]['file_id'],$sqlQueryResult[$key]['file_id']);
            $this->assertEquals($queryResult1[$key]['field_id'],$sqlQueryResult[$key]['field_id']);
            $this->assertEquals($queryResult1[$key]['field_value_type'],$sqlQueryResult[$key]['field_value_type']);
            $this->assertEquals($queryResult1[$key]['created_by'],$sqlQueryResult[$key]['created_by']);
            $this->assertEquals($queryResult1[$key]['date_created'],$sqlQueryResult[$key]['date_created']);
            $this->assertEquals(2,$sqlQueryResult[$key]['modified_by']);
            $this->assertEquals(true,$sqlQueryResult[$key]['date_modified']!=null);
            $this->assertEquals($queryResult1[$key]['sequence'],$sqlQueryResult[$key]['sequence']);
                
        }
    }

    public function testReindexFiles(){
        $dataset = $this->dataset;
        $appUuid = $dataset['ox_app'][0]['uuid'];
        $entityId = $dataset['ox_app_entity'][3]['id'];
        $sqlQuery = 'SELECT fa.*, f.name, f.index FROM ox_indexed_file_attribute fa inner join ox_field f 
                        on f.id = fa.field_id 
                        inner join ox_file fi on fi.id = fa.file_id where fi.entity_id = '.$entityId;
        $sqlQuery1 = 'SELECT fa.*, f.name, f.type, fi.entity_id FROM ox_file_document fa inner join ox_field f 
                        on f.id = fa.field_id 
                        inner join ox_file fi on fi.id = fa.file_id where fi.entity_id = '.$entityId;
        $queryResult = $this->runQuery($sqlQuery1);
        $sqlQuery2 = 'SELECT fa.*, f.name FROM ox_file_attribute fa inner join ox_field f on f.id = fa.field_id 
                        inner join ox_file fi on fi.id = fa.file_id where fi.entity_id = '.$entityId;
        $queryResult1 = $this->runQuery($sqlQuery2);

        $query  = "delete fa.* from ox_file_attribute fa inner join ox_file f on f.id = fa.file_id 
                        where f.entity_id = $entityId";
        $this->executeUpdate($query);
        $query  = "delete fd.* from ox_file_document fd inner join ox_file f on f.id = fd.file_id 
                        where f.entity_id = $entityId";
        $this->executeUpdate($query);
        
        $data = array('datagrid' => array(0 => array('firstname' => 'manduk','lastname' => 'lastname','padi' =>1700, 'id_document' => array(array("file" => "SampleAttachment.txt", "path" => __DIR__."/Dataset"))), 1 => array('firstname' => 'marmade','lastname' => 'hamil', 'padi' => 322, 'id_document' => array(array("file" => "SampleAttachment1.txt", "path" => __DIR__."/Dataset")))));
        $query = "update ox_file set data = '".json_encode($data)."' where entity_id = $entityId";
        $this->executeUpdate($query);
        $this->fileService->reindexFile(['entity_id' => $entityId]);

        $sqlQueryResult = $this->runQuery($sqlQuery);
        $sqlQuery1Result = $this->runQuery($sqlQuery1);
        $this->assertEquals(0, count($sqlQueryResult));
        $this->assertEquals(2, count($sqlQuery1Result));
        $this->assertEquals($data['datagrid'][0]['id_document'], json_decode($sqlQuery1Result[0]['field_value'], true));
        $this->assertEquals(14, $sqlQuery1Result[0]['field_id']);
        $this->assertEquals(0, $sqlQuery1Result[0]['sequence']);
        $this->assertEquals($entityId, $sqlQuery1Result[0]['entity_id']);
        $this->assertEquals($data['datagrid'][1]['id_document'], json_decode($sqlQuery1Result[1]['field_value'], true));
        $this->assertEquals(14, $sqlQuery1Result[1]['field_id']);
        $this->assertEquals($entityId, $sqlQuery1Result[1]['entity_id']);
        $this->assertEquals(1, $sqlQuery1Result[1]['sequence']);
        $sqlQueryResult = $this->runQuery($sqlQuery2);
        //print_r($sqlQueryResult);
        foreach ($sqlQueryResult as $key => $value) {
            $fieldValue = $sqlQueryResult[$key]['field_value'];
            if($sqlQueryResult[$key]['field_value_type'] == 'OTHER'){
                $fieldValue = json_decode($fieldValue, true);
            }
            if($key == 0){
                $this->assertEquals($data['datagrid'], $fieldValue);
            }else{
                $this->assertEquals($data['datagrid'][fmod($key-1, 2)][$sqlQueryResult[$key]['name']], $fieldValue);
            }
            switch ($sqlQueryResult[$key]['field_value_type']) {
                case 'TEXT':
                    $this->assertEquals($sqlQueryResult[$key]['field_value_text'], $fieldValue);
                    break;
                case 'NUMERIC':
                    $this->assertEquals($sqlQueryResult[$key]['field_value_numeric'], $fieldValue);
                    break;
                case 'BOOLEAN':
                    $this->assertEquals($sqlQueryResult[$key]['field_value_boolean'], $fieldValue);
                    break;
                case 'DATE':
                    $this->assertEquals($sqlQueryResult[$key]['field_value_date'], $fieldValue);
                    break;
            }
            $this->assertEquals($queryResult1[$key]['file_id'],$sqlQueryResult[$key]['file_id']);
            $this->assertEquals($queryResult1[$key]['field_id'],$sqlQueryResult[$key]['field_id']);
            $this->assertEquals($queryResult1[$key]['field_value_type'],$sqlQueryResult[$key]['field_value_type']);
            $this->assertEquals(2,$sqlQueryResult[$key]['created_by']);
            $this->assertEquals(true,$sqlQueryResult[$key]['date_created']!=null);
            $this->assertEquals(2,$sqlQueryResult[$key]['modified_by']);
            $this->assertEquals(true,$sqlQueryResult[$key]['date_modified']!=null);
            $this->assertEquals($queryResult1[$key]['sequence'],$sqlQueryResult[$key]['sequence']);
                
        }
    }

    public function testUpdateFileAttributeWithInvalidFileId(){
        $this->expectException(EntityNotFoundException::class);
        $this->fileService->updateFileAttributes("InvalidFileId");
    }

}
