<?php
	namespace Oxzion;
	use DateTime;

	class UserIndexer{
		
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
				$where = "where a.avatar_date_created >='".$date."'";
			}elseif(isset($params['id'])){
				$id = $params['id'];
				$where = "where a.id =".$id;
			}

			$sql = "select a.id, a.orgid, a.gamelevel, a.username, a.firstname, a.lastname, a.name, a.role, 
					a.email, a.status, a.country, a.dob, a.designation, a.phone, a.address, a.sex, a.website, 
					a.avatar_date_created, a.about, a.interest, a.hobbies, a.managerid, CONCAT(m.firstname, ' ', 
					m.lastname) as manager, a.doj, CONCAT('[',GROUP_CONCAT(g.id), ']') as groupids, 
					CONCAT('[',GROUP_CONCAT(CONCAT('\"',g.name,'\"')), ']') as group_names
					from avatars a left outer join avatars m on a.managerid = m.id
					left outer join groups_avatars ga on a.id = ga.avatarid
					left outer join groups g on ga.groupid = g.id
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
			while ($data = $result->fetch_assoc()) {
				$formData = array();
				$formData['entity_type'] = 'USER';
				$formData['id'] = $this->computeId($data['id']);
				$formData['entity_id'] = $data['id'];
				$formData['org_id'] = $data['orgid'];
				$formData['gamelevel_s'] = $data['gamelevel'];
				$formData['firstname_txt'] = $data['firstname'];
				$formData['lastname_txt'] = $data['lastname'];
				$formData['name_txt'] = $data['name'];
				$formData['role_s'] = $data['role'];
				$formData['email_s'] = $data['email'];
				$formData['status'] = $data['status'];
				$formData['country_s'] = $data['country'];
				$formData['designation_s'] = $data['designation'];
				$formData['phone_s'] = $data['phone'];
				$formData['address_s'] = $data['address'];
				$formData['gender_s'] = $data['sex'];
				$formData['website_s'] = $data['website'];
				$formData['about_txt'] = $data['about'];
				$formData['interest_txt'] = $data['interest'];
				$formData['hobbies_txt'] = $data['hobbies'];
				$formData['manager_id'] = $data['managerid'];
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

				$formData['manager_user'] = $data['manager'];
				
					
				
				
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
			return 'U-'.$id;
		}
			
	}
?>