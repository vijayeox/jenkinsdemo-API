<?php
namespace ElasticSearch;

require __DIR__ .'/../autoload.php';
use Oxzion\Dao;
use Elasticsearch\ClientBuilder;
use Exception;
use DateTime;

ini_set('memory_limit', -1);
class MessageIndexer{
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
		$this->core = $ini['resources.elastic.core'].'_messages';
		$this->client = ElasticClient::createClient();
	}

	public function __destruct(){
		$this->dao->close();
	}

	public function index($params = array()){
		$where = '';
		if(isset($params['id'])){
			$id = $params['id'];
			$where = "where m.id =".$id;
		} else {
			$countsql = "select count(id) as count from messages";
			if(!$countresult = $this->dao->execQuery($countsql)){
				return;
			}
			$rowcount = $countresult->fetch_assoc()['count'];
			$chunkcount = ceil($rowcount/50000);
		}
			print_r($chunkcount);
		$sql = "select m.id, m.subject, m.message, m.date_created, m.replyid, 
		COALESCE(m.instanceformid, 0) as instanceformid, COALESCE(m.tags, '') as tags, 
		CONCAT(a.name) as from_user, r.recipients, a.orgid,a.id as fromid,
		g.name as assigned_group, og.name as owner_group, g.id as assignedgroupid, 
		og.id as ownergroupid
		from messages m inner join 
		(select mr.messageid, 
			CONCAT('[', GROUP_CONCAT(CONCAT('\"', t.name, '\"')), ']') as recipients 
			from message_recepients mr inner join avatars t on t.id = mr.toid group by 
			mr.messageid) as r on m.id = r.messageid
			inner join avatars a on a.id = m.fromid
			left outer join instanceforms i on i.id = m.instanceformid
			left outer join groups g on i.assignedgroup = g.id
			left outer join groups og on i.ownergroupid = og.id";
			if($rowcount>50000){
				for ($i=0; $i < $chunkcount; $i++) {
					$limit = " LIMIT ".($i*50000).", 50000";
					$chunksql = "$sql $where $limit ";
					if(!$chunksqlresult = $this->dao->execQuery($chunksql)){
						return;
					}
					print_r($limit);
					$j=0;
					while ($data = $chunksqlresult->fetch_assoc()) {
						$formData = array();
						$formData['entity_type'] = 'MESSAGE';
						$formData['id'] = $data['id'];
						$formData['entity_id'] = $data['id'];
						$formData['subject'] = $data['subject'];
						$formData['message'] = $data['message'];
						$formData['reply_id'] = $data['replyid'];
						$formData['fromid'] = $data['fromid'];
						$formData['orgid'] = $data['orgid'];
						$formData['instanceformid'] = $data['instanceformid'];
						$formData['tags'] = $data['tags'] ;
						$formData['recipient_list'] = json_decode($data['recipients'], true);
						$formData['from_user'] = $data['from_user'];
						$formData['date_created'] = $data['date_created'];
						$formData['assignedgroupid'] = $data['assignedgroupid'];
						$formData['ownergroupid'] = $data['ownergroupid'];
						$formData['ownergroup'] = $data['owner_group'];
						$formData['date_created'] = $data['date_created'];
						$count++;
						$params['body'][] = ['index' => ['_index' => $this->core,'_type' => $this->type,'_id' => $formData['id']]];
						$params['body'][] = $formData;
						if ($j % 1000 == 0) {
							$responses = $this->client->bulk($params);
							$params = array();
							echo ' Number of Records indexed: '.$j.' is Completed';
							echo "\n";
        			// unset the bulk response when you are done to save memory
							unset($responses);
						}
						$j++;
					}
				}
			} else {
				if(!$result = $this->dao->execQuery($sql.' '.$where)){
					return;
				}
				$fails = 0;
				$total = 0;
				$count = 0;
				$i=0;
				while ($data = $result->fetch_assoc()) {
					$formData = array();
					$formData['entity_type'] = 'MESSAGE';
					$formData['id'] = $data['id'];
					$formData['entity_id'] = $data['id'];
					$formData['subject'] = $data['subject'];
					$formData['message'] = $data['message'];
					$formData['reply_id'] = $data['replyid'];
					$formData['orgid'] = $data['orgid'];
					$formData['instanceformid'] = $data['instanceformid'];
					$formData['tags'] = $data['tags'] ;
					$formData['recipient_list'] = json_decode($data['recipients'], true);
					$formData['from_user'] = $data['from_user'];
					$formData['date_created'] = $data['date_created'];
					$formData['assignedgroupid'] = $data['assignedgroupid'];
					$formData['ownergroupid'] = $data['ownergroupid'];
					$formData['ownergroup'] = $data['ownergroup'];
					$formData['date_created'] = $data['date_created'];
					$count++;
				// var_dump($formData);
					$params['body'][] = ['index' => ['_index' => $this->core,'_type' => $this->type,'_id' => $formData['id']]];
					$params['body'][] = $formData;
					if(!isset($id)){
						if ($i % 100 == 0) {
							$responses = $this->client->bulk($params);
        			// erase the old bulk request
							$params = array();
							echo ' Number of Records indexed: '.$i.' is Completed';
							echo "\n";
        			// unset the bulk response when you are done to save memory
							unset($responses);
						}
						$i++;
					} else {
						$this->update($id,$formData);
					}
				}
			}
			if (!empty($params['body'])&&!isset($id)) {
				$responses = $this->client->bulk($params);
			}
			$total = $result->num_rows;
			$result->free();
			return array('fails' => $fails, 'total' => $total);
		}
	public function bulkIndex(){

	}
	public function delete($params =array()){
		if($params['id']){
			$id = $params['id'];
			$searchparams = ['index' => $this->core,'type' => $this->type,'body' => ['query' => ['match' => ['id' => $params['id']]]]];
			if($this->client->search($searchparams)['hits']['total']>0){
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