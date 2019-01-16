<?php
namespace Oxzion\Search\Elastic;

use Oxzion\Search\SearchEngine;
use Elasticsearch\ClientBuilder;
use Oxzion\Service\ElasticService;
use Bos\Auth\AuthContext;
use Bos\Auth\AuthConstants;

class SearchEngineImpl implements SearchEngine {
    private $config;
    public function __construct($config) {
        $this->config = $config;
    }

    public function search($parameters, $app_id)
    {
        try {
            $elasticService = new ElasticService($this->config);
            $text = $parameters['searchtext'];
            $pagesize = (isset($parameters['pagesize'])) ? $parameters['pagesize'] : 25;
            $start = (isset($parameters['start'])) ? $parameters['start'] : 0;
            $index = ($app_id) ? $app_id : '_all';
            $orgid = AuthContext::get(AuthConstants::ORG_ID);
            $body = array();
            if (isset($parameters['type'])) {
                $body['query']['bool']['filter']['must'] = [
                    ['term' => ['org_id' => $orgid]],
                    ['term' => ['type' => $type]]
                ];
            } else {
                $body['query']['bool']['filter'] = ['term' => ['org_id' => $orgid]];
            }
            $body['query']['bool']['should'] = ["multi_match" => ["fields" => ['display_id^6', 'name^4', 'status^0.1', 'modified_by^2', 'created_by^2'], "query" => $text, "fuzziness" => "AUTO"]];
            $body['highlight'] = ['order' => 'score', "require_field_match" => 'true', 'fields' => ["*" => ['force_source' => false, "pre_tags" => ["<b class='highlight'>"], "post_tags" => ["</b>"], 'number_of_fragments' => 3, 'fragment_size' => 100]], 'encoder' => 'html'];
            $body['min_score'] = "0.5";
            $source = ['display_id', 'name', 'status', 'created_by', 'date_created','modified_by','date_modified'];
            $data = $elasticService->getSearchResults($index, $body, $source, $start, $pagesize);
            return $data;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

}
?>