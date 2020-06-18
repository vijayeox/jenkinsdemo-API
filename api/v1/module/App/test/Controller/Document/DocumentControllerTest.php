<?php
namespace App;

use App\Controller\DocumentController;
use Zend\Stdlib\ArrayUtils;
use Form\Model\Field;
use Oxzion\Test\ControllerTest;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Adapter\Platform\Mysql;
use Zend\Db\Adapter\Adapter;
use PHPUnit\DbUnit\TestCaseTrait;
use PHPUnit\DbUnit\DataSet\YamlDataSet;
use Oxzion\Utils\FileUtils;
class DocumentControllerTest extends ControllerTest
{
    public function setUp() : void
    {
        $this->loadConfig();
        $config = $this->getApplicationConfig();
        parent::setUp();
        $this->data = array(
            'UUID' => '1c0f0bc6-df6a-11e9-8a34-2a2ae2dbcce4',
        );
        $this->docFile = $config['APP_DOCUMENT_FOLDER'].$this->data['UUID'];
        $docLocation = __DIR__."/../../Dataset/Files";

        if(FileUtils::fileExists($this->docFile)){
                FileUtils::rmDir($this->docFile);
        }
        FileUtils::symlink($docLocation, $this->docFile);
    }


    public function tearDown(): void
    {
        parent::tearDown();
        $config = $this->getApplicationConfig();
        $this->data = array(
            'UUID' => '1c0f0bc6-df6a-11e9-8a34-2a2ae2dbcce4',
        );
        $path = $config['APP_DOCUMENT_FOLDER'].$this->data['UUID'];
        if (is_link($path)) {
            unlink($path);
        }
    }


    public function getDataSet()
    {
        $dataset = new YamlDataSet(dirname(__FILE__)."/../../Dataset/Workflow.yml");
        return $dataset;
    }


    public function testgetDocument(){
        $this->initAuthToken($this->adminUser);
        $this->dispatch("/app/1c0f0bc6-df6a-11e9-8a34-2a2ae2dbcce4/document/certificate.pdf?docPath=1c0f0bc6-df6a-11e9-8a34-2a2ae2dbcce4/certificate.pdf", 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('App');
        $this->assertControllerName(DocumentController::class); // as specified in router's controller name alias
        $this->assertControllerClass('DocumentController');
        $this->assertMatchedRouteName('getFileDocuments');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
    }  

     public function testDocumentNotFound(){
        $this->initAuthToken($this->adminUser);
        $this->dispatch("/app/1c0f0bc6-df6a-11e9-8a34-2a2ae2dbcce4/document/cerficate.pdf?docPath=1c0f0bc6-df6a-11e9-8a34-2a2ae2dbcce4/ceficate.pdf", 'GET');
        $this->assertResponseStatusCode(404);
        $this->assertModuleName('App');
        $this->assertControllerName(DocumentController::class); // as specified in router's controller name alias
        $this->assertControllerClass('DocumentController');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $this->assertMatchedRouteName('getFileDocuments');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'File Not Found');
    }  
}
