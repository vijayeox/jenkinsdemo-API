<?php
	namespace Oxzion;

	use DateTime;

	class FormIndexer{
		
		private $dao;

		public function __construct(){
			$this->dao = new Dao();
		}

		public function __destruct(){
			$this->dao->close();
		}

		public function index($params = array(), $indexFn){
			$date = null;
			$id = null;
			$where = '';
			if(isset($params['date'])){
				$date = $params['date'];
				$where = "where fm.id in (select formid from instanceforms where date_created >='".$date."' OR date_modified >= '".$date."')";
			}elseif(isset($params['id'])){
				$id = $params['id'];
				$where = "where fm.id in (select formid from instanceforms where id =".$id.")";
			}elseif(isset($params['start'])){
				$date = "date(date_created) between '".$params['start']."' and '".$params['end']."'";
				$where = "where fm.id in (select formid from instanceforms where $date)";
			}

			$where .= ' and fm.id not in (348)';

			$sql = "select fm.id, fm.name,
			CONCAT('{',GROUP_CONCAT(CONCAT('\"', fd.columnname,'\":\"',fd.text, '\"')), '}') as field_texts, 
			CONCAT('{',GROUP_CONCAT(CONCAT('\"', fd.columnname,'\":\"',fd.type, '\"')), '}') as field_types,
			CONCAT('{',GROUP_CONCAT(CONCAT('\"', fd.columnname,'\":\"',fd.options, '\"')), '}') as field_options,
			GROUP_CONCAT(fd.columnname) as form_fields, fm.statuslist 
			from metaforms fm inner join metafields fd on fm.id = fd.formid
			$where group by fm.id, fm.name, fm.statuslist;";

			// echo '<pre>';print_r($sql);exit;

			if(!$result = $this->dao->execQuery($sql)){
				return;
			}
			
			$fails = 0;
			$total = 0;
			while ($row = $result->fetch_assoc()) {
				$ret = $this->indexForm($row, $date, $id, $indexFn);
				if($ret){
					$fails = $fails + $ret['fails'];
					$total = $total + $ret['total'];
				}
			}

			$result->free();
			return array('fails' => $fails, 'total' => $total);
		} 

		public function computeId($id){
			return 'F-'.$id;
		}

		private function indexForm($row, $date, $id, $indexFn){
			$where = "where i.formid = ".$row['id'];
			if(isset($date)){
				$where .= " AND (i.date_created >= '".$date."' OR i.date_modified >= '".$date."')";
				// $where .= " AND $date";
			}elseif(isset($id)){
				$where .= " AND i.id=".$id;
			}

			$formFields = ($row['form_fields'])?','.$row['form_fields']:'';

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
					$where order by i.id ASC";

			if(!$result = $this->dao->execQuery($sql)){
				return;
			}
			$fieldTypes = json_decode($row['field_types'], true);
			$fieldOptions = null;
			if(isset($row['field_options'])){
				// $fieldOptions = json_decode($row['field_options'], true);
				$fieldOptions = json_decode(preg_replace('/[[:^print:]]/', '', $row['field_options']), true);
				foreach ($fieldOptions as $key => $value) {
					$optionList = $this->dao->extractMap($value); //array();
					$fieldOptions[$key] = $optionList;
				}
			}

			$statusList = $this->dao->extractMap($row['statuslist']);
			$fails = 0;
			while ($data = $result->fetch_assoc()) {
				$formData = array();
				$formData['entity_type'] = 'INSTANCE_FORM';
				$formData['type'] = $row['name'];//Form name
				// $formData['fld_names'] = $row['field_texts'];//field name json string
				// $formData['fld_types'] = $row['field_types'];//field types json string
				$formData['id'] = $this->computeId($data['id']);
				$formData['entity_id'] = $data['id'];
				$formData['title_txt'] = $data['name'];
				$formData['org_id'] = $data['orgid'];
				$formData['form_id'] = $data['formid'];
				$formData['status_id'] = $data['status'];
				$formData['created_id'] = $data['createdid'];
				$formData['modified_id'] = $data['modifiedid'];
				$formData['assigned_id'] = $data['assignedto'];
				$formData['assignedgroup_id'] = $data['assignedgroupid'];
				$formData['ownergroup_id'] = $data['ownergroupid'];
				$formData['desc_txt'] = $data['description'];
				$formData['html_txt'] = $data['htmltext'];
				$formData['createdby_user'] = $data['created_by'];
				$formData['modifiedby_user'] = $data['modified_by'];
				$formData['assignedto_user'] = $data['assigned_to'];
				$formData['createdby_s'] = $data['created_by'];
				$formData['modifiedby_s'] = $data['modified_by'];
				$formData['assignedto_s'] = $data['assigned_to'];
				$formData['assigned_group'] = $data['assigned_group'];
				$formData['owner_group'] = $data['owner_group'];
				$formData['date_created'] = $data['date_created'];
				$formData['date_start'] = $data['startdate'];
				$formData['date_end'] = $data['enddate'];
				$formData['date_nextaction'] = $data['nextactiondate'];
				$formData['tags_txt'] = $data['tags'];
				if($data['date_modified']) $formData['date_modified'] = $data['date_created'];
				
				$dateValue = DateTime::createFromFormat(DB_DATETIME_FORMAT, 
														 $data['date_modified']);
				if($dateValue){
					$formData['date_modified'] = $dateValue->format(SOLR_DATETIME_FORMAT);
				}		
				
				if(array_key_exists($data['status'], $statusList)){
					$formData['status'] = $statusList[$data['status']];
				}

				//var_dump($data);
				foreach($fieldTypes as $col => $type){
					if(!array_key_exists($col, $data)){
						continue;
					}
					$fieldValue = $data[$col];
					if(!isset($fieldValue)){
						continue;
					}
					//print('col - '.$col.', fieldValue - '.$fieldValue."\n");
					if($type === 'date' || $type === 'dateorrepeat'){
						$dateValue = DateTime::createFromFormat(DB_DATETIME_FORMAT, $fieldValue);
						if($dateValue){
							$formData[$col.'_dt'] = $dateValue->format(SOLR_DATETIME_FORMAT);
						}else{
							$formData[$col.'_txt'] = $fieldValue;
						}
					}elseif($type === 'select'){
						if(array_key_exists($col, $fieldOptions) && array_key_exists($fieldValue, $fieldOptions[$col])){
							$formData[$col.'_txt'] = $fieldOptions[$col][$fieldValue];
						}
					}elseif($type == 'integer'){
						$formData[$col.'_l'] = preg_replace("/[^0-9]/", "", $fieldValue);
					}elseif($type == 'float'){
						$formData[$col.'_f'] = preg_replace("/[^0-9\.]/", "", $fieldValue);
					}else{
						$formData[$col.'_txt'] = $fieldValue;
					}
				}
				try{
				call_user_func_array($indexFn, array(&$formData));
			}catch(Exception $e){
				continue;
			}
				//TODO update the db with progress update
			}
			//TODO update the db with completion status
			$total = $result->num_rows;
			$result->free();

			return array('fails' => $fails, 'total' => $total);
		}

		public function deletedForms($params){
			if(isset($params['date'])){
				$where = " and modifieddate >= '".$params['date']."'";
			}
			$sql = "SELECT instanceformid FROM auditlog WHERE changetype LIKE 'deleted' $where";
			if(!$result = $this->dao->execQuery($sql)){
				return;
			}
			while ($data =  $result->fetch_assoc()){
				$rows[] = $this->computeId($data['instanceformid']);
			}
			return $rows;
		}
	}
?>