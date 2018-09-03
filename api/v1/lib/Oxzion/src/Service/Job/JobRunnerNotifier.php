<?php
namespace Job;

interface JobRunnerNotifier{
	function notifyJob($jobDetail);
}
?>