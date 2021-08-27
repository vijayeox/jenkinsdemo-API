<?php
namespace Oxzion\Service;

use Oxzion\Test\ServiceTest;
use Zend\Db\Adapter\AdapterInterface;
use Oxzion\Service\TemplateService;
use Zend\Db\Adapter\Adapter;
use Oxzion\Utils\FileUtils;
use Oxzion\Auth\AuthContext;
use Oxzion\Auth\AuthConstants;
use Exception;
use Oxzion\Transaction\TransactionManager;

class TemplateServiceTest extends ServiceTest
{
    public function setUp() : void
    {
        $this->loadConfig();
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

    public function testEmailTemplate()
    {
        $data = ['username' => 'John','orgUuid' => '53012471-2863-4949-afb1-e69b0891c98a'];
        AuthContext::put(AuthConstants::ORG_UUID, $data['orgUuid']);
        $config = $this->getApplicationConfig();
        $tempFolder = $config['TEMPLATE_FOLDER'].$data['orgUuid'];
        if (!is_link($tempFolder)) {
            FileUtils::createDirectory($tempFolder."/");
        }
        $tempFile = $config['TEMPLATE_FOLDER']."/";
        FileUtils::createDirectory($tempFile);
        copy(__DIR__."/template/GenericTemplate.tpl", $tempFile."GenericTemplate.tpl");
        $TemplateService = new TemplateService($config, $this->adapter);
        $content = $TemplateService->getContent('GenericTemplate', $data);
        $temp = "Hello ".$data['username'].", this is a generic template.</p>";
        $this->assertEquals(strpos($content, $temp), 3);
        $templateName="GenericTemplate.tpl";
        FileUtils::deleteFile($templateName, $tempFile);
        FileUtils::rmDir($tempFolder);
    }

    public function testEmailTemplateDirectoryWIthOrgUuid()
    {
        $data = ['username' => 'John','orgUuid' => '53012471-2863-4949-afb1-e69b0891c98a'];
        AuthContext::put(AuthConstants::ORG_UUID, $data['orgUuid']);
        $config = $this->getApplicationConfig();
        $tempFolder = $config['TEMPLATE_FOLDER'].$data['orgUuid'];
        if (!is_link($tempFolder)) {
            FileUtils::createDirectory($tempFolder."/");
        }
        $tempFile = $config['TEMPLATE_FOLDER']."53012471-2863-4949-afb1-e69b0891c98a/";
        FileUtils::createDirectory($tempFile);
        copy(__DIR__."/template/53012471-2863-4949-afb1-e69b0891c98a/NewTemplate.tpl", $tempFile."NewTemplate.tpl");
        $TemplateService = new TemplateService($config, $this->adapter);
        $content = $TemplateService->getContent('NewTemplate', $data);
        $this->assertEquals("<p>Hello ".$data['username'].", this is a organization specific template.</p>", $content);
        $templateName="NewTemplate.tpl";
        FileUtils::deleteFile($templateName, $tempFile);
        FileUtils::rmDir($tempFolder);
    }

    public function testEmailTemplateNotFound()
    {
        $data = ['username' => 'John','orgUuid' => '53012471-2863-4949-afb1-e69b0891c98a'];
        AuthContext::put(AuthConstants::ORG_UUID, $data['orgUuid']);
        $config = $this->getApplicationConfig();
        $tempFolder = $config['TEMPLATE_FOLDER'].$data['orgUuid'];
        if (!is_link($tempFolder)) {
            FileUtils::createDirectory($tempFolder."/");
        }
        $tempFile = $config['TEMPLATE_FOLDER']."/";
        FileUtils::createDirectory($tempFile);
        $TemplateService = new TemplateService($config, $this->adapter);
        $this->expectException(Exception::class);
        $content = $TemplateService->getContent('UnknownTemplate', $data);
        FileUtils::rmDir($tempFolder);
    }

    public function testEmailTemplateWithOrgId()
    {
        $data = ['username' => 'John','orgid'=>3];
        $config = $this->getApplicationConfig();
        $tempFolder = $config['TEMPLATE_FOLDER']."b6499a34-c100-4e41-bece-5822adca3844/";
        FileUtils::createDirectory($tempFolder);
        $tempFile = $config['TEMPLATE_FOLDER']."/";
        FileUtils::createDirectory($tempFile);
        copy(__DIR__."/template/GenericTemplate.tpl", $tempFile."GenericTemplate.tpl");
        $TemplateService = new TemplateService($config, $this->adapter);
        $content = $TemplateService->getContent('GenericTemplate', $data);
        $temp = "Hello ".$data['username'].", this is a generic template.</p>";
        $this->assertEquals(strpos($content, $temp), 3);
        $templateName="GenericTemplate.tpl";
        FileUtils::deleteFile($templateName, $tempFile);
        FileUtils::rmDir($tempFolder);
    }
    public function testEmailTemplateWithOrgUUId()
    {
        $data = ['username' => 'John','orgid'=>"b6499a34-c100-4e41-bece-5822adca3844"];
        $config = $this->getApplicationConfig();
        $tempFolder = $config['TEMPLATE_FOLDER']."b6499a34-c100-4e41-bece-5822adca3844/";
        FileUtils::createDirectory($tempFolder);
        $tempFile = $config['TEMPLATE_FOLDER']."/";
        FileUtils::createDirectory($tempFile);
        copy(__DIR__."/template/GenericTemplate.tpl", $tempFile."GenericTemplate.tpl");
        $TemplateService = new TemplateService($config, $this->adapter);
        $content = $TemplateService->getContent('GenericTemplate', $data);
        $temp = "Hello ".$data['username'].", this is a generic template.</p>";
        $this->assertEquals(strpos($content, $temp), 3);
        $templateName="GenericTemplate.tpl";
        FileUtils::deleteFile($templateName, $tempFile);
        FileUtils::rmDir($tempFolder);
    }
    public function testEmailTemplateWithInvalidOrgId()
    {
        $data = ['username' => 'John','orgid' => '4'];
        $config = $this->getApplicationConfig();
        $TemplateService = new TemplateService($config, $this->adapter);
        $this->expectException(Exception::class);
        $content = $TemplateService->getContent('GenericTemplate', $data);
    }
    public function testEmailTemplateWithInvalidOrgUUId()
    {
        $data = ['username' => 'John','orgid'=>"b6499a34-c100-4e41-bece-5822adca3844"];
        $config = $this->getApplicationConfig();
        $TemplateService = new TemplateService($config, $this->adapter);
        $this->expectException(Exception::class);
        $content = $TemplateService->getContent('GenericTemplate', $data);
    }
}
