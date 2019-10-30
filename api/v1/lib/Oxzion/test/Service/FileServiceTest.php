<?php
namespace Oxzion\Service;

use Oxzion\Auth\AuthConstants;
// use Oxzion\Service\FormService;
use Oxzion\Auth\AuthContext;
use Oxzion\Service\FileService;
use Oxzion\Test\ServiceTest;
use Oxzion\Transaction\TransactionManager;
use Zend\Db\Adapter\Adapter;

class FileServiceTest extends ServiceTest
{
    public function setUp(): void
    {
        $this->loadConfig();
        $config = $this->getApplicationConfig();
        $this->adapter = new Adapter($config['db']);
        $this->table = $this->getApplicationServiceLocator()->get(\Oxzion\Model\FileTable::class);
        $this->form = $this->getApplicationServiceLocator()->get(\Oxzion\Service\FormService::class);
        $tm = TransactionManager::getInstance($this->adapter);
        $tm->setRollbackOnly(true);
        $tm->beginTransaction();
        $this->data = array(
            "appName" => 'ox_client_app',
            'UUID' => 8765765,
            'fileUuid' => '53012471-2863-4949-afb1-e69b0891cabt',
            'description' => 'FirstAppOfTheClient',
            'orgUuid' => '53012471-2863-4949-afb1-e69b0891c98a',
        );
    }

    public function tearDown(): void
    {
        $tm = TransactionManager::getInstance($this->adapter);
        $tm->rollback();
        $_REQUEST = [];
    }

    // public function testGetFollowupList()
    // {
    //     $orgId = AuthContext::put(AuthConstants::ORG_ID, 3);
    //     $data = ['form_id' => '6', "entity_id" => "5", "field_list" => ["initial" => "retiredInstructor" , "padi" => "23243" ], 'app_id' => '259', 'org_id' => 1];
    //     $appId = $this->data['UUID'];
    //     $fileService = new FileService($this->getApplicationConfig(), $this->getDbAdapter(), $this->table, $this->form);
    //     $content = $fileService->checkFollowUpFiles($appId, $data);
    //     // print_r($content);exit;
    //     $this->assertEquals($data['app_id'], $content[0]['app_id']);
    // }
}
