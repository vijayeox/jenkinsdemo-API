<?php
namespace Analytics;

use Oxzion\Test\ControllerTest;
use Bos\Db\ModelTable;
use PHPUnit\DbUnit\TestCaseTrait;
use PHPUnit\DbUnit\DataSet\YamlDataSet;
use Zend\Db\Sql\Sql;
use Zend\Db\Adapter\Adapter;
use Oxzion\Analytics\AnalyticsEngine;
use Oxzion\Analytics\AnalyticsFactory;
use Oxzion\Search\SearchFactory;
use Oxzion\Search;
use Oxzion\Analytics;
use PHPUnit\DbUnit\DataSet\SymfonyYamlParser;
use Oxzion\Test\MainControllerTest;
use Bos\Auth\AuthContext;
use Bos\Auth\AuthConstants;

class AnalyticsTest extends MainControllerTest{
    
    private $dataset;
    private $searchFactory;
    private $analyticsFactory;

    public function setUp() : void{
        $this->loadConfig();
        parent::setUp();
        $this->setSearchData();
        
        $config = $this->getApplicationConfig();
        $this->searchFactory = new SearchFactory($config);   
        $this->analyticsFactory = new AnalyticsFactory($config);  
        $this->setupData(); 
        sleep (1) ;
    }   
    

    public function setSearchData() {
          $parser = new SymfonyYamlParser();
          $this->dataset = $parser->parseYaml(dirname(__FILE__)."/Dataset/Analytics.yml");
    }

    public function createIndex($indexer,$body) {       
        $type = 'type';
        $app_id = $body['app_id'];
        $id = $body['id'];
        AuthContext::put(AuthConstants::ORG_ID, $body['org_id']);
        $return=$indexer->index($app_id,$id,$type,$body);
    }

    public function setupData(){
        if(enableElastic!=0){ 
            $indexer = $this->searchFactory->getIndexer();
            $dataset = $this->dataset['ox_analysis'];
            foreach($dataset as $body) {
                $this->createIndex($indexer,$body);
            }
        }
    }

    
    public function testGrouping() {
        if(enableElastic==0){
            $this->markTestSkipped('Only Integration Test');        
        }
        AuthContext::put(AuthConstants::ORG_ID, 1);
        $ae = $this->analyticsFactory->getAnalyticsEngine();
        $parameters = ['group'=>'created_by','field'=>'amount','operation'=>'sum','date-period'=>'2018-01-01/2018-12-12','date_type'=>'date_created'];
        $results = $ae->runQuery('11_test',null,$parameters);
        $results = $results['data'];
        $this->assertEquals($results[0]['name'], "John Doe");
        $this->assertEquals($results[0]['value'], "800");
        $this->assertEquals($results[0]['grouplist'], "created_by");
        $this->assertEquals($results[1]['name'], "Mike Price");
        $this->assertEquals($results[1]['value'], "50.5");
        $this->assertEquals($results[1]['grouplist'], "created_by");
    }

    public function testDoubleGrouping() {
        if(enableElastic==0){
            $this->markTestSkipped('Only Integration Test');        
        }
        AuthContext::put(AuthConstants::ORG_ID, 1);
        $ae = $this->analyticsFactory->getAnalyticsEngine();
        $parameters = ['group'=>'created_by,category','field'=>'amount','operation'=>'sum','date-period'=>'2018-01-01/2018-12-12','date_type'=>'date_created'];
        $results = $ae->runQuery('11_test',null,$parameters);
        $results = $results['data'];
        $this->assertEquals($results[0]['name'], "John Doe - A");
        $this->assertEquals($results[0]['value'], "200");
        $this->assertEquals($results[0]['grouplist'][0], "John Doe");
        $this->assertEquals($results[2]['name'], "Mike Price - A");
        $this->assertEquals($results[2]['value'], "50.5");
        $this->assertEquals($results[2]['grouplist'][0], "Mike Price");
    }

    public function testLists() {
        if(enableElastic==0){
            $this->markTestSkipped('Only Integration Test');        
        }
        AuthContext::put(AuthConstants::ORG_ID, 1);
        $ae = $this->analyticsFactory->getAnalyticsEngine();
        $parameters = ['field'=>'amount','date-period'=>'2018-01-01/2019-12-12','date_type'=>'date_created','list'=>'name,created_by,category'];
        $results = $ae->runQuery('11_test',null,$parameters);
        $results = $results['data'];
        $this->assertEquals($results[0]['name'], "testing document");
        $this->assertEquals($results[0]['category'], "A");
        $this->assertEquals($results[0]['created_by'], "Mike Price");                
    }


    public function tearDown()
    {
         parent::tearDown();
         if(enableElastic!=0){                
            $indexer = $this->searchFactory->getIndexer();
            $return1=$indexer->delete('11_test','all');
            $return2=$indexer->delete('12_test','all');
        }    
    }

  
}