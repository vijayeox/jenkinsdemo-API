<?php
namespace ElasticSearch;
require __DIR__ .'/../autoload.php';
use Oxzion\Dao;
use DateTime;
use Elasticsearch\ClientBuilder;

ini_set('memory_limit', -1);
class FormCommentIndexer{

	private $dao;
	public $type;
	private $elasticaddress;
	private $core;
	private $client;

	public function __construct(){
		$this->dao = new Dao();
		$ini = parse_ini_file(dirname(dirname(dirname(dirname((__DIR__))))).'/application/configs/application.ini');
		$this->elasticaddress = $ini['resources.elastic.serveraddress'];
		$this->type = $ini['resources.elastic.type'];
		$this->core = $ini['resources.elastic.core'].'_formcomments';
		$this->client = ElasticClient::createClient();
	}
	public function __destruct(){
		$this->dao->close();
	}

	public function index($params = array()){
		$id = null;
		if(isset($params['id'])){
			$id = $params['id'];
			// $this->delete(array('id'=>$id));
		}
		$formStatusList = $this->fetchFormStatusList($id);
		return $this->indexFormComments($id, $formStatusList);
	}

	private function fetchFormStatusList($id = null){
		$where = "";
		if(isset($id)){
			$where = "where fm.id in 
			(select f.formid from instanceforms f inner join formcomments fc 
			on f.id = fc.instanceformid
			where fc.id = ".$id.")";
		}
		$sql = "select fm.id, fm.statuslist from metaforms fm $where;";
		if(!$result = $this->dao->execQuery($sql)){
			return;
		}
		$formStatusList = array();
		while ($row = $result->fetch_assoc()) {
			$formStatusList[$row['id']] = $this->dao->extractMap($row['statuslist']);
		}
		$result->free();
		return $formStatusList;
	}

	private function indexFormComments($id, $formStatusList){
		$where = "";
		if(isset($id)){
			$where .= "where fc.id = ".$id;
		}
		$sql = "select fc.id, fc.comment, fc.date_created, fc.date_modified, fc.status,
		CONCAT(o.firstname, ' ',o.lastname) as owner_user,
		CONCAT(au.firstname, ' ',au.lastname) as assigned_user,
		CONCAT(a.firstname, ' ',a.lastname) as comment_by,
		i.name as form_title, i.formid, fc.instanceformid, fc.replyid,
		m.name as module_name, i.orgid, g.id as assignedgroupid, og.id as ownergroupid,
		g.name as assigned_group, og.name as owner_group
		from formcomments fc inner join avatars a on a.id = fc.avatarid
		left outer join avatars o on o.id = fc.ownerid
		left outer join avatars au on au.id = fc.assignedto
		inner join instanceforms i on i.id = fc.instanceformid
		left outer join groups g on i.assignedgroup = g.id
		left outer join groups og on i.ownergroupid = og.id
		inner join modules m on m.id = fc.moduleid
		$where;";

		if(!$result = $this->dao->execQuery($sql)){
			return;
		}

		$fails = 0;
		$total = 0;
		$i=0;
		while ($data = $result->fetch_assoc()) {
			$formData = array();
			$formData['entity_type'] = 'COMMENT';
			$formData['id'] = $data['id'];
			$formData['entity_id'] = $data['id'];
			$formData['module'] = $data['module_name'];
			$formData['comment'] = $data['comment'];
			$formData['title'] = $data['form_title'];
			if(array_key_exists($data['formid'], $formStatusList) && 
				array_key_exists($data['status'], $formStatusList[$data['formid']])){
				$formData['status'] = $formStatusList[$data['formid']][$data['status']];
		}
		$formData['orgid'] = $data['orgid'];
		$formData['reply_id'] = $data['replyid'];
		$formData['instanceform_id'] = $data['instanceformid'];
		$formData['assignedgroupid'] = $data['assignedgroupid'];
		$formData['ownergroupid'] = $data['ownergroupid'];
		$formData['assignedgroup'] = $data['assigned_group'];
		$formData['ownergroup'] = $data['owner_group'];
		$formData['commenting_user'] = $data['comment_by'];
		$formData['owner_user'] = $data['owner_user'];
		$formData['assigned_user'] = $data['assigned_user'];
		$formData['date_created'] = $data['date_created'];
		$dateValue = DateTime::createFromFormat(DB_DATETIME_FORMAT, $data['date_modified']);
		if($dateValue){
			$formData['date_modified'] = $dateValue->format(SOLR_DATETIME_FORMAT);
		}	
				//var_dump($formData);
		$params['body'][] = ['index' => ['_index' => $this->core,'_type' => $this->type,'_id' => $formData['id']]];
		$params['body'][] = $formData;
		if(!isset($id)){
			if ($i % 1000 == 0) {
				$responses = $this->client->bulk($params);
        			// erase the old bulk request
				$params = array();
				echo ' Number of Records indexed: '.$i.' is Completed for formcomments';
				echo "\n";
        			// unset the bulk response when you are done to save memory
				unset($responses);
			}
			$i++;
		} else {
			$this->update($id,$formData);
		}
	}
	if (!empty($params['body'])&&!isset($id)) {
		$responses = $this->client->bulk($params);
	}
	$total = $result->num_rows;
	$result->free();

	return array('fails' => $fails, 'total' => $total);
} 
public function delete($params =array()){
	if($params['id']){
		$id = $params['id'];
		$searchparams = ['index' => $this->core,'type' => $this->type,'body' => ['query' => ['match' => ['id' => $params['id']]]]];
		if($this->client->search($params)['hits']['total']>0){
			$deleteparams = ['index' => $this->core,'type' => $this->type,'id' => $params['id']];
			$response = $this->client->delete($deleteparams);
		}
	}
}
public function update($id,$formData){
	$searchparams = ['index' => $this->core,'type' => $this->type,'body' => ['query' => ['match' => ['id' => $id]]]];
	if($this->client->search($searchparams)['hits']['total']>0){
		$params = ['index' => $this->core,'type' => $this->type,'id' => $id,'body' =>['doc'=> $formData]];
		return $this->client->update($params);
	} else {
		$params = ['index' => $this->core,'type' => $this->type,'id' => $id,'body' => $formData];
		return $this->client->index($params);
	}
}

}
?>