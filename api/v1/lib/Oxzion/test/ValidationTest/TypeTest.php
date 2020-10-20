<?php
namespace Oxzion;

use Oxzion\Type;
use PHPUnit\Framework\TestCase;
use Oxzion\InvalidInputException;

/*
 * Tests for \Oxzion\Type class.
 */
class TypeTest extends TestCase
{
    public function setUp() : void
    {
        parent::setUp();
    }

    public function testIntegerWithPositiveInputAsString()
    {
        $converted = Type::convert('123', Type::INTEGER);
        $this->assertEquals('integer', gettype($converted));
        $this->assertEquals(123, $converted);
    }

    public function testIntegerWithPositiveInputAsInt()
    {
        $converted = Type::convert(123, Type::INTEGER);
        $this->assertEquals('integer', gettype($converted));
        $this->assertEquals(123, $converted);
    }

    public function testIntegerWithNegativeInputAsString() {
        $converted = Type::convert('-123', Type::INTEGER);
        $this->assertEquals('integer', gettype($converted));
        $this->assertEquals(-123, $converted);
    }

    public function testIntegerWithNegativeInputAsInt() {
        $converted = Type::convert(-123, Type::INTEGER);
        $this->assertEquals('integer', gettype($converted));
        $this->assertEquals(-123, $converted);
    }

    public function testIntegerWithFloatInputAsString() {
        try {
            Type::convert('12.23', Type::INTEGER);
            $this->fail('InvalidInputException is expected.');
        }
        catch (InvalidInputException $e) {
            $this->assertNotNull($e);

        }
    }

    public function testIntegerWithFloatInput() {
        try {
            Type::convert(12.23, Type::INTEGER);
            $this->fail('InvalidInputException is expected.');
        }
        catch (InvalidInputException $e) {
            $this->assertNotNull($e);

        }
    }

    public function testIntegerWithEmptyStringInput() {
        try {
            Type::convert('', Type::INTEGER);
            $this->fail('InvalidInputException is expected.');
        }
        catch (InvalidInputException $e) {
            $this->assertNotNull($e);

        }
    }

    public function testIntegerWithTextStringInput() {
        try {
            Type::convert('India', Type::INTEGER);
            $this->fail('InvalidInputException is expected.');
        }
        catch (InvalidInputException $e) {
            $this->assertNotNull($e);

        }
    }

    public function testIntegerWithInvalidNumericStringInput() {
        try {
            Type::convert('12 34', Type::INTEGER);
            $this->fail('InvalidInputException is expected.');
        }
        catch (InvalidInputException $e) {
            $this->assertNotNull($e);

        }
    }

    public function testIntegerWithNullInput() {
        $converted = Type::convert(NULL, Type::INTEGER);
        $this->assertNull($converted);
    }

    public function testFloatWithPositiveInputAsString()
    {
        $converted = Type::convert('123.45', Type::FLOAT);
        $this->assertEquals('double', gettype($converted));
        $this->assertEquals(123.45, $converted);
    }

    public function testFloatWithPositiveInputAsFloat()
    {
        $converted = Type::convert(123.45, Type::FLOAT);
        $this->assertEquals('double', gettype($converted));
        $this->assertEquals(123.45, $converted);
    }

    public function testFloatWithNegativeInputAsString() {
        $converted = Type::convert('-123.45', Type::FLOAT);
        $this->assertEquals('double', gettype($converted));
        $this->assertEquals(-123.45, $converted);
    }

    public function testFloatWithNegativeInputAsFloat() {
        $converted = Type::convert(-123.45, Type::FLOAT);
        $this->assertEquals('double', gettype($converted));
        $this->assertEquals(-123.45, $converted);
    }

    public function testFloatWithFloatInputAsString() {
        $converted = Type::convert('123.45', Type::FLOAT);
        $this->assertEquals('double', gettype($converted));
        $this->assertEquals(123.45, $converted);
    }

    public function testFloatWithIntInput() {
        $converted = Type::convert(1234, Type::FLOAT);
        $this->assertEquals('double', gettype($converted));
        $this->assertEquals(1234.0, $converted);
    }

    public function testFloatWithEmptyStringInput() {
        try {
            Type::convert('', Type::FLOAT);
            $this->fail('InvalidInputException is expected.');
        }
        catch (InvalidInputException $e) {
            $this->assertNotNull($e);

        }
    }

    public function testFloatWithTextStringInput() {
        try {
            Type::convert('India', Type::FLOAT);
            $this->fail('InvalidInputException is expected.');
        }
        catch (InvalidInputException $e) {
            $this->assertNotNull($e);

        }
    }

    public function testFloatWithInvalidNumericStringInput() {
        try {
            Type::convert('12 34', Type::FLOAT);
            $this->fail('InvalidInputException is expected.');
        }
        catch (InvalidInputException $e) {
            $this->assertNotNull($e);
        }
    }

    public function testFloatWithNullInput() {
        $converted = Type::convert(NULL, Type::FLOAT);
        $this->assertNull($converted);
    }

    public function testDateWithValidDateInput() {
        $converted = Type::convert('2020-01-01', Type::DATE);
        $this->assertEquals('string', gettype($converted));
        $this->assertEquals('2020-01-01', $converted);
    }

    public function testDateWithMonthOutOfRangeInput() {
        try {
            Type::convert('2020-14-01', Type::DATE);
            $this->fail('InvalidInputException is expected.');
        }
        catch (InvalidInputException $e) {
            $this->assertNotNull($e);
        }
    }

    public function testDateWithDayOutOfRangeInput() {
        try {
            Type::convert('2020-04-32', Type::DATE);
            $this->fail('InvalidInputException is expected.');
        }
        catch (InvalidInputException $e) {
            $this->assertNotNull($e);
        }
    }

    public function testDateWithNumberInput() {
        try {
            Type::convert('20200432', Type::DATE);
            $this->fail('InvalidInputException is expected.');
        }
        catch (InvalidInputException $e) {
            $this->assertNotNull($e);
        }
    }

    public function testDateWithDateTimeInput() {
        $converted = Type::convert(\DateTime::createFromFormat('Y-m-d', '2020-01-01'), Type::DATE);
        $this->assertEquals('string', gettype($converted));
        $this->assertEquals('2020-01-01', $converted);
    }

    public function testDateWithNullInput() {
        $converted = Type::convert(NULL, Type::DATE);
        $this->assertNull($converted);
    }

    public function testUuidWithValidUuid() {
        $converted = Type::convert('123e4567-e89b-12d3-a456-426614174000', Type::UUID);
        $this->assertEquals('string', gettype($converted));
        $this->assertEquals('123e4567-e89b-12d3-a456-426614174000', $converted);
    }

    public function testUuidWithInvalidUuidShortString() {
        try {
            Type::convert('123e4567-e89b-12d3-a456-42661417400', Type::UUID);
            $this->fail('InvalidInputException is expected.');
        }
        catch (InvalidInputException $e) {
            $this->assertNotNull($e);
        }
    }

    public function testUuidWithInvalidUuidLongString() {
        try {
            Type::convert('123e4567-e89b-12d3-a456-4266141740000', Type::UUID);
            $this->fail('InvalidInputException is expected.');
        }
        catch (InvalidInputException $e) {
            $this->assertNotNull($e);
        }
    }

    public function testUuidWithInvalidUuidWrongHyphenation() {
        try {
            Type::convert('123e4567e8-9b12d3-a456-426614174000', Type::UUID);
            $this->fail('InvalidInputException is expected.');
        }
        catch (InvalidInputException $e) {
            $this->assertNotNull($e);
        }
    }

    public function testUuidWithInvalidUuidWrongCharacters() {
        try {
            Type::convert('123e4567-e89G-12d3-a456-426614174000', Type::UUID);
            $this->fail('InvalidInputException is expected.');
        }
        catch (InvalidInputException $e) {
            $this->assertNotNull($e);
        }
    }

    public function testUuidWithEmptyString() {
        try {
            Type::convert('', Type::UUID);
            $this->fail('InvalidInputException is expected.');
        }
        catch (InvalidInputException $e) {
            $this->assertNotNull($e);
        }
    }

    public function testUuidWithNullInput() {
        $converted = Type::convert(NULL, Type::UUID);
        $this->assertNull($converted);
    }

    public function testStringWithStringInput() {
        $converted = Type::convert('PHP code', Type::STRING);
        $this->assertEquals('string', gettype($converted));
        $this->assertEquals('PHP code', $converted);
    }

    public function testStringWithNullInput() {
        $converted = Type::convert(NULL, Type::STRING);
        $this->assertNull($converted);
    }

    public function testStringWithIntegerInput() {
        $converted = Type::convert(12345, Type::STRING);
        $this->assertEquals('string', gettype($converted));
        $this->assertEquals('12345', $converted);
    }

    public function testStringWithFloatInput() {
        $converted = Type::convert(12345.67, Type::STRING);
        $this->assertEquals('string', gettype($converted));
        $this->assertEquals('12345.67', $converted);
    }

    public function testStringWithBooleanTrueInput() {
        $converted = Type::convert(TRUE, Type::STRING);
        $this->assertEquals('string', gettype($converted));
        $this->assertEquals('true', $converted);
    }

    public function testStringWithBooleanFalseInput() {
        $converted = Type::convert(FALSE, Type::STRING);
        $this->assertEquals('string', gettype($converted));
        $this->assertEquals('false', $converted);
    }

    public function testTimestampWithValidTimestampString() {
        $converted = Type::convert('2020-01-01 10:11:12', Type::TIMESTAMP);
        $this->assertEquals('string', gettype($converted));
        $this->assertEquals('2020-01-01 10:11:12', $converted);
    }

    public function testTimestampWithDateTimeInput() {
        $converted = Type::convert(\DateTime::createFromFormat('Y-m-d H:i:s', '2020-01-01 10:11:12'), Type::TIMESTAMP);
        $this->assertEquals('string', gettype($converted));
        $this->assertEquals('2020-01-01 10:11:12', $converted);
    }

    public function testTimestampWithDateTimeWithoutHMSInput() {
        $input = \DateTime::createFromFormat('Y-m-d', '2020-01-01');
        $converted = Type::convert($input, Type::TIMESTAMP);
        $this->assertEquals('string', gettype($converted));
        $this->assertEquals($input->format('Y-m-d H:i:s'), $converted);
    }

    public function testTimestampWithInValidTimestampMonthOutOfRange() {
        try {
            Type::convert('2020-14-01 10:11:12', Type::TIMESTAMP);
            $this->fail('InvalidInputException is expected.');
        }
        catch (InvalidInputException $e) {
            $this->assertNotNull($e);
        }
    }

    public function testTimestampWithInValidTimestampDateOutOfRange() {
        try {
            Type::convert('2020-01-35 10:11:12', Type::TIMESTAMP);
            $this->fail('InvalidInputException is expected.');
        }
        catch (InvalidInputException $e) {
            $this->assertNotNull($e);
        }
    }

    public function testTimestampWithInValidTimestampHourOutOfRange() {
        try {
            Type::convert('2020-01-01 25:11:12', Type::TIMESTAMP);
            $this->fail('InvalidInputException is expected.');
        }
        catch (InvalidInputException $e) {
            $this->assertNotNull($e);
        }
    }

    public function testTimestampWithInValidTimestampMinuteOutOfRange() {
        try {
            Type::convert('2020-01-01 10:65:12', Type::TIMESTAMP);
            $this->fail('InvalidInputException is expected.');
        }
        catch (InvalidInputException $e) {
            $this->assertNotNull($e);
        }
    }

    public function testTimestampWithInValidTimestampSecondOutOfRange() {
        try {
            Type::convert('2020-01-01 10:11:65', Type::TIMESTAMP);
            $this->fail('InvalidInputException is expected.');
        }
        catch (InvalidInputException $e) {
            $this->assertNotNull($e);
        }
    }

    public function testTimestampWithNullInput() {
        $converted = Type::convert(NULL, Type::TIMESTAMP);
        $this->assertNull($converted);
    }

    public function testTimestampWithEmptyStringInput() {
        try {
            Type::convert('', Type::TIMESTAMP);
            $this->fail('InvalidInputException is expected.');
        }
        catch (InvalidInputException $e) {
            $this->assertNotNull($e);
        }
    }

    public function testTimestampWithStringDate() {
        try {
            Type::convert('2020-01-01', Type::TIMESTAMP);
            $this->fail('InvalidInputException is expected.');
        }
        catch (InvalidInputException $e) {
            $this->assertNotNull($e);
        }
    }

    public function testBooleanWithValidBooleanStringTrue() {
        $converted = Type::convert('true', Type::BOOLEAN);
        $this->assertEquals('boolean', gettype($converted));
        $this->assertEquals(true, $converted);
    }

    public function testBooleanWithValidBooleanStringOn() {
        $converted = Type::convert('on', Type::BOOLEAN);
        $this->assertEquals('boolean', gettype($converted));
        $this->assertEquals(true, $converted);
    }

    public function testBooleanWithValidBooleanStringYes() {
        $converted = Type::convert('yes', Type::BOOLEAN);
        $this->assertEquals('boolean', gettype($converted));
        $this->assertEquals(true, $converted);
    }

    public function testBooleanWithValidBooleanString1() {
        $converted = Type::convert('1', Type::BOOLEAN);
        $this->assertEquals('boolean', gettype($converted));
        $this->assertEquals(true, $converted);
    }

    public function testBooleanWithValidBooleanStringFalse() {
        $converted = Type::convert('false', Type::BOOLEAN);
        $this->assertEquals('boolean', gettype($converted));
        $this->assertEquals(false, $converted);
    }

    public function testBooleanWithValidBooleanStringOff() {
        $converted = Type::convert('off', Type::BOOLEAN);
        $this->assertEquals('boolean', gettype($converted));
        $this->assertEquals(false, $converted);
    }

    public function testBooleanWithValidBooleanStringNo() {
        $converted = Type::convert('no', Type::BOOLEAN);
        $this->assertEquals('boolean', gettype($converted));
        $this->assertEquals(false, $converted);
    }

    public function testBooleanWithValidBooleanString0() {
        $converted = Type::convert('0', Type::BOOLEAN);
        $this->assertEquals('boolean', gettype($converted));
        $this->assertEquals(false, $converted);
    }

    public function testBooleanWithValidBooleanTrue() {
        $converted = Type::convert(true, Type::BOOLEAN);
        $this->assertEquals('boolean', gettype($converted));
        $this->assertEquals(true, $converted);
    }

    public function testBooleanWithValidBooleanFalse() {
        $converted = Type::convert(false, Type::BOOLEAN);
        $this->assertEquals('boolean', gettype($converted));
        $this->assertEquals(false, $converted);
    }

    public function testBooleanWithInteger1() {
        $converted = Type::convert(1, Type::BOOLEAN);
        $this->assertEquals('boolean', gettype($converted));
        $this->assertEquals(true, $converted);
    }

    public function testBooleanWithInteger0() {
        $converted = Type::convert(0, Type::BOOLEAN);
        $this->assertEquals('boolean', gettype($converted));
        $this->assertEquals(false, $converted);
    }

    public function testBooleanWithNonZeroAndOneInteger() {
        $converted = Type::convert(10, Type::BOOLEAN);
        $this->assertEquals('boolean', gettype($converted));
        $this->assertEquals(true, $converted);
    }

    public function testBooleanWithNullInput() {
        $converted = Type::convert(NULL, Type::BOOLEAN);
        $this->assertNull($converted);
    }

    public function testBooleanWithEmptyStringInput() {
        try {
            Type::convert('', Type::BOOLEAN);
            $this->fail('InvalidInputException expected.');
        }
        catch (InvalidInputException $e) {
            $this->assertNotNull($e);
        }
    }
}
