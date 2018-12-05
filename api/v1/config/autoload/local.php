<?php

/**
 * Local Configuration Override
 *
 * This configuration override file is for overriding environment-specific and
 * security-sensitive configuration information. Copy this file without the
 * .dist extension at the end and populate values as needed.
 *
 * @NOTE: This file is ignored from Git by default with the .gitignore included
 * in ZendSkeletonApplication. This is a good practice, as it prevents sensitive
 * credentials from accidentally being committed into version control.
 */
use Doctrine\DBAL\Driver\PDOMySql\Driver as PDOMySqlDriver;

$host = '172.16.1.118';
$db = 'rakshithapi';
$username = 'rakshith';
$password = '7zet3os!';
// print_r($_ENV);exit;

if (isset($_ENV['ENV']) && $_ENV['ENV'] == 'test') {
	$host = '172.16.1.118';
	$db = 'rakshithapi_test';
	$username = 'rakshith';
	$password = '7zet3os!';
} else if (isset($_ENV['ENV']) && $_ENV['ENV'] == 'appinstall') {
	echo "Installation Mode";exit;
	$host = '172.16.1.118';
	$db = 'rakshithapi_app';
	$username = 'rakshith';
	$password = '7zet3os!';	
}

return [
	'db' => [
		'host' => $host,
		'driver' => 'Pdo_Mysql',
		'dsn' => 'mysql:dbname=' . $db . ';host=' . $host . ';charset=utf8;username=' . $username . ';password=' . $password,
	],

];
