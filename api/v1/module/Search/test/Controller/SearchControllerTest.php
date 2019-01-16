<?php
namespace Search;

use Search\Controller\SearchController;
use Oxzion\Test\ControllerTest;
use Bos\Db\ModelTable;
use PHPUnit\DbUnit\TestCaseTrait;
use PHPUnit\DbUnit\DataSet\YamlDataSet;
use Zend\Db\Sql\Sql;
use Zend\Db\Adapter\Adapter;
use Oxzion\Search\SearchFactory;
use Oxzion\Search;
use PHPUnit\DbUnit\DataSet\SymfonyYamlParser;
use Oxzion\Test\MainControllerTest;
use Bos\Auth\AuthContext;
use Bos\Auth\AuthConstants;

class SearchControllerTest extends MainControllerTest{
    
    private $dataset;
    private $searchFactory;

    public function setUp() : void{
        $this->loadConfig();
        parent::setUp();
        $this->setSearchData();
        $config = $this->getApplicationConfig();
        $this->searchFactory = new SearchFactory($config);   
    }   
    

    public function setSearchData() {
          $parser = new SymfonyYamlParser();
          $this->dataset = $parser->parseYaml(dirname(__FILE__)."/../Dataset/Search.yml");
    }

    public function assertIndex($indexer,$body) {       
        $type = 'type';
        $app_id = $body['app_id'];
        $id = $body['id'];
        AuthContext::put(AuthConstants::ORG_ID, $body['org_id']);
        $return=$indexer->index($app_id,$id,$type,$body);
        $this->assertEquals($return['result'],1);
    }

    public function testIndex(){
        if(enableElastic==0){
            $this->markTestSkipped('Only Integration Test');        
        }
        $indexer = $this->searchFactory->getIndexer();
        $body = $this->dataset['ox_search'];
        $this->assertIndex($indexer,$body[0]);
        $this->assertIndex($indexer,$body[1]);
        $this->assertIndex($indexer,$body[2]);
        $this->assertIndex($indexer,$body[3]);
        $this->assertIndex($indexer,$body[4]);
    }

    public function testSearch(){
        if(enableElastic==0){
            $this->markTestSkipped('Only Integration Test');        
        }
        $data = ['searchtext' => 'Test'];
        $this->initAuthToken($this->employeeUser);
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/search', 'POST', null);
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content,0);
    }

    public function testDelete() {
        if(enableElastic==0){
            $this->markTestSkipped('Only Integration Test');        
        }
        $indexer = $this->searchFactory->getIndexer();
        $return1=$indexer->delete('1_test','all');
        $return2=$indexer->delete('2_test','all');
        $this->assertEquals($return1['result'],1);
        $this->assertEquals($return2['result'],1);
    }

  
}