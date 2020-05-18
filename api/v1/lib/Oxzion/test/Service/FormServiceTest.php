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
use PHPUnit\DbUnit\DataSet\DefaultDataSet;
use Oxzion\Document\Parser\Spreadsheet\SpreadsheetParserImpl;
use Oxzion\Document\Parser\Spreadsheet\SpreadsheetFilter;
use Oxzion\Document\Parser\Form\FormRowMapper;

class FormServiceTest extends AbstractServiceTest
{
    private $adapter = null;
    protected function setUp(): void
    {
        $this->loadConfig();
        parent::setUp();
        $this->formService = $this->getApplicationServiceLocator()->get(\Oxzion\Service\FormService::class);
        AuthContext::put(AuthConstants::ORG_ID, 1);
        AuthContext::put(AuthConstants::USER_ID, 1);
        $this->adapter = $this->getDbAdapter();
        // $this->adapter->getDriver()->getConnection()->setResource(static::$pdo);
    }

    public function getDataSet()
    {
        $dataset = new YamlDataSet(dirname(__FILE__) . "/Dataset/Form.yml");
        return $dataset;
    }

    private function runQuery($query) {
        $statement = $this->adapter->query($query);
        $result = $statement->execute();
        $resultSet = new ResultSet();
        $result = $resultSet->initialize($result)->toArray();
        return $result;
    }

    public function testCreateForm() {
        
        $this->performCreateTest();

    }
    private function performCreateTest($fieldReference = null){
        $data = ['name' => 'Form creation', 'app_id' => 100, 'entity_id' => 1, 'template' =>file_get_contents(dirname(__FILE__)."/Dataset/Form.json")];
        $appUuid = '0e4f00d4-86e2-11ea-bc55-0242ac130003';
        $result = 0;
        $result = $this->formService->createForm($appUuid,$data, $fieldReference);
        $this->assertEquals(1, $result);
        $sqlQuery = "SELECT * FROM ox_form";
        $sqlQueryResult = $this->runQuery($sqlQuery);
        $this->assertEquals(1,count($sqlQueryResult));
        $sqlQuery = "SELECT name,data_type FROM ox_field";
        $sqlQueryResult = $this->runQuery($sqlQuery);
        $this->assertEquals(38,count($sqlQueryResult));
        $sqlQuery = "SELECT * FROM ox_field where parent_id IN (SELECT id from ox_field where type='datagrid')";
        $sqlQueryResult = $this->runQuery($sqlQuery);
        $this->assertEquals(9,count($sqlQueryResult));
        $sqlQuery = "SELECT * FROM ox_field where parent_id IN (SELECT id from ox_field where type='editgrid')";
        $sqlQueryResult = $this->runQuery($sqlQuery);
        $this->assertEquals(3,count($sqlQueryResult));
        $sqlQuery = "SELECT * FROM ox_field where type='survey'";
        $sqlQueryResult = $this->runQuery($sqlQuery);
        $this->assertEquals(1,count($sqlQueryResult));
        $sqlQuery = "SELECT * FROM ox_field where type='editgrid'";
        $sqlQueryResult = $this->runQuery($sqlQuery);
        $this->assertEquals(1,count($sqlQueryResult));
        $sqlQuery = "SELECT * FROM ox_field where type='datagrid'";
        $sqlQueryResult = $this->runQuery($sqlQuery);
        $this->assertEquals(1,count($sqlQueryResult));
        $sqlQuery = "SELECT distinct type FROM ox_field";
        $sqlQueryResult = $this->runQuery($sqlQuery);
        $this->assertEquals(23,count($sqlQueryResult));
        $sqlQuery = "SELECT distinct data_type FROM ox_field";
        $sqlQueryResult = $this->runQuery($sqlQuery);
        $this->assertEquals(9,count($sqlQueryResult));
        $sqlQuery = "SELECT name,data_type FROM ox_field where required = 1";
        $sqlQueryResult = $this->runQuery($sqlQuery);
        $this->assertEquals(15,count($sqlQueryResult));
        $sqlQuery = "SELECT * FROM ox_field where type = 'select'";
        $sqlQueryResult = $this->runQuery($sqlQuery);
        $this->assertEquals(5,count($sqlQueryResult));
        $sqlQuery = "SELECT * FROM ox_form_field";
        $sqlQueryResult = $this->runQuery($sqlQuery);
        $this->assertEquals(38,count($sqlQueryResult));
    }
    public function testCreateFormWithValidation() {
        $parser = new SpreadsheetParserImpl();
        $parser->init(__DIR__."/Dataset/FieldValidationSample.xlsx");
        $sheetNames = $parser->getSheetNames();
        $rowMapper = new FormRowMapper();
        $filter = new SpreadsheetFilter();
        $filter->setRows(2);
        $fieldReference = $parser->parseDocument(array('rowMapper' => $rowMapper,
                                                       'filter' => $filter));
        
        $fieldReference = $fieldReference[$sheetNames[0]];
        try{
            $this->performCreateTest($fieldReference);
        }catch(ValidationException $e){
            print_r($e->getErrors());
            $this->assertEquals(0,1);       
        }
    }

}
