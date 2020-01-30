<?php
namespace App;

use App\Controller\AppDelegateController;
use Oxzion\Test\ControllerTest;
use PHPUnit\DbUnit\DataSet\YamlDataSet;

class AppDelegateControllerTest extends ControllerTest
{
    public function setUp() : void
    {
        $this->loadConfig();
        $this->data = array(
            "appName" => 'ox_client_app',
            'UUID' => '1c0f0bc6-df6a-11e9-8a34-2a2ae2dbcce4',
            'description' => 'FirstAppOfTheClient',
        );
        $path = __DIR__.'/../../../../data/delegate/'.$this->data['UUID'];
        if (!is_link($path)) {
            symlink(__DIR__.'/../../../../../../clients/DiveInsurance/data/delegate/',$path);
        }
        parent::setUp();
    }

    public function tearDown() : void
    {
        parent::tearDown();
        $path = __DIR__.'/../../../../data/delegate/'.$this->data['UUID'];
        if (is_link($path)) {
            unlink($path);
        }
    }

    public function getDataSet()
    {
        $dataset = new YamlDataSet(dirname(__FILE__)."/../Dataset/App.yml");
        return $dataset;
    }


    public function testDelegateExecute()
    {
        $data = array("Checking App Delegate","Checking1");
        $appId = "1c0f0bc6-df6a-11e9-8a34-2a2ae2dbcce4";
        $delegate = 'IndividualLiabilityImpl';
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/app/'.$appId.'/delegate/'.$delegate, 'POST', $data);
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('App');
        $this->assertControllerName(AppDelegateController::class); // as specified in router's controller name alias
        $this->assertControllerClass('AppDelegateController');
        $this->assertMatchedRouteName('appDelegate');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals("success", $content['status']);
        $this->assertEquals("Checking App Delegate", $content['data'][0]);
    }

    public function testInvalidDelegate()
    {
        $data = array("Checking App Delegate","Checking1");
        $appId = "debf3d35-a0ee-49d3-a8ac-8e480be9dac7";
        $delegate = 'IndividualLiabilityImpl123';
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/app/'.$appId.'/delegate/'.$delegate, 'POST', $data);
        $this->assertResponseStatusCode(404);
        $this->assertModuleName('App');
        $this->assertControllerName(AppDelegateController::class); // as specified in router's controller name alias
        $this->assertControllerClass('AppDelegateController');
        $this->assertMatchedRouteName('appDelegate');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals("error", $content['status']);
        $this->assertEquals("Delegate not found", $content['message']);
    }
    
}
