<?php
namespace Search;

use Search\Controller\SearchController;
use Oxzion\Test\ControllerTest;
use Oxzion\Db\ModelTable;
use PHPUnit\DbUnit\TestCaseTrait;
use PHPUnit\DbUnit\DataSet\YamlDataSet;
use Zend\Db\Sql\Sql;
use Zend\Db\Adapter\Adapter;
use Oxzion\Search\SearchEngine;
use Oxzion\Search\Indexer;
use Oxzion\Search;
use PHPUnit\DbUnit\DataSet\SymfonyYamlParser;
use Oxzion\Test\MainControllerTest;
use Oxzion\Auth\AuthContext;
use Oxzion\Auth\AuthConstants;

class SearchControllerTest extends MainControllerTest
{
    private $dataset;
    private $searchFactory;

    public function setUp() : void
    {
        $this->loadConfig();
        parent::setUp();
        $this->setSearchData();
        $config = $this->getApplicationConfig();
    }
    

    public function setSearchData()
    {
        $parser = new SymfonyYamlParser();
        $this->dataset = $parser->parseYaml(dirname(__FILE__)."/../Dataset/Search.yml");
    }

    public function assertIndex($indexer, $body)
    {
        $type = 'type';
        $app_id = $body['app_id'];
        $id = $body['id'];
        AuthContext::put(AuthConstants::ORG_ID, $body['org_id']);
        $return=$indexer->index($app_id, $id, $type, $body);
        $this->assertEquals($return['result'], "created");
    }

    public function testIndex()
    {
        if (enableElastic==0) {
            $this->markTestSkipped('Only Integration Test');
        }
        $indexer = $this->getApplicationServiceLocator()->get(Indexer::class);
        $body = $this->dataset['ox_search'];
        $this->assertIndex($indexer, $body[0]);
        $this->assertIndex($indexer, $body[1]);
        $this->assertIndex($indexer, $body[2]);
        $this->assertIndex($indexer, $body[3]);
        $this->assertIndex($indexer, $body[4]);
        sleep(1);
    }

    public function testAllSearch()
    {
        if (enableElastic==0) {
            $this->markTestSkipped('Only Integration Test');
        }
        $data = ['searchtext' => 'Test'];
        $this->initAuthToken($this->employeeUser);
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/search', 'POST', null);
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['result']['hits']['total'], 2);
        $this->assertEquals($content['data']['result']['hits']['hits'][0]['_source']['name'], 'test document');
        $this->assertEquals($content['data']['result']['hits']['hits'][1]['_source']['name'], 'west document');
    }

    public function testAppSearchMultipe()
    {
        if (enableElastic==0) {
            $this->markTestSkipped('Only Integration Test');
        }
        $data = ['searchtext' => 'Document','app_id' => '1_test'];
        $this->initAuthToken($this->employeeUser);
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/search', 'POST', null);
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['result']['hits']['total'], 3);
        $this->assertEquals($content['data']['result']['hits']['hits'][0]['_source']['name'], 'testing document');
        $this->assertEquals($content['data']['result']['hits']['hits'][1]['_source']['name'], 'test document');
        $this->assertEquals($content['data']['result']['hits']['hits'][2]['_source']['name'], 'different document');
    }

    public function testAppSearchSingle()
    {
        if (enableElastic==0) {
            $this->markTestSkipped('Only Integration Test');
        }
        $data = ['searchtext' => 'Test','app_id' => '1_test'];
        $this->initAuthToken($this->employeeUser);
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/search', 'POST', null);
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['result']['hits']['total'], 1);
        $this->assertEquals($content['data']['result']['hits']['hits'][0]['_source']['name'], 'test document');
    }

    public function testDelete()
    {
        if (enableElastic==0) {
            $this->markTestSkipped('Only Integration Test');
        }
        $indexer = $this->getApplicationServiceLocator()->get(Indexer::class);
        $return1=$indexer->delete('1_test', 'all');
        $return2=$indexer->delete('2_test', 'all');
        $this->assertEquals($return1['acknowledged'], 1);
        $this->assertEquals($return2['acknowledged'], 1);
    }
}
