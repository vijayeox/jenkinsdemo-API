<?php
namespace Oxzion\Service;

use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;

ini_set("memory_limit", -1);
class ElasticService{
	private $avatarobj;
	private $elasticaddress;
	private $type;
	private $core;
	private $onlyaggs;
	private $config;
	private $client;

	public function __construct($config){
		$this->config = $config;
        $clientsettings = array();        
		$clientsettings['host'] = $config['elasticsearch']['serveraddress'];
		$clientsettings['user'] = $config['elasticsearch']['user'];
		$clientsettings['pass'] = $config['elasticsearch']['password'];
		$clientsettings['type'] = $config['elasticsearch']['type'];
		$clientsettings['port'] = $config['elasticsearch']['port'];
		$clientsettings['scheme'] = $config['elasticsearch']['scheme'];
		$this->core = $config['elasticsearch']['core'];
		$this->type = $config['elasticsearch']['type'];
		$this->client= ClientBuilder::create()->setHosts(array($clientsettings))->build();
	}

	
	public function getSettings() {
		return array('index'=>$this->core,'type'=>$this->type);
	}

	public function setIsOnlyAggs(){
		$this->onlyaggs=1;
	}

	public function FilterDirect($entity,$bodyjson) {
		$body=json_decode($bodyjson,true);
		$params = array('index'=>$this->core.'_'.$entity,'type'=>$this->type,'body'=>$body,"size"=>0);
		$result_obj = $this->search($params);
		if (isset($body['aggs']) && isset($result_obj['aggregations']['groupdata']['buckets'])) {
			$results = array('data'=>$result_obj['aggregations']['groupdata']['buckets']);
		} else if(isset($result_obj['aggregations'])){
			$results = array('data'=>$result_obj['aggregations']['value']['value']);
			
		} else {
			$results = array('data'=>$result_obj['hits']['total']);
		}
		return $results;
	}

	public function getSearchResults($index,$body,$source,$start,$pagesize) {
		$params = ['index'=>$index,'type'=>$this->type,'body'=>$body,"_source"=>$source,'from'=>$start?$start:0,"size"=>$pagesize];
		$result = $this->search($params);
		return $result;
	}

	public function getQueryResults($appId,$params) {
		$searchconfig['fields'] = $params['fields'];
		$searchconfig['start'] = $params['start'];
		$searchconfig['pagesize'] = $params['pagesize'];
		$searchconfig['filter'] = $params['filter'];
		$searchconfig['range'] = $params['range'];
		$searchconfig['group'] = $params['group'];
		$searchconfig['aggregates'] = $params['aggregates'];
		$searchconfig['sort'] = $params['sort'];
		$searchconfig['select'] = $params['select'];
		$result = $this->filterData($appId,$params);
		return $result;

	}



	public function filterData($appId,$searchconfig) {
		$boolfilter = array();
		$tmpfilter = $this->getFilters($searchconfig);
		if ($tmpfilter) {
			$boolfilterquery['query']['bool']['filter'] = array($tmpfilter);
		}
		if(isset($searchconfig['select'])){
			$boolfilterquery['_source'] = $searchconfig['select'];
		} 
		$pagesize = isset($searchconfig['pagesize'])?$searchconfig['pagesize']:499999;
		if($searchconfig['aggregates']) {
			if (isset($searchconfig['select'])) {
				$pagesize=0;
			}
			$aggs=$this->getAggregate($searchconfig['aggregates'],$boolfilterquery,$entity);		
			if($searchconfig['group']) {
				$this->getGroups($searchconfig,$boolfilterquery,$aggs);
			} else {
				if($aggs){
					$pagesize=0;
					$boolfilterquery['aggs']=$aggs;
				}
			}
		}
		$boolfilterquery['explain'] = true;
		$params = array('index'=>$appId,'type'=>$this->type,'body'=>$boolfilterquery,"_source"=>$boolfilterquery['_source'],'from'=>$searchconfig['start']?$searchconfig['start']:0,"size"=>$pagesize);
		$result_obj = $this->search($params);
		if (!isset($searchconfig['select'])) {
			if ($searchconfig['group']) {
				$results = array('data'=>$result_obj['aggregations']['groupdata']['buckets']);
			} else if(key($searchconfig['aggregates'])=='count'){
				$results = array('data'=>$result_obj['hits']['total']);
			} else {
				$results = array('data'=>$result_obj['aggregations']['value']['value']);
			}
		} else {
			$results = array();
			foreach($result_obj['hits']['hits'] as $key=>$value){
				$results['data']['response']['docs'][$key] = $value['_source'];
				$results['data']['response']['docs'][$key]['id'] = $value['_source']['_id'];
			}
		}
		return $results;
	}
	

	protected function getGroups($searchconfig,&$boolfilterquery,$aggs){

		$grouparray=null;
		$size = (isset($searchconfig['pagesize'])) ? $searchconfig['pagesize']:499999;
		for($i= count($searchconfig['group'])-1;$i>=0;$i--) {
			$grouptext=$searchconfig['group'][$i];			
			if (substr($grouptext,0,7)=="period-") {
				$interval = substr($grouptext,7);
				if ($interval=="day") {
					$format="yyyy-MM-dd";
				} elseif ($interval=="year") {
					$format="yyyy";
				} else {
					$format="MMM-yyyy";
				}					
				$grouparraytmp = array('date_histogram'=>array('field'=>key($searchconfig['range']),'interval'=>$interval,'format'=>$format));
			} else {
				$grouparraytmp = array('terms'=>array('field'=>$grouptext.'.keyword','size'=>$size));
				$boolfilterquery['_source'][] = $grouptext;
			}						
		
			if ($grouparray) {			    						
				$grouparray = array_merge($grouparraytmp,array('aggs'=>array('groupdata'.$i=>$grouparray)));					
			} else {
				if (isset($searchconfig['sort'])){
					if ($aggs) {
						$grouparraytmp['terms']['order'] = array("value"=>$searchconfig['sort']);
					} else {
						$grouparraytmp['terms']['order'] = array("_count"=>$searchconfig['sort']);
					}
				}
				if ($aggs) {
					$grouparray = array_merge($grouparraytmp,array('aggs'=>$aggs));
				} else {
					$grouparray = $grouparraytmp;
				}
			}
		}
		
		$boolfilterquery['aggs']=array('groupdata'=>$grouparray);
//		print_r($boolfilterquery);exit;
	}
	

	protected function generateHighlightingFields($query,$fields){
		return array('order'=>'score',"require_field_match"=>'true','fields'=>array("*"=>array('force_source'=>false,"pre_tags"=>array("<b class='highlight'>"),"post_tags"=>array("</b>"),'number_of_fragments'=>3,'fragment_size'=>100)),'encoder'=>'html');
	}

	protected function getTextQuery($query,$text,$entity,$fields){
		if (strpos($text, 'query:') !== false) {
			$query['bool']['must'][] = array("query_string"=>(array("query"=>str_replace('query:', "", $text))));
		} else {
			// $query['bool']['should'][] = array("query_string"=>(array("query"=>$text)));
			$query['bool']['should'][] = array("multi_match"=>array("fields"=>$this->getBoostFields($entity,$fields),"query"=>$text,"fuzziness"=>"AUTO"));
		}
		return $query;
	}
	protected function getAggregate($aggregates,$filter,$entity){
		$aggs=null;
		if (key($aggregates)=='count_distinct') {
			$aggs = array('value'=>array("cardinality"=>array("field"=>$aggregates[key($aggregates)])));
		} else if (key($aggregates)!="count") {
				$aggs = array('value'=>array(key($aggregates)=>array("script"=>array("inline"=>"try { return Float.parseFloat(doc['".$aggregates[key($aggregates)].".keyword'].value); } catch (NumberFormatException e) { return 0; }"))));
		}
		return $aggs;
	}


	protected function getFilters($searchconfig) {
		if (isset($searchconfig['aggregates'])) {
			$aggregates= $searchconfig['aggregates'];
			if($aggregates){
					$mustquery[] = array('exists'=>array('field'=>$aggregates[key($aggregates)]));
			}
		}
		if($searchconfig['filter']){
			foreach ($searchconfig['filter'] as $key => $value) {
				$type = '';
				if(strpos($key,'__') !== false) list($key,$type) = explode('__',$key);
				if(!is_array($value)){
					if ($type=='value') {
						$mustquery[] = array('match'=>array($key=>array('query'=>$value,'operator'=>'and')));
					}else {						
						$mustquery[] = array('term'=>array($key."_key"=>$value));
					}
				} else {
					if($type=='value'){
						$mustquery[] = array('terms'=>array($key=>array_values($value)));
					} else {
						$mustquery[] = array('terms'=>array($key."_key"=>array_values($value)));
					}
				}
			}
		}
		return $mustquery;

	}



	protected function getFiltersByEntity($entity){
		$avatar_groups = $this->avatarobj->getGroupArray();
		switch ($entity) {
			case 'instanceforms':
			case 'formcomments':
			$mustquery['bool']['should'][] = array('terms'=>array('ownergroupid'=>array_values($avatar_groups)));
			$mustquery['bool']['should'][] = array('terms'=>array('assignedgroupid'=>array_values($avatar_groups)));
			break;
			case 'messages':
			$mustquery['bool']['should'][] = array('match'=>array('recipient_list'=>$this->avatarobj->name));
			$mustquery['bool']['should'][] = array('term'=>array('from_user'=>$this->avatarobj->name));
			break;
			case 'ole':
			$mustquery[] = array('terms'=>array('groupid'=>array_values($avatar_groups)));
			break;
			case 'user':
			$mustquery[] = array('match'=>array('status'=>'Active'));
			$mustquery[] = array('match'=>array('role'=>'employee'));
			break;
			case 'timesheet':
			$mustquery[] = array('terms'=>array('group_id'=>array_values($avatar_groups)));
			break;
			case 'attachments':
			$mustquery['bool']['should'][] = array('terms'=>array('ownergroupid'=>array_values($avatar_groups)));
			$mustquery['bool']['should'][] = array('terms'=>array('assignedgroupid'=>array_values($avatar_groups)));
			$mustquery['bool']['should'][] = array('match'=>array('recipient_list'=>$this->avatarobj->name));
			$mustquery['bool']['should'][] = array('term'=>array('from_user'=>$this->avatarobj->name));
			break;
			default:
			break;
		}
		// print_r($mustquery);exit;
		return $mustquery;
	}

	public 	function search($q){
	//	 echo '<pre>';print_r($q);echo '</pre>'; exit;
	//	 echo '<pre>';print_r($client->search($q));echo '</pre>';exit;
		return $this->client->search($q);

	}

	public function index($index,$id,$body) {
		$params['index']=$index;
		$params['id']=$id;
		$params['type']=$this->type;
		$params['body']=$body;
		return $this->client->index($params);
	}

	public function delete($index,$id) {
		if ($id=='all') {
			return $this->client->indices()->delete(['index'=>$index]);
		} else {
			return $this->client->delete(['index'=>$index,'type'=>$this->type,'id'=>$id]);
		}
	}

}
?>