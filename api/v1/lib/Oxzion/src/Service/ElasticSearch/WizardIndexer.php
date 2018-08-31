<?php
namespace ElasticSearch;
require __DIR__ .'/../autoload.php';
//require __DIR__ .'/../../../../public/xo.php';

use DateTime;
use Common\Dao;
ini_set('memory_limit', -1);
ini_set('display_errors',1);
class WizardIndexer{
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
		$this->core = $ini['resources.elastic.core'].'_wizard';
		$this->client = ElasticClient::createClient();
	}

	public function __destruct(){
		$this->dao->close();
	}

	public function index($params = array()){
		$id = null;
		$where = '';
		if($params){
			if($params['id']){
				$where = "WHERE id = ".$params['id'];
				$id = $params['id'];
			}
		} else {
			$where = "WHERE `assessid` IS NOT NULL AND `assessid` <> 0 GROUP BY `assessid`";
		}
		$sql = "SELECT assessid,formid,name FROM `instanceforms` $where";
		if(!$result = $this->dao->execQuery($sql)){
			return;
		}
		$fails = 0;
		$total = 0;
		while ($row = $result->fetch_assoc()) {
			$ret = $this->indexWizard($row,$id);
		}
		$result->free();
		return $ret;
	}

	private function indexWizard($row, $id){
		if($id){
			$sql = "select i.id,fm.statuslist ,i.name,i.formid, i.orgid, i.formid, i.createdid, i.modifiedid, tags, i.assignedto, 
			i.startdate, i.enddate, i.nextactiondate,
			CONCAT(a.firstname, ' ',a.lastname) as created_by, 
			CONCAT(m.firstname, ' ', m.lastname) as modified_by, 
			CONCAT(asn.firstname, ' ', asn.lastname) as assigned_to, 
			g.name as assigned_group, i.date_created, i.date_modified, og.name as owner_group, 
			i.status, g.id as assignedgroupid, og.id as ownergroupid  
			from instanceforms i inner join avatars a on i.createdid = a.id
			left outer join instanceforms_join j on j.instanceformid = i.id
			left outer join avatars m on i.modifiedid = m.id
			left outer join avatars asn on i.assignedto = asn.id
			left outer join groups g on i.assignedgroup = g.id
			left outer join groups og on i.ownergroupid = og.id 
			left outer join metaforms fm on fm.id = i.formid WHERE i.id=".$id;
		} else {
			$sql = "select i.id,fm.statuslist,i.name,i.formid, i.orgid, i.formid, i.createdid, i.modifiedid, tags, i.assignedto, 
			i.startdate, i.enddate, i.nextactiondate,
			CONCAT(a.firstname, ' ',a.lastname) as created_by, 
			CONCAT(m.firstname, ' ', m.lastname) as modified_by, 
			CONCAT(asn.firstname, ' ', asn.lastname) as assigned_to, 
			g.name as assigned_group, i.date_created, i.date_modified, og.name as owner_group, 
			i.status, g.id as assignedgroupid, og.id as ownergroupid  
			from instanceforms i inner join avatars a on i.createdid = a.id
			left outer join instanceforms_join j on j.instanceformid = i.id
			left outer join avatars m on i.modifiedid = m.id
			left outer join avatars asn on i.assignedto = asn.id
			left outer join groups g on i.assignedgroup = g.id
			left outer join groups og on i.ownergroupid = og.id
			left outer join metaforms fm on fm.id = i.formid  WHERE assessid=".$row['assessid'];
		}
		if(!$result = $this->dao->execQuery($sql)){
			return;
		}
		if(!$wizardresult = $this->dao->execQuery("select id,name from evolve_wizards where id=".$row['assessid'])){
			return;
		}
				// print_r($result);exit;
		$wizarddata = mysqli_fetch_all($wizardresult,MYSQLI_ASSOC);
		$headervalue = new \VA_Model_EvolveMetaFields();
		$headervalues1 = $headervalue->findallquestionsusingwizardid($row['assessid'],0);
		print_r("Rows found :".$result->num_rows);
		echo "\n"; 
		$fails = 0;
		$indexparams = ['body' => []];
		$i = 0;
		if($result){
			while ($data = $result->fetch_assoc()) {
				$statusList = $this->dao->extractMap($data['statuslist']);
				$fetchsql = "select a.id as valueid, value, score ,avatarid, instanceformid,b.* FROM evolve_question_values as a JOIN evolve_metafields b ON a.questionid = b.id WHERE a.wizardid = ".$row['assessid']." AND instanceformid=".$data['id'];
				if(!$wizarditem = $this->dao->execQuery($fetchsql)){
					return;
				}
				$formData = array();
				$formData['id'] = $data['id'];
				$formData['name'] = $row['name'];
				$formData['instance_formid'] = $row['formid'];
				$formData['wizardid'] = $row['assessid'];
				$formData['avatarid'] = $data['assignedto'];
				$formData['wizardname'] = $wizarddata[0]['name'];
				$formData['orgid'] = $data['orgid'];
				$formData['statusid'] = $data['status'];
				$formData['createdid'] = $data['createdid'];
				if(array_key_exists($data['status'], $statusList)){
					$formData['statusname'] = $statusList[$data['status']];
				}
				$formData['modifiedid'] = $data['modifiedid'];
				$formData['assignedto_key'] = $data['assignedto'];
				$formData['assignedgroupid'] = $data['assignedgroupid'];
				$formData['ownergroupid'] = $data['ownergroupid'];
				$formData['createdby'] = $data['created_by'];
				$formData['modifiedby'] = $data['modified_by'];
				$formData['assignedto'] = $data['assigned_to'];
				$formData['assignedgroup'] = $data['assigned_group'];
				$formData['ownergroup'] = $data['owner_group'];
				$wizardvalues = array_column(mysqli_fetch_all($wizarditem,MYSQLI_ASSOC),'value','id');
				foreach ($$wizardvalues as $key => $value) {
					$headerkey = array_search($key, array_column($headervalues1, 'id'));
					$formData[$headervalues1[$headerkey]['name']] = $value;
					$formData[$headervalues1[$headerkey]['name'].'_key'] = $value;
					$formData[$headervalues1[$headerkey]['name']."_question"] = $headervalues1[$headerkey]['text'];
					// $formData[str_replace(" ", "_", $headervalues1[$headerkey]['text'])] = $value;
					if($optionslist = $headervalues1[$headerkey]['options']){
						if(substr($optionslist, 0, 1)=="$"){
							if(!$optionsresult = $this->dao->execQuery("select name,value from metalist where name='".str_replace("$", "", $optionslist)."'")){
								return;
							}
							$opt_list = mysqli_fetch_all($optionsresult,MYSQLI_ASSOC)[0]['value'];
							if(strpos($opt_list, "=>")>-1){
								$optionslist = $this->dao->extractMap($opt_list);
							}
						} else {
							$optionslist = $this->dao->extractMap($optionslist);
						}
						$formData[$headervalues1[$headerkey]['name']] = $optionslist[$value];
					}
				}
				// print_r($formData);exit;
				$formData['index_type'] = 'wizard';
				if(!isset($id)){
					$indexparams['body'][] = ['index' => ['_index' => $this->core,'_type' => $this->type,'_id' => $formData['id']]];
					$indexparams['body'][] = $formData;
					if ($i % 10000 ==0) {
						$responses = $this->client->bulk($indexparams);
						echo ' Number of Records indexed: '.$i.' is Completed for '.$row['name'];
						echo "\n";
						print_r($responses);
						$indexparams = ['body' => []];
						$formData = array();
						unset($responses);
						unset($data);
					}
				} else {
					return $this->update($id,$formData);
				}
				$i++;
			}
			if (!empty($indexparams['body'])&&!isset($id)) {
				try {
					echo ' Number of Records indexed: '.$i.' is Completed for '.$row['name'];
					echo "\n";
					$responses = $this->client->bulk($indexparams);
					echo $responses;
					$formData = array();
					unset($responses);
				} catch(Exception $e){
					print_r($e);
				}
			}
			$indexparams = ['body' => []];
			$total = $result->num_rows;
			$result->free();
		}
		return array('fails' => $fails, 'total' => $total);
	}
	public function delete($params =array()){
		if($params['id']){
			$id = $params['id'];
			$this->client = ClientBuilder::create()->setHosts(array($this->elasticaddress))->build();
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
				// $response = $this->client->indices()->create($getParams);
			// }
		$searchparams = ['index' => $this->core,'type' => $this->type,'body' => ['query' => ['match' => ['id' => $id]]]];
		try {
			if($this->client->search($searchparams)['hits']['total']>0){
				$params = ['index' => $this->core,'type' => $this->type,'id' => $id,'body' =>['doc'=> $formData]];
				return $this->client->update($params);
			} else {
				$params = ['index' => $this->core,'type' => $this->type,'id' => $id,'body' => $formData];
				return $this->client->index($params);
			}
		} catch(Exception $e){
			print_r($e);
		}
	}
}
?>