<?php
namespace Callback;

use Callback\Controller\ChatCallbackController;
use Callback\Service\ChatService;
use Mockery;
use Oxzion\Test\ControllerTest;
use PHPUnit\DbUnit\DataSet\DefaultDataSet;

class ChatCallbackControllerTest extends ControllerTest
{
    public function setUp(): void
    {
        $this->loadConfig();
        parent::setUp();
    }
    public function getDataSet()
    {
        return new DefaultDataSet();
    }

    private function getMockRestClientForChatService()
    {
        $chatService = $this->getApplicationServiceLocator()->get(Service\ChatService::class);
        $mockRestClient = Mockery::mock('Oxzion\Utils\RestClient');
        $chatService->setRestClient($mockRestClient);
        return $mockRestClient;
    }
    public function testAddOrg()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['orgname' => 'Teams-1', 'status' => 'Active'];
        if (enableMattermost == 0) {
            $mockRestClient = $this->getMockRestClientForChatService();
            $mockRestClient->expects('postWithHeader')->with("api/v4/teams", array("name" => "teams1", "display_name" => "teams1", "type" => "O"), Mockery::any())->once()->andReturn(array("body" => json_encode(array("name" => "teams-1", "display_name" => "teams-1"))));
        }
        $this->dispatch('/callback/chat/addorg', 'POST', $data);
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('Callback');
        $this->assertControllerName(ChatcallbackController::class); // as specified in router's controller name alias
        $this->assertControllerClass('ChatcallbackController');
        $this->assertMatchedRouteName('addcallback');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
    }

    public function testAddOrgAlreadyExists()
    {
        $data = ['orgname' => 'Teams-1', 'status' => 'Active'];
        $this->initAuthToken($this->adminUser);
        $this->setJsonContent(json_encode($data));
        if (enableMattermost == 0) {
            $mockRestClient = $this->getMockRestClientForChatService();
            $exception = Mockery::Mock('GuzzleHttp\Exception\ClientException');
            $mockRestClient->expects('postWithHeader')->with("api/v4/teams", array("name" => 'teams1', "display_name" => 'teams1', "type" => 'O'), Mockery::any())->once()->andThrow($exception);
        }
        $this->dispatch('/callback/chat/addorg', 'POST', $data);
        $this->assertResponseStatusCode(400);
        $this->assertMatchedRouteName('addcallback');
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }

    public function testAddOrgNotFound()
    {
        $data = ['status' => 'Active'];
        $this->initAuthToken($this->adminUser);
        $this->setJsonContent(json_encode($data));
        if (enableMattermost == 0) {
            $mockRestClient = $this->getMockRestClientForChatService();
            $exception = Mockery::Mock('GuzzleHttp\Exception\ClientException');
            $mockRestClient->expects('postWithHeader')->with("api/v4/teams", array("status" => "Active"), Mockery::any())->once()->andThrow($exception);
        }
        $this->dispatch('/callback/chat/addorg', 'POST', $data);
        $this->assertResponseStatusCode(400);
        $this->assertMatchedRouteName('addcallback');
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }

    public function testUpdateOrg()
    {
        $data = ['new_orgname' => 'new-oxzion1', 'old_orgname' => 'teams-1', 'status' => 'Active'];
        $this->initAuthToken($this->adminUser);
        $this->setJsonContent(json_encode($data));
        if (enableMattermost == 0) {
            $mockRestClient = $this->getMockRestClientForChatService();
            $mockRestClient->expects('get')->with("api/v4/teams/name/teams1", array(), Mockery::any())->once()->andReturn(json_encode(array("name" => "teams1", "display_name" => 'teams1', "id" => 121)));
            $mockRestClient->expects('put')->with("api/v4/teams/121", array("name" => 'newoxzion1', "display_name" => 'newoxzion1', "id" => 121), Mockery::any())->once()->andReturn(json_encode(array('name' => "newoxzion1", "display_name" => 'newoxzion1')));
        }

        $this->dispatch('/callback/chat/updateorg', 'POST', $data);
        $this->assertResponseStatusCode(200);
        $this->assertMatchedRouteName('updatecallback');
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
    }

    public function testUpdateOrgNameNotExists()
    {
        $data = ['old_orgname' => 'Team Vantage123', 'status' => 'Active'];
        $this->initAuthToken($this->adminUser);
        $this->setJsonContent(json_encode($data));
        if (enableMattermost == 0) {
            $mockRestClient = $this->getMockRestClientForChatService();
            $exception = Mockery::Mock('GuzzleHttp\Exception\ClientException');
            $mockRestClient->expects('get')->with("api/v4/teams/name/teams1", array(), Mockery::any())->once()->andReturn(json_encode(array("name" => "teams1", "display_name" => 'teams1', "id" => 121)));
            $mockRestClient->expects('put')->with("api/v4/teams/121", array("id" => 121), Mockery::any())->once()->andThrow($exception);
        }
        $this->dispatch('/callback/chat/updateorg', 'POST', $data);
        $this->assertResponseStatusCode(404);
        $this->assertMatchedRouteName('updatecallback');
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }
    // // No mock test
    public function testUpdateOrgOldNameNotExists()
    {
        $data = ['new_orgname' => 'Vantage Vantage', 'status' => 'Active'];
        $this->initAuthToken($this->adminUser);
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/callback/chat/updateorg', 'POST', $data);
        $this->assertResponseStatusCode(404);
        $this->assertMatchedRouteName('updatecallback');
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }

    public function testAddUserToOrgBothExists()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['username' => $this->managerUser, 'orgname' => 'teams-1', 'status' => 'Active'];
        if (enableMattermost == 0) {
            $mockRestClient = $this->getMockRestClientForChatService();
            $mockRestClient->expects('get')->with("api/v4/teams/name/teams1", array(), Mockery::any())->once()->andReturn(json_encode(array('name' => "teamoxzion", "display_name" => 'teamoxzion', "id" => 121)));
            $mockRestClient->expects('get')->with("api/v4/users/username/" . $this->managerUser, array(), Mockery::any())->once()->andReturn(json_encode(array('name' => $this->managerUser, "id" => 1)));
            $mockRestClient->expects('postWithHeader')->with("api/v4/teams/121/members", array('team_id' => 121, 'user_id' => 1), Mockery::any())->once()->andReturn(array("body" => json_encode(array("team_id" => 121, "user_id" => 1, "roles" => "team_user"))));
        }

        $this->dispatch('/callback/chat/adduser', 'POST', $data);
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('Callback');
        $this->assertControllerName(ChatcallbackController::class); // as specified in router's controller name alias
        $this->assertControllerClass('ChatcallbackController');
        $this->assertMatchedRouteName('addusercallback');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
    }

    public function testAddUserToOrgForNewUser()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['username' => 'shravani', 'orgname' => 'Teams 1', 'status' => 'Active'];
        if (enableMattermost == 0) {
            $mockRestClient = $this->getMockRestClientForChatService();
            $request = Mockery::Mock('Psr\Http\Message\RequestInterface');
            $response = Mockery::Mock('Psr\Http\Message\ResponseInterface');
            $response->expects('getStatusCode')->andReturn(404);
            $mockRestClient->expects('get')->with("api/v4/teams/name/teams1", array(), Mockery::any())->once()->andReturn(json_encode(array('name' => "teamoxzion", "display_name" => 'teamoxzion', "id" => 121)));
            $mockRestClient->expects('get')->with("api/v4/users/username/shravani", array(), Mockery::any())->once()->andThrow(new \GuzzleHttp\Exception\ClientException('"id" : "store.sql_user.get_by_username.app_error"', $request, $response));
            $mockRestClient->expects('postWithHeader')->with("api/v4/users", array("email" => "shravani@gmail.com", "username" => "shravani", "first_name" => "shravani", "password" => md5('shravani')), Mockery::any())->once()->andReturn(array("body" => json_encode(array("id" => 2, "username" => "shravani", "email" => "shravani@gmail.com", "first_name" => "shravani"))));
            $mockRestClient->expects('postWithHeader')->with("api/v4/teams/121/members", array('team_id' => 121, 'user_id' => 2), Mockery::any())->once()->andReturn(array("body" => json_encode(array("team_id" => 121, "user_id" => 2, "roles" => "team_user"))));
        }
        $this->dispatch('/callback/chat/adduser', 'POST', $data);
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('Callback');
        $this->assertControllerName(ChatcallbackController::class); // as specified in router's controller name alias
        $this->assertControllerClass('ChatcallbackController');
        $this->assertMatchedRouteName('addusercallback');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
    }

    public function testAddUserToOrgNetworkIssue()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['username' => 'ramya', 'orgname' => 'Teams 1', 'status' => 'Active'];
        if (enableMattermost == 0) {
            $mockRestClient = $this->getMockRestClientForChatService();
            $request = Mockery::Mock('Psr\Http\Message\RequestInterface');
            $response = Mockery::Mock('Psr\Http\Message\ResponseInterface');
            $response->expects('getStatusCode')->andReturn(500);
            $mockRestClient->expects('get')->with("api/v4/teams/name/teams1", array(), Mockery::any())->once()->andReturn(json_encode(array('name' => "teamoxzion", "display_name" => 'teamoxzion', "id" => 121)));
            $mockRestClient->expects('get')->with("api/v4/users/username/ramya", array(), Mockery::any())->once()->andThrow(new \GuzzleHttp\Exception\ClientException('', $request, $response));
        }
        if (enableMattermost == 1) {
            $this->assertTrue(true);
        } else {
            $this->dispatch('/callback/chat/adduser', 'POST', $data);
            $this->assertResponseStatusCode(500);
            $this->assertModuleName('Callback');
            $this->assertControllerName(ChatcallbackController::class); // as specified in router's controller name alias
            $this->assertControllerClass('ChatcallbackController');
            $this->assertMatchedRouteName('addusercallback');
            $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
            $content = (array) json_decode($this->getResponse()->getContent(), true);
            $this->assertEquals($content['status'], 'error');
        }
    }

    public function testAddUserToOrgForNewOrg()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['username' => $this->managerUser, 'orgname' => 'Teams new1', 'status' => 'Active'];
        if (enableMattermost == 0) {
            $mockRestClient = $this->getMockRestClientForChatService();
            $request = Mockery::Mock('Psr\Http\Message\RequestInterface');
            $response = Mockery::Mock('Psr\Http\Message\ResponseInterface');
            $response->expects('getStatusCode')->andReturn(404);
            $mockRestClient->expects('get')->with("api/v4/teams/name/teamsnew1", array(), Mockery::any())->once()->andThrow(new \GuzzleHttp\Exception\ClientException('"id" : "store.sql_team.get_by_name.app_error"', $request, $response));
            $mockRestClient->expects('postWithHeader')->with("api/v4/teams", array('name' => "teamsnew1", 'display_name' => "teamsnew1", 'type' => 'O'), Mockery::any())->once()->andReturn(array("body" => json_encode(array("id" => 125, "name" => "teamsnew1", "display_name" => "teamsnew1"))));
            $mockRestClient->expects('get')->with("api/v4/users/username/" . $this->managerUser, array(), Mockery::any())->once()->andReturn(json_encode(array('name' => $this->managerUser, "id" => 1)));
            $mockRestClient->expects('postWithHeader')->with("api/v4/teams/125/members", array('team_id' => 125, 'user_id' => 1), Mockery::any())->once()->andReturn(array("body" => json_encode(array("team_id" => 125, "user_id" => 1, "roles" => "team_user"))));
        }
        $this->dispatch('/callback/chat/adduser', 'POST', $data);
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('Callback');
        $this->assertControllerName(ChatcallbackController::class); // as specified in router's controller name alias
        $this->assertControllerClass('ChatcallbackController');
        $this->assertMatchedRouteName('addusercallback');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
    }

    public function testAddUserToOrgOrgNotFound()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['username' => $this->managerUser, 'orgname' => 'Orgo organization', 'status' => 'Active'];
        if (enableMattermost == 0) {
            $mockRestClient = $this->getMockRestClientForChatService();
            $request = Mockery::Mock('Psr\Http\Message\RequestInterface');
            $response = Mockery::Mock('Psr\Http\Message\ResponseInterface');
            $response->expects('getStatusCode')->andReturn(500);
            $mockRestClient->expects('get')->with("api/v4/teams/name/orgoorganization", array(), Mockery::any())->once()->andThrow(new \GuzzleHttp\Exception\ClientException('', $request, $response));
        }
        if (enableMattermost == 1) {
            $this->assertTrue(true);
        } else {
            $this->dispatch('/callback/chat/adduser', 'POST', $data);
            $this->assertResponseStatusCode(500);
            $this->assertModuleName('Callback');
            $this->assertControllerName(ChatcallbackController::class); // as specified in router's controller name alias
            $this->assertControllerClass('ChatcallbackController');
            $this->assertMatchedRouteName('addusercallback');
            $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
            $content = (array) json_decode($this->getResponse()->getContent(), true);
            $this->assertEquals($content['status'], 'error');
        }
    }

    public function testAddUserToOrgUserAndOrgNotFound()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['status' => 'Active'];
        if (enableMattermost == 0) {
            $mockRestClient = $this->getMockRestClientForChatService();
            $exception = Mockery::Mock('GuzzleHttp\Exception\ClientException');
            $mockRestClient->expects('postWithHeader')->with("api/v4/teams", array("status" => "Active"), Mockery::any())->once()->andThrow($exception);
        }
        $this->dispatch('/callback/chat/adduser', 'POST', $data);
        $this->assertResponseStatusCode(400);
        $this->assertModuleName('Callback');
        $this->assertControllerName(ChatcallbackController::class); // as specified in router's controller name alias
        $this->assertControllerClass('ChatcallbackController');
        $this->assertMatchedRouteName('addusercallback');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }

    public function testCreateChannel()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['orgname' => 'teams-1', 'groupname' => 'Channel Private-1', 'status' => 'Active'];
        if (enableMattermost == 0) {
            $mockRestClient = $this->getMockRestClientForChatService();
            $mockRestClient->expects('get')->with("api/v4/teams/name/teams1", array(), Mockery::any())->once()->andReturn(json_encode(array('name' => "teams1", "display_name" => 'teams1', "id" => 121)));
            $mockRestClient->expects('postWithHeader')->with("api/v4/channels", array('team_id' => 121, 'name' => "channelprivate1", 'display_name' => "channelprivate1", 'type' => 'P'), Mockery::any())->once()->andReturn(array("body" => json_encode(array("id" => 234, "name" => "channelprivate1", "display_name" => "channelprivate1", "team_id" => 121))));
        }

        $this->dispatch('/callback/chat/createchannel', 'POST', $data);
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('Callback');
        $this->assertControllerName(ChatcallbackController::class); // as specified in router's controller name alias
        $this->assertControllerClass('ChatcallbackController');
        $this->assertMatchedRouteName('createchannelcallback');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
    }

    public function testCreateChannelForNewOrg()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['orgname' => 'New Org-1', 'groupname' => 'New Channel-Test', 'status' => 'Active'];
        if (enableMattermost == 0) {
            $mockRestClient = $this->getMockRestClientForChatService();
            $request = Mockery::Mock('Psr\Http\Message\RequestInterface');
            $response = Mockery::Mock('Psr\Http\Message\ResponseInterface');
            $response->expects('getStatusCode')->andReturn(404);
            $mockRestClient->expects('get')->with("api/v4/teams/name/neworg1", array(), Mockery::any())->once()->andThrow(new \GuzzleHttp\Exception\ClientException('"id" : "store.sql_team.get_by_name.app_error"', $request, $response));
            $mockRestClient->expects('postWithHeader')->with("api/v4/teams", array('name' => "neworg1", 'display_name' => "neworg1", 'type' => 'O'), Mockery::any())->once()->andReturn(array("body" => json_encode(array("id" => 130, "name" => "neworg1", "display_name" => "neworg1"))));
            $mockRestClient->expects('postWithHeader')->with("api/v4/channels", array('team_id' => 130, 'name' => "newchanneltest", 'display_name' => "newchanneltest", 'type' => 'P'), Mockery::any())->once()->andReturn(array("body" => json_encode(array("id" => 250, "name" => "newchanneltest", "display_name" => "newchanneltest", "team_id" => 130))));
        }
        $this->dispatch('/callback/chat/createchannel', 'POST', $data);
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('Callback');
        $this->assertControllerName(ChatcallbackController::class); // as specified in router's controller name alias
        $this->assertControllerClass('ChatcallbackController');
        $this->assertMatchedRouteName('createchannelcallback');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
    }

    public function testCreateChannelNetworkIssue()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['orgname' => 'Orgo organization', 'groupname' => 'New Channel-Test', 'status' => 'Active'];
        if (enableMattermost == 0) {
            $mockRestClient = $this->getMockRestClientForChatService();
            $request = Mockery::Mock('Psr\Http\Message\RequestInterface');
            $response = Mockery::Mock('Psr\Http\Message\ResponseInterface');
            $response->expects('getStatusCode')->andReturn(500);
            $mockRestClient->expects('get')->with("api/v4/teams/name/orgoorganization", array(), Mockery::any())->once()->andThrow(new \GuzzleHttp\Exception\ClientException('', $request, $response));
        }
        if (enableMattermost == 1) {
            $this->assertTrue(true);
        } else {
            $this->dispatch('/callback/chat/createchannel', 'POST', $data);
            $this->assertResponseStatusCode(500);
            $this->assertModuleName('Callback');
            $this->assertControllerName(ChatcallbackController::class); // as specified in router's controller name alias
            $this->assertControllerClass('ChatcallbackController');
            $this->assertMatchedRouteName('createchannelcallback');
            $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
            $content = (array) json_decode($this->getResponse()->getContent(), true);
            $this->assertEquals($content['status'], 'error');
        }
    }

    public function testCreateChannelNameNotFound()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['orgname' => 'Teams 1', 'status' => 'Active'];
        $this->setJsonContent(json_encode($data));
        if (enableMattermost == 0) {
            $mockRestClient = $this->getMockRestClientForChatService();
            $exception = Mockery::Mock('GuzzleHttp\Exception\ClientException');
            $mockRestClient->expects('get')->with("api/v4/teams/name/teams1", array(), Mockery::any())->once()->andReturn(json_encode(array('name' => "teams1", "display_name" => 'teams1', "id" => 121)));
            $mockRestClient->expects('postWithHeader')->with("api/v4/channels", array('team_id' => 121, 'type' => 'P'), Mockery::any())->once()->andThrows($exception);
        }
        $this->dispatch('/callback/chat/createchannel', 'POST', $data);
        $this->assertResponseStatusCode(400);
        $this->assertMatchedRouteName('createchannelcallback');
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }
    // No mock test
    public function testCreateChannelTeamNotFound()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['groupname' => 'Private Channel 1', 'status' => 'Active'];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/callback/chat/createchannel', 'POST', $data);
        $this->assertResponseStatusCode(400);
        $this->assertMatchedRouteName('createchannelcallback');
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }

    public function testUpdateChannel()
    {
        $data = ['new_groupname' => 'New Channel Private 1', 'old_groupname' => 'Channel private-1', 'orgname' => 'teams 1', 'status' => 'Active'];
        $this->initAuthToken($this->adminUser);
        $this->setJsonContent(json_encode($data));
        if (enableMattermost == 0) {
            $mockRestClient = $this->getMockRestClientForChatService();
            $mockRestClient->expects('get')->with("api/v4/teams/name/teams1", array(), Mockery::any())->once()->andReturn(json_encode(array('name' => "teams1", "display_name" => 'teams1', "id" => 121)));
            $mockRestClient->expects('get')->with("api/v4/teams/121/channels/name/channelprivate1", array(), Mockery::any())->once()->andReturn(json_encode(array("id" => 234, "name" => "channelprivate1", "display_name" => "channelprivate1", "team_id" => 121)));
            $mockRestClient->expects('put')->with("api/v4/channels/234", array('id' => 234, 'name' => "newchannelprivate1", 'display_name' => "newchannelprivate1"), Mockery::any())->once()->andReturn(json_encode(array("id" => 234, "name" => "newchannelprivate1", "display_name" => "newchannelprivate1", "team_id" => 121)));
        }
        $this->dispatch('/callback/chat/updatechannel', 'POST', $data);
        $this->assertResponseStatusCode(200);
        $this->assertMatchedRouteName('updatechannelcallback');
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
    }

    public function testUpdateChannelNewNameNotFound()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['old_groupname' => 'Channel Private 1', 'orgname' => 'Teams 1', 'status' => 'Active'];
        $this->setJsonContent(json_encode($data));
        if (enableMattermost == 0) {
            $mockRestClient = $this->getMockRestClientForChatService();
            $exception = Mockery::Mock('GuzzleHttp\Exception\ClientException');
            $mockRestClient->expects('get')->with("api/v4/teams/name/teams1", array(), Mockery::any())->once()->andReturn(json_encode(array('name' => "teams1", "display_name" => 'teams1', "id" => 121)));
            $mockRestClient->expects('get')->with("api/v4/teams/121/channels/name/channelprivate1", array(), Mockery::any())->once()->andReturn(json_encode(array("id" => 234, "name" => "channelprivate1", "display_name" => "channelprivate1", "team_id" => 121)));
            $mockRestClient->expects('put')->with("api/v4/channels/234", array('id' => 234), Mockery::any())->once()->andThrow($exception);
        }
        $this->dispatch('/callback/chat/updatechannel', 'POST', $data);
        $this->assertResponseStatusCode(404);
        $this->assertMatchedRouteName('updatechannelcallback');
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }
    // No mock test
    public function testUpdateChannelNameNotFound()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['new_groupname' => 'Oh myh god', 'orgname' => 'Testing Team', 'status' => 'Active'];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/callback/chat/updatechannel', 'POST', $data);
        $this->assertResponseStatusCode(404);
        $this->assertMatchedRouteName('updatechannelcallback');
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }

    public function testAddUserToChannel()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['username' => $this->managerUser, 'orgname' => 'Teams 1', 'groupname' => 'New Channel Private 1', 'status' => 'Active'];
        if (enableMattermost == 0) {
            $mockRestClient = $this->getMockRestClientForChatService();
            $mockRestClient->expects('get')->with("api/v4/teams/name/teams1", array(), Mockery::any())->once()->andReturn(json_encode(array('name' => "teams1", "display_name" => 'teams1', "id" => 121)));
            $mockRestClient->expects('get')->with("api/v4/teams/121/channels/name/newchannelprivate1", array(), Mockery::any())->once()->andReturn(json_encode(array("id" => 234, "name" => "newchannelprivate1", "display_name" => "newchannelprivate1", "team_id" => 121)));
            $mockRestClient->expects('get')->with("api/v4/users/username/" . $this->managerUser, array(), Mockery::any())->once()->andReturn(json_encode(array("id" => 1, "name" => $this->managerUser)));
            $mockRestClient->expects('get')->with("api/v4/teams/121/members/1", array(), Mockery::any())->once()->andReturn(json_encode(array("team_id" => 121, "user_id" => 1)));
            $mockRestClient->expects('postWithHeader')->with("api/v4/channels/234/members", array('user_id' => 1), Mockery::any())->once()->andReturn(array("body" => json_encode(array('channel_id' => 234, "user_id" => 1))));
        }

        $this->dispatch('/callback/chat/addusertochannel', 'POST', $data);
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('Callback');
        $this->assertControllerName(ChatcallbackController::class); // as specified in router's controller name alias
        $this->assertControllerClass('ChatcallbackController');
        $this->assertMatchedRouteName('addusertochannelcallback');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
    }

    public function testAddUserToChannelCreateChannel()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['username' => $this->managerUser, 'orgname' => 'Teams 1', 'groupname' => 'Private1 Private', 'status' => 'Active'];
        if (enableMattermost == 0) {
            $mockRestClient = $this->getMockRestClientForChatService();
            $request = Mockery::Mock('Psr\Http\Message\RequestInterface');
            $response = Mockery::Mock('Psr\Http\Message\ResponseInterface');
            $response->expects('getStatusCode')->andReturn(404);
            $mockRestClient->expects('get')->with("api/v4/teams/name/teams1", array(), Mockery::any())->once()->andReturn(json_encode(array('name' => "teams1", "display_name" => 'teams1', "id" => 121)));
            $mockRestClient->expects('get')->with("api/v4/teams/121/channels/name/private1private", array(), Mockery::any())->once()->andThrow(new \GuzzleHttp\Exception\ClientException('"id" : "store.sql_channel.get_by_name.missing.app_error"', $request, $response));
            $mockRestClient->expects('postWithHeader')->with("api/v4/channels", array('team_id' => 121, 'name' => "private1private", 'display_name' => "private1private", 'type' => 'P'), Mockery::any())->once()->andReturn(array("body" => json_encode(array('id' => 260, "team_id" => 121))));
            $mockRestClient->expects('get')->with("api/v4/users/username/" . $this->managerUser, Mockery::any(), Mockery::any())->once()->andReturn(json_encode(array("id" => 1, "name" => $this->managerUser)));
            $mockRestClient->expects('get')->with("api/v4/teams/121/members/1", array(), Mockery::any())->once()->andReturn(json_encode(array("team_id" => 121, "user_id" => 1)));
            $mockRestClient->expects('postWithHeader')->with("api/v4/channels/260/members", array('user_id' => 1), Mockery::any())->once()->andReturn(array("body" => json_encode(array('channel_id' => 260, "user_id" => 1))));
        }
        $this->dispatch('/callback/chat/addusertochannel', 'POST', $data);
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('Callback');
        $this->assertControllerName(ChatcallbackController::class); // as specified in router's controller name alias
        $this->assertControllerClass('ChatcallbackController');
        $this->assertMatchedRouteName('addusertochannelcallback');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
    }

    public function testAddUserToChannelNetworkIssue()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['username' => $this->managerUser, 'orgname' => 'Teams 1', 'groupname' => 'Channel Chan', 'status' => 'Active'];
        if (enableMattermost == 0) {
            $mockRestClient = $this->getMockRestClientForChatService();
            $request = Mockery::Mock('Psr\Http\Message\RequestInterface');
            $response = Mockery::Mock('Psr\Http\Message\ResponseInterface');
            $response->expects('getStatusCode')->andReturn(500);
            $mockRestClient->expects('get')->with("api/v4/teams/name/teams1", array(), Mockery::any())->once()->andReturn(json_encode(array('name' => "teams1", "display_name" => 'teams1', "id" => 121)));
            $mockRestClient->expects('get')->with("api/v4/teams/121/channels/name/channelchan", array(), Mockery::any())->once()->andThrow(new \GuzzleHttp\Exception\ClientException('', $request, $response));
        }
        if (enableMattermost == 1) {
            $this->assertTrue(true);
        } else {
            $this->dispatch('/callback/chat/addusertochannel', 'POST', $data);
            $this->assertResponseStatusCode(500);
            $this->assertModuleName('Callback');
            $this->assertControllerName(ChatcallbackController::class); // as specified in router's controller name alias
            $this->assertControllerClass('ChatcallbackController');
            $this->assertMatchedRouteName('addusertochannelcallback');
            $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
            $content = (array) json_decode($this->getResponse()->getContent(), true);
            $this->assertEquals($content['status'], 'error');
        }
    }

    public function testAddUserToChannelCreateTeam()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['username' => $this->managerUser, 'orgname' => 'Boscos Team', 'groupname' => 'Private1 Private', 'status' => 'Active'];
        if (enableMattermost == 0) {
            $mockRestClient = $this->getMockRestClientForChatService();
            $request = Mockery::Mock('Psr\Http\Message\RequestInterface');
            $response = Mockery::Mock('Psr\Http\Message\ResponseInterface');
            $response->expects('getStatusCode')->andReturn(404);
            $mockRestClient->expects('get')->with("api/v4/teams/name/boscosteam", array(), Mockery::any())->once()->andThrow(new \GuzzleHttp\Exception\ClientException('"id" : "store.sql_team.get_by_name.app_error"', $request, $response));
            $mockRestClient->expects('postWithHeader')->with("api/v4/teams", array('name' => "boscosteam", 'display_name' => "boscosteam", 'type' => 'O'), Mockery::any())->andReturn(array("body" => json_encode(array('id' => 170, "name" => "boscosteam", "display_name" => "boscosteam"))));
            $mockRestClient->expects('get')->with("api/v4/teams/170/channels/name/private1private", array(), Mockery::any())->once()->andReturn(json_encode(array("id" => 270, "name" => "private1private", "display_name" => "private1private", "team_id" => 170)));
            $mockRestClient->expects('postWithHeader')->with("api/v4/channels", array('team_id' => 121, 'name' => "private1private", 'display_name' => "private1private", 'type' => 'P'), Mockery::any())->once()->andReturn(array("body" => json_encode(array('id' => 270, "team_id" => 170))));
            $mockRestClient->expects('get')->with("api/v4/users/username/" . $this->managerUser, array(), Mockery::any())->once()->andReturn(json_encode(array("id" => 1, "name" => $this->managerUser)));
            $mockRestClient->expects('get')->with("api/v4/teams/170/members/1", array(), Mockery::any())->once()->andReturn(json_encode(array("team_id" => 170, "user_id" => 1)));
            $mockRestClient->expects('postWithHeader')->with("api/v4/channels/270/members", array('user_id' => 1), Mockery::any())->once()->andReturn(array("body" => json_encode(array('channel_id' => 270, "user_id" => 1))));
        }
        $this->dispatch('/callback/chat/addusertochannel', 'POST', $data);
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('Callback');
        $this->assertControllerName(ChatcallbackController::class); // as specified in router's controller name alias
        $this->assertControllerClass('ChatcallbackController');
        $this->assertMatchedRouteName('addusertochannelcallback');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
    }

    public function testAddUserToChannelTeamNotFoundBeczNetworkIssue()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['username' => $this->managerUser, 'orgname' => 'PES Team', 'groupname' => 'Private1 Private', 'status' => 'Active'];
        if (enableMattermost == 0) {
            $mockRestClient = $this->getMockRestClientForChatService();
            $request = Mockery::Mock('Psr\Http\Message\RequestInterface');
            $response = Mockery::Mock('Psr\Http\Message\ResponseInterface');
            $response->expects('getStatusCode')->andReturn(500);
            $mockRestClient->expects('get')->with("api/v4/teams/name/pesteam", array(), Mockery::any())->once()->andThrow(new \GuzzleHttp\Exception\ClientException('', $request, $response));
        }
        if (enableMattermost == 1) {
            $this->assertTrue(true);
        } else {
            $this->dispatch('/callback/chat/addusertochannel', 'POST', $data);
            $this->assertResponseStatusCode(500);
            $this->assertModuleName('Callback');
            $this->assertControllerName(ChatcallbackController::class); // as specified in router's controller name alias
            $this->assertControllerClass('ChatcallbackController');
            $this->assertMatchedRouteName('addusertochannelcallback');
            $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
            $content = (array) json_decode($this->getResponse()->getContent(), true);
            $this->assertEquals($content['status'], 'error');
        }
    }

    public function testAddUserToChannelCreateUser()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['username' => 'Girly', 'orgname' => 'Teams 1', 'groupname' => 'Private1 Private', 'status' => 'Active'];
        if (enableMattermost == 0) {
            $mockRestClient = $this->getMockRestClientForChatService();
            $request = Mockery::Mock('Psr\Http\Message\RequestInterface');
            $response = Mockery::Mock('Psr\Http\Message\ResponseInterface');
            $response->expects('getStatusCode')->andReturn(404);
            $mockRestClient->expects('get')->with("api/v4/teams/name/teams1", array(), Mockery::any())->once()->andReturn(json_encode(array('name' => "teams1", "display_name" => 'teams1', "id" => 121)));
            $mockRestClient->expects('get')->with("api/v4/teams/121/channels/name/private1private", array(), Mockery::any())->once()->andReturn(json_encode(array("id" => 260, "name" => "private1private", "display_name" => "private1private", "team_id" => 121)));
            $mockRestClient->expects('get')->with("api/v4/users/username/girly", array(), Mockery::any())->once()->andThrow(new \GuzzleHttp\Exception\ClientException('"id" : "store.sql_user.get_by_username.app_error"', $request, $response));
            $mockRestClient->expects('postWithHeader')->with("api/v4/users", array('email' => "girly@gmail.com", 'username' => "girly", 'first_name' => "girly", 'password' => md5('girly')), Mockery::any())->once()->andReturn(array("body" => json_encode(array("id" => 3, 'email' => "girly@gmail.com", 'username' => "girly", 'first_name' => "girly"))));
            $mockRestClient->expects('get')->with("api/v4/teams/121/members/3", array(), Mockery::any())->once()->andReturn(json_encode(array("team_id" => 121, "user_id" => 3)));
            $mockRestClient->expects('postWithHeader')->with("api/v4/channels/260/members", array('user_id' => 3), Mockery::any())->once()->andReturn(array("body" => json_encode(array('channel_id' => 260, "user_id" => 3))));
        }
        $this->dispatch('/callback/chat/addusertochannel', 'POST', $data);
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('Callback');
        $this->assertControllerName(ChatcallbackController::class); // as specified in router's controller name alias
        $this->assertControllerClass('ChatcallbackController');
        $this->assertMatchedRouteName('addusertochannelcallback');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
    }

    public function testAddUserToChannelUserNotCreatedBeczNetworkIssue()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['username' => 'Boyish', 'orgname' => 'Teams 1', 'groupname' => 'Private1 Private', 'status' => 'Active'];
        if (enableMattermost == 0) {
            $mockRestClient = $this->getMockRestClientForChatService();
            $request = Mockery::Mock('Psr\Http\Message\RequestInterface');
            $response = Mockery::Mock('Psr\Http\Message\ResponseInterface');
            $response->expects('getStatusCode')->andReturn(500);
            $mockRestClient->expects('get')->with("api/v4/teams/name/teams1", array(), Mockery::any())->once()->andReturn(json_encode(array('name' => "teams1", "display_name" => 'teams1', "id" => 121)));
            $mockRestClient->expects('get')->with("api/v4/teams/121/channels/name/private1private", array(), Mockery::any())->once()->andReturn(json_encode(array("id" => 260, "name" => "private1private", "display_name" => "private1private", "team_id" => 121)));
            $mockRestClient->expects('get')->with("api/v4/users/username/boyish", array(), Mockery::any())->once()->andThrow(new \GuzzleHttp\Exception\ClientException('', $request, $response));
        }
        if (enableMattermost == 1) {
            $this->assertTrue(true);
        } else {
            $this->dispatch('/callback/chat/addusertochannel', 'POST', $data);
            $this->assertResponseStatusCode(500);
            $this->assertModuleName('Callback');
            $this->assertControllerName(ChatcallbackController::class); // as specified in router's controller name alias
            $this->assertControllerClass('ChatcallbackController');
            $this->assertMatchedRouteName('addusertochannelcallback');
            $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
            $content = (array) json_decode($this->getResponse()->getContent(), true);
            $this->assertEquals($content['status'], 'error');
        }
    }

    // No mock test
    public function testAddUserToChannelDataNotFound()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['orgname' => 'Raks Team', 'groupname' => 'Channel Crrate Private', 'status' => 'Active'];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/callback/chat/addusertochannel', 'POST', $data);
        $this->assertResponseStatusCode(400);
        $this->assertMatchedRouteName('addusertochannelcallback');
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }

    public function testRemoveUserFromChannel()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['username' => $this->managerUser, 'orgname' => 'Teams 1', 'groupname' => 'New Channel Private 1', 'status' => 'Active'];
        if (enableMattermost == 0) {
            $mockRestClient = $this->getMockRestClientForChatService();
            $mockRestClient->expects('get')->with("api/v4/teams/name/teams1", array(), Mockery::any())->once()->andReturn(json_encode(array('name' => "teams1", "display_name" => 'teams1', "id" => 121)));
            $mockRestClient->expects('get')->with("api/v4/teams/121/channels/name/newchannelprivate1", array(), Mockery::any())->once()->andReturn(json_encode(array("id" => 234, "name" => "newchannelprivate1", "display_name" => "newchannelprivate1", "team_id" => 121)));
            $mockRestClient->expects('get')->with("api/v4/users/username/" . $this->managerUser, array(), Mockery::any())->once()->andReturn(json_encode(array("id" => 1, "name" => $this->managerUser)));
            $mockRestClient->expects('get')->with("api/v4/teams/121/members/1", array(), Mockery::any())->once()->andReturn(json_encode(array("team_id" => 121, "user_id" => 1)));
            $mockRestClient->expects('get')->with("api/v4/channels/234/members/1", array(), Mockery::any())->once()->andReturn(json_encode(array("channel_id" => 234, "user_id" => 1)));
            $mockRestClient->expects('delete')->with("api/v4/channels/234/members/1", array(), Mockery::any())->once()->andReturn(json_encode(array("status" => "OK")));
        }
        $this->dispatch('/callback/chat/removeuserfromchannel', 'POST', $data);
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('Callback');
        $this->assertControllerName(ChatcallbackController::class);
        $this->assertControllerClass('ChatcallbackController');
        $this->assertMatchedRouteName('removeuserfromchannelcallback');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
    }
    // No mock test
    public function testRemoveUserFromChannelUserNotFound()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['orgname' => 'Raks Team', 'groupname' => 'Channel Crrate Private', 'status' => 'Active'];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/callback/chat/removeuserfromchannel', 'POST', $data);
        $this->assertResponseStatusCode(400);
        $this->assertMatchedRouteName('removeuserfromchannelcallback');
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }

    public function testRemoveUserFromChannelUserNotInChannel()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['username' => $this->managerUser, 'orgname' => 'Teams 1', 'groupname' => 'Private1 Private', 'status' => 'Active'];
        $this->setJsonContent(json_encode($data));
        if (enableMattermost == 0) {
            $mockRestClient = $this->getMockRestClientForChatService();
            $exception = Mockery::Mock('GuzzleHttp\Exception\ClientException');
            $mockRestClient->expects('get')->with("api/v4/teams/name/teams1", array(), Mockery::any())->once()->andReturn(json_encode(array('name' => "teams1", "display_name" => 'teams1', "id" => 121)));
            $mockRestClient->expects('get')->with("api/v4/teams/121/channels/name/private1private", array(), Mockery::any())->once()->andReturn(json_encode(array("id" => 260, "name" => "private1private", "display_name" => "private1private", "team_id" => 121)));
            $mockRestClient->expects('get')->with("api/v4/users/username/" . $this->managerUser, array(), Mockery::any())->once()->andReturn(json_encode(array("id" => 1, "name" => $this->managerUser)));
            $mockRestClient->expects('get')->with("api/v4/teams/121/members/1", array(), Mockery::any())->once()->andReturn(json_encode(array("team_id" => 121, "user_id" => 1)));
            $mockRestClient->expects('get')->with("api/v4/channels/260/members/1", array(), Mockery::any())->once()->andThrow($exception);
        }
        $this->dispatch('/callback/chat/removeuserfromchannel', 'POST', $data);
        $this->assertResponseStatusCode(400);
        $this->assertMatchedRouteName('removeuserfromchannelcallback');
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }

    public function testRemoveUserFromChannelNotCreatedIssue()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['username' => $this->managerUser, 'orgname' => 'Teams 1', 'groupname' => 'Payyannur', 'status' => 'Active'];
        $this->setJsonContent(json_encode($data));
        if (enableMattermost == 0) {
            $mockRestClient = $this->getMockRestClientForChatService();
            $request = Mockery::Mock('Psr\Http\Message\RequestInterface');
            $response = Mockery::Mock('Psr\Http\Message\ResponseInterface');
            $response->expects('getStatusCode')->andReturn(500);
            $mockRestClient->expects('get')->with("api/v4/teams/name/teams1", array(), Mockery::any())->once()->andReturn(json_encode(array('name' => "teams1", "display_name" => 'teams1', "id" => 121)));
            $mockRestClient->expects('get')->with("api/v4/teams/121/channels/name/payyannur", array(), Mockery::any())->once()->andThrow(new \GuzzleHttp\Exception\ClientException('', $request, $response));
        }
        if (enableMattermost == 1) {
            $this->assertTrue(true);
        } else {
            $this->dispatch('/callback/chat/removeuserfromchannel', 'POST', $data);
            $this->assertResponseStatusCode(500);
            $this->assertMatchedRouteName('removeuserfromchannelcallback');
            $content = (array) json_decode($this->getResponse()->getContent(), true);
            $this->assertEquals($content['status'], 'error');
        }
    }

    public function testDeleteChannel()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['orgname' => 'teams-1', 'groupname' => 'New Channel Private 1', 'status' => 'Active'];
        if (enableMattermost == 0) {
            $mockRestClient = $this->getMockRestClientForChatService();
            $mockRestClient->expects('get')->with("api/v4/teams/name/teams1", array(), Mockery::any())->once()->andReturn(json_encode(array('name' => "teams1", "display_name" => 'teams1', "id" => 121)));
            $mockRestClient->expects('get')->with("api/v4/teams/121/channels/name/newchannelprivate1", array(), Mockery::any())->once()->andReturn(json_encode(array("id" => 234, "name" => "newchannelprivate1", "display_name" => 'newchannelprivate1', "team_id" => 121)));
            $mockRestClient->expects('delete')->with("api/v4/channels/234", array(), Mockery::any())->once()->andReturn(json_encode(array("status" => "OK")));
        }

        $this->dispatch('/callback/chat/deletechannel', 'POST', $data);
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('Callback');
        $this->assertControllerName(ChatcallbackController::class); // as specified in router's controller name alias
        $this->assertControllerClass('ChatcallbackController');
        $this->assertMatchedRouteName('deletechannelcallback');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
    }
    // no mock test
    public function testDeleteChannelNameNotFound()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['orgname' => 'Raks Team', 'status' => 'Active'];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/callback/chat/deletechannel', 'POST', $data);
        $this->assertResponseStatusCode(400);
        $this->assertMatchedRouteName('deletechannelcallback');
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }
    // no mock test
    public function testDeleteChannelTeamNameNotFound()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['groupname' => 'off-topic', 'status' => 'Active'];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/callback/chat/deletechannel', 'POST', $data);
        $this->assertResponseStatusCode(400);
        $this->assertMatchedRouteName('deletechannelcallback');
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }

    public function testDeleteChannelNotFound()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['orgname' => 'Teams 1', 'groupname' => 'Privatecs 3', 'status' => 'Active'];
        if (enableMattermost == 0) {
            $mockRestClient = $this->getMockRestClientForChatService();
            $exception = Mockery::Mock('GuzzleHttp\Exception\ClientException');
            $request = Mockery::Mock('Psr\Http\Message\RequestInterface');
            $response = Mockery::Mock('Psr\Http\Message\ResponseInterface');
            $response->expects('getStatusCode')->andReturn(500);
            $mockRestClient->expects('get')->with("api/v4/teams/name/teams1", array(), Mockery::any())->once()->andReturn(json_encode(array('name' => "teams1", "display_name" => 'teams1', "id" => 121)));
            $mockRestClient->expects('get')->with("api/v4/teams/121/channels/name/privatecs3", array(), Mockery::any())->once()->andThrow(new \GuzzleHttp\Exception\ClientException('', $request, $response));
        }
        if (enableMattermost == 1) {
            $this->assertTrue(true);
        } else {
            $this->dispatch('/callback/chat/deletechannel', 'POST', $data);
            $this->assertResponseStatusCode(500);
            $this->assertModuleName('Callback');
            $this->assertControllerName(ChatcallbackController::class); // as specified in router's controller name alias
            $this->assertControllerClass('ChatcallbackController');
            $this->assertMatchedRouteName('deletechannelcallback');
            $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
            $content = (array) json_decode($this->getResponse()->getContent(), true);
            $this->assertEquals($content['status'], 'error');
        }
    }

    public function testRemoveUserFromOrg()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['username' => $this->managerUser, 'orgname' => 'teams-1', 'status' => 'Active'];
        if (enableMattermost == 0) {
            $mockRestClient = $this->getMockRestClientForChatService();
            $mockRestClient->expects('get')->with("api/v4/users/username/" . $this->managerUser, array(), Mockery::any())->once()->andReturn(json_encode(array('name' => $this->managerUser, "id" => 1)));
            $mockRestClient->expects('get')->with("api/v4/teams/name/teams1", array(), Mockery::any())->once()->andReturn(json_encode(array('name' => "teams1", "display_name" => 'teams1', "id" => 121)));
            $mockRestClient->expects('delete')->with("api/v4/teams/121/members/1", array(), Mockery::any())->once()->andReturn(json_encode(array("status" => "OK")));
        }

        $this->dispatch('/callback/chat/removeuser', 'POST', $data);
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('Callback');
        $this->assertControllerName(ChatcallbackController::class); // as specified in router's controller name alias
        $this->assertControllerClass('ChatcallbackController');
        $this->assertMatchedRouteName('removeusercallback');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
    }

    public function testRemoveUserFromOrgUserNotFound()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['username' => 'laxmi', 'orgname' => 'teams-1', 'status' => 'Active'];
        $this->setJsonContent(json_encode($data));
        if (enableMattermost == 0) {
            $mockRestClient = $this->getMockRestClientForChatService();
            $request = Mockery::Mock('Psr\Http\Message\RequestInterface');
            $response = Mockery::Mock('Psr\Http\Message\ResponseInterface');
            $response->expects('getStatusCode')->andReturn(500);
            $mockRestClient->expects('get')->with("api/v4/users/username/laxmi", array(), Mockery::any())->once()->andThrow(new \GuzzleHttp\Exception\ClientException('', $request, $response));
        }
        if (enableMattermost == 1) {
            $this->assertTrue(true);
        } else {
            $this->dispatch('/callback/chat/removeuser', 'POST', $data);
            $this->assertResponseStatusCode(500);
            $this->assertMatchedRouteName('removeusercallback');
            $content = (array) json_decode($this->getResponse()->getContent(), true);
            $this->assertEquals($content['status'], 'error');
        }
    }

    // No mock test
    public function testRemoveUserFromOrgDataNotFound()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['orgname' => 'Raks Team', 'status' => 'Active'];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/callback/chat/removeuser', 'POST', $data);
        $this->assertResponseStatusCode(404);
        $this->assertMatchedRouteName('removeusercallback');
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }
    // No mock test
    public function testRemoveUserFromOrgNotFound()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['username' => $this->adminUser, 'status' => 'Active'];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/callback/chat/removeuser', 'POST', $data);
        $this->assertResponseStatusCode(404);
        $this->assertMatchedRouteName('removeusercallback');
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }

    public function testRemoveUserFromOrgUserNotInTeam()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['username' => $this->managerUser, 'orgname' => 'Raks Team', 'status' => 'Active'];
        $this->setJsonContent(json_encode($data));
        if (enableMattermost == 0) {
            $mockRestClient = $this->getMockRestClientForChatService();
            $exception = Mockery::Mock('GuzzleHttp\Exception\ClientException');
            $mockRestClient->expects('get')->with("api/v4/users/username/" . $this->managerUser, array(), Mockery::any())->once()->andReturn(json_encode(array('name' => $this->managerUser, "id" => 1)));
            $mockRestClient->expects('get')->with("api/v4/teams/name/raksteam", array(), Mockery::any())->once()->andReturn(json_encode(array('name' => "raksteam", "display_name" => 'raksteam', "id" => 175)));
            $mockRestClient->expects('delete')->with("api/v4/teams/175/members/1", array(), Mockery::any())->once()->andThrow($exception);
        }
        $this->dispatch('/callback/chat/removeuser', 'POST', $data);
        $this->assertResponseStatusCode(404);
        $this->assertMatchedRouteName('removeusercallback');
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }

    public function testDeleteOrg()
    {
        $data = ['orgname' => 'teams-1', 'status' => 'Active'];
        $this->initAuthToken($this->adminUser);
        $this->setJsonContent(json_encode($data));
        if (enableMattermost == 0) {
            $mockRestClient = $this->getMockRestClientForChatService();
            $mockRestClient->expects('postWithHeader')->with("api/v4/teams/search", array('term' => "teams1"), Mockery::any())->once()->andReturn(array("body" => json_encode(array(array("name" => "teams1", "display_name" => "teams1", "id" => 121)))));
            $mockRestClient->expects('delete')->with("api/v4/teams/121", array('permanent' => 'false'), Mockery::any())->once()->andReturn(json_encode(array("status" => "OK")));
        }
        $this->dispatch('/callback/chat/deleteorg', 'POST', $data);
        $this->assertResponseStatusCode(200);
        $this->assertMatchedRouteName('deletecallback');
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
    }

    public function testDeleteOrgNotFound()
    {
        $data = ['orgname' => 'hmm ok jog', 'status' => 'Active'];
        $this->initAuthToken($this->adminUser);
        $this->setJsonContent(json_encode($data));
        if (enableMattermost == 0) {
            $mockRestClient = $this->getMockRestClientForChatService();
            $exception = Mockery::Mock('GuzzleHttp\Exception\ClientException');
            $mockRestClient->expects('postWithHeader')->with("api/v4/teams/search", array('term' => "hmmokjog"), Mockery::any())->once()->andThrow($exception);
        }
        $this->dispatch('/callback/chat/deleteorg', 'POST', $data);
        $this->assertResponseStatusCode(400);
        $this->assertMatchedRouteName('deletecallback');
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }
}
