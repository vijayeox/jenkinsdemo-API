<?php
	namespace Oxzion;
	require_once __DIR__.'/../Common/Config.php';
	use DateTime;

	class AttachmentIndexer{
		
		private $dao;

		public function __construct(){
			$this->dao = new Dao();
		}

		public function __destruct(){
			$this->dao->close();
		}

		public function index($params = array(), $indexFn){
			$result = $this->indexFormAttachments($params, $indexFn);
			$result1 = $this->indexMsgAttachments($params, $indexFn);

			return array('fails' => $result['fails'] + $result1['fails'],
						 'total' => $result['total'] + $result1['total']);
		}

		public function computeId($id){
			return 'A-'.$id;
		}

		private function indexMsgAttachments($params, $indexFn){
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
					$where";

			if(!$result = $this->dao->execQuery($sql)){
				return;
			}	
			
			$fails = 0;
			$total = 0;
			while ($data = $result->fetch_assoc()) {
				$formData = array();
				$formData['entity_type'] = 'ATTACHMENT';
				$formData['id'] = $this->computeId($data['id']);
				$formData['entity_id'] = $data['id'];
				$formData['filename_txt'] = $data['filename'];
				$formData['message_id'] = $data['messageid'];
				$formData['subject_txt'] = $data['subject'];
				$formData['org_id'] = $data['orgid'];
				$formData['recipient_list'] = json_decode($data['recipients'], true);
				$formData['from_user'] = $data['from_user'];
				$fileLocation = ATTACHMENT_BASE.$data['orgid'].DIRECTORY_SEPARATOR.'messages'.DIRECTORY_SEPARATOR.$data['id'].DIRECTORY_SEPARATOR.$data['filename'];

				//var_dump($formData);
				if(file_exists($fileLocation)){
					call_user_func_array($indexFn, array(&$formData, &$fileLocation));
				}else{
					$fails++;
				}
				
				//TODO update the db with progress update
			}
			
			
			//TODO update the db with completion status
			$total = $result->num_rows;
			$result->free();

			return array('fails' => $fails, 'total' => $total);	
		}

		private function indexFormAttachments($params, $indexFn){
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
					$where;";
			
			
			if(!$result = $this->dao->execQuery($sql)){
				return;
			}

			$fails = 0;
			$total = 0;
			while ($data = $result->fetch_assoc()) {
				$formData = array();
				$formData['entity_type'] = 'ATTACHMENT';
				$formData['id'] = 'A-'.$data['id'];
				$formData['entity_id'] = $data['id'];
				$formData['title_txt'] = $data['name'];
				$formData['filename_txt'] = $data['filename'];
				$formData['instanceform_id'] = $data['instanceformid'];
				$formData['org_id'] = $data['orgid'];
				$formData['form_id'] = $data['formid'];
				$formData['assignedgroup_id'] = $data['assignedgroupid'];
				$formData['ownergroup_id'] = $data['ownergroupid'];
				$formData['createdby_user'] = $data['created_by'];
				$formData['modifiedby_user'] = $data['modified_by'];
				$formData['assignedto_user'] = $data['assigned_to'];
				$formData['assigned_group'] = $data['assigned_group'];
				$formData['owner_group'] = $data['owner_group'];
				$fileLocation = ATTACHMENT_BASE.$data['orgid'].DIRECTORY_SEPARATOR.$data['instanceformid'].DIRECTORY_SEPARATOR.$data['filename'];

				//var_dump($formData);
				if(file_exists($fileLocation)){
					call_user_func_array($indexFn, array(&$formData, &$fileLocation));
				}else{
					$fails++;
				}
				
				//TODO update the db with progress update
			}
			
			
			//TODO update the db with completion status
			$total = $result->num_rows;
			$result->free();

			return array('fails' => $fails, 'total' => $total);
		}
		

		
	}
?>