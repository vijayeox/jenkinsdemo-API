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



    public function search($parameters){
        $elasticService = new ElasticService($this->config);
        $text = $parameters['searchtext'];
        $pagesize= (isset($parameters['pagesize']))?$parameters['pagesize']:25;
        $start= (isset($parameters['start']))?$parameters['start']:0;
        $orgid = AuthContext::get(AuthConstants::ORG_ID);
        $body = array();
        $body['query']['bool']['filter'] = array('term'=>array('orgid'=>$orgid));
		$body['query']['bool']['should'] = array("multi_match"=>array("fields"=>array('id^6','name^4','desc_raw^0.1','assignedto^2','createdby^2'),"query"=>$text,"fuzziness"=>"AUTO"));
		$body['highlight'] = array('order'=>'score',"require_field_match"=>'true','fields'=>array("*"=>array('force_source'=>false,"pre_tags"=>array("<b class='highlight'>"),"post_tags"=>array("</b>"),'number_of_fragments'=>3,'fragment_size'=>100)),'encoder'=>'html');
        $body['min_score'] = "0.5";
        $source = array('id','name','statusname','createdby','assignedto','date_created','date_modified');
        $entity = 'instanceforms';
		$data = $elasticService->getSearchResults($entity,$body,$source,$start,$pagesize);
        return $data;
    }

    public function index($parameters){
        $entity = 'instanceforms';
        $elasticService = new ElasticService($this->config);       
        $response = $elasticService->index($entity,$parameters);
    }

}
?>