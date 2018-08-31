<?php
namespace ElasticSearch;
require_once __DIR__.'/../Common/Config.php';
use DateTime;
use Oxzion\Dao;

class AttachmentIndexer{

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
		$this->core = $ini['resources.elastic.core'].'_attachments';
		$this->client = ElasticClient::createClient();
	}

	public function __destruct(){
		$this->dao->close();
	}

	public function index($params = array()){
		$result = $this->indexFormAttachments($params);
		$result1 = $this->indexMsgAttachments($params);
		return array('fails' => $result['fails'] + $result1['fails'],
			'total' => $result['total'] + $result1['total']);
	}

	public function computeId($id){
		return $id;
	}

	private function indexMsgAttachments($params){
		$where = '';
		if(isset($params['date'])){
			$date = $params['date'];
			$where = "where m.date_created >='".$date."'";
		}elseif(isset($params['id'])){
			$id = $params['id'];
			$where = "where ma.id =".$id;
		}
		$sql = "select ma.id, ma.messageid, ma.filename, m.subject, a.orgid, r.recipients, CONCAT(a.firstname, ' ',a.lastname) as from_user
		from instforms_files ma
		inner join messages m on ma.messageid = m.id
		inner join avatars a on a.id = m.fromid
		inner join (select mr.messageid,
			CONCAT('[', GROUP_CONCAT(CONCAT('\"', t.firstname, ' ',t.lastname, '\"')), ']') as recipients
			from message_recepients mr inner join avatars t on t.id = mr.toid where mr.message_status = 0 group by
			mr.messageid) as r on ma.messageid = r.messageid
			$where order by ma.id DESC";
			if(!$result = $this->dao->execQuery($sql)){
				return;
			}
			$fails = 0;
			$total = 0;
			// print_r('indexing messages '.$result->num_rows);
			while ($data = $result->fetch_assoc()) {
				$formData = array();
				$formData['entity_type'] = 'ATTACHMENT';
				$formData['id'] = $this->computeId($data['id']);
				$formData['entity_id'] = $data['id'];
				$formData['filename'] = $data['filename'];
				$formData['messageid'] = $data['messageid'];
				$formData['subject'] = $data['subject'];
				$formData['orgid'] = $data['orgid'];
				$formData['recipient_list'] = json_decode($data['recipients'], true);
				$formData['from'] = $data['from_user'];
				$fileLocation = ATTACHMENT_BASE.$data['orgid'].DIRECTORY_SEPARATOR.'messages'.DIRECTORY_SEPARATOR.$data['id'].DIRECTORY_SEPARATOR.$data['filename'];
				//var_dump($formData);
				if(file_exists($fileLocation)){
					$formData['attachmentdata'] = base64_encode(file_get_contents($fileLocation));
					if(!isset($params['id'])){
					$indexparams['body'][] = ['index' => ['_index' => $this->core,'_type' => $this->type,'_id' => $formData['id'],'pipeline'=>'attachment']];
					$indexparams['body'][] = $formData;
					print_r($formData['id']);
					if ($i % 10 ==0) {
						try {
							echo ' Number of Records indexed: '.$i.' is Completed';
							echo "\n";
							$responses = $this->client->bulk($indexparams);
							if($responses['errors']){
								$shard = array_column(array_column(array_values($responses['items']), 'index'), '_shards','_id');
								foreach ($shard as $indvidualshard) {
									if($indvidualshard['failed']){
										print_r($indvidualshard);
									}
								}
							}
							echo $responses;
							$indexparams = ['body' => []];
						} catch(Exception $e){
							print_r($e);
						}
						unset($responses);
					}
					$i++;
				} else {
					$this->update($params['id'],$formData);
				}
				//TODO update the db with progress update
			}
			}
			if (!empty($indexparams['body'])&&!isset($params['id'])) {
				try {
					echo ' Number of Records indexed: '.$i.' is Completed ';
					echo "\n";
					$responses = $this->client->bulk($indexparams);
					if($responses['errors']){
						$shard = array_column(array_column(array_values($responses['items']), 'index'), '_shards','_id');
						foreach ($shard as $indvidualshard) {
							if($indvidualshard['failed']){
								print_r($indvidualshard);
						}
					}
				}
					echo $responses;
				} catch(Exception $e){
					print_r($e);
				}
			}			
			//TODO update the db with completion status
			$total = $result->num_rows;
			$result->free();
			return array('fails' => $fails, 'total' => $total);	
		}

		private function indexFormAttachments($params){
			$where = '';
			if(isset($params['date'])){
				$date = $params['date'];
				$where = "where (i.date_created >='".$date."' OR i.date_modified >= '".$date."')";
			}elseif(isset($params['id'])){
				$id = $params['id'];
				$where = "where fa.id =".$id;
			}
			$sql = "select fa.id, fa.instanceformid, fa.filename,
			i.name, i.orgid, i.formid,
			CONCAT(a.firstname, ' ',a.lastname) as created_by,
			CONCAT(m.firstname, ' ', m.lastname) as modified_by,
			CONCAT(asn.firstname, ' ', asn.lastname) as assigned_to, 
			g.name as assigned_group, og.name as owner_group, g.id as assignedgroupid, 
			og.id as ownergroupid
			from instforms_files fa inner join instanceforms i on fa.instanceformid=i.id
			inner join avatars a on i.createdid = a.id
			left outer join avatars m on i.modifiedid = m.id
			left outer join avatars asn on i.modifiedid = asn.id
			left outer join groups g on i.assignedgroup = g.id
			left outer join groups og on i.ownergroupid = og.id 
			$where order by fa.id DESC";
			if(!$result = $this->dao->execQuery($sql)){
				return;
			}
			print_r('indexing forms '.$result->num_rows);
			echo "\n";
			$fails = 0;
			$total = 0;
			$i = 0;
			$indexparams = ['body' => []];
			while ($data = $result->fetch_assoc()) {
				$formData = array();
				$formData['entity_type'] = 'ATTACHMENT';
				$formData['id'] = $data['id'];
				$formData['entity_id'] = $data['id'];
				$formData['title'] = $data['name'];
				$formData['filename'] = $data['filename'];
				$formData['instanceformid'] = $data['instanceformid'];
				$formData['orgid'] = $data['orgid'];
				$formData['formid'] = $data['formid'];
				$formData['assignedgroupid'] = $data['assignedgroupid'];
				$formData['ownergroupid'] = $data['ownergroupid'];
				$formData['createdby'] = $data['created_by'];
				$formData['modifiedby'] = $data['modified_by'];
				$formData['assignedto'] = $data['assigned_to'];
				$formData['assignedgroup'] = $data['assigned_group'];
				$formData['ownergroup'] = $data['owner_group'];
				$fileLocation  = ATTACHMENT_BASE.$data['orgid'].DIRECTORY_SEPARATOR.$data['instanceformid'].DIRECTORY_SEPARATOR.$data['filename'];
				//var_dump($formData);
				if(file_exists($fileLocation)){
					$formData['attachmentdata'] = base64_encode(file_get_contents($fileLocation));
					if(!isset($params['id'])){
					$indexparams['body'][] = ['index' => ['_index' => $this->core,'_type' => $this->type,'_id' => $formData['id'],'pipeline'=>'attachment']];
					$indexparams['body'][] = $formData;
					print_r($formData['id']);
						echo "\n";
					if ($i % 10 == 0) {
						try {
							echo ' Number of Records indexed: '.$i.' is Completed';
							echo "\n";
							$responses = $this->client->bulk($indexparams);
							if($responses['errors']){
								$shard = array_column(array_column(array_values($responses['items']), 'index'), '_shards','_id');
								foreach ($shard as $indvidualshard) {
									if($indvidualshard['failed']){
										print_r($indvidualshard);
									}
								}
							}
							echo $responses;
							$indexparams = ['body' => []];
						} catch(Exception $e){
							print_r($e);
						}
						unset($responses);
					}
					$i++;
				} else {
					$this->update($params['id'],$formData);
				}
				//TODO update the db with progress update
			} else {
				$formData = array();
			}
			}
			if (!empty($indexparams['body'])&&!isset($params['id'])) {
				try {
					echo ' Number of Records indexed: '.$i.' is Completed';
					echo "\n";
					$responses = $this->client->bulk($indexparams);
					if($responses['errors']){
						$shard = array_column(array_column(array_values($responses['items']), 'index'), '_shards','_id');
						foreach ($shard as $indvidualshard) {
							if($indvidualshard['failed']){
								print_r($indvidualshard);
							}
						}
					}
					echo $responses;
				} catch(Exception $e){
					print_r($e);
				}
			}
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
			$getParams = [
				'index' => $this->core
			];
			// $exists = $this->client->indices()->exists($getParams);
			// if(!$exists){
			// 	$response = $this->client->indices()->create($getParams);
			// }
			try {
				$searchparams = ['index' => $this->core,'type' => $this->type,'body' => ['query' => ['match' => ['id' => $id]]]];
				$searchresult = $this->client->search($searchparams)['hits']['total'];
				if($searchresult>0){
					$params = ['index' => $this->core,'type' => $this->type,'id' => $id,'pipeline'=>'attachment','body' =>['doc'=> $formData]];
					return $this->client->update($params);
				} else {
					$params = ['index' => $this->core,'type' => $this->type,'id' => $id,'body' => $formData,'pipeline'=>'attachment'];
				// print_r($params);exit;
					return $this->client->index($params);
				}
			} catch(Exception $e){
				print_r($e);
			}
		}
	}
	?>