<?php
	require __DIR__.'/Common/Config.php';
	require __DIR__.'/autoload.php';
	use Oxzion\EmailSyncTask;
	use Oxzion\CalendarSyncTask;
	use Job\JobRunner;

	date_default_timezone_set('UTC');
	$autoloader = require __DIR__.'/../../vendor/autoload.php';
	$jobRunner = JobRunner::getInstance();
?>