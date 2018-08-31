<?php
	namespace Oxzion;
	use DateTime;

	class FormCommentIndexer{
		
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
			if(isset($params['date'])){
				$date = $params['date'];
				
			}elseif(isset($params['id'])){
				$id = $params['id'];
			}

			$formStatusList = $this->fetchFormStatusList($date, $id);
			return $this->indexFormComments($date, $id, $formStatusList, $indexFn);
		}

		public function computeId($id){
			return 'C-'.$id;
		}

		private function fetchFormStatusList($date, $id = null){
			$where = "";
			if(isset($date)){
				$where = "where fm.id in 
							(select f.formid from instanceforms f inner join formcomments fc 
								on f.id = fc.instanceformid
								where fc.date_created >='".$date."' OR fc.date_modified >= '".$date."')";
			}elseif(isset($id)){
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

		private function indexFormComments($date, $id, $formStatusList, $indexFn){
			$where = "";
			if(isset($date)){
				$where .= "where (fc.date_created >= '".$date."' OR fc.date_modified >= '".$date."')";
			}elseif(isset($id)){
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
			while ($data = $result->fetch_assoc()) {
				$formData = array();
				$formData['entity_type'] = 'COMMENT';
				$formData['id'] = $this->computeId($data['id']);
				$formData['entity_id'] = $data['id'];
				$formData['module_txt'] = $data['module_name'];
				$formData['comment_txt'] = $data['comment'];
				$formData['title_txt'] = $data['form_title'];
				if(array_key_exists($data['formid'], $formStatusList) && 
						array_key_exists($data['status'], $formStatusList[$data['formid']])){
					$formData['status'] = $formStatusList[$data['formid']][$data['status']];
				}
				$formData['org_id'] = $data['orgid'];
				$formData['reply_id'] = $data['replyid'];
				$formData['instanceform_id'] = $data['instanceformid'];
				$formData['assignedgroup_id'] = $data['assignedgroupid'];
				$formData['ownergroup_id'] = $data['ownergroupid'];
				$formData['assigned_group'] = $data['assigned_group'];
				$formData['owner_group'] = $data['owner_group'];
				$formData['commenting_user'] = $data['comment_by'];
				$formData['owner_user'] = $data['owner_user'];
				$formData['assigned_user'] = $data['assigned_user'];
				$formData['date_created'] = $data['date_created'];
				$dateValue = DateTime::createFromFormat(DB_DATETIME_FORMAT, $data['date_modified']);
				if($dateValue){
					$formData['date_modified'] = $dateValue->format(SOLR_DATETIME_FORMAT);
				}	
				//var_dump($formData);
				call_user_func_array($indexFn, array(&$formData));
				
				//TODO update the db with progress update
			}
			
			
			//TODO update the db with completion status
			$total = $result->num_rows;
			$result->free();

			return array('fails' => $fails, 'total' => $total);

		} 

		
	}
?>