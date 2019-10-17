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

$host = '172.16.1.101';
$db = 'appBuilder_oxzionapi';
$username = 'bharat';
$password = 'password';

if(isset($_ENV['ENV']) && $_ENV['ENV'] == 'test'){
    $host = '172.16.1.101';
    $db = "appBuilder_oxzionapi_test";
    $username = "bharat";
    $password = "password";
}

return [
    'db' => [
        'host' => $host,
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
    'chat' => [
        'chatServerUrl' => 'http://localhost:8065/',
        'authToken' => ''
    ],
    'crm' => [
        'crmServerUrl' => 'http://localhost:8075/crm/public/',
        'authToken' => ''
    ],
    'task' => [
        'taskServerUrl' => 'http://localhost:3000/api/v3/',
        'username' => 'apikey',
        'authToken' => ''
    ],
    'calendar' => [
        'calendarServerUrl' => 'http://localhost:8075/calendar',
        'authToken' => ''
    ],
    'DELEGATE_FOLDER'=>__DIR__.'/../../data/delegate/',
    'applicationUrl' => 'http://localhost:8081',
    'RULE_FOLDER'=>__DIR__.'/../../data/rules/',
    'DATA_FOLDER'=>__DIR__.'/../../data/',
    "UPLOAD_FOLDER" => __DIR__.'/../../data/uploads/',
    'TEMPLATE_FOLDER'=>__DIR__.'/../../data/template/',
    'APP_UPLOAD_FOLDER' => __DIR__.'/../../data/app',
    'APP_DOCUMENT_FOLDER' => __DIR__.'/../../data/file_docs/',
    'CLIENT_FOLDER' => __DIR__.'/../../../../clients/',
    'baseUrl' => 'http://localhost:8080',
    'salt' => 'arogAegatnaVOfficeBack123',
    'jwtKey' => 'l7Hnf6TGMYTy6eP7oyyWNG1MGay1T39/If495vwYBhS2j6OOHlMKhSf3qADPlWwkHQ6h3tjP2klI0kvKPltvVA==',
    'jwtAlgo' => 'HS512',
    'authRequiredText' => 'Authentication Required',
    'refreshTokenPeriod' => '7',
    'paymentGatewayType'=>'DEMO',
];
