<?php
namespace Oxzion;
require_once __DIR__.'/../Common/Config.php';
include __DIR__.'/../autoload.php';
use Oxzion\Notification;
use Job\Job;

	class BackgroundCalendarService {
	 	private static $instance;
		private $dao;
	 	private $job;
		private static $autoloader; 

	private function __construct(){
		$this->dao = new Dao();
		$this->job = Job::getInstance();
		date_default_timezone_set('UTC');
		static::$autoloader = require_once(__DIR__.'/../../../vendor/autoload.php');
	}
		
		public static function getInstance(){
			if(!isset(static::$instance)){
				static::$instance = new BackgroundCalendarService();
			}
			return static::$instance;
		}
		
	 	public function sendReminder(){
	 		$this->getRecurringEvents();
	 	}
	 	protected function getRecurringEvents(){
			spl_autoload_register();
	 		$now = strtotime("today");
	 		$year = date("Y", $now);
	 		$month = date("m", $now);
	 		$day = date("d", $now);
	 		$nowString = $year . "-" . $month . "-" . $day;
	 		$week = (int) ((date('d', $now) - 1) / 7) + 1;
	 		$weekday = date("N", $now);
	 		$sql = "SELECT o.*,TIMESTAMPDIFF(MINUTE,NOW(),startdate) as timeremaining FROM operatingrhythm AS o  WHERE DATE(startdate) = CURDATE() AND DATE(enddate) = CURDATE() AND (TIMESTAMPDIFF(MINUTE,NOW(),startdate) =5 OR TIMESTAMPDIFF(MINUTE,NOW(),startdate) = 10 OR TIMESTAMPDIFF(MINUTE,NOW(),startdate) = 15 OR (TIMESTAMPDIFF(MINUTE,NOW(),startdate) < 5 AND TIMESTAMPDIFF(MINUTE,NOW(),startdate) > 0) AND TIMESTAMPDIFF(MINUTE,NOW(),startdate) > 0)  UNION SELECT EV.*,TIMESTAMPDIFF(MINUTE,NOW(),startdate) as timeremaining
	 		FROM operatingrhythm EV
	 		RIGHT JOIN or_meta EM1 ON EM1.eventid = EV.id
	 		WHERE DATE(startdate) = CURDATE() AND ( DATEDIFF( '$nowString', repeat_start ) % repeat_interval = 0 )
	 		OR ( 
	 		(repeat_year = $year OR repeat_year = '*' )
	 		AND
	 		(repeat_month = $month OR repeat_month = '*' )
	 		AND
	 		(repeat_day = $day OR repeat_day = '*' )
	 		AND
	 		(repeat_week = $week OR repeat_week = '*' )
	 		AND
	 		(repeat_weekday = $weekday OR repeat_weekday = '*' )
	 		AND repeat_start >= DATE('$nowString')) AND (TIMESTAMPDIFF(MINUTE,NOW(),startdate) = 5 OR TIMESTAMPDIFF(MINUTE,NOW(),startdate) = 10  OR TIMESTAMPDIFF(MINUTE,NOW(),startdate) = 15  OR (TIMESTAMPDIFF(MINUTE,NOW(),startdate) < 5 AND TIMESTAMPDIFF(MINUTE,NOW(),startdate) > 0) AND TIMESTAMPDIFF(MINUTE,NOW(),startdate) > 0)";
	 		if(!$result = $this->dao->execQuery($sql)){ //a RUNNING job exists for the userid
	 			return;
	 		}
	 		$status = "";
	 		$details = array();
	 		$total = 0;
	 		$failures = 0;
	 		try{
	 			$notification = new Notification();
	 			while ($row = $result->fetch_assoc()) {
	 				if($row['timeremaining']>0){
	 					$notification->socketEmit('newcalendarnotify',array('userid'=>$row['organizer'],'name'=>$row['name'],'time'=>$row['timeremaining']));
	 					$notification->sendFcmNotification($row['organizer'],array("message" => "You have a meeting -" . $row['name'] . " in " . $row['timeremaining']." minutes.", "title" => "OX Zion", "id" => $row['organizer']),array( 'body' => "You have a meeting -" . $row['name'] . " in " . $row['timeremaining']." minutes.", 'title' => "OX Calendar", 'sound' => "default", 'color' => "#452767" ));
	 					$this->getAttendeesAndNotify($row['id'],$notification,$row['timeremaining'],$row['name']);
	 				}
	 			}
	 		} catch(Exception $e) {
	 			echo $e;
	 		}
	 	}
	 	public function getAttendeesAndNotify($eventid,$notification,$timeremaining,$name){
			$sql = "SELECT * FROM  or_attendees WHERE  eventid =$eventid";
	 		if(!$result = $this->dao->execQuery($sql)){ //a RUNNING job exists for the userid
	 			return;
	 		}
	 		try{
	 			while ($row = $result->fetch_assoc()) {
	 					$notification->socketEmit('newcalendarnotify',array('userid'=>$row['avatarid'],'name'=>$name,'time'=>$timeremaining));
	 					$notification->sendFcmNotification($row['avatarid'],array("message" => "You have a meeting -" . $name . " in " . $timeremaining." minutes.", "title" => "OX Zion", "id" => $row['avatarid']),array( 'body' => "You have a meeting -" . $name . " in " . $timeremaining." minutes.", 'title' => "OX Calendar", 'sound' => "default", 'color' => "#452767" ));
	 			}
	 		} catch(Exception $e) {
	 			echo $e;
	 		}
	 	}
	 }
?>