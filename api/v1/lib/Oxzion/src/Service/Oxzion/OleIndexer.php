<?php
	namespace Oxzion;
	use DateTime;

	class OleIndexer {
		
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
				$where = "where (c.date_created >='".$date."' OR c.date_modified >= '".$date."')";
			}elseif(isset($params['id'])){
				$id = $params['id'];
				$where = "where c.id =".$id;
			}

			$sql = "select c.id, c.comment, c.date_created, c.date_modified, c.replyid, c.groupid,
					g.name as ole_group, CONCAT(a.firstname, ' ',a.lastname) as created_by, a.orgid
					from comments c inner join groups g on g.id = c.groupid
					inner join avatars a on a.id = c.avatarid 
					$where;";
			
			
			if(!$result = $this->dao->execQuery($sql)){
				return;
			}

			$fails = 0;
			$total = 0;
			while ($data = $result->fetch_assoc()) {
				$formData = array();
				$formData['entity_type'] = 'OLE';
				$formData['id'] = $this->computeId($data['id']);
				$formData['entity_id'] = $data['id'];
				$formData['ole_txt'] = $data['comment'];
				$formData['ole_group'] = $data['ole_group'];
				$formData['createdby_user'] = $data['created_by'];
				$formData['org_id'] = $data['orgid'];
				$formData['group_id'] = $data['groupid'];
				$formData['reply_id'] = $data['replyid'];
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

		public function computeId($id){
			return 'O-'.$id;
		}
		
	}
?>