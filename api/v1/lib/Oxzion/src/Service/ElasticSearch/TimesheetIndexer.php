<?php
namespace ElasticSearch;
require __DIR__ .'/../autoload.php';
use Oxzion\Dao;
use \DateTime;
use Elasticsearch\ClientBuilder;
ini_set("display_errors", 1);
ini_set('memory_limit', -1);

class TimesheetIndexer {

	private $dao;
	public $type;
	private $elasticaddress;
	private $core;

	public function __construct(){
		$this->dao = new Dao();
		$ini = parse_ini_file(dirname(dirname(dirname(dirname((__DIR__))))).'/application/configs/application.ini');
		$this->elasticaddress = $ini['resources.elastic.serveraddress'];
		$this->type = $ini['resources.elastic.type'];
		$this->core = $ini['resources.elastic.core'].'_timesheet';
		$this->client = ElasticClient::createClient();
	}

	public function __destruct(){
		$this->dao->close();
	}

	public function timesheetDefaultError(){
		$sql = "";
		$errors = array(1=>'Internal Error', 2=>'Client Reported Error', 3=>'Training Reported Error', 4=>'Ramp up Error');
		return $errors;
	}

	public function index($params = array()){
		if(!$params){
			$groupsql = "SELECT client from club_task GROUP BY client ASC";
			if(!$groupresult = $this->dao->execQuery($groupsql)){
				print_r("No Data to Index");
				return;
			}
			while ($client = $groupresult->fetch_assoc()) {
				if(!$client['client']){
					$wherefilter = "WHERE client is NULL";
				} else {
					$wherefilter = "WHERE client = ".$client['client'];
				}
				$this->indexByFilter($wherefilter,$client['client']);
			}
		} else {
			if($params['id']){
				$wherefilter = "WHERE ct.id = ".$params['id'];
				$this->indexByFilter($wherefilter,$params['id']);
			}
			if($params['date']){
				$wherefilter = "WHERE ct.id = ".$params['date'];
				$this->indexByFilter($wherefilter);
			}
			if($params['clientid']){
				$wherefilter = "WHERE client = ".$params['clientid'];
				$this->indexByFilter($wherefilter,$params['clientid']);
			}
		}
	}

	private function indexByFilter($wherefilter,$items,$id=null){
		$countresult = $this->dao->execQuery("SELECT count(id) as count FROM club_task ct $wherefilter");
		$rowcount = $countresult->fetch_assoc()['count'];
		$selectsql = "SELECT ct.id,ct.task_name,ct.avatar_id,ct.status as status_key,ct.start_time ,ct.end_time ,ct.task_duration as duration,ct.process as process_key,ct.project as project_key,ct.billable,ct.lob as lob_key,ct.client as client_key,ct.client_id,ct.received_date ,ct.effective_date ,ct.state,ct.tat,ct.days_out,ct.comments,ct.error as error_key,ct.file_share,ct.skip_counting,ct.field1,ct.field2,ct.field3,ct.field4,ct.field5,ct.field6,ct.field7,ct.field8,ct.field9,ct.field10,ct.dropdown1 as dropdown1_key,ct.dropdown2 as dropdown2_key,ct.dropdown3 as dropdown3_key,ct.dropdown4 as dropdown4_key,ct.dropdown5 as dropdown5_key,ct.datefield1 ,ct.datefield2 ,ct.datefield3 ,ct.datefield4 ,ct.datefield5 ,ct.cost ,ct.cost_quality,ct.error_date,ct.session,ct.last_modified ,ct.instanceforms,ct.matrixid,ct.file_upload,ct.file_download,ct.points_flag,CONCAT(a.firstname, ' ', a.lastname) as avatar,c.client_name as client,a.orgid as orgid, e.field_name AS process, d.field_name AS lob, f.field_name AS project, g.field_name AS status,h.field_name as dropdown1,i.field_name as dropdown2,j.field_name as dropdown3,k.field_name as dropdown4,l.field_name as dropdown5 FROM club_task as ct LEFT JOIN avatars AS a ON ct.avatar_id = a.id LEFT JOIN timesheet_clients AS c ON ct.client = c.id LEFT JOIN timesheet_lob AS d ON ct.lob = d.id LEFT JOIN timesheet_process AS e ON ct.process = e.id LEFT JOIN timesheet_project AS f ON ct.project = f.id LEFT JOIN timesheet_status AS g ON ct.status = g.id LEFT JOIN timesheet_dropdown1 AS h ON ct.dropdown1 = h.id LEFT JOIN timesheet_dropdown2 AS i ON ct.dropdown2 = i.id LEFT JOIN timesheet_dropdown3 AS j ON ct.dropdown3 = j.id  LEFT JOIN timesheet_dropdown4 AS k ON ct.dropdown4 = k.id LEFT JOIN timesheet_dropdown5 AS l ON ct.dropdown5 = l.id ";
		if($rowcount>100000){
			$chunkcount = ceil($rowcount/100000);
			for ($i=0; $i < $chunkcount; $i++) {
				$limit = " LIMIT ".($i*100000).", 100000";
				$sql = "$selectsql $wherefilter $limit ";
				if(!$chunksqlresult = $this->dao->execQuery($sql)){
					return;
				}
				$j=0;
				while ($data = $chunksqlresult->fetch_assoc()) {
					$formData = array();
					$formData = $data;
					if($data['task_name']){
						$formData['name']= $data['task_name'];
					}
					if($data['duration']){
						$parsed = date_parse($data['duration']);
						$formData['duration'] = $parsed['hour'] * 3600 + $parsed['minute'] * 60 + $parsed['second'];
					}
					$date_start = DateTime::createFromFormat(DB_DATETIME_FORMAT,$data['start_time']);
					$date_end = DateTime::createFromFormat(DB_DATETIME_FORMAT,$data['end_time']);
					if($date_start){
						$formData['start_time'] = $date_start->format(SOLR_DATETIME_FORMAT);
					}
					if($date_end){
						$formData['end_time'] = $date_end->format(SOLR_DATETIME_FORMAT);
					}
					if($data['error_date']=="0000-00-00"){
						unset($formData['error_date']);
					}
					switch ($formData['error_key']) {
						case 0:
						$formData['error'] = 'No';
						break;
						case 1:
						$formData['error'] = 'Internal QC';
						break;
						case 2:
						$formData['error'] = 'Client Reported Error';
						break;
						case 3:
						$formData['error'] = 'Training Reported Error';
						break;
						case 4:
						$formData['error'] = 'On-Boarding Error';
						break;
						default:
						$formData['error'] = 'No';
						break;
					}
					$formData['index_type'] = 'timesheet';
					$indexparams['body'][] = ['index' => ['_index' => $this->core,'_type' => $this->type,'_id' => $formData['id']]];
					$indexparams['body'][] = $formData;
					if ($j % 500 ==0) {
						$responses = $this->client->bulk($indexparams);
						$indexparams = ['body' => []];
						echo ' Number of Records indexed: '.$j.' is Completed for '.$items;
						echo "\n";
						if($responses['errors']){
							$shard = array_column(array_column(array_values($responses['items']), 'index'), '_shards','_id');
							foreach ($shard as $indvidualshard) {
								if($indvidualshard['failed']){
									print_r($indvidualshard);exit;
								}
							}
						}
						unset($responses);
					}
					$j++;
				}
			}
		} else {
			$sql = "$selectsql $wherefilter ";
			if(!$result = $this->dao->execQuery($sql)){
				return;
			}
			$j=0;
			while ($data = $result->fetch_assoc()) {
				$formData = array();
				$formData = $data;
				if($data['task_name']){
					$formData['name']= $data['task_name'];
				}
				if($data['avatar_id']){
					$data['avatar_id_key'] = $data['avatar_id'];
				}
				if($data['duration']){
					$parsed = date_parse($data['duration']);
					$formData['duration'] = $parsed['hour'] * 3600 + $parsed['minute'] * 60 + $parsed['second'];
				}
				$date_start = DateTime::createFromFormat(DB_DATETIME_FORMAT,$data['start_time']);
				$date_end = DateTime::createFromFormat(DB_DATETIME_FORMAT,$data['end_time']);
				if($date_start){
					$formData['start_date'] = $date_start->format(DB_DATE_FORMAT);
					$formData['start_time'] = $date_start->format(SOLR_DATETIME_FORMAT);
				}
				if($date_end){
					$formData['end_date'] = $date_end->format(DB_DATE_FORMAT);
					$formData['end_time'] = $date_end->format(SOLR_DATETIME_FORMAT);
				}
				$formData['index_type'] = 'timesheet';
				$indexparams['body'][] = ['index' => ['_index' => $this->core,'_type' => $this->type,'_id' => $formData['id']]];
				$indexparams['body'][] = $formData;
				if(!isset($id)){
					if ($j % 500 ==0) {
						$responses = $this->client->bulk($indexparams);
						$indexparams = ['body' => []];
						echo ' Number of Records indexed: '.$j.' is Completed for '.$items;
						echo "\n";
						if($responses['errors']){
							$shard = array_column(array_column(array_values($responses['items']), 'index'), '_shards','_id');
							foreach ($shard as $indvidualshard) {
								if($indvidualshard['failed']){
									print_r($indvidualshard);exit;
								}
							}
						}
						unset($responses);
					}
					$j++;
				} else {
					$this->update($id,$formData);
				}
			}
			$fails = 0;
			$total = 0;
			$i=0;
			if (!empty($indexparams['body'])&&!isset($id)) {
				try {
					echo "\n";
					$responses = $this->client->bulk($indexparams);
					if($responses['errors']){
						$shard = array_column(array_column(array_values($responses['items']), 'index'), '_shards','_id');
						foreach ($shard as $indvidualshard) {
							if($indvidualshard['failed']){
								print_r($indvidualshard);exit;
							}
						}
					}
					echo ' Number of Records indexed: '.$i.' is Completed for '.$items;
					unset($responses);
				} catch(Exception $e){
					print_r($e);
				}
			}
		}
		return array('fails' => $fails, 'total' => $total);
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
		$exists = $this->client->indices()->exists($getParams);
		if(!$exists){
			$response = $this->client->indices()->create($getParams);
		}
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