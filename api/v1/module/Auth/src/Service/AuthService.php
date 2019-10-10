<?php
namespace Auth\Service;

use Oxzion\Service\AbstractService;
use Oxzion\Auth\AuthContext;
use Oxzion\Auth\AuthConstants;
use Oxzion\ValidationException;
use Zend\Db\Sql\Expression;
use Exception;

use function GuzzleHttp\json_decode;

class AuthService extends AbstractService
{
    private $table;
    private $userService;
    private $userCacheService;
    public function __construct($config, $dbAdapter, $userService,$userCacheService)
    {
        parent::__construct($config, $dbAdapter);
        $this->userService = $userService;
        $this->userCacheService = $userCacheService;
    }

    public function getApiSecret($apiKey)
    {
        $queryString = "select secret from ox_api_key";
        $where = 'where api_key = "'.$apiKey.'"';
        $resultSet = $this->executeQuerywithParams($queryString, $where);
        return $resultSet->toArray();
    }
    public function executeActions($params){
        if(isset($params['data'])){
            $data = $params['data'];
        } else {
            $data = $params;
        }
        $rawData = $params;
        if(isset($data['commands'])){
            $commands = json_decode($data['commands']);
            $params = $data;
            foreach ($commands as $command) {
                $params = $this->performCommand($command,$params,$data,$rawData);
            }
        }
        return $params;
    }
    private function performCommand($command,$params,$data,$rawData){
        switch ($command) {
            case 'create_user':
                $params = $this->createUser($params,$data,$rawData);
                break;
            case 'sign_in':
                $params['auto_login'] = 1;
                break;
            case 'store_cache_data':
                $params = $this->storeCacheData($data,$params,$rawData);
                break;
            default:
                break;
        }
        return $params;
    }
    private function createUser($params,$data,$rawData){
        if(!isset($data['username'])){
            $data['username'] = $data['firstname'].".".$data['lastname'];
        }
        try {
            $success = $this->userService->createUser($params,$data);
            if($success){
                $params['user'] = $data;
                return $params;
            } else {
                throw new Exception("Error Creating User", 1);
            }
        } catch(Exception $e){
            throw $e;
        }
        return 0;
    }
    private function storeCacheData($data,$params,$rawData){
        if(isset($params['user']['username'])){
            $user = $this->userService->getUserDetailsbyUserName($params['user']['username']);
        }
        if(isset($params['username'])){
            $user = $this->userService->getUserDetailsbyUserName($params['username']);
        }
        if(!isset($user)){
            throw new Exception("Cache Creation Failed", 1);
        }
        $appId = 0;
        if(isset($params['app_id'])){
            if ($app = $this->getIdFromUuid('ox_app', $params['app_id'])) {
                $appId = $app;
            }
        } else {
            $appId = null;
        }
        $cacheData = array('user_id'=>$user['id'],'content'=>json_encode($rawData),'app_id'=>$appId);
        $userCache = $this->userCacheService->createUserCache($cacheData);
        $params['cache_data'] = $cacheData;
        return $params;
    }
}
