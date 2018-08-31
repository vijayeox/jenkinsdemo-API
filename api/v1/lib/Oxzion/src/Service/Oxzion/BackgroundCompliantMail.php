<?php
namespace Oxzion;
require_once __DIR__.'/../Common/Config.php';
include __DIR__.'/../autoload.php';
include __DIR__.'/../../../../bin/init.php';
include __DIR__.'/../../../../application/modules/comevolve/models/Evolvequestionvalues.php';
include __DIR__.'/../../../../library/VA/Model/EvolveMetaFields.php';

	class BackgroundCompliantMail {
	 	private static $instance;
		private $dao;
		private static $autoloader; 
		private $params;
		private $orgid;

		public function __construct(){
			$this->dao = new Dao();
			date_default_timezone_set('UTC');
			static::$autoloader = require_once(__DIR__.'/../../../vendor/autoload.php');
		}
		
		public static function getInstance(){
			if(!isset(static::$instance)){
				static::$instance = new BackgroundCompliantMail();
			}
			return static::$instance;
		}
		
	 	public function sendReminderMail($params, $orgid = 110){
	 		// $organization = new \VA_Logic_Organization($orgid);
	 		// $value = $organization->getConfigurationValue($config_param);
	 		// $params = \VA_Service_Utils::listToArray($value);
	 		if($params){
	 			if($params['attachments']){
	 				$params['attachments'] = explode(',', $params['attachments']);
	 			}
	 			$this->params = $params;
	 			$this->orgid = $orgid;
	 			$this->instanceMailTrigger();
	 		}
	 		else{
	 			echo "Unable to Process";
	 		}
	 	}

	 	public function instanceMailTrigger(){
	 		$wizard = explode(',',$this->params['wizard']);
	 		foreach ($wizard as $wizardid) {
	 			$wizardlogic = new \VA_Logic_EvolveWizard($wizardid);
	 			if($wizardlogic->id){
	 				$method = $this->params['method'];
	 				$this->$method($wizardlogic);
	 			}
 			}			
	 	}

	 	private function getNonComplaintDetails($wizardlogic){	
	 		$to = $this->params['tofield'];
	 		$contractor_details = "SELECT 
	 					evolve_instanceseq.instanceformid, 
	 					avatarid as userid, 
	 					wizardid, 
	 					$to as recipient, 
	 					instanceforms.status
					FROM instanceforms
					INNER JOIN evolve_instanceseq on evolve_instanceseq.instanceformid = instanceforms.id
					LEFT JOIN instanceforms_join ON instanceforms_join.instanceformid = instanceforms.id
					WHERE instanceforms.assessid = " . $wizardlogic->id . "  
						AND instanceforms.status = 2 
						AND instanceforms.$to IS NOT NULL";

			$result = $this->executeQuery($contractor_details);
			if($result){
				$this->sendMail($result, $wizardlogic);
			}
	 	}

	 	private function getNonOrComplaintDetails($wizardlogic){	
	 		$cc_to = $to = $this->params['tofield'];
	 		$nota30days = '';
	 		if($this->params['ccfield']){
	 			$cc = $this->params['ccfield'];
	 			$cc_to = "CONCAT_WS('|', $to,$cc)";
	 			$cc_condition = "AND instanceforms.$cc IS NOT NULL";
	 		}
	 		if($this->params['flag']){
	 			$flag = $this->params['flag'];
	 			$flag_condition = "AND (instanceforms.$flag IS NULL || instanceforms.$flag = 0)";
	 		}
	 		if(!$this->params['nota30days']){
	 			$nota30days = "AND str_to_date(DATE_ADD(evolve_instanceseq.completedatetime, INTERVAL 30 DAY), '%Y-%m-%d') = CURRENT_DATE()";
	 		}
	 		$status = ($this->params['status'])?$this->params['status']:101;
	 		$contractor_details = "SELECT 
	 					evolve_instanceseq.instanceformid, 
	 					avatarid as userid, 
	 					wizardid, 
	 					$cc_to as recipient, 
	 					instanceforms.status
					FROM instanceforms
					INNER JOIN evolve_instanceseq on evolve_instanceseq.instanceformid = instanceforms.id
					LEFT JOIN instanceforms_join ON instanceforms_join.instanceformid = instanceforms.id
					WHERE instanceforms.assessid = " . $wizardlogic->id . "  
						AND instanceforms.status = $status
						$nota30days
						AND instanceforms.$to IS NOT NULL
						AND evolve_instanceseq.completedatetime IS NOT NULL
						$cc_condition
						$flag_condition";

			$result = $this->executeQuery($contractor_details);
			if($result){
				$this->sendMail($result, $wizardlogic);
				if($this->params['flag']){
					$updatecontractor_details = "UPDATE instanceforms
							INNER JOIN evolve_instanceseq on evolve_instanceseq.instanceformid = instanceforms.id
							LEFT JOIN instanceforms_join ON instanceforms_join.instanceformid = instanceforms.id
							SET $flag = 1
							WHERE instanceforms.assessid = " . $wizardlogic->id . "  
								AND instanceforms.status = $status
								$nota30days
								AND instanceforms.$to IS NOT NULL
								AND evolve_instanceseq.completedatetime IS NOT NULL
								$cc_condition
								$flag_condition";
					$this->executeQuery($updatecontractor_details);
				}
			}
	 	}

	 	private function getPolicyExpiration($wizardlogic){
	 		$cc_to = $to = $this->params['tofield'];
	 		$nota30days = '';
	 		if($this->params['ccfield']){
	 			$cc = $this->params['ccfield'];
	 			$cc_to = "CONCAT_WS('|', $to,$cc)";
	 			$cc_condition = "AND instanceforms.$cc IS NOT NULL";
	 		}
	 		if($this->params['flag']){
	 			$flag = $this->params['flag'];
	 			$flag_condition = "AND (instanceforms.$flag IS NULL || instanceforms.$flag = 0)";
	 		}

	 		$status = ($this->params['status'])?$this->params['status']:101;

	 		$expiry_date = explode(',', $this->params['condition']);
	 		$expiry_condition = $this->getExpiryFields($wizardlogic->id, 'getDateCalForPolicyExpiration');
	 		if($expiry_condition){
	 			$expiry_condition = ' AND (' . rtrim($expiry_condition, " OR ") . ')';
	 		
		 		$contractor_details = "" . 
		 				"SELECT 
		 					evolve_instanceseq.instanceformid, 
		 					avatarid as userid, 
		 					wizardid, 
		 					$cc_to as recipient,
		 					instanceforms.status
						FROM instanceforms
						INNER JOIN evolve_instanceseq on evolve_instanceseq.instanceformid = instanceforms.id
						LEFT JOIN instanceforms_join ON instanceforms_join.instanceformid = instanceforms.id
						WHERE instanceforms.assessid = " . $wizardlogic->id . "  
							AND instanceforms.status = $status
							$expiry_condition
							AND evolve_instanceseq.completedatetime IS NOT NULL
							AND instanceforms.$to IS NOT NULL
							$cc_condition
							$flag_condition;";

				$result = $this->executeQuery($contractor_details);
			}
			if($result){
				$this->sendMail($result, $wizardlogic);
				if($this->params['flag']){
					$updatecontractor_details = "UPDATE instanceforms
							INNER JOIN evolve_instanceseq on evolve_instanceseq.instanceformid = instanceforms.id
							LEFT JOIN instanceforms_join ON instanceforms_join.instanceformid = instanceforms.id
							SET $flag = 1
							WHERE instanceforms.assessid = " . $wizardlogic->id . "  
								AND instanceforms.status = $status
								$expiry_condition
								AND evolve_instanceseq.completedatetime IS NOT NULL
								AND instanceforms.$to IS NOT NULL
								$cc_condition
								$flag_condition";
					$this->executeQuery($updatecontractor_details);
				}
			}
	 	}
	 	
		private function executeQuery($sql){
			return $this->dao->execQuery($sql);
		}

		private function sendMail($data, $wizardlogic){
			$attach_status = $attachments = array();
			if(count($this->params['attachments']) > 0){
				$attach_status['attach_status'] = 1;
			}
			$cc = $bcc = '';
			if($this->params['cc']){
				$cc = '|' . $this->params['cc'];
			}
			if($this->params['bcc']){
				$bcc = '|' . $this->params['bcc'];
			}
			if(!$cc && $bcc){
				$append_to = '|'.$bcc;
			}
			else{
				$append_to = $cc . $bcc;
			}
			$emailtemplate_logic = new \VA_Logic_EmailTemplate($this->params['templateid']);
			while($row = $data->fetch_assoc()){	
				$to = $row['recipient'] . $append_to;
        		$return_msg = $emailtemplate_logic->wizardEmail($wizardlogic->email_from, $row['recipient'], $row + $attach_status , $this->params['attachments']);
				echo $return_msg;
				echo "\n";
			}
		}

		private function getExpiryFields($wizardid, $method){
			$evolvefields_details = (new \VA_Model_EvolveMetaFields())->getDatabyParamsJoin(array('fieldname'=>'name','instancefield'), 'ef.wizard_id = "' . $wizardid . '" AND instancefield IS NOT NULL AND instancefield != "" AND evolve_metafields.name like "%exp%" AND evolve_metafields.type = "date"', null, null, null, 0, array(array('jointable' => array('ef' => 'evolve_metaforms'),'condition' => 'ef.id = evolve_metafields.evolveformid', 'joinfields' =>'join','joinfields' => array('evolveformname' => 'name'))));
        	foreach ($evolvefields_details as $key => $value) {
        		$expwhere_condition .= $this->$method($value['instancefield']);
        	}
        	return $expwhere_condition;
		}

		private function getDateCalForExpiryStatus($field){
			return " (" . $field . " IS NOT NULL AND " . $field . " != '' AND CURRENT_DATE() > (str_to_date(" . $field . ", '%m/%d/%Y')) )OR";
		}

		private function getDateCalForPolicyExpiration($field){
			return " (DATE_FORMAT(str_to_date(" . $field . ", '%m/%d/%Y'), '%Y-%m-%d') = CAST(DATE_ADD(CURRENT_DATE, INTERVAL -15 DAY) AS DATE)) OR ";
		}

		function getExpiryStatusChange($wizardlogic){
        	$expwhere_condition = $this->getExpiryFields($wizardlogic->id, 'getDateCalForExpiryStatus');
        	$where_condition = ' (status = 101 AND assessid = ' . $wizardlogic->id . ' ) ';
        	if($expwhere_condition){
        		$where_condition = $where_condition . ' AND (' . rtrim($expwhere_condition, 'OR') . ')';
        		$update_query = "UPDATE instanceforms 
        					LEFT JOIN instanceforms_join ON instanceforms_join.instanceformid = instanceforms.id
        					SET status = 2 
        					WHERE $where_condition";
        		$result = $this->executeQuery($update_query);
        		if($result){
        			echo "Updated successfully<br>";
        		}

        	} 	
		}
	}	
?>