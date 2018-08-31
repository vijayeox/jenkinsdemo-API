<?php
namespace Oxzion;
require_once __DIR__.'/../Common/Config.php';
include __DIR__.'/../autoload.php';
// use Exception;
// Define path to application directory

ignore_user_abort(true);
set_time_limit(0);

ini_set('memory_limit', -1);
ini_set('max_execution_time', -1);
ini_set('max_input_time', -1);


class WorkflowService{
	public function __construct(){
		date_default_timezone_set('UTC');
	}

	public function execWorkflow($params,$id){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, SERVER_URL.'bin/runworkflow.php');
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POSTFIELDS, array('param'=>json_encode($params),'id'=>$id));
		$result = curl_exec($ch);
		if ($result === FALSE) {
			die('Curl failed: ' . curl_error($ch));
		}
		// print_r($result);exit;
		curl_close($ch);
	}
}
?>