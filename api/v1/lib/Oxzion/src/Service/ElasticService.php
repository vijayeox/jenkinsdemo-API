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

	public function getSearchResults($entity,$body,$source,$start,$pagesize) {
		$params = array('index'=>$this->core.'_'.$entity,'type'=>$this->type,'body'=>$body,"_source"=>$source,'from'=>$start?$start:0,"size"=>$pagesize);
		$result = $this->search($params);
		return $result;
	}

	public function FilterWithParams($params){
		$searchconfig['name'] = $params['name'];
		$searchconfig['orgid'] = $params['orgid'];
		$searchconfig['fields'] = $params['fields'];
		$searchconfig['select'] = $params['select'];
		$searchconfig['start'] = $params['start'];
		$searchconfig['pagesize'] = $params['pagesize'];
		$searchconfig['filter'] = $params['filter'];
		$searchconfig['range'] = $params['range'];
		$searchconfig['group'] = $params['group'];
		$searchconfig['aggregates'] = $params['aggregates'];
		$searchconfig['type'] = $params['type'];
		$searchconfig['sort'] = $params['sort'];
		$searchconfig['listfields'] = $params['listfields'];
		if(json_decode($params['searchval'],true)){
			$searchconfig['searchtext'] = json_decode($params['searchval'],true);
		} else {
			$searchconfig['searchtext'] = $params['searchval'];
		}
		if($params['formid']||$params['moduleid']){
			$params['entity'] = 'form';
			$searchconfig['formid'] = $params['formid'];
			$searchconfig['moduleid'] = $params['moduleid'];
		}
		if($params['entity']){
			$result = $this->FilterDataByEntity($this->getIndex($params['entity']), $searchconfig);
		}else{
			if($params['source']=='instanceforms'){
				return $this->FilterDataByEntity($this->getIndex($params['source']), $searchconfig);
			} else if($params['source']=='timesheet'){
				$result['timesheet'] = $this->FilterDataByEntity($this->getIndex($params['source']), $searchconfig);
			} else {
				$result['form'] = $this->FilterDataByEntity($this->getIndex('form'), $searchconfig);
				$result['comment'] = $this->FilterDataByEntity($this->getIndex('comment'), $searchconfig);
				$result['message'] = $this->FilterDataByEntity($this->getIndex($params['entity']), $searchconfig);
				$result['ole'] = $this->FilterDataByEntity($this->getIndex($params['entity']), $searchconfig);
				$result['user'] = $this->FilterDataByEntity($this->getIndex($params['entity']), $searchconfig);
				$result['attachment'] = $this->FilterDataByEntity($this->getIndex($params['entity']), $searchconfig);
			}
		}
		return $result
;    }
    
	protected function getFieldsList(&$searchconfig,$entity){
		if($entity=='instanceforms'){
			if($searchconfig['select']){
				return array_values($searchconfig['select']);
			}
			if($searchconfig['formid']){
			//	$form = new VA_Logic_MetaForm($searchconfig['formid']);
				return array_column($form->getFieldsWithTitle(),'name');
			} else {
		//			return array_column(VA_Logic_Utilities::getDefaultFieldsWithTitle(),'field');
			}
		} else if($entity=='user'){
			return array('id','firstname','lastname','name','about');
		} else if($entity=='formcomments'){
			return array('instanceform_id','comment','title','date_created','commenting_user');
		}else if($entity=='messages'){
			return array('id','instanceformid','from_user','date_created','message','subject');
		}else if($entity=='attachments'){
			return array('id','instanceformid','from_user','date_created','message','subject','title','filename');
		}else if($entity=='timesheet'){
			return array('id','name','status');
		} else if($entity=='ole'){
			return array('id','ole_group','createdby_user','groupid','ole');
		} else {
			return array('id');
		}
    }
    
	public function getIdByEntity($entity){
		switch ($entity){
			case 'instanceforms':
			return 'id';
			break;
			case 'formcomments':
			return 'instanceform_id';
			break;
			case 'messages':
			return 'id';
			break;
			case 'ole':
			return 'id';
			break;
			case 'user':
			return 'id';
			break;
			case 'attachments':
			return 'id';
			break;
			default:
			return 'id';
			break;
		}
	}

	public function FilterDataByEntity($entity, &$searchconfig, $sortkey=null, $sortdir=null){
		list($entity_type, $type) = explode('_',$entity);
		$tmpfilter = $this->getDefaultFilters($searchconfig,$entity);
		if ($tmpfilter) {
			$boolfilterquery['query']['bool']['filter'] = array($this->getDefaultFilters($searchconfig,$entity));
		}
		if($searchconfig['select']){
			foreach ($searchconfig['select'] as  $value) {
				$listoffields[] = $value;
			}
			$boolfilterquery['_source'] = $listoffields;
		} else {
			if($searchconfig['fields']){
				$boolfilterquery['_source'] = $searchconfig['fields'];
			} else {
				if (!$this->onlyaggs) $boolfilterquery['_source'] = $this->getFieldsList($searchconfig,$entity);
			}
		}
		if (!$this->onlyaggs) {
			$boolfilterquery['_source'] = array_unique($boolfilterquery['_source']);
			// if($this->avatarobj->getFlagValue('opendata')||$searchconfig['type']){
			// 	$boolfilterquery['query']['bool']['must']['bool']['should'][] = array('range'=>array('status_key'=>array("gte"=>0,"lte"=>100)));
			// 	$boolfilterquery['query']['bool']['must']['bool']['should'][] = array('range'=>array('status_key'=>array("gte"=>110)));
			// }	
		}
		if($searchconfig['searchtext']){
			$boolfilterquery['query']['bool']['should']=$this->getTextQuery($query,$searchconfig['searchtext'],$entity,$boolfilterquery['_source']);
			$boolfilterquery['highlight'] = $this->generateHighlightingFields($boolfilterquery,$boolfilterquery['_source']);
			$boolfilterquery['min_score'] = "0.5";
		}
		$pagesize = isset($searchconfig['pagesize'])?$searchconfig['pagesize']:499999;
		if($searchconfig['aggregates']) {
			if ($this->onlyaggs) {
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
		$params = array('index'=>$this->core.'_'.$entity,'type'=>$this->type,'body'=>$boolfilterquery,"_source"=>$boolfilterquery['_source'],'from'=>$searchconfig['start']?$searchconfig['start']:0,"size"=>$pagesize);
		// print_r($params);exit;
		$result_obj = $this->search($params);
			// print_r($result_obj);exit;
		if ($this->onlyaggs) {
			if ($searchconfig['group']) {
				$results = array('data'=>$result_obj['aggregations']['groupdata']['buckets']);
			} else if(key($searchconfig['aggregates'])=='count'){
				$results = array('data'=>$result_obj['hits']['total']);
			} else {
				$results = array('data'=>$result_obj['aggregations']['value']['value']);
			}
			if(empty($results['data'])&&$results['data']!=0){
					// print_r($searchconfig);
				//	print_r($params);
					// print_r($result_obj);
				//	exit;
			}
		} else {
			$results = array();
			// print_r($result_obj);exit;
			$idparam = $this->getIdByEntity($entity);
			foreach($result_obj['hits']['hits'] as $key=>$value){
				$results['data']['response']['docs'][$key] = $value['_source'];
				$results['data']['response']['docs'][$key]['id'] = $value['_source'][$idparam];
				$highlightarray = array();
				foreach ($value['highlight'] as $k => $v) {
					foreach ($v as $val) {
						$val = strip_tags($val,'<b>');
						if(in_array($k, array_values($this->getContentKeys($entity)))){
							$highlightarray[] = ucfirst($k)." : ".strip_tags($val,'<b>');
						} else {
							if($k=='name'){
								$highlightarray[] = ucfirst($k)." : ".strip_tags($val,'<b>');
							}
							if($k=='desc_raw'||$k=='message'){
								 $highlightarray[] = "Description : ".strip_tags(html_entity_decode($val),'<b>');
							
							}
						}
					}
				}
				if(count($highlightarray)>1){
					$results['data']['highlighting'][$value['_source']['id']]['data'] = implode(" , ",$highlightarray);
				} else {
					if($entity=='formcomments'){
						$results['data']['highlighting'][$value['_source']['instanceform_id']]['data'] = $highlightarray[0];
					} else {
						if($highlightarray){
							$results['data']['highlighting'][$value['_source']['id']]['data'] = $highlightarray[0];
						}
					}
				}
			}
			$results['data']['response']['start'] = $searchconfig['start'];
			$results['data']['response']['numFound'] = $result_obj['hits']['total'];
			$results['display_keys'] = $this->getDisplayKeys($entity);
			$results['fields'] = $searchconfig['select'];
			$results['listfields'] = $searchconfig['listfields'];
			$results['content_keys'] = $this->getContentKeys($entity);
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
	

	protected function getContentKeys($entity){
		switch ($entity){
			case 'instanceforms':
			return array(VA_Logic_Session::translate('MOD_SEARCH_ID')=>'entity_id',
				VA_Logic_Session::translate('MOD_EMPLOYEE_ASSIGNED_GROUP')=>'assignedgroup',VA_Logic_Session::translate('MOD_EMPLOYEE_AVATAR_MODULE_OWNER_GROUP_NEW')=>'ownergroup',
				VA_Logic_Session::translate('MOD_EMPLOYEE_AVATAR_MODULE_STATUS')=>'statusname',VA_Logic_Session::translate('MOD_EMPLOYEE_AVATAR_MODULE_ASSIGNEDTO')=>'assignedto',
				VA_Logic_Session::translate('MOD_EMPLOYEE_AVATAR_MODULE_OWNER')=>'createdby');
			break;
			case 'formcomments':
			return array(VA_Logic_Session::translate('MOD_SEARCH_ID')=>'instanceform_id',
				VA_Logic_Session::translate('MOD_SEARCH_TITLE')=>'title',
				"Comment"=>'comment',
				VA_Logic_Session::translate('MOD_SEARCH_COMMENTING_USER')=>'commenting_user',VA_Logic_Session::translate('MOD_SEARCH_COMMENTED_CREATED')=>'date_created');
			break;
			case 'messages':
			return array(VA_Logic_Session::translate('MOD_SEARCH_ID')=>'id',VA_Logic_Session::translate('MOD_EMPLOYEE_AVATAR_INBOX_FROM')=>'from_user',VA_Logic_Session::translate('MOD_SEARCH_MESSAGE_ON')=>'date_created','Subject'=>'subject');
			break;
			case 'ole':
			return array(VA_Logic_Session::translate('MOD_SEARCH_ID')=>'id',VA_Logic_Session::translate('MOD_SEARCH_GROUP')=>'ole_group',
				VA_Logic_Session::translate('MOD_SEARCH_OLE_CREATED_BY')=>'createdby_user','Group Chat'=>'ole');
			break;
			case 'user':
			return array("First Name"=>'firstname',"Last Name"=>'lastname');
			break;
			case 'attachments':
			return array(VA_Logic_Session::translate('MOD_SEARCH_ID')=>array('instanceformid','messageid'),
				VA_Logic_Session::translate('MOD_CLUBVA_EMPLOYEE_EMAIL_TITLE_SUBJECT')=>'subject',
				VA_Logic_Session::translate('MOD_EMPLOYEE_AVATAR_INBOX_FROM')=>'from_user',
				VA_Logic_Session::translate('MOD_EMPLOYEE_ASSIGNED_GROUP')=>'assignedgroup',
				VA_Logic_Session::translate('MOD_EMPLOYEE_AVATAR_MODULE_OWNER_GROUP_NEW')=>'ownergroup',
				VA_Logic_Session::translate('MOD_EMPLOYEE_AVATAR_MODULE_ASSIGNEDTO')=>'assignedto',
				VA_Logic_Session::translate('MOD_EMPLOYEE_AVATAR_MODULE_OWNER')=>'createdby','Attachment'=>'attachment.content','Filename'=>'filename');
			break;
			default:
			return array('fields'=>'*');
			break;
		}
	}

	protected function getDisplayKeys($entity){
		switch ($entity){
			case 'instanceforms':
			return array('id'=>'entity_id','text'=>'name');
			break;
			case 'formcomments':
			return array('id'=>'instanceform_id','text'=>'comment');
			break;
			case 'messages':
			return array('id'=>'id','text'=>'subject');
			break;
			case 'ole':
			return array('id'=>'reply_id','text'=>'ole','param1'=>'groupid');
			break;
			case 'user':
			return array('id'=>'id','text'=>array('firstname','lastname'));
			break;
			case 'attachments':
			return array('id'=>'id','text'=>'filename');
			break;
			default:
			return array('fields'=>'*');
			break;
		}
	}

	protected function generateHighlightingFields($query,$fields){
		return array('order'=>'score',"require_field_match"=>'true','fields'=>array("*"=>array('force_source'=>false,"pre_tags"=>array("<b class='highlight'>"),"post_tags"=>array("</b>"),'number_of_fragments'=>3,'fragment_size'=>100)),'encoder'=>'html');
		// foreach ($fields as $key => $value) {
		// 	if(!strpos($value, 'date')&&$value!='date_created'&&$value!='date_modified'&&$value!='id'){
		// 		// $filterfields[] = $value;
		// 		$query['highlight']['fields'][$value] = array(); 
		// 	}
		// }
		// return $query;
	}

	protected function getTextQuery($query,$text,$entity,$fields){
		// foreach ($fields as $key => $value) {
		// 	if(!strpos($value, 'date')&&$value!='date_created'&&$value!='date_modified'){
		// 		$filterfields[] = $value;
		// 		$query['bool']['should']['multi_match']['query'] = $text; 
		// 	}
		// }
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
			if($entity=='instanceforms'){
				$aggs = array('value'=>array(key($aggregates)=>array("script"=>array("inline"=>"try { return Float.parseFloat(doc['".$aggregates[key($aggregates)]."_key.keyword'].value); } catch (NumberFormatException e) { return 0; }"))));
			} else {
				$aggs = array('value'=>array(key($aggregates)=>array("script"=>array("inline"=>"try { return Float.parseFloat(doc['".$aggregates[key($aggregates)].".keyword'].value); } catch (NumberFormatException e) { return 0; }"))));
			}
		}
		return $aggs;
	}
	protected function getIndex($entity){
		switch ($entity) {
			case 'form':
			return 'instanceforms';
			case 'comment':
			return 'formcomments';
			case 'message':
			return 'messages';
			case 'attachment':
			return 'attachments';
			default:
			return $entity;
			break;
		}
	}
	protected function getBoostFields($entity,$fields){
		switch ($entity){
			case 'instanceforms':
				return array('id^6','name^4','desc_raw^0.1','assignedto^2','createdby^2');
				break;
			case 'formcomments':
				return array('id^6','comment^4','title^4');
				break;
			case 'messages':
				return array('id^6','subject^4','message^2');
				break;
			case 'ole':
				return array('id^4','ole^6','group^3');
				break;
			case 'user':
				return array('id^6','firstname^2','lastname^2','name^4','about^0.1');
				break;
			case 'attachments':
				return array('id^6','attachment.content^4','filename^2');
				break;
			default:
				return ;
				break;
		}
	}

	protected function getDefaultFilters(&$searchconfig,$entity){
		$avatar_groups = $this->avatarobj->getGroupArray();
		if (!$this->onlyaggs) {
			$mustquery[] =array('term'=>array('orgid'=>$searchconfig['orgid']));
		}
		if (isset($searchconfig['aggregates'])) {
			$aggregates= $searchconfig['aggregates'];
			if($aggregates){
					$mustquery[] = array('exists'=>array('field'=>$aggregates[key($aggregates)]));
			}
		}
		if($entity=='instanceforms' || $entity=='timesheet'){
			if($searchconfig['formid']){
				$mustquery[] = array('term'=>array('formid'=>$searchconfig['formid']));
			} else {
				if($searchconfig['moduleid']){
					$modulemapper = new VA_Model_MetaForms();
					$formlist = $modulemapper->enlistByModuleAndOrgId($searchconfig['moduleid'],$searchconfig['orgid']);
					$mustquery[] = array('terms'=>array('formid'=>array_column($formlist, 'id')));
				}
			}
			if($searchconfig['filter']){
				foreach ($searchconfig['filter'] as $key => $value) {
					$type = '';
					if(strpos($key,'__') !== false) list($key,$type) = explode('__',$key);
					if(!is_array($value)){
//						if($key=='status'){
//							$mustquery[] = array('term'=>array($key."id"=>$value));
//						} else 
						if($key =='formid'){
							$mustquery[] = array('term'=>array($key=>$value));
						} else {
							if ($type=='value') {
								$mustquery[] = array('match'=>array($key=>array('query'=>$value,'operator'=>'and')));
							}else {						
								$mustquery[] = array('term'=>array($key."_key"=>$value));
							}
						}
					} else {
						if($key =='formid' || $type=='value'){
							$mustquery[] = array('terms'=>array($key=>array_values($value)));
						} else {
							$mustquery[] = array('terms'=>array($key."_key"=>array_values($value)));
						}
					}
				}
			}
		 } else {
			if($searchconfig['filter']){
				foreach ($searchconfig['filter'] as $key => $value) {
					if(!is_array($value)){
							$mustquery[] = array('term'=>array($key.'.keyword'=>$value));
						} else {
							$mustquery[] = array('terms'=>array($key.'.keyword'=>array_values($value)));
						}
					} 
				}
		}
		if($searchconfig['range']){
			$daterange = $searchconfig['range'][key($searchconfig['range'])];
			$dates = explode("/", $daterange);
			$mustquery[] = array('range'=>array(key($searchconfig['range'])=>array("gte"=>$dates[0],"lte"=>$dates[1])));

		}
		if (!$this->onlyaggs) {
			$mustquery[] = $this->getFiltersByEntity($entity);
		}
		if($this->avatarobj->getFlagValue('onlyme') && ($entity_type == 'form' || $entity_type == 'formcomments')){
			$mustquery[] = array('term'=>array('createdby_user'=>$searchconfig['name']));
			$mustquery[] = array('term'=>array('assignedto_user'=>$searchconfig['name']));
		}
		if($searchconfig['type']=='assignments'){
			$mustquery[] = array('term'=>array('assignedtoid'=>$this->avatarobj->id));
		}
		if($searchconfig['type']=='followups'){
			$mustquery[] = array('term'=>array('createdid'=>$this->avatarobj->id));
		}
		// print_r($mustquery);exit;
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

<<<<<<< HEAD
	public function index($index,$id,$body) {
		$params['index']=$index;
		$params['id']=$id;
		$params['type']=$this->type;
		$params['body']=$body;
=======
	public function index($entity,$params) {
		$params['index']=$this->core.'_'.$entity;
>>>>>>> 468b4ac1d73402a05d9233178c0fc79f92e45865
		return $this->client->index($params);
	}

	public function delete($index,$id) {
		if ($id=='all') {
			$this->client->indices()->delete(['index'=>$index]);
		} else {
			$this->client->delete(['index'=>$index,'type'=>$this->type,'id'=>$id]);
		}
	}

}
?>