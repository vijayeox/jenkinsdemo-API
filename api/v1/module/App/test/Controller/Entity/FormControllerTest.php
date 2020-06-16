<?php
namespace App;

use App\Controller\FormController;
use Oxzion\Test\ControllerTest;
use PHPUnit\DbUnit\DataSet\YamlDataSet;

class FormControllerTest extends ControllerTest
{
    public function setUp(): void
    {
        $this->loadConfig();
        parent::setUp();
    }

    public function getDataSet()
    {
        $dataset = new YamlDataSet(dirname(__FILE__) . "/../../Dataset/Workflow.yml");
        return $dataset;
    }

    public function testGetList()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/app/1c0f0bc6-df6a-11e9-8a34-2a2ae2dbcce4/form', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(count($content['data']), 2);
        $this->assertEquals($content['data'][0]['form_id'], '465c8ff8-df82-11e9-8a34-2a2ae2dbcce4');
        $this->assertEquals($content['data'][0]['name'], 'Task');
        $this->assertEquals($content['data'][0]['entity_id'], 'd23d0c68-98c9-11e9-adc5-308d99c9145b');
        $this->assertEquals($content['data'][1]['form_id'], '465c9142-df82-11e9-8a34-2a2ae2dbcce4');
        $this->assertEquals($content['data'][1]['name'], 'Test Form 2');
        $this->assertEquals($content['data'][1]['entity_id'], 'd23d0c68-98c9-11e9-adc5-308d99c9145b');
    }

    protected function setDefaultAsserts()
    {
        $this->assertModuleName('App');
        $this->assertControllerName(FormController::class); // as specified in router's controller name alias
        $this->assertControllerClass('FormController');
        $this->assertMatchedRouteName('appform');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
    }

    private function getFieldUuid()
    {
        $selctQuery = "SELECT * from ox_form where id=1";
        $selectResult = $this->executeQueryTest($selctQuery);
        return $selectResult;
    }

    public function testGet()
    {
        $this->initAuthToken($this->adminUser);
        $selectResult = $this->getFieldUuid();
        $this->dispatch('/app/1c0f0bc6-df6a-11e9-8a34-2a2ae2dbcce4/form/' . $selectResult[0]['uuid'], 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['uuid'], '465c8ff8-df82-11e9-8a34-2a2ae2dbcce4');
        $this->assertEquals($content['data']['name'], 'Task');
    }

    public function testGetNotFound()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/app/1c0f0bc6-df6a-11e9-8a34-2a2ae2dbcce4/form/ef993838-df86-11e9-8a34-2a2ae2dbcce4', 'GET');
        $this->assertResponseStatusCode(404);
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }

    public function testCreate()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['name' => 'Form create', 'app_id' => 1, 'entity_id' => 1, 'template' => '{"_id":"5d3afa5ccc5f75b982967721","type":"form","tags":[],"owner":"5cd2e0d62363f67ddc9489c6","components":[{"autofocus":false,"input":true,"tableView":true,"inputType":"text","inputMask":"","label":"Name","key":"name","placeholder":"","prefix":"","suffix":"","multiple":false,"defaultValue":"","protected":false,"unique":false,"persistent":true,"hidden":false,"clearOnHide":true,"spellcheck":true,"validate":{"required":false,"minLength":"","maxLength":"","pattern":"","custom":"","customPrivate":false},"conditional":{"show":"","when":null,"eq":""},"type":"textfield","labelPosition":"top","inputFormat":"plain","tags":[],"properties":{}},{"autofocus":false,"input":true,"label":"Submit","tableView":false,"key":"submit","size":"md","leftIcon":"","rightIcon":"","block":false,"action":"submit","disableOnInvalid":false,"theme":"primary","type":"button"}],"revisions":"","_vid":0,"title":"SampleFormForTests","display":"form","access":[{"roles":["5ce43aa430776b950569301d","5ce43aa430776b4a5969301e","5ce43aa430776bdfb569301f"],"type":"read_all"}],"submissionAccess":[],"settings":{},"properties":{},"name":"sampleFormForTests","path":"sampleformfortests","project":"5ce43aa430776b2aff69301c","created":"2019-07-26T13:04:28.211Z","modified":"2019-07-26T13:04:28.214Z","machineName":"testdivehub:sampleFormForTests"}'];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/app/1c0f0bc6-df6a-11e9-8a34-2a2ae2dbcce4/form', 'POST', $data);
        $this->assertResponseStatusCode(201);
        $this->setDefaultAsserts();
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['name'], 'Form create');
    }

    public function testCreateWithOutNameFailure()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['app_id' => 1, 'entity_id' => 1, 'template' => '{"_id":"5d3afa5ccc5f75b982967721","type":"form","tags":[],"owner":"5cd2e0d62363f67ddc9489c6","components":[{"autofocus":false,"input":true,"tableView":true,"inputType":"text","inputMask":"","label":"Name","key":"name","placeholder":"","prefix":"","suffix":"","multiple":false,"defaultValue":"","protected":false,"unique":false,"persistent":true,"hidden":false,"clearOnHide":true,"spellcheck":true,"validate":{"required":false,"minLength":"","maxLength":"","pattern":"","custom":"","customPrivate":false},"conditional":{"show":"","when":null,"eq":""},"type":"textfield","labelPosition":"top","inputFormat":"plain","tags":[],"properties":{}},{"autofocus":false,"input":true,"label":"Submit","tableView":false,"key":"submit","size":"md","leftIcon":"","rightIcon":"","block":false,"action":"submit","disableOnInvalid":false,"theme":"primary","type":"button"}],"revisions":"","_vid":0,"title":"SampleFormForTests","display":"form","access":[{"roles":["5ce43aa430776b950569301d","5ce43aa430776b4a5969301e","5ce43aa430776bdfb569301f"],"type":"read_all"}],"submissionAccess":[],"settings":{},"properties":{},"path":"sampleformfortests","project":"5ce43aa430776b2aff69301c","created":"2019-07-26T13:04:28.211Z","modified":"2019-07-26T13:04:28.214Z","machineName":"testdivehub:sampleFormForTests"}'];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/app/1c0f0bc6-df6a-11e9-8a34-2a2ae2dbcce4/form', 'POST', null);
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'Validation Errors');
        $this->assertEquals($content['data']['errors']['name'], 'required');
    }

    public function testCreateAccess()
    {
        $this->initAuthToken($this->employeeUser);
        $data = ['name' => 'Test Form 1', 'entity_id' => 1, 'app_id' => 1, 'template' => '{"_id":"5d3afa5ccc5f75b982967721","type":"form","tags":[],"owner":"5cd2e0d62363f67ddc9489c6","components":[{"autofocus":false,"input":true,"tableView":true,"inputType":"text","inputMask":"","label":"Name","key":"name","placeholder":"","prefix":"","suffix":"","multiple":false,"defaultValue":"","protected":false,"unique":false,"persistent":true,"hidden":false,"clearOnHide":true,"spellcheck":true,"validate":{"required":false,"minLength":"","maxLength":"","pattern":"","custom":"","customPrivate":false},"conditional":{"show":"","when":null,"eq":""},"type":"textfield","labelPosition":"top","inputFormat":"plain","tags":[],"properties":{}},{"autofocus":false,"input":true,"label":"Submit","tableView":false,"key":"submit","size":"md","leftIcon":"","rightIcon":"","block":false,"action":"submit","disableOnInvalid":false,"theme":"primary","type":"button"}],"revisions":"","_vid":0,"title":"SampleFormForTests","display":"form","access":[{"roles":["5ce43aa430776b950569301d","5ce43aa430776b4a5969301e","5ce43aa430776bdfb569301f"],"type":"read_all"}],"submissionAccess":[],"settings":{},"properties":{},"name":"sampleFormForTests","path":"sampleformfortests","project":"5ce43aa430776b2aff69301c","created":"2019-07-26T13:04:28.211Z","modified":"2019-07-26T13:04:28.214Z","machineName":"testdivehub:sampleFormForTests"}'];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/app/1c0f0bc6-df6a-11e9-8a34-2a2ae2dbcce4/form', 'POST', null);
        $this->assertResponseStatusCode(401);
        $this->assertModuleName('App');
        $this->assertControllerName(FormController::class); // as specified in router's controller name alias
        $this->assertControllerClass('FormController');
        $this->assertMatchedRouteName('appform');
        $this->assertResponseHeaderContains('content-type', 'application/json');
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'You have no Access to this API');
    }

    public function testUpdate()
    {
        $data = ['name' => 'Test Form 1', 'entity_id' => 1, 'app_id' => 1, 'template' => '{"_id":"5d3afa5ccc5f75b982967721","type":"form","tags":[],"owner":"5cd2e0d62363f67ddc9489c6","components":[{"autofocus":false,"input":true,"tableView":true,"inputType":"text","inputMask":"","label":"Name","key":"name","placeholder":"","prefix":"","suffix":"","multiple":false,"defaultValue":"","protected":false,"unique":false,"persistent":true,"hidden":false,"clearOnHide":true,"spellcheck":true,"validate":{"required":false,"minLength":"","maxLength":"","pattern":"","custom":"","customPrivate":false},"conditional":{"show":"","when":null,"eq":""},"type":"textfield","labelPosition":"top","inputFormat":"plain","tags":[],"properties":{}},{"autofocus":false,"input":true,"label":"Submit","tableView":false,"key":"submit","size":"md","leftIcon":"","rightIcon":"","block":false,"action":"submit","disableOnInvalid":false,"theme":"primary","type":"button"}],"revisions":"","_vid":0,"title":"SampleFormForTests","display":"form","access":[{"roles":["5ce43aa430776b950569301d","5ce43aa430776b4a5969301e","5ce43aa430776bdfb569301f"],"type":"read_all"}],"submissionAccess":[],"settings":{},"properties":{},"name":"sampleFormForTests","path":"sampleformfortests","project":"5ce43aa430776b2aff69301c","created":"2019-07-26T13:04:28.211Z","modified":"2019-07-26T13:04:28.214Z","machineName":"testdivehub:sampleFormForTests"}'];
        $this->initAuthToken($this->adminUser);
        $this->setJsonContent(json_encode($data));
        $selectResult = $this->getFieldUuid();
        $this->dispatch('/app/1c0f0bc6-df6a-11e9-8a34-2a2ae2dbcce4/form/' . $selectResult[0]['uuid'], 'PUT', null);
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['name'], $data['name']);
    }

    public function testUpdateRestricted()
    {
        $data = ['name' => 'Test Form 1', 'app_id' => 1];
        $this->initAuthToken($this->employeeUser);
        $this->setJsonContent(json_encode($data));
        $selectResult = $this->getFieldUuid();
        $this->dispatch('/app/1c0f0bc6-df6a-11e9-8a34-2a2ae2dbcce4/form/' . $selectResult[0]['uuid'], 'PUT', null);
        $this->assertResponseStatusCode(401);
        $this->assertModuleName('App');
        $this->assertControllerName(FormController::class); // as specified in router's controller name alias
        $this->assertControllerClass('FormController');
        $this->assertMatchedRouteName('appform');
        $this->assertResponseHeaderContains('content-type', 'application/json');
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'You have no Access to this API');
    }

    public function testUpdateNotFound()
    {
        $data = ['name' => 'Test Form 1', 'app_id' => 1];
        $this->initAuthToken($this->adminUser);
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/app/1c0f0bc6-df6a-11e9-8a34-2a2ae2dbcce4/form/ef9936ee-df86-11e9-8a34-2a2ae2dbcce4', 'PUT', null);
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }

    public function testDelete()
    {
        $this->initAuthToken($this->adminUser);
        $selectResult = $this->getFieldUuid();
        $this->dispatch('/app/1c0f0bc6-df6a-11e9-8a34-2a2ae2dbcce4/form/' . $selectResult[0]['uuid'], 'DELETE');
        $this->assertResponseStatusCode(405);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
    }

    public function testDeleteNotFound()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/app/1c0f0bc6-df6a-11e9-8a34-2a2ae2dbcce4/form/ef99352c-df86-11e9-8a34-2a2ae2dbcce4', 'DELETE');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(405);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
    }
}
