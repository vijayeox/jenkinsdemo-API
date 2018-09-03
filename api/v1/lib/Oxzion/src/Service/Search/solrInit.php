<?php
	// error_reporting(E_ALL);
	// ini_set('display_errors', true);
	$ini = parse_ini_file(dirname(dirname(dirname(dirname(__DIR__)))).'/application/configs/application.ini');
	$GLOBALS['config'] = array(
	    'endpoint' => array(
	        'localhost' => array(
	            'host' => $ini['resources.solr.serveraddress'],
	            'port' => 8983,
	            'path' => '/solr/',
	            'core' => $ini['resources.solr.core']
	        )
	    )
	);
?>