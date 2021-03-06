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
$username = 'user';
$password = 'password';

if(isset($_ENV['ENV']) && $_ENV['ENV'] == 'test'){
    $host = 'localhost';
    $db = "oxzionapi_test";
    $username = "user";
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
        'serveraddress'=>'localhost',
        'port'=>'9200',
        'scheme'=>'http',
        'core'=>'core',
        'type'=>'type',
        'user'=>'user',
        'password'=>'password'
    ],
    'amqp' => [
        'host' => 'tcp://localhost:61613'
    ],
    'workflow' => [
        'engineUrl' => 'http://localhost:8090/engine-rest/engine/default/'
    ],
    'chat' => [
        'chatServerUrl' => 'http://localhost:8065/',
        'authToken' => '',
        'appBotUrl' => 'plugins/com.code.oxzion.mattermost.appbot-plugin/'
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
    'job' => [
        'jobUrl' => 'http://localhost:8085/',
        'authToken' => ''
    ],
    'foley' => [
        'username' => 'foley',
        'password' => 'h+MfHWZhND64FpTmoKwy8Q==::3157db2c39b1c896a95904d1a0e7555e',
        'foleyurl' => 'https://sandboxapi.foleyservices.com/api/foley/v1.2/json/',
        'foleyapiusername' => 'HUBAPI-136757',
        'foleyapipassword' => 'qdf9uqMRBs5TnUkivPHAro4=::f60dfe4a0a79beb2a8ab5d3848c3941c'
    ],
    'esign' => [
        'clientid' => '6l3lpgsbrha13u8rdg9t5hq3au',
        'clientsecret' => '3uvdithse6p3qmjvv4s3k1mcjt33p06f9iiein5sbmka9prdcqj',
        'url' => 'https://lab-auth.insuresign.io/oauth2/token',
        'docurl' => 'https://lab-api.insuresign.io/',
        "email" => "info@vantageagora.com",
        "username" => "info@vantageagora.com",
        "password" => "CFnEWs0g",
        "callbackUrl" => 'https://qa3.eoxvantage.com/esign/event'
    ],
    'ims' => [
        'wsdlUrl' => 'https://ws2.mgasystems.com/ims_demo/',
        "userName" => "vantage.agora",
        "tripleDESEncryptedPassword" => "9srGG5hflGT0aDrgsxs3GQ=="
    ],
    'insurelearn' => [
        //'olpSystem' => 'https://hubstg.insurelearn.com/',
        'olpSystem' => 'https://training.hubinternational.com/',
        "ILSUSER" => "eox_connector",
        "ILSPASSWD" => "P9mwT~&/6Qk@y}hk"
    ],
    'zendrive' => [
        'zendriveServerUrl' => 'https://staging-hub.wla.zendrive.com/api/v1/',
        'authToken' => 'ZfudDKB4.Y7Cxcu0gMRut51mVx3HRXOs3KL3tssSz'
    ],
    'DELEGATE_FOLDER'=>__DIR__.'/../../data/delegate/',
    'ENTITY_FOLDER'=>__DIR__.'/../../data/entity/',
    'FORM_FOLDER'=>__DIR__.'/../../data/forms/',
    'PAGE_FOLDER'=>__DIR__.'/../../data/pages/',
    'apiUrl' => 'http://localhost:8080',
    'applicationUrl' => 'http://localhost:8081',
    'RULE_FOLDER'=>__DIR__.'/../../data/rules/',
    'DATA_FOLDER'=>__DIR__.'/../../data/',
    "UPLOAD_FOLDER" => __DIR__.'/../../data/uploads/',
    'TEMPLATE_FOLDER'=>__DIR__.'/../../data/template/',
    'APP_UPLOAD_FOLDER' => __DIR__.'/../../data/app',
    'APP_DOCUMENT_FOLDER' => __DIR__.'/../../data/file_docs/',
    'APP_ESIGN_FOLDER' => __DIR__.'/../../data/esign/',
    'CLIENT_FOLDER' => __DIR__.'/../../../../clients/',
    'APPS_FOLDER' => __DIR__."/../../../../view/apps/",
    'GUI_FOLDER' => __DIR__."/../../../../view/gui/src/externals/",
    'THEME_FOLDER' => __DIR__."/../../../../view/themes/",
    'EOX_APP_SOURCE_DIR' => realpath(__DIR__.'/../../data/AppSource') . '/',
    'EOX_APP_DEPLOY_DIR' => realpath(__DIR__.'/../../data/AppDeploy') . '/',
    'internalBaseUrl' => 'http://localhost:8080',
    'baseUrl' => 'http://localhost:8080',
    'batch_size' => 100,
    'salt' => 'arogAegatnaVOfficeBack123',
    'jwtKey' => 'l7Hnf6TGMYTy6eP7oyyWNG1MGay1T39/If495vwYBhS2j6OOHlMKhSf3qADPlWwkHQ6h3tjP2klI0kvKPltvVA==',
    'jwtAlgo' => 'HS512',
    'authRequiredText' => 'Authentication Required',
    'refreshTokenPeriod' => '7',
    'paymentGatewayType'=>'DEMO'

];
