<?php
namespace Import;

use Oxzion\Import\ImportText;
use Oxzion\Test\MainControllerTest;
use Zend\Db\Adapter\Adapter;

class ImportTest extends MainControllerTest
{
    private $database;
    private $adapter;

    public function setUp(): void
    {
        $this->loadConfig();
        // parent::setUp();
        $config = $this->getApplicationConfig();
        $config = $config['db'];
        $this->adapter = new Adapter($config);
    }

    public function testImport()
    {
        $config = $this->getApplicationConfig();
        $importObj = new ImportText($config, $this->database, $this->adapter);
        $dataSet = array_diff(scandir(dirname(__FILE__) . "/Dataset/"), array(".", ".."));
        $filePath = dirname(__FILE__) . "/Dataset/";
        $columnList = array("member_number", "first_name", "MI", "last_name", "address_1", "address_2", "address_international", "city", "state", "zip", "country_code", "home_phone", "work_phone", "insurance_type", "date_expire", "rating", "email");
        $textImport = $importObj->extractTextFileToArrayImport($dataSet[2], $filePath, $columnList, null);
        // echo $textImport;exit;
        $this->assertEquals(1, $textImport);
    }

    public function testImportWrongFileName()
    {
        $config = $this->getApplicationConfig();
        $importObj = new ImportText($config, $this->database, $this->adapter);
        $dataSet = array_diff(scandir(dirname(__FILE__) . "/Dataset/"), array(".", ".."));
        $filePath = dirname(__FILE__) . "/DatasetWrong/";
        $columnList = Array("member_number", "first_name", "MI", "last_name", "address_1", "address_2", "address_international", "city", "state", "zip", "country_code", "home_phone", "work_phone", "insurance_type", "date_expire", "rating", "email");
        $textImport = $importObj->extractTextFileToArrayImport($dataSet[2], $filePath, $columnList, null);
        $this->assertEquals(3, $textImport);
    }

    public function testImportEmptyFolder()
    {
        $config = $this->getApplicationConfig();
        $importObj = new ImportText($config, $this->database, $this->adapter);
        $dataSet = array_diff(scandir(dirname(__FILE__) . "/Dataset/"), array(".", ".."));
        $filePath = dirname(__FILE__) . "/DatasetEmpty/";
        $columnList = Array("member_number", "first_name", "MI", "last_name", "address_1", "address_2", "address_international", "city", "state", "zip", "country_code", "home_phone", "work_phone", "insurance_type", "date_expire", "rating", "email");
        $textImport = $importObj->extractTextFileToArrayImport($dataSet[2], $filePath, $columnList, null);
        $this->assertEquals(3, $textImport);
    }

}
