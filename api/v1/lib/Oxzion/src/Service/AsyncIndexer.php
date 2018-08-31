<?php
	include_once __DIR__.'/autoload.php';

	use Messaging\MessageProducer;
	use Oxzion\OxzionIndexer;

	
	function batchIndex($params = array()){
		//$producer = MessageProducer::getInstance();
		//$producer->sendMessage($params, BATCH_INDEX_QUEUE);
		$indexObj = OxzionIndexer::getInstance('oxzion');
		$indexObj->batchIndex($params);
	}

	function index($params = array()){
		$producer = MessageProducer::getInstance();
		$producer->sendMessage($params, INDEX_DOCUMENT_QUEUE);	
	}	

	function delete($params = array()){
		$producer = MessageProducer::getInstance();
		$producer->sendMessage($params, DELETE_DOCUMENT_QUEUE);	
	}	
?>