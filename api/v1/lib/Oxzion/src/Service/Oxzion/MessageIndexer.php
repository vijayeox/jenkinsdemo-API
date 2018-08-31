<?php
	namespace Oxzion;

	use DateTime;
	use Exception;
	
	class MessageIndexer{
		private $dao;

		public function __construct(){
			$this->dao = new Dao();
		}

		public function __destruct(){
			$this->dao->close();
		}

		public function index($params = array(), $indexFn){
			$where = '';
			if(isset($params['date'])){
				$date = $params['date'];
				$where = "where m.date_created >='".$date."'";
			}elseif(isset($params['id'])){
				$id = $params['id'];
				$where = "where m.id =".$id;
			}

			$sql = "select m.id, m.subject, m.message, m.date_created, m.replyid, 
					COALESCE(m.instanceformid, 0) as instanceformid, COALESCE(m.tags, '') as tags, 
					CONCAT(a.firstname, ' ',a.lastname) as from_user, r.recipients, a.orgid,
					g.name as assigned_group, og.name as owner_group, g.id as assignedgroupid, 
					og.id as ownergroupid
					from messages m inner join 
					(select mr.messageid, 
						CONCAT('[', GROUP_CONCAT(CONCAT('\"', t.firstname, ' ',t.lastname, '\"')), ']') as recipients 
					 from message_recepients mr inner join avatars t on t.id = mr.toid group by 
					 mr.messageid) as r on m.id = r.messageid
					inner join avatars a on a.id = m.fromid
					left outer join instanceforms i on i.id = m.instanceformid
					left outer join groups g on i.assignedgroup = g.id
					left outer join groups og on i.ownergroupid = og.id
					$where;";
			
			if(!$result = $this->dao->execQuery($sql)){
				return;
			}

			$fails = 0;
			$total = 0;
			$count = 0;
			while ($data = $result->fetch_assoc()) {
				$formData = array();
				$formData['entity_type'] = 'MESSAGE';
				$formData['id'] = $this->computeId($data['id']);
				$formData['entity_id'] = $data['id'];
				$formData['subject_txt'] = $data['subject'];
				$formData['message_txt'] = $data['message'];
				$formData['reply_id'] = $data['replyid'];
				$formData['org_id'] = $data['orgid'];
				$formData['instanceform_id'] = $data['instanceformid'];
				$formData['tags'] = $data['tags'] ;
				$formData['recipient_list'] = json_decode($data['recipients'], true);
				$formData['from_user'] = $data['from_user'];
				$formData['date_created'] = $data['date_created'];
				$formData['assignedgroup_id'] = $data['assignedgroupid'];
				$formData['ownergroup_id'] = $data['ownergroupid'];
				$formData['owner_group'] = $data['owner_group'];
				$formData['date_created'] = $data['date_created'];
				
				$count++;
				//var_dump($formData);
				call_user_func_array($indexFn, array(&$formData));
				
				//TODO update the db with progress update
			}
			

			//TODO update the db with completion status
			$total = $result->num_rows;
			$result->free();

			return array('fails' => $fails, 'total' => $total);

		} 

		public function computeId($id){
			return 'M-'.$id;
		}
	}
?>