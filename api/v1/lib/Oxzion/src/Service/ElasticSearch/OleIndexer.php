<?php
namespace ElasticSearch;
require __DIR__ .'/../autoload.php';
use Oxzion\Dao;
use Elasticsearch\ClientBuilder;
use DateTime;

class OleIndexer {

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
		$this->core = $ini['resources.elastic.core'].'_ole';
		$this->client = ElasticClient::createClient();
	}

	public function __destruct(){
		$this->dao->close();
	}

	public function index($params = array()){
		$where = '';
		if(isset($params['id'])){
			$id = $params['id'];
			// $this->delete(array('id'=>$id));
			$where = "where c.id =".$id;
		}
		$sql = "select c.id, c.comment, c.date_created, c.date_modified, c.replyid, c.groupid,
		g.name as ole_group, CONCAT(a.firstname, ' ',a.lastname) as created_by, a.orgid
		from comments c inner join groups g on g.id = c.groupid
		inner join avatars a on a.id = c.avatarid 
		$where;";
		if(!$result = $this->dao->execQuery($sql)){
			return;
		}
		$fails = 0;
		$total = 0;
		while ($data = $result->fetch_assoc()) {
			$formData = array();
			$formData['entity_type'] = 'OLE';
			$formData['id'] = $data['id'];
			$formData['entity_id'] = $data['id'];
			$formData['ole'] = $data['comment'];
			$formData['ole_group'] = $data['ole_group'];
			$formData['createdby_user'] = $data['created_by'];
			$formData['orgid'] = $data['orgid'];
			$formData['groupid'] = $data['groupid'];
			$formData['replyid'] = $data['replyid'];
			$formData['date_created'] = $data['date_created'];

			$dateValue = DateTime::createFromFormat(DB_DATETIME_FORMAT, $data['date_modified']);
			if($dateValue){
				$formData['date_modified'] = $dateValue->format(SOLR_DATETIME_FORMAT);
			}		
			$params['body'][] = ['index' => ['_index' => $this->core,'_type' => $this->type,'_id' => $formData['id']]];
			$params['body'][] = $formData;
			if(!isset($id)){
				if ($i % 1000 == 0) {
					$responses = $this->client->bulk($params);
        			// erase the old bulk request
					$params = array();
					echo ' Number of Records indexed: '.$i.' is Completed for '.$row['name'];
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
			$searchparams = ['index' => $this->core,'type' => $this->type,'body' => ['query' => ['match' => ['id' => $id]]]];
			if($this->client->search($searchparams)['hits']['total']>0){
				$deleteparams = ['index' => $this->core,'type' => $this->type,'id' => $id];
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