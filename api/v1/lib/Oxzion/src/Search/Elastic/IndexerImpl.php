<?php
namespace Oxzion\Search\Elastic;

use Oxzion\Auth\AuthConstants;
use Oxzion\Auth\AuthContext;
use Oxzion\Search\Indexer;
use Oxzion\Service\ElasticService;

class IndexerImpl implements Indexer
{
    private $config;
    public function __construct($config)
    {
        $this->config = $config;
    }

    public function index($app_name, $id, $entity_name, $body, $fieldTypeAarray = null)
    {
        try {
            if (empty($body['account_id'])) {
                $account_id = AuthContext::get(AuthConstants::ACCOUNT_ID);
                $body['account_id'] = $account_id;
            }
            if (empty($body['entity_name'])) {
                $body['entity_name'] = $entity_name;
            }
            $index = $app_name.'_index';
            if ($fieldTypeAarray) {
                foreach ($body as $key => $value) {
                    if (isset($fieldTypeAarray[$key])) {
                        if ($fieldTypeAarray[$key] == 'date') {
                            $body[$key] = DateTime::createFromFormat("yyyy-MM-dd HH:mm:ss", $value);
                        }
                    }
                }
            }
            $elasticService = new ElasticService();
            $elasticService->setConfig($this->config);
            $response = $elasticService->index($index, $id, $body);
            return $response;
        } catch (Exception $e) {
            throw new Exception("Could not Perform Elastic Index", 0, $e);
        }
    }

    public function delete($appId, $id)
    {
        try {
            $elasticService = new ElasticService();
            $elasticService->setConfig($this->config);
            $index = $appId;
            $response = $elasticService->delete($index, $id);
            return $response;
        } catch (Exception $e) {
            throw new Exception("Could not Delete Elastic Index", 0, $e);
        }
    }


    public function bulk($body)
    {
        try {
            $elasticService = new ElasticService();
            $elasticService->setConfig($this->config);
            $response = $elasticService->bulk($body);
            return $response;
        } catch (Exception $e) {
            throw new Exception("Elastic:Could not Add Bulk Data", 0, $e);
        }
    }

    public function bulkArray($appID,$body) {
        $core = null;
        if (isset($this->config['elasticsearch']['core'])) {
            $core = $this->config['elasticsearch']['core'];
        }
        $elasticService = new ElasticService($this->config);
        $index = $appID;
        if (substr($index,-6)!="_index") {
            $index = $index."_index";
        }
        $index = ($core) ? $core.'_'.$index:$index;
        $i = 0;
        $params = ['body' => []];
        foreach ($body as $record) {
            $params['body'][] = [
                'index' => [
                    '_index' => $index,
                    '_id'    => $record['id']
                ]
            ];
        
            $params['body'][] = $record;
        
            // Every 1000 documents stop and send the bulk request
            if ($i % 1000 == 0) {
                $response = $elasticService->bulk($params);
                // erase the old bulk request
                $params = ['body' => []];
        
                // unset the bulk response when you are done to save memory
                unset($response);
            }
            if (!empty($params['body'])) {
                $response = $elasticService->bulk($params);
            }
            $i++;
        }
        return ;

    }

}
