<?php
include_once __DIR__.'/autoload.php';
require __DIR__.'/../../vendor/autoload.php';
use \Auto\Autoloader;
use Thread;
use Oxzion\BackgroundEmailService;
use Oxzion\BackgroundCalendarService;
use Pool;
error_reporting(E_ALL);
    ini_set("display_errors", "On");

use Oxzion\EmailSyncTask;

// $emailService = BackgroundEmailService::getInstance();
// 		$emailService->syncEmailsForUser(array("userid"=>1709));

$pool = new Pool(4, Autoloader::class, [__DIR__.'/../../vendor/autoload.php']);
/* submit a task to the pool */
$pool->submit(new EmailSyncTask('1737',"{userid:1709}"));
/* in the real world, do some ::collect somewhere */
/* shutdown, because explicit is good */
$pool->shutdown();
?>