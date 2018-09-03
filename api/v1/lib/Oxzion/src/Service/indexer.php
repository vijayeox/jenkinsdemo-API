<?php
ini_set('max_execution_time', -1);
ini_set('memory_limit', -1); 
require __DIR__ .'/autoload.php';
require __DIR__ .'/../../../bin/init.php';
\Zend_Session::start();
// \VA_Logic_Session::setAvatar('beebe');
$formindex = new ElasticSearch\FormIndexer();
$wizardindex = new ElasticSearch\WizardIndexer();
$mailindex = new ElasticSearch\MessageIndexer();
$oleindex = new ElasticSearch\OleIndexer();
$formcommentindex = new ElasticSearch\FormCommentIndexer();
$userindex = new ElasticSearch\UserIndexer();
$timesheet = new ElasticSearch\TimesheetIndexer();
$attachment = new ElasticSearch\AttachmentIndexer();
// $csv = new ElasticSearch\CsvIndexer();
// $csv->index();
// $formindex->index();
// $mailindex->index();
// $oleindex->index();
// $formcommentindex->index();
// $userindex->index();
// $wizardindex->index();
// $timesheet->index();
$attachment->index();
 ?>