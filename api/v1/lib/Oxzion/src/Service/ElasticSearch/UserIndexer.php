<?php
namespace ElasticSearch;
require __DIR__ .'/../autoload.php';
use Oxzion\Dao;
use Elasticsearch\ClientBuilder;
use DateTime;

ini_set('memory_limit', -1);
class UserIndexer{

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
		$this->core = $ini['resources.elastic.core'].'_user';
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
			$where = "where a.id =".$id;
		}

		$sql = "select a.*,o.name as organization, CONCAT(m.firstname, ' ', 
		m.lastname) as manager, a.doj, CONCAT('[',GROUP_CONCAT(g.id), ']') as groupids, 
		GROUP_CONCAT(CONCAT('\"',g.name,'\"')) as group_names,l.*
		from avatars a left outer join avatars m on a.managerid = m.id
		left outer join groups_avatars ga on a.id = ga.avatarid
		left outer join groups g on ga.groupid = g.id
		left join organizations o on a.orgid = o.id
		left join leaderboard l on a.id = l.avatarid
		$where
		group by a.id, a.orgid, a.gamelevel, a.username, a.firstname, a.lastname, a.name, a.role, 
		a.email, a.status, a.country, a.dob, a.designation, a.phone, a.address, a.sex, a.website, 
		a.about, a.interest, a.hobbies, a.managerid, m.firstname, m.lastname, a.avatar_date_created,
		a.doj;";

		if(!$result = $this->dao->execQuery($sql)){
			return;
		}

		$fails = 0;
		$total = 0;
		$i=0;
		while ($data = $result->fetch_assoc()) {
			$formData = array();
			$formData = $data;
			$formData['entity_type'] = 'USER';
			$formData['id'] = $data['id'];
			$formData['entity_id'] = $data['id'];
			$formData['orgid'] = $data['orgid'];
			$formData['gamelevel'] = $data['gamelevel'];
			$formData['firstname'] = $data['firstname'];
			$formData['lastname'] = $data['lastname'];
			$formData['name'] = $data['name'];
			$formData['role'] = $data['role'];
			$formData['email'] = $data['email'];
			$formData['status'] = $data['status'];
			$formData['country'] = $data['country'];
			$formData['designation'] = $data['designation'];
			$formData['phone'] = $data['phone'];
			$formData['address'] = $data['address'];
			$formData['gender'] = $data['sex'];
			$formData['website'] = $data['website'];
			$formData['about'] = $data['about'];
			$formData['interest'] = $data['interest'];
			$formData['hobbies'] = $data['hobbies'];
			$formData['manager_key'] = $data['managerid'];
			$formData['organization'] = $data['organization'];
			$formData['group_names'] = $data['group_names'];
			unset($formData['inmail_label']);
			unset($formData['defaultmatrixid']);
			if(isset($data['dob'])){
				$dateValue = DateTime::createFromFormat(DB_DATE_FORMAT, $data['dob']);
				if($dateValue){
					$formData['date_of_birth'] = $dateValue->format(SOLR_DATETIME_FORMAT);
				}
			}
			if(isset($data['avatar_date_created'])){
				$dateValue = DateTime::createFromFormat(DB_DATETIME_FORMAT, $data['avatar_date_created']);
				if($dateValue){
					$formData['date_created'] = $dateValue->format(SOLR_DATETIME_FORMAT);
				}
			}
			if(isset($data['doj'])){
				$dateValue = DateTime::createFromFormat(DB_DATE_FORMAT, $data['doj']);
				if($dateValue){
					$formData['date_of_joining'] = $dateValue->format(SOLR_DATETIME_FORMAT);
				}
			}

			$formData['manager'] = $data['manager'];
			$formData['index_type'] = 'avatar';
			$params['body'][] = ['index' => ['_index' => $this->core,'_type' => $this->type,'_id' => $formData['id']]];
			$params['body'][] = $formData;
			if(!isset($id)){
				if ($i % 1000 == 0) {
					$responses = $this->client->bulk($params);
        			// erase the old bulk request
					$params = array();
					echo ' Number of Records indexed: '.$i.' is Completed ';
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
			//TODO update the db with completion status
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