<?php
namespace Oxzion\Search\Elastic;

use Oxzion\Auth\AuthConstants;
use Oxzion\Auth\AuthContext;
use Oxzion\Search\SearchEngine;
use Oxzion\Service\ElasticService;
use Exception;

class SearchEngineImpl implements SearchEngine
{
    private $config;
    public function __construct($config)
    {
        $this->config = $config;
    }

    public function search($parameters, $appId)
    {
        try {
            $elasticService = new ElasticService();
            $elasticService->setConfig($this->config);
            $text = $parameters['searchtext'];
            // $type = $parameters['type'];
            $pagesize = (isset($parameters['pagesize'])) ? $parameters['pagesize'] : 25;
            $start = (isset($parameters['start'])) ? $parameters['start'] : 0;
            $index = ($appId) ? $appId : '_all';
            $accountId = AuthContext::get(AuthConstants::ACCOUNT_ID);
            $body = array();
            if (isset($parameters['type'])) {
                $body['query']['bool']['filter']['must'] = [
                    ['term' => ['account_id' => $accountId]],
                    ['term' => ['type' => $type]],
                ];
            } else {
                $body['query']['bool']['filter'] = ['term' => ['account_id' => $accountId]];
            }
            $fieldList = $elasticService->getBoostFields('user');
            $body['query']['bool']['should'] = [
                "multi_match" => [
                    "fields" => $fieldList,
                    "query" => $text,
                    "type"=> "best_fields",
                    "fuzziness" => 'auto',
                    "prefix_length" => 3,
                ],
            ];
            $body['highlight'] = [
                'order' => 'score',
                "require_field_match" => 'true',
                'fields' => [
                    "*" => [
                        'force_source' => false,
                        "pre_tags" => [
                            "<b class='highlight'>",
                        ],
                        "post_tags" => ["</b>"],
                        'number_of_fragments' => 3,
                        'fragment_size' => 100]],
                'encoder' => 'html',
            ];
            $body['min_score'] = "0.1";
            $source = "*";
            $data = $elasticService->getSearchResults($index, $body, $source, $start, $pagesize);
            return $data;
        } catch (Exception $e) {
            throw new Exception("Error performing Elastic Search", 0, $e);
        }
    }
}
