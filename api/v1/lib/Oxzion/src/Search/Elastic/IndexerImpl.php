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


    public function index($app_id,$id,$type,$body){
        try {
            $org_id = AuthContext::get(AuthConstants::ORG_ID);
            $body['org_id']=$org_id;
            $body['type']=$type;
            $index = $app_id;
            $elasticService = new ElasticService($this->config);    
            $response = $elasticService->index($index,$id,$body);
            return $response;
        } catch (Exception $e) {
            throw new Exception("Could not Perform Elastic Index", 0, $e);
        }
    }

    public function  delete($app_id,$id) {
         try {
            $elasticService = new ElasticService($this->config);       
            $index = $app_id;
            $response = $elasticService->delete($index,$id);
            return $response;
        } catch (Exception $e) {
            throw new Exception("Could not Delete Elastic Index", 0, $e);
        }
    }

}
?>