<?php
	
	//set database variables
ini_set('error_reporting' , E_ALL & ~E_DEPRECATED & ~E_STRICT & ~E_NOTICE & ~E_WARNING);
	$ini = parse_ini_file(dirname(dirname(dirname(dirname(__DIR__)))).'/application/configs/application.ini');
	defined('SERVER_NAME')? null : define("SERVER_NAME",$ini['resources.db.params.host']);
	defined('USERNAME')? null : define("USERNAME",$ini['resources.db.params.username']);
	defined('PASSWORD')? null : define("PASSWORD",$ini['resources.db.params.password']);
	defined('SERVER_URL')? null : define("SERVER_URL",$ini['resources.fullurl']);
	defined('DBNAME')? null : define("DBNAME",$ini['resources.db.params.dbname']);
	defined('SOLR_SERVER_NAME')? null : define("SOLR_SERVER_NAME",$ini['resources.solr.serveraddress']);
	defined('SOLR_BUFFER_SIZE')? null : define("SOLR_BUFFER_SIZE",100);
	defined('BATCH_INDEX_QUEUE')? null : define("BATCH_INDEX_QUEUE",'batchIndex');
	defined('INDEX_DOCUMENT_QUEUE')? null : define("INDEX_DOCUMENT_QUEUE",'indexDocument');
	defined('DELETE_DOCUMENT_QUEUE')? null : define("DELETE_DOCUMENT_QUEUE",'deleteDocument');
	defined('DB_DATETIME_FORMAT')?null : define("DB_DATETIME_FORMAT", 'Y-m-d H:i:s');
	defined('DB_DATE_FORMAT')? null : define("DB_DATE_FORMAT", 'Y-m-d');
	defined('SOLR_DATETIME_FORMAT')?null : define("SOLR_DATETIME_FORMAT", 'Y-m-d\TH:i:s\Z');
	defined('ATTACHMENT_BASE')?null : define('ATTACHMENT_BASE', dirname(dirname(dirname(dirname(__DIR__)))).'/data/uploads/organization/');
	defined('LOGIN_EMAILS')? null : define("LOGIN_EMAILS",'loginEmails');
	defined('LOGOUT_EMAILS')? null : define("LOGOUT_EMAILS",'logoutEmails');
	defined('INBOX_STATUS')? null : define("INBOX_STATUS",'inboxStatus');
	defined('SYNC_EMAIL')? null : define("SYNC_EMAIL",'syncEmail');
	defined('EMAIL_SYNC_JOB_EXPIRY')? null : define("EMAIL_SYNC_JOB_EXPIRY", 120);
	defined('EMAIL_SYNC_PERIOD')? null : define("EMAIL_SYNC_PERIOD", 1);
	defined('EMAIL_SYNC_CHUNK_SIZE')? null : define("EMAIL_SYNC_CHUNK_SIZE", 1000);
	defined('EMAIL_SYNC_FOR_LAST_MONTHS')? null : define("EMAIL_SYNC_FOR_LAST_MONTHS", 1);
	defined('NODEJS_URL')? null : define("NODEJS_URL",$ini['resources.nodejs.url']);
	defined('BASE_URL')? null : define("BASE_URL",$ini['resources.base.url']);
	defined('FCM_TOKEN')? null : define("FCM_TOKEN",$ini['resources.fcm.token']);
	defined("GOOGLE_OAUTH_SECRET") ? null : define("GOOGLE_OAUTH_SECRET",__DIR__."\googlecert.php");
	defined("CACHE_FOLDER") ? null : define("CACHE_FOLDER", '/tmp/oz_cache/');
	defined("LATEST_UNSEEN_EMAILS_TO_CACHE") ? null : define("LATEST_UNSEEN_EMAILS_TO_CACHE", 100);
	defined("CACHE_TTL_HOURS") ? null : define("CACHE_TTL_HOURS", 48);
	defined('THREAD_SLEEPDURATION')? null : define("THREAD_SLEEPDURATION",$ini['thread.sleepduration']);
	defined('THREAD_LIMIT')? null : define("THREAD_LIMIT",$ini['thread.limit']);
	defined('HOUSEKEEPING_TIME')? null : define("HOUSEKEEPING_TIME",$ini['thread.housekeepingtime']);

?>
