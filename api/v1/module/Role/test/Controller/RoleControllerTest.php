<?php
namespace Role;

use Oxzion\Test\ControllerTest;
use PHPUnit\DbUnit\DataSet\YamlDataSet;
use Role\Controller\RoleController;

class RoleControllerTest extends ControllerTest
{
    public function setUp(): void
    {
        $this->loadConfig();
        parent::setUp();
    }

    public function getDataSet()
    {
        $dataset = new YamlDataSet(dirname(__FILE__) . "/../Dataset/Role.yml");
        return $dataset;
    }

    protected function setDefaultAsserts()
    {
        $this->assertModuleName('Role');
        $this->assertControllerName(RoleController::class); // as specified in router's controller name alias
        $this->assertControllerClass('RoleController');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
    }

    public function testGetList()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/role', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(5, count($content['data']));
        $this->assertEquals($content['data'][0]['id'], 4);
        $this->assertEquals($content['data'][0]['name'], 'ADMIN');
        $this->assertEquals($content['data'][1]['id'], 6);
        $this->assertEquals($content['data'][1]['name'], 'EMPLOYEE');
        $this->assertEquals($content['data'][2]['id'], 17);
        $this->assertEquals($content['data'][2]['name'], 'EMPLOYEE-2');
        $this->assertEquals($content['total'], 5);
    }

    public function testGetListWithOrgId()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/organization/53012471-2863-4949-afb1-e69b0891c98a/role', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(5, count($content['data']));
        $this->assertEquals($content['data'][0]['id'], 4);
        $this->assertEquals($content['data'][0]['name'], 'ADMIN');
        $this->assertEquals($content['data'][1]['id'], 6);
        $this->assertEquals($content['data'][1]['name'], 'EMPLOYEE');
        $this->assertEquals($content['data'][2]['id'], 17);
        $this->assertEquals($content['data'][2]['name'], 'EMPLOYEE-2');
        $this->assertEquals($content['total'], 5);
    }

    public function testGetListWithDifferentOrgId()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/organization/b0971de7-0387-48ea-8f29-5d3704d96a46/role', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(4, count($content['data']));
        $this->assertEquals($content['data'][0]['id'], 7);
        $this->assertEquals($content['data'][0]['name'], 'ADMIN');
        $this->assertEquals($content['data'][1]['id'], 15);
        $this->assertEquals($content['data'][1]['name'], 'ADMIN-3');
        $this->assertEquals($content['data'][2]['id'], 9);
        $this->assertEquals($content['data'][2]['name'], 'EMPLOYEE');
        $this->assertEquals($content['data'][3]['id'], 8);
        $this->assertEquals($content['data'][3]['name'], 'MANAGER');
        $this->assertEquals($content['total'], 4);
    }

    public function testGetListMyManagerWithDifferentOrgId()
    {
        $this->initAuthToken($this->managerUser);
        $this->dispatch('/organization/b0971de7-0387-48ea-8f29-5d3704d96a46/role', 'GET');
        $this->assertResponseStatusCode(401);
        $this->assertModuleName('Role');
        $this->assertControllerName(RoleController::class); // as specified in router's controller name alias
        $this->assertControllerClass('RoleController');
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'You have no Access to this API');
    }

    public function testGetListWithQuery()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/role?filter=[{"filter":{"logic":"and","filters":[{"field":"name","operator":"endswith","value":"in"},{"field":"description","operator":"startswith","value":"mu"}]},"sort":[{"field":"id","dir":"asc"}],"skip":0,"take":1}]', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(1, count($content['data']));
        $this->assertEquals($content['data'][0]['id'], 4);
        $this->assertEquals($content['data'][0]['name'], 'ADMIN');
        $this->assertEquals($content['total'], 1);
    }

    public function testGetListWithQueryPageNo()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/role?filter=[{"skip":1,"take":1}]', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(1, count($content['data']));
        $this->assertEquals($content['data'][0]['id'], 6);
        $this->assertEquals($content['data'][0]['name'], 'EMPLOYEE');
        $this->assertEquals($content['total'], 5);
    }

    public function testGetListWithQuerySort()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/role?filter=[{"sort":[{"field":"name","dir":"asc"}],"skip":1,"take":1}]', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(1, count($content['data']));
        $this->assertEquals($content['data'][0]['id'], 6);
        $this->assertEquals($content['data'][0]['name'], 'EMPLOYEE');
        $this->assertEquals($content['total'], 5);
    }

    public function testGetListWithQuerySortWithOrgId()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/organization/53012471-2863-4949-afb1-e69b0891c98a/role?filter=[{"sort":[{"field":"name","dir":"asc"}],"skip":1,"take":1}]', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(1, count($content['data']));
        $this->assertEquals($content['data'][0]['id'], 6);
        $this->assertEquals($content['data'][0]['name'], 'EMPLOYEE');
        $this->assertEquals($content['total'], 5);
    }

    public function testRolePrivilege()
    {
        $this->initAuthToken($this->adminUser);
        $update = "UPDATE ox_role SET uuid = 'a0c8bfdf-bfe6-11e9-b282-68ecc57cde45' where id = 4 and org_id = 1";
        $result = $this->executeUpdate($update);
        $this->dispatch('/role/a0c8bfdf-bfe6-11e9-b282-68ecc57cde45/privilege', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('Role');
        $this->assertControllerName(RoleController::class); // as specified in router's controller name alias
        $this->assertControllerClass('RoleController');
        $this->assertMatchedRouteName('roleprivilege');
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(18, count($content['data']));
        foreach ($content['data'] as $key => $val) {
            if ($val['privilege_name'] == "MANAGE_ANNOUNCEMENT") {
                $this->assertEquals($val['permission'], 3);
                $this->assertEquals($val['org_id'], 1);
                $this->assertEquals($val['app_id'], 1);
            }
            if ($val['privilege_name'] == "MANAGE_CRM") {
                $this->assertEquals($val['permission'], 1);
                $this->assertEquals($val['org_id'], 1);
                $this->assertEquals($val['app_id'], 9);
            }
            if ($val['privilege_name'] == "MANAGE_EMAIL") {
                $this->assertEquals($val['permission'], 1);
                $this->assertEquals($val['org_id'], 1);
                $this->assertEquals($val['app_id'], 1);
            }
            if ($val['privilege_name'] == "MANAGE_GROUP") {
                $this->assertEquals($val['permission'], 3);
                $this->assertEquals($val['org_id'], 1);
                $this->assertEquals($val['app_id'], 1);
            }
            if ($val['privilege_name'] == "MANAGE_MYAPP") {
                $this->assertEquals($val['permission'], 3);
                $this->assertEquals($val['org_id'], 1);
                $this->assertEquals($val['app_id'], 3);
            }
            if ($val['privilege_name'] == "MANAGE_ORGANIZATION") {
                $this->assertEquals($val['permission'], 15);
                $this->assertEquals($val['org_id'], 1);
                $this->assertEquals($val['app_id'], 1);
            }
            if ($val['privilege_name'] == "MANAGE_PROJECT") {
                $this->assertEquals($val['permission'], 3);
                $this->assertEquals($val['org_id'], 1);
                $this->assertEquals($val['app_id'], 1);
            }
            if ($val['privilege_name'] == "MANAGE_ROLE") {
                $this->assertEquals($val['permission'], 3);
                $this->assertEquals($val['org_id'], 1);
                $this->assertEquals($val['app_id'], 1);
            }
            if ($val['privilege_name'] == "MANAGE_USER") {
                $this->assertEquals($val['permission'], 15);
                $this->assertEquals($val['org_id'], 1);
                $this->assertEquals($val['app_id'], 1);
            }
        }
    }

    public function testRolePrivilegeWithOrgID()
    {
        $this->initAuthToken($this->adminUser);
        $update = "UPDATE ox_role SET uuid = 'a0c8bfdf-bfe6-11e9-b282-68ecc57cde45' where id = 4 and org_id = 1";
        $result = $this->executeUpdate($update);
        $this->dispatch('/organization/53012471-2863-4949-afb1-e69b0891c98a/role/a0c8bfdf-bfe6-11e9-b282-68ecc57cde45/privilege', 'GET');
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('Role');
        $this->assertControllerName(RoleController::class); // as specified in router's controller name alias
        $this->assertControllerClass('RoleController');
        $this->assertMatchedRouteName('roleprivilege');
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(18, count($content['data']));
        foreach ($content['data'] as $key => $val) {
            if ($val['privilege_name'] == "MANAGE_ANNOUNCEMENT") {
                $this->assertEquals($val['permission'], 3);
                $this->assertEquals($val['org_id'], 1);
                $this->assertEquals($val['app_id'], 1);
            }
            if ($val['privilege_name'] == "MANAGE_CRM") {
                $this->assertEquals($val['permission'], 1);
                $this->assertEquals($val['org_id'], 1);
                $this->assertEquals($val['app_id'], 9);
            }
            if ($val['privilege_name'] == "MANAGE_EMAIL") {
                $this->assertEquals($val['permission'], 1);
                $this->assertEquals($val['org_id'], 1);
                $this->assertEquals($val['app_id'], 1);
            }
            if ($val['privilege_name'] == "MANAGE_GROUP") {
                $this->assertEquals($val['permission'], 3);
                $this->assertEquals($val['org_id'], 1);
                $this->assertEquals($val['app_id'], 1);
            }
            if ($val['privilege_name'] == "MANAGE_MYAPP") {
                $this->assertEquals($val['permission'], 3);
                $this->assertEquals($val['org_id'], 1);
                $this->assertEquals($val['app_id'], 3);
            }
            if ($val['privilege_name'] == "MANAGE_ORGANIZATION") {
                $this->assertEquals($val['permission'], 15);
                $this->assertEquals($val['org_id'], 1);
                $this->assertEquals($val['app_id'], 1);
            }
            if ($val['privilege_name'] == "MANAGE_PROJECT") {
                $this->assertEquals($val['permission'], 3);
                $this->assertEquals($val['org_id'], 1);
                $this->assertEquals($val['app_id'], 1);
            }
            if ($val['privilege_name'] == "MANAGE_ROLE") {
                $this->assertEquals($val['permission'], 3);
                $this->assertEquals($val['org_id'], 1);
                $this->assertEquals($val['app_id'], 1);
            }
            if ($val['privilege_name'] == "MANAGE_USER") {
                $this->assertEquals($val['permission'], 15);
                $this->assertEquals($val['org_id'], 1);
                $this->assertEquals($val['app_id'], 1);
            }
        }
    }

    public function testRolePrivilegeWithInvalidOrgID()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/organization/b0971de7-0387-48ea-8f29-5d3704d96a46/role/a0c8bfdf-bfe6-11e9-b282-68ecc57cde45/privilege', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('Role');
        $this->assertControllerName(RoleController::class); // as specified in router's controller name alias
        $this->assertControllerClass('RoleController');
        $this->assertMatchedRouteName('roleprivilege');
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data'], array());
    }

    public function testRolePrivilegeNotFound()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/role/12345/privilege', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('Role');
        $this->assertControllerName(RoleController::class); // as specified in router's controller name alias
        $this->assertControllerClass('RoleController');
        $this->assertMatchedRouteName('roleprivilege');
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data'], array());
    }

    public function testGet()
    {
        $this->initAuthToken($this->adminUser);
        $update = "UPDATE ox_role SET uuid = 'a0c8bfdf-bfe6-11e9-b282-68ecc57cde45' where id = 4 and org_id = 1";
        $result = $this->executeUpdate($update);
        $this->dispatch('/role/a0c8bfdf-bfe6-11e9-b282-68ecc57cde45', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['id'], 4);
        $this->assertEquals($content['data']['name'], 'ADMIN');
        $this->assertEquals(32, count($content['data']['privileges']));
    }

    public function testGetWithOrgId()
    {
        $this->initAuthToken($this->adminUser);
        $update = "UPDATE ox_role SET uuid = 'a0c8bfdf-bfe6-11e9-b282-68ecc57cde45' where id = 4 and org_id = 1";
        $result = $this->executeUpdate($update);
        $this->dispatch('/organization/53012471-2863-4949-afb1-e69b0891c98a/role/a0c8bfdf-bfe6-11e9-b282-68ecc57cde45', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['id'], 4);
        $this->assertEquals($content['data']['name'], 'ADMIN');
        $this->assertEquals(32, count($content['data']['privileges']));
    }

    public function testGetWithDifferentOrgId()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/organization/b0971de7-0387-48ea-8f29-5d3704d96a46/role/a0c8bfdf-bfe6-11e9-b282-68ecc57cde45', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data'], array());
    }

    public function testGetNotFound()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/role/a0c8bfdf-bfe6-11e9-b282-68ec', 'GET');
        $this->assertResponseStatusCode(200);
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data'], array());
    }

    public function testCreateRole()
    {
        $this->initAuthToken($this->adminUser);
        $data = array('name' => 'SUPER ADMIN', 'description' => 'Must have read and write control',
            'privileges' => array(['privilege_name' => 'MANAGE_ADMIN', 'permission' => '15'], ['privilege_name' => 'MANAGE_ROLE', 'permission' => '1'], ['privilege_name' => 'MANAGE_ALERT', 'permission' => '3']));
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/role', 'POST', $data);
        $this->assertResponseStatusCode(201);

        $select = "SELECT id from ox_role where name = 'SUPER ADMIN'";
        $id = $this->executeQueryTest($select);

        $select1 = "SELECT * from ox_role_privilege where role_id = " . $id[0]['id'];
        $result = $this->executeQueryTest($select1);

        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['name'], 'SUPER ADMIN');
        $this->assertEquals($content['data']['description'], 'Must have read and write control');
        $this->assertEquals($result[0]['privilege_name'], 'MANAGE_ALERT');
        $this->assertEquals($result[1]['privilege_name'], 'MANAGE_ROLE');
    }

    public function testCreateRoleWithOrgId()
    {
        $this->initAuthToken($this->adminUser);
        $data = array('name' => 'SUPER ADMIN', 'description' => 'Must have read and write control',
            'privileges' => array(['privilege_name' => 'MANAGE_ADMIN', 'permission' => '15'], ['privilege_name' => 'MANAGE_ROLE', 'permission' => '1'], ['privilege_name' => 'MANAGE_ALERT', 'permission' => '3']));
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/organization/53012471-2863-4949-afb1-e69b0891c98a/role', 'POST', $data);
        $this->assertResponseStatusCode(201);
        $select = "SELECT id from ox_role where name = 'SUPER ADMIN'";
        $id = $this->executeQueryTest($select);

        $select1 = "SELECT * from ox_role_privilege where role_id = " . $id[0]['id'];
        $result = $this->executeQueryTest($select1);

        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['name'], 'SUPER ADMIN');
        $this->assertEquals($content['data']['description'], 'Must have read and write control');
        $this->assertEquals($result[0]['privilege_name'], 'MANAGE_ALERT');
        $this->assertEquals($result[1]['privilege_name'], 'MANAGE_ROLE');
    }

    public function testCreateRoleWithPrivileges()
    {
        $this->initAuthToken($this->adminUser);
        $data = array('name' => 'SUPER ADMIN', 'description' => 'Must have read and write control',
            'privileges' => array(['privilege_name' => 'MANAGE_ADMIN', 'permission' => '15'], ['privilege_name' => 'MANAGE_ROLE', 'permission' => '1'], ['privilege_name' => 'MANAGE_ALERT', 'permission' => '3']));
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/organization/53012471-2863-4949-afb1-e69b0891c98a/role', 'POST', $data);
        $this->assertResponseStatusCode(201);
        $select = "SELECT id from ox_role where name = 'SUPER ADMIN'";
        $id = $this->executeQueryTest($select);

        $select1 = "SELECT * from ox_role_privilege where role_id = " . $id[0]['id'] . " and org_id = 1";
        $result = $this->executeQueryTest($select1);

        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['name'], 'SUPER ADMIN');
        $this->assertEquals($content['data']['description'], 'Must have read and write control');
        $this->assertEquals($result[0]['privilege_name'], 'MANAGE_ALERT');
        $this->assertEquals($result[1]['privilege_name'], 'MANAGE_ROLE');
    }

    public function testCreateRoleWithDifferentOrgId()
    {
        $this->initAuthToken($this->adminUser);
        $data = array('name' => 'SUPER ADMIN', 'description' => 'Must have read and write control',
            'privileges' => array(['privilege_name' => 'MANAGE_ADMIN', 'permission' => '15'], ['privilege_name' => 'MANAGE_ROLE', 'permission' => '1'], ['privilege_name' => 'MANAGE_ALERT', 'permission' => '3']));
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/organization/b0971de7-0387-48ea-8f29-5d3704d96a46/role', 'POST', $data);
        $this->assertResponseStatusCode(201);
        $select = "SELECT id from ox_role where name = 'SUPER ADMIN'";
        $id = $this->executeQueryTest($select);

        $select1 = "SELECT * from ox_role_privilege where role_id = " . $id[0]['id'];
        $result = $this->executeQueryTest($select1);

        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['name'], 'SUPER ADMIN');
        $this->assertEquals($content['data']['description'], 'Must have read and write control');
        $this->assertEquals($result[0]['privilege_name'], 'MANAGE_ALERT');
        $this->assertEquals($result[1]['privilege_name'], 'MANAGE_ROLE');
    }

    public function testUpdatePrivilegePermission()
    {
        $this->initAuthToken($this->adminUser);
        $data = array('name' => 'ADMIN', 'description' => 'Must have write control',
            'privileges' => array(['id' => '1', 'privilege_name' => 'MANAGE_ANNOUNCEMENT', 'permission' => '15'], ['id' => '14', 'privilege_name' => 'MANAGE_FORM', 'permission' => '1'], ['id' => '4', 'privilege_name' => 'MANAGE_ALERT', 'permission' => '3']));
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/role/53012471-2863', 'PUT');
        $this->assertResponseStatusCode(200);

        $select = "SELECT id from ox_role where name = 'ADMIN' AND org_id = 1";
        $id = $this->executeQueryTest($select);

        $select1 = "SELECT * from ox_role_privilege where role_id = " . $id[0]['id'];
        $result = $this->executeQueryTest($select1);

        $content = json_decode($this->getResponse()->getContent(), true);

        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['name'], 'ADMIN');
        $this->assertEquals(count($result), 34);
    }

    public function testUpdatePrivilegePermissionWithOrgID()
    {
        $this->initAuthToken($this->adminUser);
        $data = array('name' => 'ADMIN', 'description' => 'Must have write control',
            'privileges' => array(['id' => '1', 'privilege_name' => 'MANAGE_ANNOUNCEMENT', 'permission' => '15'], ['id' => '14', 'privilege_name' => 'MANAGE_FORM', 'permission' => '1'], ['id' => '4', 'privilege_name' => 'MANAGE_ALERT', 'permission' => '3']));
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/organization/53012471-2863-4949-afb1-e69b0891c98a/role/53012471-2863', 'PUT');

        $this->assertResponseStatusCode(200);
        $select = "SELECT id from ox_role where name = 'ADMIN' AND org_id = 1";
        $id = $this->executeQueryTest($select);

        $select1 = "SELECT * from ox_role_privilege where role_id = " . $id[0]['id'];
        $result = $this->executeQueryTest($select1);

        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['name'], 'ADMIN');
        $this->assertEquals(count($result), 34);
    }

    public function testAddNewPrivilege()
    {
        $this->initAuthToken($this->adminUser);
        $data = array('name' => 'ADMIN', 'description' => 'Must have write control',
            'privileges' => array(['privilege_name' => 'MANAGE_FILE', 'permission' => '15'], ['privilege_name' => 'MANAGE_MAIL', 'permission' => '1'], ['id' => '4', 'privilege_name' => 'MANAGE_ALERT', 'permission' => '15']));
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/role/53012471-2863', 'PUT');
        $this->assertResponseStatusCode(200);
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
    }

    public function testCreateWithExisitingRole()
    {
        $this->initAuthToken($this->adminUser);
        $data = array('name' => 'ADMIN', 'description' => 'Must have write control',
            'privileges' => array(['privilege_name' => 'MANAGE_FILE', 'permission' => '15'], ['privilege_name' => 'MANAGE_MAIL', 'permission' => '1'], ['id' => '4', 'privilege_name' => 'MANAGE_ALERT', 'permission' => '15']));
        $this->setJsonContent(json_encode($data));

        $this->dispatch('/role', 'POST', $data);
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
    }

    public function testCreate()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['name' => 'ADMIN_SUPER', 'org_id' => 2];
        $this->dispatch('/role', 'POST', $data);
        $this->assertResponseStatusCode(201);
        $this->setDefaultAsserts();
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['name'], $data['name']);
    }

    public function testCreateAccess()
    {
        $this->initAuthToken($this->employeeUser);
        $data = ['name' => 'ADMIN_SUPER 1', 'org_id' => 4];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/role', 'POST', null);
        $this->assertResponseStatusCode(401);
        $this->assertModuleName('Role');
        $this->assertControllerName(RoleController::class); // as specified in router's controller name alias
        $this->assertControllerClass('RoleController');
        $this->assertMatchedRouteName('Role');
        $this->assertResponseHeaderContains('content-type', 'application/json');
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'You have no Access to this API');
    }

    public function testUpdate()
    {
        $data = ['name' => 'ADMINs'];
        $this->initAuthToken($this->adminUser);
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/role/53012471-2863', 'PUT', null);
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
    }

    public function testUpdateRestricted()
    {
        $data = ['name' => 'ADMINs'];
        $this->initAuthToken($this->employeeUser);
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/role/53012471-2863', 'PUT', null);
        $this->assertResponseStatusCode(401);
        $this->assertModuleName('Role');
        $this->assertControllerName(RoleController::class); // as specified in router's controller name alias
        $this->assertControllerClass('RoleController');
        $this->assertMatchedRouteName('Role');
        $this->assertResponseHeaderContains('content-type', 'application/json');
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'You have no Access to this API');
    }

    public function testDelete()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/role/53012471-2863', 'DELETE');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
    }

    public function testDeleteWithInvalidOrgId()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/organization/53012471-2863-4949-afb1-e69b0891c98a/role/53012471-2863', 'DELETE');
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'Role does not belong to the organization');
    }

    public function testDeleteWithInvalidRoleId()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/organization/53012471-2863-4949-afb1-e69b0891c98a/role/53471-2863', 'DELETE');
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'Role not found');
    }

    public function testDeleteWithOrgId()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/organization/b0971de7-0387-48ea-8f29-5d3704d96a46/role/53012471-2863', 'DELETE');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
    }

    public function testDeleteNotFound()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/role/24783', 'DELETE');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }
}
