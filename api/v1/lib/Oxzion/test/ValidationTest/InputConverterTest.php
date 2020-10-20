<?php
namespace Oxzion;

use Oxzion\InputConverter;
use PHPUnit\DbUnit\DataSet\SymfonyYamlParser;
use PHPUnit\Framework\TestCase;
use Oxzion\InvalidInputException;
use Oxzion\Model\Entity;

class InputConverterTest extends TestCase
{
    private $dataset;

    public function setUp() : void
    {
        $this->setSearchData();
        parent::setUp();
    }

    public function setSearchData()
    {
        $parser = new SymfonyYamlParser();
        $this->dataset = $parser->parseYaml(__DIR__."/Dataset/InputTypes.yml");
    }

    public function testIntType()
    {
        $data = $this->dataset;
        $response = InputConverter::checkType('id',$data['sample']['id'], 'value1', $data['sample']['id']['value1'], $data['sample']['id']['type']);
        $this->assertEquals($data['sample']['id']['value1'],$response);
    }

    public function testIntType2()
    {
        //Change in value
        $data = $this->dataset;
        $response = InputConverter::checkType('id',$data['sample']['id'], 'value2', $data['sample']['id']['value1'], $data['sample']['id']['type']);
        $this->assertEquals($data['sample']['id']['value2'],$response);
    }

    public function testIntTypeWithoutDefault()
    {
        $data = $this->dataset;
        $response = InputConverter::checkType('id',$data['sample']['id'], 'value1', null, $data['sample']['id']['type']);
        $this->assertEquals($data['sample']['id']['value1'],$response);
    }

    public function testIntTypeWithoutValue()
    {
        $data = $this->dataset;
        $response = InputConverter::checkType('id',$data['sample']['id'], 'value4', 0, $data['sample']['id']['type']);
        $this->assertEquals($data['sample']['id']['value4'],$response);
    }

    public function testIntTypeWithoutValueAndWithoutDefault()
    {
        $data = $this->dataset;
        $response = InputConverter::checkType('id',$data['sample']['id'], 'value4', null, $data['sample']['id']['type']);
        $this->assertEquals(null ,$response);
    }

    public function testIntTypeWithWrongValue()
    {
        $data = $this->dataset;
        $this->expectException(InvalidInputException::class);
        $response = InputConverter::checkType('id',$data['sample']['id'], 'value3', $data['sample']['id']['value1'], $data['sample']['id']['type']);
    }

    public function testUuidType()
    {
        $data = $this->dataset;
        $response = InputConverter::checkType('uuid',$data['sample']['uuid'], 'value1', $data['sample']['uuid']['value1'], $data['sample']['uuid']['type']);
        $this->assertEquals($data['sample']['uuid']['value1'],$response);
    }

    public function testUuidType2()
    {
        //Change in value
        $data = $this->dataset;
        $response = InputConverter::checkType('uuid',$data['sample']['uuid'], 'value2', $data['sample']['uuid']['value1'], $data['sample']['uuid']['type']);
        $this->assertEquals($data['sample']['uuid']['value2'],$response);
    }

    public function testUuidTypeWithoutDefault()
    {
        $data = $this->dataset;
        $response = InputConverter::checkType('uuid',$data['sample']['uuid'], 'value1', null, $data['sample']['uuid']['type']);
        $this->assertEquals($data['sample']['uuid']['value1'],$response);
    }

    public function testUuidTypeWithoutValue()
    {
        $data = $this->dataset;
        $response = InputConverter::checkType('uuid',$data['sample']['uuid'], 'value4', 0, $data['sample']['uuid']['type']);
        $this->assertEquals($data['sample']['uuid']['value4'],$response);
    }

    public function testUuidTypeWithoutValueAndWithoutDefault()
    {
        $data = $this->dataset;
        $response = InputConverter::checkType('uuid',$data['sample']['uuid'], 'value4', null, $data['sample']['uuid']['type']);
        $this->assertEquals(null ,$response);
    }

    public function testUuidTypeWithWrongValue()
    {
        $data = $this->dataset;
        $this->expectException(InvalidInputException::class);
        $response = InputConverter::checkType('uuid',$data['sample']['uuid'], 'value3', $data['sample']['uuid']['value1'], $data['sample']['uuid']['type']);
    }

    public function testStringType()
    {
        $data = $this->dataset;
        $response = InputConverter::checkType('name',$data['sample']['name'], 'value1', $data['sample']['name']['value1'], $data['sample']['name']['type']);
        $this->assertEquals($data['sample']['name']['value1'],$response);
    }

    public function testStringType2()
    {
        //Change in value
        $data = $this->dataset;
        $response = InputConverter::checkType('name',$data['sample']['name'], 'value2', $data['sample']['name']['value1'], $data['sample']['name']['type']);
        $this->assertEquals($data['sample']['name']['value2'],$response);
    }

    public function testStringTypeWithoutDefault()
    {
        $data = $this->dataset;
        $response = InputConverter::checkType('name',$data['sample']['name'], 'value1', null, $data['sample']['name']['type']);
        $this->assertEquals($data['sample']['name']['value1'],$response);
    }

    public function testStringTypeWithoutValue()
    {
        $data = $this->dataset;
        $response = InputConverter::checkType('name',$data['sample']['name'], 'value4', 0, $data['sample']['name']['type']);
        $this->assertEquals($data['sample']['name']['value4'],$response);
    }

    public function testStringTypeWithoutValueAndWithoutDefault()
    {
        $data = $this->dataset;
        $response = InputConverter::checkType('name',$data['sample']['name'], 'value4', null, $data['sample']['name']['type']);
        $this->assertEquals(null ,$response);
    }

    public function testStringTypeWithWrongValue()
    {
        $data = $this->dataset;
        $this->expectException(InvalidInputException::class);
        $response = InputConverter::checkType('name',$data['sample']['name'], 'value3', $data['sample']['name']['value1'], $data['sample']['name']['type']);
    }

    public function testDateType()
    {
        $data = $this->dataset;
        $response = InputConverter::checkType('date',$data['sample']['date'], 'value1', $data['sample']['date']['value1'], $data['sample']['date']['type']);
        $this->assertEquals($data['sample']['date']['value1'],$response);
    }

    public function testDateType2()
    {
        //Change in value
        $data = $this->dataset;
        $response = InputConverter::checkType('date',$data['sample']['date'], 'value2', $data['sample']['date']['value1'], $data['sample']['date']['type']);
        $this->assertEquals($data['sample']['date']['value2'],$response);
    }

    public function testDateTypeWithoutDefault()
    {
        $data = $this->dataset;
        $response = InputConverter::checkType('date',$data['sample']['date'], 'value1', null, $data['sample']['date']['type']);
        $this->assertEquals($data['sample']['date']['value1'],$response);
    }

    public function testDateTypeWithoutValue()
    {
        $data = $this->dataset;
        $response = InputConverter::checkType('date',$data['sample']['date'], 'value4', 0, $data['sample']['date']['type']);
        $this->assertEquals($data['sample']['date']['value4'],$response);
    }

    public function testDateTypeWithoutValueAndWithoutDefault()
    {
        $data = $this->dataset;
        $response = InputConverter::checkType('date',$data['sample']['date'], 'value4', null, $data['sample']['date']['type']);
        $this->assertEquals(null ,$response);
    }

    public function testDateTypeWithWrongValue()
    {
        $data = $this->dataset;
        $this->expectException(InvalidInputException::class);
        $response = InputConverter::checkType('date',$data['sample']['date'], 'value3', $data['sample']['date']['value1'], $data['sample']['date']['type']);
    }

    public function testTimestampType()
    {
        $data = $this->dataset;
        $response = InputConverter::checkType('date_created',$data['sample']['date_created'], 'value1', $data['sample']['date_created']['value1'], $data['sample']['date_created']['type']);
        $this->assertEquals($data['sample']['date_created']['value1'],$response);
    }

    public function testTimestampType2()
    {
        //Change in value
        $data = $this->dataset;
        $response = InputConverter::checkType('date_created',$data['sample']['date_created'], 'value2', $data['sample']['date_created']['value1'], $data['sample']['date_created']['type']);
        $this->assertEquals($data['sample']['date_created']['value2'],$response);
    }

    public function testTimestampTypeWithoutDefault()
    {
        $data = $this->dataset;
        $response = InputConverter::checkType('date_created',$data['sample']['date_created'], 'value1', null, $data['sample']['date_created']['type']);
        $this->assertEquals($data['sample']['date_created']['value1'],$response);
    }

    public function testTimestampTypeWithoutValue()
    {
        $data = $this->dataset;
        $response = InputConverter::checkType('date_created',$data['sample']['date_created'], 'value4', 0, $data['sample']['date_created']['type']);
        $this->assertEquals($data['sample']['date_created']['value4'],$response);
    }

    public function testTimestampTypeWithoutValueAndWithoutDefault()
    {
        $data = $this->dataset;
        $response = InputConverter::checkType('date_created',$data['sample']['date_created'], 'value4', null, $data['sample']['date_created']['type']);
        $this->assertEquals(null ,$response);
    }

    public function testTimestampTypeWithWrongValue()
    {
        $data = $this->dataset;
        $this->expectException(InvalidInputException::class);
        $response = InputConverter::checkType('date_created',$data['sample']['date_created'], 'value3', $data['sample']['date_created']['value1'], $data['sample']['date_created']['type']);
    }

    public function testFloatType()
    {
        $data = $this->dataset;
        $response = InputConverter::checkType('aggregate',$data['sample']['aggregate'], 'value1', $data['sample']['aggregate']['value1'], $data['sample']['aggregate']['type']);
        $this->assertEquals($data['sample']['aggregate']['value1'],$response);
    }

    public function testFloatType2()
    {
        //Change in value
        $data = $this->dataset;
        $response = InputConverter::checkType('aggregate',$data['sample']['aggregate'], 'value2', $data['sample']['aggregate']['value1'], $data['sample']['aggregate']['type']);
        $this->assertEquals($data['sample']['aggregate']['value2'],$response);
    }

    public function testFloatTypeWithoutDefault()
    {
        $data = $this->dataset;
        $response = InputConverter::checkType('aggregate',$data['sample']['aggregate'], 'value1', null, $data['sample']['aggregate']['type']);
        $this->assertEquals($data['sample']['aggregate']['value1'],$response);
    }

    public function testFloatTypeWithoutValue()
    {
        $data = $this->dataset;
        $response = InputConverter::checkType('aggregate',$data['sample']['aggregate'], 'value4', 0, $data['sample']['aggregate']['type']);
        $this->assertEquals($data['sample']['aggregate']['value4'],$response);
    }

    public function testFloatTypeWithoutValueAndWithoutDefault()
    {
        $data = $this->dataset;
        $response = InputConverter::checkType('aggregate',$data['sample']['aggregate'], 'value4', null, $data['sample']['aggregate']['type']);
        $this->assertEquals(null ,$response);
    }

    public function testFloatTypeWithWrongValue()
    {
        $data = $this->dataset;
        $this->expectException(InvalidInputException::class);
        $response = InputConverter::checkType('aggregate',$data['sample']['aggregate'], 'value3', $data['sample']['aggregate']['value1'], $data['sample']['aggregate']['type']);
    }

    public function testBooleanType()
    {
        $data = $this->dataset;
        $response = InputConverter::checkType('isdeleted',$data['sample']['isdeleted'], 'value1', $data['sample']['isdeleted']['value1'], $data['sample']['isdeleted']['type']);
        $this->assertEquals($data['sample']['isdeleted']['value1'],$response);
    }

    public function testBooleanType2()
    {
        //Change in value
        $data = $this->dataset;
        $response = InputConverter::checkType('isdeleted',$data['sample']['isdeleted'], 'value2', $data['sample']['isdeleted']['value1'], $data['sample']['isdeleted']['type']);
        $this->assertEquals($data['sample']['isdeleted']['value2'],$response);
    }

    public function testBooleanTypeWithoutDefault()
    {
        $data = $this->dataset;
        $response = InputConverter::checkType('isdeleted',$data['sample']['isdeleted'], 'value1', null, $data['sample']['isdeleted']['type']);
        $this->assertEquals($data['sample']['isdeleted']['value1'],$response);
    }

    public function testBooleanTypeWithoutValue()
    {
        $data = $this->dataset;
        $response = InputConverter::checkType('isdeleted',$data['sample']['isdeleted'], 'value4', 0, $data['sample']['isdeleted']['type']);
        $this->assertEquals($data['sample']['isdeleted']['value4'],$response);
    }

    public function testBooleanTypeWithoutValueAndWithoutDefault()
    {
        $data = $this->dataset;
        $response = InputConverter::checkType('isdeleted',$data['sample']['isdeleted'], 'value4', null, $data['sample']['isdeleted']['type']);
        $this->assertEquals(null ,$response);
    }

    public function testBooleanTypeWithWrongValue()
    {
        $data = $this->dataset;
        $this->expectException(InvalidInputException::class);
        $response = InputConverter::checkType('isdeleted',$data['sample']['isdeleted'], 'value3', $data['sample']['isdeleted']['value1'], $data['sample']['isdeleted']['type']);
    }
}