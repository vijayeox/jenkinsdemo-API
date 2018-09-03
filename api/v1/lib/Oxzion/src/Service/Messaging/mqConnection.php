<?php
	require_once __DIR__.'/../Common/Config.php';
	defined('MQ_PORT')?null : define("MQ_PORT", 5672);
	defined('MQ_USER')?null : define("MQ_USER", "guest");
	defined('MQ_PASSWORD')?null : define("MQ_PASSWORD", "guest");
	defined('EXCHANGE_TYPE')? null : define('EXCHANGE_TYPE', 'topic');
	defined('EXCHANGE')? null : define('EXCHANGE', 'msgxg');
	
	function mqCleanup($channel, $mqConn){
		error_log('Cleaning up the mq channel');
		$channel->callbacks=array();
		$channel->close();
		$mqConn->close();
	}	
	
		
?>