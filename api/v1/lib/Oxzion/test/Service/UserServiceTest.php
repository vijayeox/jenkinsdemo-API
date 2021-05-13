<?php
namespace Oxzion\Service;

use Zend\Stdlib\ArrayUtils;
use Oxzion\Test\ServiceTest;
use Oxzion\Service\EmailService;
use Oxzion\Service\AddressService;
use Zend\Db\Adapter\AdapterInterface;
use Oxzion\Service\TemplateService;
use Oxzion\Transaction\TransactionManager;
use Zend\Db\Adapter\Adapter;
use Oxzion\Messaging\MessageProducer;
use Oxzion\Auth\AuthContext;
use Oxzion\Auth\AuthConstants;
use Zend\Db\ResultSet\ResultSet;

class UserServiceTest extends ServiceTest
{
    public function setUp() : void
    {
        $this->loadConfig();
        // parent::setUp();
        AuthContext::put(AuthConstants::ACCOUNT_UUID, '53012471-2863-4949-afb1-e69b0891c98a');
        $config = $this->getApplicationConfig();
        $this->adapter = new Adapter($config['db']);
        $tm = TransactionManager::getInstance($this->adapter);
        $tm->setRollbackOnly(true);
        $tm->beginTransaction();
    }

    private function runQuery($query)
    {
        $statement = $this->adapter->query($query);
        $result = $statement->execute();
        $resultSet = new ResultSet();
        $result = $resultSet->initialize($result)->toArray();
        return $result;
    }

    public function tearDown() : void
    {
        $tm = TransactionManager::getInstance($this->adapter);
        $tm->rollback();
        $_REQUEST = [];
    }


    private function getUserService()
    {
        return $this->getApplicationServiceLocator()->get(\Oxzion\Service\UserService::class);
    }

    public function testGetPrivileges()
    {
        $data = $this->getUserService()->getPrivileges(1, 1);
        $this->assertEquals(isset($data), true);
        $this->assertEquals(count($data) > 0, true);
    }

    //Function to test delete user
    public function testDeleteUser()
    {
        $data = [
            "access" =>
            [
                "put" => 
                    [
                        "0" => "MANAGE_USER_WRITE",
                        "1" => "MANAGE_ACCOUNT_WRITE",
                        "2" => "MANAGE_TEAM_WRITE"
                    ],

                "post" => 
                    [
                        "0" => "MANAGE_USER_WRITE",
                        "1" => "MANAGE_ACCOUNT_WRITE",
                        "2" => "MANAGE_TEAM_WRITE"
                    ],
                "delete" => 
                    [
                        "0" => "MANAGE_USER_WRITE",
                        "1" => "MANAGE_ACCOUNT_WRITE",
                        "2" => "MANAGE_TEAM_WRITE"
                    ]

            ],
            "accountId" => "53012471-2863-4949-afb1-e69b0891c98a",
            "userId" => "768d1fb9-de9c-46c3-8d5c-23e0e484ce2e"
        ];
        $userId = $data['userId'];
        $this->getUserService()->deleteUser($data);
        $sqlQuery = "SELECT * FROM ox_user WHERE uuid = '".$userId."'";
        $result = $this->runQuery($sqlQuery);
        $this->assertEquals(1, count($result));
        $this->assertEquals('Inactive', $result[0]['status']);
    }
}
