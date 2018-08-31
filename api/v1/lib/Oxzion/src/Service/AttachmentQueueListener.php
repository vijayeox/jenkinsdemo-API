<?php
include __DIR__.'/Common/Config.php';
require __DIR__.'/autoload.php';
require __DIR__.'/../../vendor/autoload.php';
include __DIR__.'/ElasticIndex.php';
include __DIR__.'/SolrIndex.php';
require __DIR__ .'/../../../bin/init.php';
use Messaging\MessageConsumer;
use ElasticIndex;
use SolrIndex;

date_default_timezone_set('UTC');
$consumer = MessageConsumer::getInstance(True);
$autoloader = require __DIR__.'/../../vendor/autoload.php';

function indexitem($msg){
	$start = new DateTime();
	error_log("=========================================================");
	error_log(" In Queue - INDEX_ITEM");
	error_log(" [x] Received  - ".$msg->body);
	$count = 1;
	while ($count <= 5 ) {
		try{
			$doc = json_decode($msg->body, true);
		 	VA_ExternalLogic_ElasticIndex::$doc['action']($doc['id'], $doc['entity']);
		 	if($doc['action']=='indexByParams'){
		 		VA_ExternalLogic_SolrIndex::index($doc['id'], $doc['entity']);
		 	} else {
		 		VA_ExternalLogic_SolrIndex::$doc['action']($doc['id'], $doc['entity']);
		 	}
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

$consumer->consumeMessage('indexAttachment', 'indexitem');
$consumer->wait();
?>