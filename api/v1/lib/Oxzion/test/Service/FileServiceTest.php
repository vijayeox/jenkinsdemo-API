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
        $this->assertEquals(9, $result['total']);
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
        $this->assertEquals(8, $result['total']);
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
        $this->assertEquals("Completed",$result['data'][2]['status']);
        $this->assertEquals("Completed",$result['data'][3]['status']);
        $this->assertEquals(6,$result['total']);
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
            $this->assertEquals(9,$initialCount);
            $data = array('field1' => 1, 'field2' => 2, 'entity_id' => 1 ,'app_id' => $appUuid, 'form_id' => $formId);
            $result = $this->fileService->createFile($data);
            $this->assertEquals(1,$result);
            $sqlQuery2 = 'SELECT entity_id FROM ox_file order by id DESC LIMIT 1';
            $newQueryResult = $this->runQuery($sqlQuery);
            $sqlQuery2Result = $this->runQuery($sqlQuery2);
            $entityId = $sqlQuery2Result[0]['entity_id'];
            $finalCount = $newQueryResult[0]['count'];
            if(isset($newQueryResult[0]['count'])) {
            $this->assertEquals(10,$finalCount);
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
            $this->assertEquals(9,$initialCount);
            $data = array('field1' => 1, 'field2' => 2, 'entity_id' => 1 ,'app_id' => $appUuid, 'form_id' => $formId );
            $result = $this->fileService->createFile($data);
            $this->assertEquals(1,$result);
            $sqlQuery2 = 'SELECT form_id FROM ox_file order by id DESC LIMIT 1';
            $newQueryResult = $this->runQuery($sqlQuery);
            $sqlQuery2Result = $this->runQuery($sqlQuery2);
            $formId = $sqlQuery2Result[0]['form_id'];
            $finalCount = $newQueryResult[0]['count'];
            if(isset($newQueryResult[0]['count'])) {
                $this->assertEquals(10,$finalCount);
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
            $this->assertEquals(9,$initialCount);
            $data = array('field1' => 1, 'field2' => 2, 'entity_id' => 1 ,'app_id' => $appUuid);
            $result = $this->fileService->createFile($data);
            $this->assertEquals(1,$result);
            $sqlQuery2 = 'SELECT form_id FROM ox_file order by id DESC LIMIT 1';
            $newQueryResult = $this->runQuery($sqlQuery);
            $sqlQuery2Result = $this->runQuery($sqlQuery2);
            $formId = $sqlQuery2Result[0]['form_id'];
            $finalCount = $newQueryResult[0]['count'];
            if(isset($newQueryResult[0]['count'])) {
                $this->assertEquals(10,$finalCount);
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
            $this->assertEquals(9,$initialCount);
            $data = array('field1' => 1, 'field2' => 2, 'entity_id' => 1 ,'app_id' => $appUuid, 'uuid' => '7369c4e9-90bf-41d7-b774-605469294aae');
            $result = $this->fileService->createFile($data);
            $this->assertEquals(1,$result);
            $sqlQuery2 = 'SELECT uuid FROM ox_file order by id DESC LIMIT 1';
            $newQueryResult = $this->runQuery($sqlQuery);
            $sqlQuery2Result = $this->runQuery($sqlQuery2);
            $uuid = $sqlQuery2Result[0]['uuid'];
            $finalCount = $newQueryResult[0]['count'];
            if(isset($newQueryResult[0]['count'])) {
                $this->assertEquals(10,$finalCount);
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
            $this->assertEquals(9,$initialCount);
            $data = array('field1' => 1, 'field2' => 2, 'entity_id' => 1 ,'app_id' => $appUuid, 'uuid' => 'd13d0c68-98c9-11e9-adc5-308d99c9145b');
            $result = $this->fileService->createFile($data);
            $this->assertEquals(1,$result);
            $sqlQuery2 = 'SELECT uuid FROM ox_file order by id DESC LIMIT 1';
            $newQueryResult = $this->runQuery($sqlQuery);
            $sqlQuery2Result = $this->runQuery($sqlQuery2);
            $uuid = $sqlQuery2Result[0]['uuid'];
            $finalCount = $newQueryResult[0]['count'];
            if(isset($newQueryResult[0]['count'])) {
                $this->assertEquals(10,$finalCount);
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

    public function testFileCreateWithWithoutUuid() {
        $dataset = $this->dataset;
        $appUuid = $dataset['ox_app'][0]['uuid'];
        $formId = $dataset['ox_form'][0]['uuid'];
        $entityId = $dataset['ox_app_entity'][0]['id'];
        $sqlQuery = 'SELECT count(id) as count FROM ox_file';
        $queryResult = $this->runQuery($sqlQuery);
        if(isset($queryResult[0]['count'])) {
            $initialCount = $queryResult[0]['count'];
            $this->assertEquals(9,$initialCount);
            $data = array('field1' => 1, 'field2' => 2, 'entity_id' => 1 ,'app_id' => $appUuid);
            $result = $this->fileService->createFile($data);
            $this->assertEquals(1,$result);
            $sqlQuery2 = 'SELECT uuid FROM ox_file order by id DESC LIMIT 1';
            $newQueryResult = $this->runQuery($sqlQuery);
            $sqlQuery2Result = $this->runQuery($sqlQuery2);
            $uuid = $sqlQuery2Result[0]['uuid'];
            $finalCount = $newQueryResult[0]['count'];
            if(isset($newQueryResult[0]['count'])) {
                $this->assertEquals(10,$finalCount);
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
            $this->assertEquals(9,$initialCount);
            $data = array('field1' => 1, 'field2' => 2, 'app_id' => $appUuid, 'entity_name' => 'entity1');
            $result = $this->fileService->createFile($data);
            $this->assertEquals(1,$result);
            $sqlQuery2 = 'SELECT entity_id FROM ox_file order by id DESC LIMIT 1';
            $newQueryResult = $this->runQuery($sqlQuery);
            $sqlQuery2Result = $this->runQuery($sqlQuery2);
            $entityId = $sqlQuery2Result[0]['entity_id'];
            $finalCount = $newQueryResult[0]['count'];
            if(isset($newQueryResult[0]['count'])) {
                $this->assertEquals(10,$finalCount);
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
            $this->assertEquals(9,$initialCount);
            $data = array('field1' => 1, 'field2' => 2, 'entity_id' => 1 ,'app_id' => $appUuid, 'entity_name' => 'entity1');
            $result = $this->fileService->createFile($data);
            $this->assertEquals(1,$result);
            $sqlQuery2 = 'SELECT entity_id FROM ox_file order by id DESC LIMIT 1';
            $newQueryResult = $this->runQuery($sqlQuery);
            $sqlQuery2Result = $this->runQuery($sqlQuery2);
            $entityId = $sqlQuery2Result[0]['entity_id'];
            $finalCount = $newQueryResult[0]['count'];
            if(isset($newQueryResult[0]['count'])) {
                $this->assertEquals(10,$finalCount);
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
        $queryResult = $this->runQuery($sqlQuery);
        if(isset($queryResult[0]['count'])) {
            $initialCount = $queryResult[0]['count'];
            $this->assertEquals(9,$initialCount);
            $data = array('field1' => 1, 'field2' => 2, 'field3' => 3, 'field4' => 'something' ,'entity_id' => 1 ,'app_id' => $appUuid, 'entity_name' => 'entity1');
            $result = $this->fileService->createFile($data);
            $this->assertEquals(1,$result);
            $sqlQuery2 = 'SELECT entity_id FROM ox_file order by id DESC LIMIT 1';
            $sqlQuery3 = 'SELECT field_id,field_value from ox_file_attribute where field_id in (1,2,3,4) order by id DESC LIMIT 4';
            $newQueryResult = $this->runQuery($sqlQuery);
            $sqlQuery2Result = $this->runQuery($sqlQuery2);
            $sqlQuery3Result = $this->runQuery($sqlQuery3);
            $entityId = $sqlQuery2Result[0]['entity_id'];
            $finalCount = $newQueryResult[0]['count'];
            $fieldValues = array_column($sqlQuery3Result, 'field_value');
            $this->assertEquals(2,$fieldValues[0]); //Fields that exist
            $this->assertEquals(1,$fieldValues[1]);
            if(isset($newQueryResult[0]['count'])) {
                $this->assertEquals(10,$finalCount);
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

    public function testFileCreateWithCleanData() {
        $dataset = $this->dataset;
        $appUuid = $dataset['ox_app'][0]['uuid'];
        $formId = $dataset['ox_form'][0]['uuid'];
        $entityId = $dataset['ox_app_entity'][0]['id'];
        $sqlQuery = 'SELECT count(id) as count FROM ox_file';
        $queryResult = $this->runQuery($sqlQuery);
        if(isset($queryResult[0]['count'])) {
            $initialCount = $queryResult[0]['count'];
            $this->assertEquals(9,$initialCount);
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
                $this->assertEquals(10,$finalCount);
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
            $this->assertEquals(9,$initialCount);
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
                print_r($e->getMessage());exit;
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
            $this->assertEquals(9,$initialCount);
            $data = array('field1' => 1, 'field2' => 2, 'entity_id' => 1 ,'app_id' => $appUuid, 'form_id' => $formId);
            $result = $this->fileService->updateFile($data, $fileId);
            $this->assertEquals(1,$result);
            $sqlQuery2 = 'SELECT form_id FROM ox_file order by id DESC LIMIT 1';
            $newQueryResult = $this->runQuery($sqlQuery);
            $sqlQuery2Result = $this->runQuery($sqlQuery2);
            $finalCount = $newQueryResult[0]['count'];
            if(isset($newQueryResult[0]['count'])) {
                $this->assertEquals(10,$finalCount);
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
        $sqlQuery = 'SELECT * FROM ox_file_attribute where file_id = 11';
        $sqlQueryResult = $this->runQuery($sqlQuery);
        $this->assertEquals(32552, $sqlQueryResult[0]['field_value']);
        $this->assertEquals(1, $sqlQueryResult[0]['field_id']);
        $this->assertEquals(11, $sqlQueryResult[0]['file_id']);
        $fileId = $dataset['ox_file'][0]['uuid'];
        $appUuid = $dataset['ox_app'][0]['uuid'];
        $data = array('field1' => 1, 'field2' => 2, 'entity_id' => 1 ,'app_id' => $appUuid);
        $result = $this->fileService->updateFile($data, $fileId);
        $sqlQuery = 'SELECT * FROM ox_file where id = 11';
        $sqlQueryResult = $this->runQuery($sqlQuery);
        $data = json_decode($sqlQueryResult[0]['data'],true);
        $this->assertEquals(1, $data['field1']);
        $this->assertEquals(2, $data['field2']);
        $sqlQuery = 'SELECT * FROM ox_file_attribute where file_id = 11';
        $sqlQueryResult = $this->runQuery($sqlQuery);
        $this->assertEquals(1, $sqlQueryResult[0]['field_value']);
        $this->assertEquals(1, $sqlQueryResult[0]['field_id']);
        $this->assertEquals(11, $sqlQueryResult[0]['file_id']);
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
      
        $sqlQuery = "SELECT field_value,field_value_text from ox_file_attribute where field_id = 9 and file_id = 11";
        $sqlFieldQueryResult = $this->runQuery($sqlQuery);
     
        $sqlQuery = "SELECT field_value_text from ox_indexed_file_attribute where field_id = 9 and file_id = 11";
        $sqlIndexedQueryResult = $this->runQuery($sqlQuery);
     
        $this->assertEquals($data['coverage'],'200000');
        $this->assertEquals($sqlFieldQueryResult[0]['field_value'],'200000');
        $this->assertEquals($sqlFieldQueryResult[0]['field_value_text'],'200000');
        $this->assertEquals($sqlIndexedQueryResult,array());
    }

}
