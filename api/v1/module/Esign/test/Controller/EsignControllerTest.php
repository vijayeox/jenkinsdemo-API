<?php
namespace Esign\Controller;

use Oxzion\Test\ControllerTest;
use PHPUnit\DbUnit\DataSet\DefaultDataSet;
use Oxzion\Service\EsignService;
use Mockery;

class EsignControllerTest extends ControllerTest {
	public function setUp() : void
    {
        $this->loadConfig();
        parent::setUp();
    }

    public function tearDown() : void{
        parent::tearDown();
    } 

    public function getDataSet()
    {
        return new DefaultDataSet();
    }

    public function testFileUpdate()
    {
        $this->initAuthToken($this->adminUser);
        $docId = "fc97b594-a287-4592-a990-6850515fceb1";
        $mockEsignService = Mockery::mock('\Oxzion\Service\EsignService');
        $mockEsignService->expects('getDocumentStatus')->with($docId)->once()->andReturn("READY_FOR_SIGNATURE");
        $this->setService(EsignService::class, $mockEsignService);
        $this->dispatch('/status/'.$docId, 'GET');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('Esign');
        $this->assertControllerName(EsignController::class); // as specified in router's controller name alias
        $this->assertControllerClass('EsignController');
        $this->assertMatchedRouteName('esignStatus');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['status'], 'READY_FOR_SIGNATURE');
    }

}