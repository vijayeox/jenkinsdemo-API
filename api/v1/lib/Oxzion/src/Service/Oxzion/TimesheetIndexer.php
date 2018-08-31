<?php
	namespace Oxzion;
	use DateTime;

	class TimesheetIndexer {
		
		private $dao;

		public function __construct(){
			$this->dao = new Dao();
		}

		public function __destruct(){
			$this->dao->close();
		}

		public function timesheetDefaultError(){

			$sql = "";

            $errors = array(1=>'Internal Error', 2=>'Client Reported Error', 3=>'Training Reported Error', 4=>'Ramp up Error');
             return $errors;
		}

		public function index($params = array(), $indexFn){
			$errors = $this->timesheetDefaultError();
			echo '<pre>';print_r($errors);exit;
			$where = '';
			if(isset($params['date'])){
				$date = $params['date'];
				$where = "where (c.date_created >='".$date."' OR c.date_modified >= '".$date."')";
			}

			$sql = "SELECT ct.id, ct.Project as project_id, ct.Process as process_id, ct.Status as status_id, ct.LOB as lob_id, error, error_date, start_time, end_time, received_date, effective_date, a.firstname, a.lastname, c.client_name AS client_name, d.field_name AS lob_name, e.field_name AS process_name, f.field_name AS project_name, g.field_name AS status_name
				FROM club_task as ct
					LEFT JOIN avatars AS a ON ct.avatar_id = a.id
					LEFT JOIN timesheet_clients AS c ON ct.client = c.id
					LEFT JOIN timesheet_lob AS d ON ct.lob = d.id
					LEFT JOIN timesheet_process AS e ON ct.process = e.id
					LEFT JOIN timesheet_project AS f ON ct.project = f.id
					LEFT JOIN timesheet_status AS g ON ct.status = g.id
				$where";

			if(!$result = $this->dao->execQuery($sql)){
				return;
			}

			$fails = 0;
			$total = 0;
			while ($data = $result->fetch_assoc()) {
				$formData = array();
				$formData['entity_type'] = 'Timesheet';
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
			return 'T-'.$id;
		}
		
	}
?>