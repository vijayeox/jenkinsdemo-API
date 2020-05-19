<?php
namespace Oxzion\Document\Parser\Spreadsheet;

use PHPUnit\Framework\TestCase;
use \Exception;
use Oxzion\Document\Parser\Form\FormRowMapper;

class SpreadsheetParserTest extends TestCase{

    private $file;
    private $parser;
    public function setUp() : void{
        $this->file = __DIR__."/Data/Test.xlsx";
        $this->parser = new SpreadsheetParserImpl();
    }

    public function testInvalidFileExt(){
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Unrecognized file format");
        $file = __DIR__."/Data/invalidextFile.sdf";
        $this->parser->init($file);
    }

    public function testNonExistentFile(){
        $this->expectException(Exception::class);
        $file = __DIR__."/Data/NonExistentFile.xlsx";
        $this->expectExceptionMessage("File $file not found");
        $this->parser->init($file);
    }

    public function testInitXlsFile(){
        $file = __DIR__."/Data/document.xls";
        $this->parser->init($file);
        $this->assertEquals(true, true);
    }

    public function testInitXmlFile(){
        $file = __DIR__."/Data/document.xml";
        $this->parser->init($file);
        $this->assertEquals(true, true);
    }

    public function testInitOdsFile(){
        $file = __DIR__."/Data/document.ods";
        $this->parser->init($file);
        $this->assertEquals(true, true);
    }
    
    public function testInitCsvFile(){
        $file = __DIR__."/Data/document.csv";
        $this->parser->init($file);
        $this->assertEquals(true, true);
    }

    public function testLoadWithInvalidFilterClass(){
        $this->parser->init($this->file);
        $filter = new BaseRowMapper();
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Filter should be of type \Oxzion\Document\Parser\Spreadsheet\SpreadsheetFilter");
        $data = $this->parser->parseDocument(array('filter' => $filter));
    }

    public function testLoadWithInvalidRowMapperClass(){
        $this->parser->init($this->file);
        $rowMapper = new SpreadsheetFilter();
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("RowMapper should be of type \Oxzion\Document\Parser\Spreadsheet\RowMapper");
        $data = $this->parser->parseDocument(array('rowMapper' => $rowMapper));
    }

    public function testSpreadsheetMetadata(){
        $this->parser->init($this->file);
        $sheetNames = $this->parser->getSheetNames();
        $this->assertEquals(3, count($sheetNames));
        $this->assertEquals('Address', $sheetNames[0]);
        $this->assertEquals('Interests', $sheetNames[1]);
        $this->assertEquals('Sheet3', $sheetNames[2]);
        $count = $this->parser->getSheetCount();
        $this->assertEquals(3, $count);
        $worksheetInfo = $this->parser->getWorksheetInfo();
        $this->assertEquals(3, count($worksheetInfo));
        $info = $worksheetInfo[$sheetNames[0]];
        $this->assertEquals(11, $info['totalRows']);
        $this->assertEquals(7, $info['totalColumns']);
        $info = $worksheetInfo[$sheetNames[1]];
        $this->assertEquals(11, $info['totalRows']);
        $this->assertEquals(3, $info['totalColumns']);
        $info = $worksheetInfo[$sheetNames[2]];
        $this->assertEquals(0, $info['totalRows']);
        $this->assertEquals(0, $info['totalColumns']);   
    }
    public function testParseSpreadsheet()
    {
        $this->parser->init($this->file);
        $sheetNames = $this->parser->getSheetNames();
        $data = $this->parser->parseDocument(array('worksheet' => $sheetNames[2]));
        $this->assertEquals(1, count($data));
        $this->assertEquals(1, isset($data[$sheetNames[2]]));
        $this->assertEquals(1, empty($data[$sheetNames[2]][0][0]));
        $data = $this->parser->parseDocument(array('worksheet' => $sheetNames[0]));
        $this->assertEquals(1, count($data));
        $this->assertEquals(1, isset($data[$sheetNames[0]]));
        $this->assertEquals(11, count($data[$sheetNames[0]]));
        $this->assertEquals(8, count($data[$sheetNames[0]][0]));
        $this->assertEquals(8, count($data[$sheetNames[0]][1]));
        $this->assertEquals(8, count($data[$sheetNames[0]][2]));
        $this->assertEquals(8, count($data[$sheetNames[0]][3]));
        $this->assertEquals(8, count($data[$sheetNames[0]][4]));
        $this->assertEquals(8, count($data[$sheetNames[0]][5]));
        $this->assertEquals(8, count($data[$sheetNames[0]][6]));
        $this->assertEquals(8, count($data[$sheetNames[0]][7]));
        $this->assertEquals(8, count($data[$sheetNames[0]][8]));
        $this->assertEquals(8, count($data[$sheetNames[0]][9]));
        $this->assertEquals(8, count($data[$sheetNames[0]][10]));
        $row = Array('','Sl.No.', 'Name', 'Address', 'City', 'State', 'zip', 'Country');
        $this->assertEquals(1, $data[$sheetNames[0]][0] == $row);
        $row = array(null,1,"name1","address1","Bengaluru","Karnataka",560078,"India");
        $this->assertEquals(1, $data[$sheetNames[0]][1] == $row);
        $row = array(null,2,"name2","address2","Bengaluru","Karnataka",560034,"India");
        $this->assertEquals(1, $data[$sheetNames[0]][2] == $row);
        $row = array(null,3,"name3","address3","Bengaluru","Karnataka",560003,"India");
        $this->assertEquals(1, $data[$sheetNames[0]][3] == $row);
        $row = array(null,4,"name4","address4","Bengaluru","Karnataka",560066,"India");
        $this->assertEquals(1, $data[$sheetNames[0]][4] == $row);
        $row = array(null,5,"name5","address5","Bengaluru","Karnataka",560055,"India");
        $this->assertEquals(1, $data[$sheetNames[0]][5] == $row);
        $row = array(null,6,"name6","address6","Delhi","Delhi",433343,"India");
        $this->assertEquals(1, $data[$sheetNames[0]][6] == $row);
        $row = array(null,7,"name7","address7","Delhi","Delhi",433343,"India");
        $this->assertEquals(1, $data[$sheetNames[0]][7] == $row);
        $row = array(null,8,"name8","address8","Delhi","Delhi",433343,"India");
        $this->assertEquals(1, $data[$sheetNames[0]][8] == $row);
        $row = array(null,9,"name9","address9","Delhi","Delhi",433343,"India");
        $this->assertEquals(1, $data[$sheetNames[0]][9] == $row);
        $row = array(null,10,"name10","address10","Delhi","Delhi",433343,"India");
        $this->assertEquals(1, $data[$sheetNames[0]][10] == $row);
        $data = $this->parser->parseDocument(array('worksheet' => $sheetNames[1]));
        $this->assertEquals(1, count($data));
        $this->assertEquals(1, isset($data[$sheetNames[1]]));
        $this->assertEquals(11, count($data[$sheetNames[1]]));
        $this->assertEquals(4, count($data[$sheetNames[1]][0]));
        $this->assertEquals(4, count($data[$sheetNames[1]][1]));
        $this->assertEquals(4, count($data[$sheetNames[1]][2]));
        $this->assertEquals(4, count($data[$sheetNames[1]][3]));
        $this->assertEquals(4, count($data[$sheetNames[1]][4]));
        $this->assertEquals(4, count($data[$sheetNames[1]][5]));
        $this->assertEquals(4, count($data[$sheetNames[1]][6]));
        $this->assertEquals(4, count($data[$sheetNames[1]][7]));
        $this->assertEquals(4, count($data[$sheetNames[1]][8]));
        $this->assertEquals(4, count($data[$sheetNames[1]][9]));
        $this->assertEquals(4, count($data[$sheetNames[1]][10]));
        $row = array(null,"Sl.No.","Name","Interests");
        $this->assertEquals(1, $data[$sheetNames[1]][0] == $row);
        $row = array(null,1,"name1","Walking");
        $this->assertEquals(1, $data[$sheetNames[1]][1] == $row);
        $row = array(null,2,"name2","Running");
        $this->assertEquals(1, $data[$sheetNames[1]][2] == $row);
        $row = array(null,3,"name3","Sleeping");
        $this->assertEquals(1, $data[$sheetNames[1]][3] == $row);
        $row = array(null,4,"name4","Reading");
        $this->assertEquals(1, $data[$sheetNames[1]][4] == $row);
        $row = array(null,5,"name5","Writing");
        $this->assertEquals(1, $data[$sheetNames[1]][5] == $row);
        $row = array(null,6,"name6","Coding");
        $this->assertEquals(1, $data[$sheetNames[1]][6] == $row);
        $row = array(null,7,"name7","Dancing");
        $this->assertEquals(1, $data[$sheetNames[1]][7] == $row);
        $row = array(null,8,"name8","Singing");
        $this->assertEquals(1, $data[$sheetNames[1]][8] == $row);
        $row = array(null,9,"name9","Gardening");
        $this->assertEquals(1, $data[$sheetNames[1]][9] == $row);
        $row = array(null,10,"name10","Painting");
        $this->assertEquals(1, $data[$sheetNames[1]][10] == $row);
        
    }

    public function testParseSpreadsheetWithFilter()
    {
        $this->parser->init($this->file);
        $sheetNames = $this->parser->getSheetNames();
        $filter = new SpreadsheetFilter();
        $filter->setColumns(range('C', 'H'));
        $filter->setRows(1, 5);
        $data = $this->parser->parseDocument(array('filter' => $filter));
        $this->assertEquals(1, count($data));
        $this->assertEquals(1, isset($data[$sheetNames[0]]));
        $this->assertEquals(5, count($data[$sheetNames[0]]));
        $this->assertEquals(8, count($data[$sheetNames[0]][0]));
        $this->assertEquals(8, count($data[$sheetNames[0]][1]));
        $this->assertEquals(8, count($data[$sheetNames[0]][2]));
        $this->assertEquals(8, count($data[$sheetNames[0]][3]));
        $this->assertEquals(8, count($data[$sheetNames[0]][4]));
        $row = Array(null,null, 'Name', 'Address', 'City', 'State', 'zip', 'Country');
        $this->assertEquals(1, $data[$sheetNames[0]][0] == $row);
        $row = array(null, null,"name1","address1","Bengaluru","Karnataka",560078,"India");
        $this->assertEquals(1, $data[$sheetNames[0]][1] == $row);
        $row = array(null,null,"name2","address2","Bengaluru","Karnataka",560034,"India");
        $this->assertEquals(1, $data[$sheetNames[0]][2] == $row);
        $row = array(null,null,"name3","address3","Bengaluru","Karnataka",560003,"India");
        $this->assertEquals(1, $data[$sheetNames[0]][3] == $row);
        $row = array(null,null,"name4","address4","Bengaluru","Karnataka",560066,"India");
        $this->assertEquals(1, $data[$sheetNames[0]][4] == $row);
    }

    public function testParseSpreadsheetWithBaseRowMapper()
    {
        $this->parser->init(__DIR__."/Data/Test2.xlsx");
        $sheetNames = $this->parser->getSheetNames();
        $rowMapper = new BaseRowMapper();
        $data = $this->parser->parseDocument(array('rowMapper' => $rowMapper));
        $this->assertEquals(1, count($data));
        $this->assertEquals(1, isset($data[$sheetNames[0]]));
        $this->assertEquals(12, count($data[$sheetNames[0]]));
        $this->assertEquals(8, count($data[$sheetNames[0]][0]));
        $this->assertEquals(8, count($data[$sheetNames[0]][1]));
        $this->assertEquals(8, count($data[$sheetNames[0]][2]));
        $this->assertEquals(8, count($data[$sheetNames[0]][3]));
        $this->assertEquals(8, count($data[$sheetNames[0]][4]));
        $row = Array('','Sl.No.', 'Name', 'Address', 'City', 'State', 'zip', 'Country');
        $this->assertEquals(1, $data[$sheetNames[0]][0] == $row);
        $row = array(null,1,"name1","address1","Bengaluru","Karnataka",560078,"India");
        $this->assertEquals(1, $data[$sheetNames[0]][1] == $row);
        $row = array(null,2,"name2","address2","Bengaluru","Karnataka",560034,"India");
        $this->assertEquals(1, $data[$sheetNames[0]][2] == $row);
        $row = array(null,3,"name3","address3","Bengaluru","Karnataka",560003,"India");
        $this->assertEquals(1, $data[$sheetNames[0]][3] == $row);
        $row = array(null,4,"name4","address4","Bengaluru","Karnataka",560066,"India");
        $this->assertEquals(1, $data[$sheetNames[0]][4] == $row);
        $row = array(null,5,"name5","address5","Bengaluru","Karnataka",560055,"India");
        $this->assertEquals(1, $data[$sheetNames[0]][5] == $row);
        $row = array(null,6,"name6","address6","Delhi","Delhi",433343,"India");
        $this->assertEquals(1, $data[$sheetNames[0]][6] == $row);
        $row = array(null,7,"name7","address7","Delhi","Delhi",433343,"India");
        $this->assertEquals(1, $data[$sheetNames[0]][7] == $row);
        $row = array(null,8,"name8","address8","Delhi","Delhi",433343,"India");
        $this->assertEquals(1, $data[$sheetNames[0]][8] == $row);
        $row = array(null,9,"name9","address9","Delhi","Delhi",433343,"India");
        $this->assertEquals(1, $data[$sheetNames[0]][9] == $row);
        $row = array(null,10,"name10","address10","Delhi","Delhi",433343,"India");
        $this->assertEquals(1, $data[$sheetNames[0]][10] == $row);
        $row = array(null,null,null,"address11","Delhi","Delhi",433349,"India");
        $this->assertEquals(1, $data[$sheetNames[0]][11] == $row);
    }

    public function testParseSpreadsheetWithFormMapper()
    {
        $this->parser->init(__DIR__."/Data/FieldValidationTemplate.xlsx");
        $sheetNames = $this->parser->getSheetNames();
        $rowMapper = new FormRowMapper();
        $filter = new SpreadsheetFilter();
        $filter->setRows(2);
        $data = $this->parser->parseDocument(array('rowMapper' => $rowMapper,
                                                   'filter' => $filter));
        //print_r($data);
        $this->assertEquals(16, count($data[$sheetNames[0]]));
        $this->assertEquals(4, count($data[$sheetNames[0]]['programmingStack']['ITEMS']));
        $this->assertEquals(3, count($data[$sheetNames[0]]['programmingLanguages']['FIELDS']));
        $this->assertEquals(2, count($data[$sheetNames[0]]['programmingLanguages']['FIELDS']['proficiency']['ITEMS']));
        $this->assertEquals(3, count($data[$sheetNames[0]]['dayAsDate']['ITEMS'])); 
        $this->assertEquals(11, count($data[$sheetNames[0]]['dayAsDate']['ITEMS']['day'])); 
        $this->assertEquals(11, count($data[$sheetNames[0]]['dayAsDate']['ITEMS']['month'])); 
        $this->assertEquals(11, count($data[$sheetNames[0]]['dayAsDate']['ITEMS']['year'])); 
        $this->assertEquals(3, count($data[$sheetNames[0]]['monthYear']['ITEMS'])); 
        $this->assertEquals(11, count($data[$sheetNames[0]]['monthYear']['ITEMS']['day'])); 
        $this->assertEquals(11, count($data[$sheetNames[0]]['monthYear']['ITEMS']['month'])); 
        $this->assertEquals(11, count($data[$sheetNames[0]]['monthYear']['ITEMS']['year'])); 
        $this->assertEquals(3, count($data[$sheetNames[0]]['year']['ITEMS'])); 
        $this->assertEquals(11, count($data[$sheetNames[0]]['year']['ITEMS']['day'])); 
        $this->assertEquals(11, count($data[$sheetNames[0]]['year']['ITEMS']['month'])); 
        $this->assertEquals(11, count($data[$sheetNames[0]]['year']['ITEMS']['year'])); 
        $this->assertEquals(2, count($data[$sheetNames[0]]['boat_usage_survey']['ITEMS']));       
        $this->assertEquals(2, count($data[$sheetNames[0]]['boat_usage_survey']['FIELDS']));       
    }

}