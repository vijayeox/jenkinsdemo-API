<?php
namespace ProspectResearch;

use ProspectResearch\Controller\ProspectResearchController;
use Oxzion\Test\ControllerTest;
use Oxzion\Db\ModelTable;
use PHPUnit\DbUnit\TestCaseTrait;
use PHPUnit\DbUnit\DataSet\YamlDataSet;
use Zend\Db\Sql\Sql;
use Zend\Db\Adapter\Adapter;
use Oxzion\ProspectResearch\InfoEngine;
use PHPUnit\DbUnit\DataSet\SymfonyYamlParser;
use Oxzion\Test\MainControllerTest;
use Oxzion\Auth\AuthContext;
use Oxzion\Auth\AuthConstants;
use function GuzzleHttp\json_decode;

class ProspectResearchControllerTest extends MainControllerTest{
    
    /* Copied function SetUp() from SearchControllerTest.php to avoid error:  Zend\ServiceManager\Exception\ServiceNotFoundException: Unable to resolve service "ApplicationConfig" to a factory; are you certain you provided it during configuration? */
    public function setUp() : void{
        $this->loadConfig();
        parent::setUp();
        $config = $this->getApplicationConfig();
    }   

    /* A correct call with correct data */
    public function testGetScoopInfo(){
        // {
        //     "searchtype":"scoop",
        //     "searchpara":{
        //         "companyCriteria": {
        //             "websiteUrls": ["www.keybank.com"]
        //         }
        //     }
        // }

        if(enableProspectResearch==0){
            $this->markTestSkipped('Only Integration Test');        
        }
        $data = ['searchtype' => 'scoop','searchpara' => ['companyCriteria' => ['websiteUrls' => array("www.keybank.com")]]];
        $this->initAuthToken($this->adminUser);
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/prospectresearch', 'POST', null);
        $apiResponse = $this->getResponse()->getContent();
        $contentArray = json_decode($apiResponse, true);
        if ($contentArray["status"] != 'error'){
            // convert json results in content array to array
            foreach ($contentArray["data"]["result"] as $key => $result){
                if (is_string($result)){
                    $contentArray["data"]["result"][$key] = json_decode($result,true);
                }
            }
        }
        
        $this->assertEquals($contentArray['status'],'success');
        $this->assertEquals(is_string($contentArray['data']['result']['body']['content'][0]['description']),true);
    }

    /* Throwing an exception spell 'searchpara' wrong */
    public function testGetScoopInfoError(){
        // {
        //     "searchtype":"scoop",
        //     "searcpara":{
        //         "companyCriteria": {
        //             "websiteUrls": ["www.keybank.com"]
        //         }
        //     }
        // }
        if(enableProspectResearch==0){
            $this->markTestSkipped('Only Integration Test');        
        }
        $data = ['searchtype' => 'scoop','searcpara' => ['companyCriteria' => ['websiteUrls' => array("www.keybank.com")]]];
        $this->initAuthToken($this->adminUser);
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/prospectresearch', 'POST', null);
        $apiResponse = $this->getResponse()->getContent();
        $contentArray = json_decode($apiResponse, true);

        $this->assertEquals($contentArray['status'],'error');
        $this->assertEquals($contentArray['errors'][0]['exception']['message'],'Getting Company Scoop Info Failed.');
    }
    
    /* A correct call with correct data */
    public function testGetOrgchartInfo(){
        // {
        // companies test input json
        // {
        //     "searchtype":"orgchart",
        //     "searchpara":{
        //         "companyId":"1448",
        //         "departmentId": 10
        //     }
        // }
        if(enableProspectResearch==0){
            $this->markTestSkipped('Only Integration Test');        
        }
        $data = ['searchtype' => 'orgchart','searchpara' => ['companyId' => '1448','departmentId' => 10]];
        $this->initAuthToken($this->adminUser);
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/prospectresearch', 'POST', null);
        $apiResponse = $this->getResponse()->getContent();
        $contentArray = json_decode($apiResponse, true);

        // convert json results in content array to array
        if ($contentArray["status"] != 'error'){
            foreach ($contentArray["data"] as $key => $result){
                if (is_string($result)){
                    $contentArray["data"][$key] = json_decode($result,true);
                }
            }
        }
        $this->assertEquals($contentArray['status'],'success');
        $this->assertEquals(is_string($contentArray['data']['result']['departmentName']),true);
        $this->assertEquals(is_string($contentArray['data']['result']['nodes'][0]['fullName']),true);
    }

    /* Throwing an exception spell 'searchpara' wrong */
    public function testGetOrgchartInfoError(){
        // {
        // companies test input json
        // {
        //     "searchtype":"orgchart",
        //     "sarchpara":{
        //         "companyId":"1448",
        //         "departmentId": 10
        //     }
        // }
        if(enableProspectResearch==0){
            $this->markTestSkipped('Only Integration Test');        
        }
        $data = ['searchtype' => 'orgchart','sarchpara' => ['companyId' => '1448','departmentId' => 10]];
        $this->initAuthToken($this->adminUser);
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/prospectresearch', 'POST', null);
        $apiResponse = $this->getResponse()->getContent();
        $contentArray = json_decode($apiResponse, true);
        
        $this->assertEquals($contentArray['status'],'error');
        $this->assertEquals($contentArray['errors'][0]['exception']['message'],'Getting Company Org Chart Info Failed.');
    }

    /* A correct call with correct data */
    public function testGetSearchInfo(){
        // companies test input json
        // {
        //     "searchtype":"companies",
        //     "searchpara":{
        //         "companyCriteria": {
        //             "websiteUrls": ["www.keybank.com"]
        //         }
        //     }
        // }
        if(enableProspectResearch==0){
            $this->markTestSkipped('Only Integration Test');        
        }
        $data = ['searchtype' => 'companies','searchpara' => ['companyCriteria' => ['websiteUrls' => array("www.keybank.com")]]];
        $this->initAuthToken($this->adminUser);
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/prospectresearch', 'POST', null);
        $apiResponse = $this->getResponse()->getContent();
        $contentArray = json_decode($apiResponse, true);
            
        // convert json results in content array to array
        if ($contentArray["status"] != 'error'){
            foreach ($contentArray["data"]["result"] as $key => $result){
                if (is_string($result)){
                    $contentArray["data"]["result"][$key] = json_decode($result,true);
                }
            }
        }

        $this->assertEquals($contentArray['status'],'success');
        $this->assertEquals(is_string($contentArray['data']['result']['body']['content'][0]['name']),true);
        $this->assertEquals(is_int($contentArray['data']['result']['body']['content'][0]['id']),true);
    }

    /* Throwing an exception spell 'searchpara' wrong */
    public function testGetSearchInfoError(){
        // companies test input json
        // {
        //     "searchtype":"companies",
        //     "searchpaa":{
        //         "companyCriteria": {
        //             "websiteUrls": ["www.keybank.com"]
        //         }
        //     }
        // }
        if(enableProspectResearch==0){
            $this->markTestSkipped('Only Integration Test');        
        }
        $data = ['searchtype' => 'companies','searchpaa' => ['companyCriteria' => ['websiteUrls' => array("www.keybank.com")]]];
        $this->initAuthToken($this->adminUser);
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/prospectresearch', 'POST', null);
        $apiResponse = $this->getResponse()->getContent();
        $contentArray = json_decode($apiResponse, true);
            
        $this->assertEquals($contentArray['status'],'error');
        $this->assertEquals($contentArray['errors'][0]['exception']['message'],'Getting Company Info Failed.');
    }  


    /* Throwing an exception.  Give an invalid searchtype */
    public function testInvalidSearchTypeError(){
        // {
        //     "searchtype":"orchart",
        //     "searchpara":{
        //         "companyId":"1448",
        //         "departmentId": 8
        //     }
        // }
        if(enableProspectResearch==0){
            $this->markTestSkipped('Only Integration Test');        
        }
        $data = ['searchtype' => 'orchart','searchpaa' => ['companyCriteria' => ['websiteUrls' => array("www.keybank.com")]]];
        $this->initAuthToken($this->adminUser);
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/prospectresearch', 'POST', null);
        $apiResponse = $this->getResponse()->getContent();
        $contentArray = json_decode($apiResponse, true);
            
        $this->assertEquals($contentArray['status'],'error');
        $this->assertEquals($contentArray['errors'][0]['exception']['message'],"Invalid searchtype of 'orchart' given.");
    }
}