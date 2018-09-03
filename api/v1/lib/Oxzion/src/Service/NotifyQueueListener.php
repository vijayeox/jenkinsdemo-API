<?php
include __DIR__.'/Common/Config.php';
require __DIR__.'/autoload.php';
require __DIR__.'/../../vendor/autoload.php';
include __DIR__.'/ElasticIndex.php';
include __DIR__.'/SolrIndex.php';
require __DIR__ .'/../../../bin/init.php';
use Messaging\MessageConsumer;
use Messaging\MessageProducer;
date_default_timezone_set('UTC');
$consumer = MessageConsumer::getInstance(True);
$autoloader = require __DIR__.'/../../vendor/autoload.php';

function triggerNotify($msg){
	$start = new DateTime();
	error_log("=========================================================");
	error_log(" In Queue - ASYNC_NOTIFY");
	error_log(" [x] Received  - ".$msg->body);
	$count = 1;
	while ($count <= 5 ) {
		try{
			$doc = json_decode($msg->body, true);
			print_r($doc);

		 	// VA_ExternalLogic_ElasticIndex::$doc['action']($doc['id'], $doc['entity']);
		 	// if($doc['action']=='indexByParams'){
		 	// 	VA_ExternalLogic_SolrIndex::index($doc['id'], $doc['entity']);
		 	// } else {
		 	// 	VA_ExternalLogic_SolrIndex::$doc['action']($doc['id'], $doc['entity']);
		 	// }
			$count = 5;
		}catch(Exception $e){
			error_log("Exception occurred while processing index");
			error_log("Message content - ".$msg->body);
			error_log("Exception  : ".$e->getMessage());
		}
		$count++;
	}
	error_log("=========================================================");
	date_default_timezone_set('UTC');
	$msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
	$end = new DateTime();
	$diff = $start->diff($end);
	error_log("Time taken to process index: ".$diff->format("%H:%I:%S"));
	error_log("=========================================================");
}
function triggerSocket($msg){
	$start = new DateTime();
	error_log("=========================================================");
	error_log(" In Queue - ASYNC_SOCKET");
	error_log(" [x] Received  - ".$msg->body);
	$count = 1;
	while ($count <= 5 ) {
		try{
			$doc = json_decode($msg->body, true);
			print_r($doc);
			$socketService = new VA_ExternalLogic_SocketService();
			$socketService->emit($doc['event'],$doc['data']);
			$count = 5;
		}catch(Exception $e){
			error_log("Exception occurred while processing index");
			error_log("Message content - ".$msg->body);
			error_log("Exception  : ".$e->getMessage());
		}
		$count++;
	}
	error_log("=========================================================");
	date_default_timezone_set('UTC');
	$msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
	$end = new DateTime();
	$diff = $start->diff($end);
	error_log("Time taken to process index: ".$diff->format("%H:%I:%S"));
	error_log("=========================================================");
}
function triggerGCM($msg){
	$start = new DateTime();
	error_log("=========================================================");
	error_log(" In Queue - ASYNC_GCM");
	error_log(" [x] Received  - ".$msg->body);
	$count = 1;
	while ($count <= 5 ) {
		try{
			$doc = json_decode($msg->body, true);
			if($doc['avatarid']!=$doc['recieverid'])
				$gcm_mapper = new \VA_Model_Googlegcm();
			$avatar = new \VA_Logic_Avatar((int) $doc['avatarid']);
			if ($doc['avatarid'] != $doc['recieverid']) {
				if ($doc['type'] == 1) {
					$app = "Mail";
					$params['icon'] = BASE_URL."public/organization/1/media/logo_header_for_club.png";
					$params['name'] = $avatar->name;
				} else if ($doc['type'] == 2) {
					$app = "Assignment";
					$params['icon'] = BASE_URL."public/organization/1/media/logo_header_for_club.png";
					$params['avatarname'] = $avatar->name;
				} else if ($doc['type'] == 3) {
					$app = "Chat";
					$params['icon'] = BASE_URL."public/organization/1/media/logo_header_for_club.png";
					// $params['counts'] = $avatar->getNotificationsCount();
					$params['commentcount'] = $params['counts']['chatcount'];
					$chatmapper = new \VA_Model_Chat();
					$messages = $chatmapper->getUnreadChat($doc['recieverid'], 7);
					$params['threadcount'] = $messages['threadscount'];
					$params['from'] = $avatar->name;
					$i = 0;
					foreach ($messages as $key => $message) {
						if ($key != 'threadscount') {
							$messages[$i] = $message;
						}
						$i++;
					}
					$params['messages'] = $messages;
				} else if ($doc['type'] == 4) {
					$app = "Follow ups ";
					$params['counts'] = $avatar->getNotificationsCount();
					$params['icon'] = BASE_URL."public/organization/1/media/logo_header_for_club.png";
					$params['avatarname'] = $avatar->name;
				} else if ($doc['type'] == 5||$doc['type'] == 6) {
					$app = "Group Chat";
				} else if ($doc['type'] == 7) {
					$app = "Reminder ";
					$params['counts'] = $avatar->getNotificationsCount();
				} else if ($doc['type'] == 9) {
					$app = "Alert ";
					$params['counts'] = $avatar->getNotificationsCount();
				} else {
					$app = "Notification";
					$doc['type'] = 8;
					$params['counts'] = $avatar->getNotificationsCount();
				}
				if ($doc['recieverid']) {
					$gcm_reg_idlist = $gcm_mapper->enlistbyAvatarid($doc['recieverid']);
					if($gcm_reg_idlist){
						$gcm_reg_ids = array_column($gcm_reg_idlist, 'gcm_registration_id');
					}
				}
				if ($groupid) {
					$gcm_reg_id = $gcm_mapper->enlistGCMAvatars($groupid, $doc['recieverid']);
					foreach ($gcm_reg_id as $v) {
						$gcm_reg_ids[] = current($v);
					}
				}
				if($gcm_reg_ids){
					\VA_API_FCM::sendNotification($gcm_reg_ids,array("message" => "You have a new " . $app . " from " . $avatar->name, "title" => "OX Zion", "id" => $id, "type" => $doc['type'], "name" => $avatar->name, "params" => json_encode($params)),array( 'body' => "You have a new " . $app . " from " . $avatar->name, 'title' => "OX Zion", 'sound' => "default", 'color' => "#452767" ));
				}
			}
			$count = 5;
		}catch(Exception $e){
			error_log("Exception occurred while processing send GCM");
			error_log("Message content - ".$msg->body);
			error_log("Exception  : ".$e->getMessage());
		}
		$count++;
	}
	error_log("=========================================================");
	date_default_timezone_set('UTC');
	$msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
	$end = new DateTime();
	$diff = $start->diff($end);
	error_log("Time taken to process index: ".$diff->format("%H:%I:%S"));
	error_log("=========================================================");
}
function triggerPoints($msg){
	$start = new DateTime();
	error_log("=========================================================");
	error_log(" In Queue - ASYNC_SOCKET");
	error_log(" [x] Received  - ".$msg->body);
	$count = 1;
	while ($count <= 5 ) {
		try{
			$doc = json_decode($msg->body, true);
			$avatar = new \VA_Logic_Avatar($doc['avatarid']);
			print_r($avatar->IKRARecursiveUpdate($doc));
			$count = 5;
		}catch(Exception $e){
			error_log("Exception occurred while processing index");
			error_log("Message content - ".$msg->body);
			error_log("Exception  : ".$e->getMessage());
		}
		$count++;
	}
	error_log("=========================================================");
	date_default_timezone_set('UTC');
	$msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
	$end = new DateTime();
	$diff = $start->diff($end);
	error_log("Time taken to process index: ".$diff->format("%H:%I:%S"));
	error_log("=========================================================");
}
$consumer->consumeMessage(ASYNC_NOTIFY, 'triggerNotify');
$consumer->consumeMessage(ASYNC_GCM, 'triggerGCM');
$consumer->consumeMessage(ASYNC_SOCKET, 'triggerSocket');
$consumer->consumeMessage(ASYNC_POINTS, 'triggerPoints');
$consumer->wait();
?>