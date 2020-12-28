<?php
namespace Esign\Controller;

use Oxzion\Test\ControllerTest;
use PHPUnit\DbUnit\DataSet\DefaultDataSet;
use Oxzion\Service\EsignService;
use Mockery;

class EsignCallbackControllerTest extends ControllerTest {
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

    public function testSignEvent()
    {
        $this->initAuthToken($this->adminUser);
        $data  = array('documentId'=>'fc97b594-a287-4592-a990-6850515fceb1',
                       'eventType' =>'FINALIZED');
        $mockEsignService = Mockery::mock('\Oxzion\Service\EsignService');
        $mockEsignService->expects('signEvent')->with($data['documentId'],'FINALIZED')->once()->andReturn(array("downloadUrl"=> __DIR__."../../../lib/Oxzion/test/Service/Files/mockpdf_with_field.pdf"));
        $this->setService(EsignService::class, $mockEsignService);
        $this->dispatch('/esign/event', 'POST',$data);
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('Esign');
        $this->assertControllerName(EsignCallbackController::class); // as specified in router's controller name alias
        $this->assertControllerClass('EsignCallbackController');
        $this->assertMatchedRouteName('esignCallback');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $this->assertEquals($content['status'], 'success');
    }

}