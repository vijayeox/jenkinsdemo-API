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
$host = '61.12.69.67';
$db = 'stephenox';
$username = 'stephen';
$password = 'stephen321!';



return [
    'db' => [
    	'driver' => 'Pdo_Mysql',
    	'database' => $db,
    	'username' => $username,
    	'password' => $password,
    	'dsn' => 'mysql:dbname=' . $db . ';host=' . $host . ';charset=utf8;',
    ],
    'elasticsearch' =>[
        'serveraddress'=>'dataocean.oxzion.com',
        'port'=>'9200',
        'scheme'=>'http',
        'core'=>'oxstaging',
        'type'=>'type',
        'user'=>'elastic',
        'password'=>'hvqr9799/'
    ],
    'salt' => 'arogAegatnaVOfficeBack123',
    'jwtKey' => 'l7Hnf6TGMYTy6eP7oyyWNG1MGay1T39/If495vwYBhS2j6OOHlMKhSf3qADPlWwkHQ6h3tjP2klI0kvKPltvVA==',
    'jwtAlgo' => 'HS512',
    'authRequiredText' => 'Authentication Required',
];
