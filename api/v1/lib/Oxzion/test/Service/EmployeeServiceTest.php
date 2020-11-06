<?php
namespace Oxzion\Service;

use Zend\Stdlib\ArrayUtils;
use Oxzion\Test\ServiceTest;
use Oxzion\Service\EmailService;
use Oxzion\Auth\AuthContext;
use Oxzion\Auth\AuthConstants;
use Oxzion\Service\AddressService;
use Zend\Db\Adapter\AdapterInterface;
use Oxzion\Service\TemplateService;
use Oxzion\Transaction\TransactionManager;
use Zend\Db\Adapter\Adapter;
use Oxzion\Messaging\MessageProducer;
use PHPUnit\DbUnit\DataSet\YamlDataSet;

class EmployeeServiceTest extends ServiceTest
{
    public function setUp() : void
    {
        $this->loadConfig();
        // parent::setUp();
        $config = $this->getApplicationConfig();
        $this->adapter = new Adapter($config['db']);
        $tm = TransactionManager::getInstance($this->adapter);
        $tm->setRollbackOnly(true);
        $tm->beginTransaction();
    }

    public function tearDown() : void
    {
        $tm = TransactionManager::getInstance($this->adapter);
        $tm->rollback();
        $_REQUEST = [];
    }
    public function getDataSet()
    {
        $dataset = new YamlDataSet(dirname(__FILE__) . "/Dataset/UserServiceTest.yml");
        return $dataset;
    }

    private function getEmployeeService()
    {
        return $this->getApplicationServiceLocator()->get(\Oxzion\Service\EmployeeService::class);
    }

    public function testaddEmployeeRecord()
    {
        AuthContext::put(AuthConstants::USER_ID, '1');
        AuthContext::put(AuthConstants::ACCOUNT_ID, '1');
        $data = ['firstname' => 'test', 
        'lastname' => 'test', 
        'username' => 'testuser', 
        'email' => 'testuser@eoxvantage.in', 
        'date_of_birth' => '2020-11-02', 
        'gender' => 'Male', 
        'managerId' => '84c6dc08-1cc9-11eb-bbfa-485f997ffb6f', 
        'role' => Array(['0' => Array(['uuid' => 'f5359981-fca4-11ea-8921-485f997ffb6f'])]),
        'address1' => '23811 Chagrin Blvd, Ste 244', 
        'city' => 'Beachwood', 
        'state' => 'OH', 
        'zip' => '44122', 
        'country' => 'US', 
        'account_id' => '1', 
        'address2' => '', 
        'person_id' => '1', 
        'name' => 'test test', 
        'date_created' => '2020-11-05 10:07:39', 
        'password_reset_code' => 'c347a277-e724-42ea-9b2a-c80dc62f7d7e', 
        'created_by' => ''];
        $content = $this->getEmployeeService()->addEmployeeRecord($data);
        $empUuid = $content['uuid'];
        $data['uuid'] = $empUuid;
        $rows = $this->executeQueryTest("SELECT * FROM ox_employee WHERE uuid='${empUuid}'");
        $this->assertEquals($content['uuid'], $rows[0]['uuid']);
        $this->assertEquals($content['org_id'], $rows[0]['org_id']);
        $this->assertEquals($content['manager_id'], $rows[0]['manager_id']);
        $this->assertEquals($content['person_id'], $rows[0]['person_id']);
    }
    public function testaddEmployeeWithdesignationRecord()
    {
        AuthContext::put(AuthConstants::USER_ID, '1');
        AuthContext::put(AuthConstants::ACCOUNT_ID, '1');
        $data = ['firstname' => 'test', 
        'lastname' => 'test', 
        'username' => 'testuser', 
        'email' => 'testuser@eoxvantage.in', 
        'date_of_birth' => '2020-11-02', 
        'gender' => 'Male', 
        'managerId' => '84c6dc08-1cc9-11eb-bbfa-485f997ffb6f', 
        'role' => Array(['0' => Array(['uuid' => 'f5359981-fca4-11ea-8921-485f997ffb6f'])]),
        'address1' => '23811 Chagrin Blvd, Ste 244', 
        'city' => 'Beachwood', 
        'state' => 'OH', 
        'zip' => '44122', 
        'country' => 'US', 
        'account_id' => '1', 
        'address2' => '', 
        'designation' => 'Dev',
        'person_id' => '1', 
        'name' => 'test test', 
        'date_created' => '2020-11-05 10:07:39', 
        'password_reset_code' => 'c347a277-e724-42ea-9b2a-c80dc62f7d7e', 
        'created_by' => ''];
        $content = $this->getEmployeeService()->addEmployeeRecord($data);
        $empUuid = $content['uuid'];
        $data['uuid'] = $empUuid;
        $rows = $this->executeQueryTest("SELECT * FROM ox_employee WHERE uuid='${empUuid}'");
        $this->assertEquals($content['uuid'], $rows[0]['uuid']);
        $this->assertEquals($content['org_id'], $rows[0]['org_id']);
        $this->assertEquals($content['manager_id'], $rows[0]['manager_id']);
        $this->assertEquals($content['person_id'], $rows[0]['person_id']);
        $this->assertEquals($content['designation'], $data['designation']);
    }

    public function testeditEmployeeWithRecord()
    {
        AuthContext::put(AuthConstants::USER_ID, '1');
        AuthContext::put(AuthConstants::ACCOUNT_ID, '1');
        $data = [
            'uuid' => '2c7590d9-301c-4d29-bad9-38c4530e122c',
            'username' => 'testuser',
            'password' => '68de082ad0afbcdb3cdec0427e38dd3f',
            'name' => 'test test',
            'account_id' => '1',
            'icon' => '',
            'status' => 'Active',
            'in_game' => '',
            'timezone' => 'Asia/Kolkata',
            'preferences' => '',
            'password_reset_code' => 'a3fb75c1-dc0f-47d5-8950-ace6f1ac373f',
            'password_reset_expiry_date' => '',
            'person_id' => '1',
            'firstname' => 'test',
            'lastname' => 'test',
            'email' => 'testuser@eoxvantage.in',
            'date_of_birth' => '2020-11-16',
            'designation' => 'Dev',
            'gender' => 'Male',
            'managerid' => '84c6dc08-1cc9-11eb-bbfa-485f997ffb6f',
            'role' => Array(['0' => Array(['uuid' => 'f5359981-fca4-11ea-8921-485f997ffb6f'])]),
            'project' => Array
            (
            ),

            'date_of_join' => '2020-11-23',
            'address1' => '23811 Chagrin Blvd, Ste 244',
            'city' => 'Beachwood',
            'state' => 'OH',
            'zip' => '44122',
            'country' => 'US',
            'address2' => '',
            'modified_id' => '1',
            'date_modified' => '2020-11-06 11:30:42'
        ];
        $content = $this->getEmployeeService()->updateEmployeeDetails($data);
        $empUuid = $content['uuid'];
        $data['uuid'] = $empUuid;
        $rows = $this->executeQueryTest("SELECT * FROM ox_employee WHERE uuid='${empUuid}'");
        $this->assertEquals($content['uuid'], $rows[0]['uuid']);
        $this->assertEquals($content['org_id'], $rows[0]['org_id']);
        $this->assertEquals($content['manager_id'], $rows[0]['manager_id']);
        $this->assertEquals($content['person_id'], $rows[0]['person_id']);
        $this->assertEquals($content['designation'], $data['designation']);

    }

}
