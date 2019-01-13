<?php
namespace Oxzion\Search\Elastic;

use Oxzion\Search\Indexer;
use Elasticsearch\ClientBuilder;
use Oxzion\Service\ElasticService;
use Bos\Auth\AuthContext;
use Bos\Auth\AuthConstants;

class IndexerImpl implements Indexer {
    private $config;
    public function __construct($config) {
        $this->config = $config;
    }


    public function index($body,$app_id,$type){
        try {
            $orgid = AuthContext::get(AuthConstants::ORG_ID);
            $body['type']=$type;
            $body['org_id']=$orgid;
            $index = $app_id;
            $elasticService = new ElasticService($this->config);       
            $response = $elasticService->index($index,$body);
        } catch (Exception $e) {
            throw e;
        }
    }

}
?>