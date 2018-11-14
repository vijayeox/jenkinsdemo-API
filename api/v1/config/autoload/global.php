<?php
/**
 * Global Configuration Override
 *
 * You can use this file for overriding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * @NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file.
 */
use Doctrine\DBAL\Driver\PDOMySql\Driver as PDOMySqlDriver;

$host = 'localhost';
$db = 'oxzionapi';
$username = 'root';
$password = 'oxzion';


if(isset($_ENV['ENV']) && $_ENV['ENV'] == 'test') {
    $host = 'localhost';
    $db = 'oxzionapi';
    $username = 'root';
    $password = 'oxzion';

}

return [
    'db' => [
    	'driver' => 'Pdo_Mysql',
    	'database' => $db,
    	'username' => $username,
    	'password' => $password,
    	'dsn' => 'mysql:dbname=' . $db . ';host=' . $host . ';charset=utf8;',
    ],
    'elasticsearch' => [
        'serveraddress'=>'dataocean.oxzion.com',
        'port'=>'9200',
        'scheme'=>'http',
        'core'=>'oxstaging',
        'type'=>'type',
        'user'=>'elastic',
        'password'=>'hvqr9799/'
    ],
    'DATA_FOLDER'=>__DIR__.'/../../data/uploads/',
    'salt' => 'arogAegatnaVOfficeBack123',
    'jwtKey' => 'l7Hnf6TGMYTy6eP7oyyWNG1MGay1T39/If495vwYBhS2j6OOHlMKhSf3qADPlWwkHQ6h3tjP2klI0kvKPltvVA==',
    'jwtAlgo' => 'HS512',
    'authRequiredText' => 'Authentication Required',
];
