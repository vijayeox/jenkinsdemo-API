<?php
namespace Oxzion;
require_once __DIR__.'/../Common/Config.php';
include __DIR__.'/../autoload.php';
use Exception;
use Common\Dao;

class InstanceFormService {
	private $dao;

	public function __construct(){
		$this->dao = new Dao();
		date_default_timezone_set('UTC');
		$this->CheckAndSendReds();
	}
	public function CheckAndSendReds(){
		$sql = "SELECT id,name,assignedto,createdid,original_createdid FROM `instanceforms` WHERE TIMESTAMPDIFF(MINUTE,enddate,NOW()) = 15 AND DATE(enddate) = CURDATE()";
		if(!$result = $this->dao->execQuery($sql)){
			return;
		}
		try{
			$notification = new Notification();
			while ($row = $result->fetch_assoc()) {
				$notification->socketEmit('assignedtored',array('userid'=>$row['assignedto'],'name'=>$row['name']));
				$notification->sendFcmNotification($row['assignedto'],array("message" => "You have an assignment turning Red -" . $row['name'] . " in 15 minutes.", "title" => "OX Zion", "id" => $row['assignedto']),array( 'body' => "You have an Assignment -" . $row['name'] . " in 15 minutes.", 'title' => "OX Assignment", 'sound' => "default", 'color' => "#452767" ));
				$notification->socketEmit('followupred',array('userid'=>$row['createdid'],'name'=>$row['name']));
				$notification->sendFcmNotification($row['createdid'],array("message" => "You have a Follow up turning Red -" . $row['name'] . " in 15 minutes.", "title" => "OX Zion", "id" => $row['createdid']),array( 'body' => "You have a Follow up -" . $row['name'] . " in 15 minutes.", 'title' => "OX Follow up", 'sound' => "default", 'color' => "#452767" ));
			}
		} catch(Exception $e) {
			echo $e;
		}

	}
	public function CheckAndSendYellows(){
		$sql = "SELECT id,name,assignedto,createdid,original_createdid FROM `instanceforms` WHERE TIMESTAMPDIFF(MINUTE,nextactiondate,NOW()) = 15 AND DATE(nextactiondate) = CURDATE()";
		if(!$result = $this->dao->execQuery($sql)){
			return;
		}
		try{
			$notification = new Notification();
			while ($row = $result->fetch_assoc()) {
				$notification->socketEmit('assignedtoyellow',array('userid'=>$row['assignedto'],'name'=>$row['name']));
				$notification->sendFcmNotification($row['assignedto'],array("message" => "You have an assignment turning Red -" . $row['name'] . " in 15 minutes.", "title" => "OX Zion", "id" => $row['assignedto']),array( 'body' => "You have an Assignment -" . $row['name'] . " in 15 minutes.", 'title' => "OX Assignment", 'sound' => "default", 'color' => "#452767" ));
				$notification->socketEmit('followupyellow',array('userid'=>$row['createdid'],'name'=>$row['name']));
				$notification->sendFcmNotification($row['createdid'],array("message" => "You have a Follow up turning Yellow -" . $row['name'] . " in 15 minutes.", "title" => "OX Zion", "id" => $row['createdid']),array( 'body' => "You have a Follow up -" . $row['name'] . " in 15 minutes.", 'title' => "OX Follow up", 'sound' => "default", 'color' => "#452767" ));
			}
		} catch(Exception $e) {
			echo $e;
		}

	}
}
?>