<?php

namespace ElasticSearch;
require __DIR__ .'/../autoload.php';
// require __DIR__ .'/../../../../bin/init.php';
use DateTime;
use Oxzion\Dao;
ini_set('memory_limit', -1);
ini_set('display_errors',1);
class FormIndexer{

	private $dao;
	public $type;
	private $elasticaddress;
	private $core;

	public function __construct(){
		$this->dao = new Dao();
		$ini = parse_ini_file(dirname(dirname(dirname(dirname((__DIR__))))).'/application/configs/application.ini');
		$this->elasticaddress = $ini['resources.elastic.serveraddress'];
		$this->type = $ini['resources.elastic.type'];
		$this->core = $ini['resources.elastic.core'].'_instanceforms';
		$this->client = ElasticClient::createClient();
	}

	public function __destruct(){
		$this->dao->close();
	}

	public function index($params = array()){
		$id = null;
		$where = '';
		if(isset($params['date'])){
			$date = $params['date'];
			$where = "where fm.id in (select formid from instanceforms where date_created >='".$date."' OR date_modified >= '".$date."')";
		}elseif(isset($params['start'])){
			$date = "date(date_created) between '".$params['start']."' and '".$params['end']."' and formid not in (622)";
			$where = "where fm.id in (select formid from instanceforms where $date)";
		} else {
		if(isset($params['id'])){
			$id = $params['id'];
			// $this->delete(array('id'=>$id));
			$where = "where fm.id in (select formid from instanceforms where id =".$id.")  and fm.id not in (348) and formid not in (622)";
		}
		if(isset($params['formid'])){
			$where = "where fm.id=".$params['formid'];
		}
		if(isset($params['moduleid'])){
			$modulemapper = new \VA_Model_MetaForms();
			$formlist = $modulemapper->enlistByModuleAndOrgId($params['moduleid']);
			$where = "where fm.id IN (".implode(",",array_column($formlist,'id')).")";
		}
		}
		$sql = "select fm.id, fm.name,
		CONCAT('{',GROUP_CONCAT(CONCAT('\"', fd.columnname,'\":\"',fd.name, '\"')), '}') as field_texts, 
		CONCAT('{',GROUP_CONCAT(CONCAT('\"', fd.columnname,'\":\"',fd.type, '\"')), '}') as field_types,
		CONCAT('{',GROUP_CONCAT(CONCAT('\"', fd.columnname,'\":\"',fd.options, '\"')), '}') as field_options,
		GROUP_CONCAT(fd.columnname) as form_fields, fm.statuslist 
		from metaforms fm inner join metafields fd on fm.id = fd.formid
		$where and fm.id not in (348) and formid not in (622) group by fm.id, fm.name, fm.statuslist order by fm.id ASC;";
		if(!$result = $this->dao->execQuery($sql)){
			return;
		}
		$fails = 0;
		$total = 0;
		while ($row = $result->fetch_assoc()) {
			try {
				$ret = $this->indexForm($row,$id);
				if($ret){
					$fails = $fails + $ret['fails'];
					$total = $total + $ret['total'];
				}
			} catch(Exception $e){
				print_r($e);exit;
			}
		}
		$result->free();
		return array('fails' => $fails, 'total' => $total);
	}

	private function indexForm($row, $id){
		$where = "where i.formid = ".$row['id'];
		$formFields = ($row['form_fields'])?','.$row['form_fields']:'';
		if(isset($id)){
			$where .= " and i.id=".$id;
		}
		print_r("Starteed indexing Formid = ".$row['id']);
		echo "\n";
		$sql = "select i.id, i.name, i.description, i.htmltext, i.orgid, i.formid, i.createdid, i.modifiedid, tags, i.assignedto, 
		i.startdate, i.enddate, i.nextactiondate,
		CONCAT(a.firstname, ' ',a.lastname) as created_by, 
		CONCAT(m.firstname, ' ', m.lastname) as modified_by, 
		CONCAT(asn.firstname, ' ', asn.lastname) as assigned_to, 
		g.name as assigned_group, i.date_created, i.date_modified, og.name as owner_group, 
		i.status, g.id as assignedgroupid, og.id as ownergroupid $formFields 
		from instanceforms i inner join avatars a on i.createdid = a.id
		left outer join instanceforms_join j on j.instanceformid = i.id
		left outer join avatars m on i.modifiedid = m.id
		left outer join avatars asn on i.assignedto = asn.id
		left outer join groups g on i.assignedgroup = g.id
		left outer join groups og on i.ownergroupid = og.id
		$where order by i.id DESC";
		if(!$result = $this->dao->execQuery($sql)){
			return;
		}
		print_r("Rows found :".$result->num_rows);
		echo "\n";
		$fieldTypes = json_decode($row['field_types'], true);
		$fieldTexts = json_decode($row['field_texts'], true);
		$fieldOptions = null;
		if(isset($row['field_options'])){
			$fieldOptions = json_decode(preg_replace('/[[:^print:]]/', '', $row['field_options']), true);
			if($fieldOptions){
				foreach ($fieldOptions as $key => $value) {
					if(strpos($value, "=>")>-1){
						$optionList = $this->dao->extractMap($value);
						$fieldOptions[$key] = $optionList;
					} else {
						$fieldOptions[$key] = $value;
					}
				}
			}
		}

			$statusList = $this->dao->extractMap($row['statuslist']);
			$fails = 0;
			$indexparams = ['body' => []];
			$i = 0;
			if($result){
			while ($data = $result->fetch_assoc()) {
				$formData = array();
				$formData['entity_type'] = 'INSTANCE_FORM';
				$formData['form_name'] = $row['name'];
				// $formData['fld_names'] = $row['field_texts'];//field name json string
				// $formData['fld_types'] = $row['field_types'];//field types json string
				$formData['id'] = $data['id'];
				$formData['entity_id'] = $data['id'];
				$formData['name'] = $data['name'];
				$formData['formname'] = $row['name'];
				$formData['orgid'] = $data['orgid'];
				$formData['formid'] = $data['formid'];
				$formData['status_key'] = $data['status'];
				$formData['createdid'] = $data['createdid'];
				$formData['modifiedid'] = $data['modifiedid'];
				$formData['assignedtoid'] = $data['assignedto'];
				$formData['assignedgroupid'] = $data['assignedgroupid'];
				$formData['ownergroupid'] = $data['ownergroupid'];
				$formData['desc'] = $data['description'];
				$formData['desc_raw'] = strip_tags($data['description']);
				$formData['html'] = $data['htmltext'];
				$formData['createdby_user'] = $data['created_by'];
				$formData['modifiedby_user'] = $data['modified_by'];
				$formData['assignedto_user'] = $data['assigned_to'];
				$formData['createdby'] = $data['created_by'];
				$formData['modifiedby'] = $data['modified_by'];
				$formData['assignedto'] = $data['assigned_to'];
				$formData['assignedgroup'] = $data['assigned_group'];
				$formData['ownergroup'] = $data['owner_group'];
				$date_created = DateTime::createFromFormat(DB_DATETIME_FORMAT,$data['date_created']);
				$date_start = DateTime::createFromFormat(DB_DATETIME_FORMAT,$data['startdate']);
				$date_end = DateTime::createFromFormat(DB_DATETIME_FORMAT,$data['enddate']);
				$date_nextaction = DateTime::createFromFormat(DB_DATETIME_FORMAT,$data['nextactiondate']);
				if($date_created){
					$formData['date_created'] = $date_created->format(SOLR_DATETIME_FORMAT);
				}
				if($date_start){
					$formData['startdate'] = $date_start->format(SOLR_DATETIME_FORMAT);
					$formData['startdatey'] = $date_start->format("Y");
					$formData['startdatem'] = $date_start->format("F");
				}
				if($date_end){
					$formData['enddate'] = $date_end->format(SOLR_DATETIME_FORMAT);
					$formData['enddatey'] = $date_end->format("Y");
					$formData['enddatem'] = $date_end->format("F");
				}
				if($date_nextaction){
					$formData['nextactiondate'] = $date_nextaction->format(SOLR_DATETIME_FORMAT);
					$formData['nextactiondatey'] = $date_nextaction->format("Y");
					$formData['nextactiondatem'] = $date_nextaction->format("F");
				}
				$formData['tags'] = $data['tags'];
				if($data['date_modified']) $formData['date_modified'] = $data['date_created'];
				
				$dateValue = DateTime::createFromFormat(DB_DATETIME_FORMAT, 
					$data['date_modified']);
				if($dateValue){
					$formData['date_modified'] = $dateValue->format(SOLR_DATETIME_FORMAT);
				}		
				
				if(array_key_exists($data['status'], $statusList)){
					$formData['status'] = $statusList[$data['status']];
					$formData['statusname'] = $statusList[$data['status']];
				}
				//var_dump($data);
				if($fieldTypes){
					foreach($fieldTypes as $col => $type){
						if(!array_key_exists($col, $data)){
							continue;
						}
						$fieldValue = $data[$col];
						if(!isset($fieldValue)){
							continue;
						}
						$col = strtolower($col);
						$val = null;
					// print('col - '.$col.', fieldValue - '.$fieldValue."\n");
						if($type === 'date' || $type === 'dateorrepeat'){
							$dateValue = DateTime::createFromFormat(DB_DATETIME_FORMAT, $fieldValue);
							if($dateValue){
								$val = $dateValue->format(SOLR_DATETIME_FORMAT);
								$formData[$col] = $val;
							}else{
								$val = $fieldValue;
								$formData[$col] = $val;
							}
						}elseif($type === 'select'){
							if($fieldOptions[$col]&&is_array($fieldOptions[$col])){
								if(array_key_exists($col, $fieldOptions) && array_key_exists($fieldValue, $fieldOptions[$col])){
									$val = $fieldOptions[$col][$fieldValue];
									$formData[$col] = $val;
								}
								$formData[$col."_key"] = $fieldValue;
								$formData[$fieldTexts[$col]."_key"] = $fieldValue;
							}
						}elseif($type === 'ajaxselect'){
							if($fieldOptions[$col]){
								if($ajaxparams = json_decode($fieldOptions[$col],true)){
									if($ajaxparams['datasource']){
										switch($ajaxparams['datasource']){
											case 'forms':
											try{
												$checkinstanceformsql = "select name from instanceforms WHERE id=".$fieldValue;
												if(!$instanceresult = $this->dao->execQuery($checkinstanceformsql)){
													break;
												} else {
													$instancenamerow = $instanceresult->fetch_all();
													$formData[$col] = $instancenamerow[0][0];
													$val = $instancenamerow[0][0];
												}
											} catch(Exception $e){
												print_r($e->getMessage());
											}
											break;
											case 'avatar':
											try {											
												$avatar = new \VA_Logic_Avatar($fieldValue);
												$formData[$col] = $avatar->name;
												$val = $avatar->name;
											} catch (Exception $e){
												print_r($e->getMessage());
											}
											break;
											default:
											break;
										}
									}
								} else {
									if(strpos($fieldOptions[$col],"fillclientinfo")>-1){
										if($fieldValue){
											try {
												$checkinstanceformsql = "select name from instanceforms WHERE id=".$fieldValue;
												if(!$instanceresult = $this->dao->execQuery($checkinstanceformsql)){
													break;
												} else {
													$instancenamerow = $instanceresult->fetch_all();
													$formData[$col] = $instancenamerow[0][0];
													$val = $instancenamerow[0][0];
												}
											} catch(Exception $e){
												print_r($e->getMessage());
												$formData[$col] = $fieldValue;
												$val = $fieldValue;
											}
										}
									} else if(strpos($fieldOptions[$col],"getadminticketlist")>-1){
										$arr = array(1 => array(0 => 'Select', 1 => 'Housekeeping', 2 => 'Security', 3 => 'Office Boy', 4 => 'Theft'), 2 => array(0 => 'Select', 5 => 'International Flight Booking', 6 => 'Domestic Flight Booking', 7 => 'Hotel Booking', 8 => 'Train Ticketing', 9 => 'Bus Ticketing', 10 => 'Visa Processing', 11 => 'Perdiem Request', 12 => 'Domestic Cab Booking'), 4 => array(0 => 'Select', 13 => 'System Movements', 14 => 'File Movements', 15 => 'Telephone Issues'));
										$formData[$col] = $arr[$fieldValue];
										$val = $arr[$fieldValue];
									} else if(strpos($fieldOptions[$col],"gethrticketlist")>-1){
										$arr = array(1 => array(0 => 'Select', 1 => 'Housekeeping', 2 => 'Security', 3 => 'Office Boy', 4 => 'Theft'), 2 => array(0 => 'Select', 5 => 'International Flight Booking', 6 => 'Domestic Flight Booking', 7 => 'Hotel Booking', 8 => 'Train Ticketing', 9 => 'Bus Ticketing', 10 => 'Visa Processing', 11 => 'Perdiem Request', 12 => 'Domestic Cab Booking'), 4 => array(0 => 'Select', 13 => 'System Movements', 14 => 'File Movements', 15 => 'Telephone Issues'));
										$formData[$col] = $arr[$fieldValue];
										$val = $arr[$fieldValue];
									} else if(strpos($fieldOptions[$col],"getitticketlist")>-1){
										$arr = array(1 => array(0 => 'Select', 1 => 'Printer Issue', 2 => 'Internet Issue'), 2 => array(0 => 'Select', 3 => 'System Slow', 4 => 'New System Request', 5 => 'Keyboard not working', 6 => 'Mouse not working', 7 => 'Network Issue', 8 => 'System Problem', 9 => 'Laptop Problem'), 3 => array(0 => 'Select', 10 => 'New Joinee login ID & email ID creation', 11 => 'Tally Installation', 12 => 'QB Installation', 13 => 'Tally Restore', 14 => 'QB Access', 15 => 'Share drive Access', 16 => 'Backup Restore', 17 => 'Applications slow', 18 => 'Email Problem', 19 => 'Add email IDs to Group ID', 20 => 'Out of Office', 21 => 'New Application Installation', 22 => 'Tally Slow'));
										$formData[$col] = $arr[$fieldValue];
										$val = $arr[$fieldValue];
									} else if(strpos($fieldOptions[$col],"getperiodlist")>-1){
										$arr = array(4 => array(0 => 'Select', 1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April', 5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August', 9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'), 5 => array(0 => 'Select', 13 => '1st Quarter', 14 => '2nd Quarter', 15 => '3rd Quarter', 16 => '4th Quarter'));
										$formData[$col] = $arr[$fieldValue];
										$val = $arr[$fieldValue];
									} else if(strpos($fieldOptions[$col],"getadminticketlist")>-1){
										$arr = array(1 => array(0 => 'Select', 1 => 'Housekeeping', 2 => 'Security', 3 => 'Office Boy', 4 => 'Theft'), 2 => array(0 => 'Select', 5 => 'International Flight Booking', 6 => 'Domestic Flight Booking', 7 => 'Hotel Booking', 8 => 'Train Ticketing', 9 => 'Bus Ticketing', 10 => 'Visa Processing', 11 => 'Perdiem Request', 12 => 'Domestic Cab Booking'), 4 => array(0 => 'Select', 13 => 'System Movements', 14 => 'File Movements', 15 => 'Telephone Issues'));
										$formData[$col] = $arr[$fieldValue];
										$val = $arr[$fieldValue];
									} else {
										$formData[$col] = $fieldValue;
										$val = $fieldValue;
									}
								}
							}
							$formData[$col."_key"] = $fieldValue;
							$formData[$fieldTexts[$col]."_key"] = $fieldValue;
						}elseif($type == 'integer'){
							$val = (string) preg_replace("/[^0-9]/", "", $fieldValue);
							$formData[$col."_key"] = (int) preg_replace("/[^0-9]/", "", $fieldValue);
							$formData[$col] = $val;
						}elseif($type == 'float'){
							$val = (string) preg_replace("/[^0-9\.]/", "", $fieldValue);
							$formData[$col."_key"] = (float) preg_replace("/[^0-9\.]/", "", $fieldValue);
							$formData[$col] = $val;
						}elseif($type == 'multiselect'){
							$items = array();
							$selectvals = array_diff(array_map('trim', explode(',', $fieldValue)), array(''));
							foreach ($selectvals as $multivals) {
								$items[$multivals] = $fieldOptions[$col][$multivals];
							}
							$val = implode(",",$items);
							$formData[$col] = $val;
							$formData[$col."_key"] = $fieldValue;
							$formData[$fieldTexts[$col]."_key"] = $fieldValue;
						}else{
							$val =$fieldValue;
							$formData[$col] = $val;
							$formData[$col."_key"] = $fieldValue;
							$formData[$fieldTexts[$col]."_key"] = $fieldValue;
						}
						$formData[$fieldTexts[$col]] = $val;
						$formData[strtolower($fieldTexts[$col])] = $val;
					}
				}
				$formData['index_type'] = 'instanceforms';
				// $params = ['index' => $this->core,'type' => $type,'id' => $formData['id'],'body' => $formData];
				// print_r($formData);
				// array_push($indexparams['body'], ['index' => ['_index' => $this->core,'_type' => $this->type,'_id' => $formData['id']]]);
				// array_push($indexparams['body'], $formData);
				if(!isset($id)){
					$indexparams['body'][] = ['index' => ['_index' => $this->core,'_type' => $this->type,'_id' => $formData['id']]];
					$indexparams['body'][] = $formData;
					if ($i % 1000 ==0) {
						// print_r($indexparams);exit;
						try {
							echo ' Number of Records indexed: '.$i.' is Completed for '.$row['name'];
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
							echo $responses;
						} catch(Exception $e){
							print_r($e);
						}
						$indexparams['body'] = array();
						unset($responses);
					}
					$i++;
				} else {
					$this->update($id,$formData);
				}
			}
			if (!empty($indexparams['body'])&&!isset($id)) {
				try {
					echo ' Number of Records indexed: '.$i.' is Completed for '.$row['name'];
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
					echo $responses;
					$indexparams['body'] = array();
				} catch(Exception $e){
					print_r($e);
				}
			}
			$total = $result->num_rows;
			$result->free();
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
				// print_r($params);exit;
				return $this->client->index($params);
			}
		}
	}
	?>