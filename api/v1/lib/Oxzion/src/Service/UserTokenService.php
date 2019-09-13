<?php
namespace Oxzion\Service;

use Oxzion\Auth\AuthContext;
use Oxzion\Auth\AuthConstants;
use Oxzion\Service\AbstractService;
use Oxzion\ValidationException;
use Oxzion\Model\UserToken;
use Oxzion\Model\UserTokenTable;
use Oxzion\Jwt\JwtHelper;
use Zend\Db\ResultSet\ResultSet;

class UserTokenService extends AbstractService
{
    protected $config;
    /**
     * @ignore table
     */
    private $table;

    public function __construct($config, $dbAdapter, UserTokenTable $table = null)
    {
        parent::__construct($config, $dbAdapter);
        if ($table) {
            $this->table = $table;
        }
        $this->config = $config;
    }

    public function generateRefreshToken($userDetail){
        $dataSalt = self::getRefreshTokenPayload();
        try {
            if ($userDetail['id'] === null || $userDetail['id'] === '') {
                return 0;
            }
            $userInfo = $this->checkUserInfo($userDetail);
            if(!isset($userInfo[0])){
               return $this->createUpdateToken($userDetail['id'],$dataSalt);
            } else {
                if($expiredToken = $this->checkRefreshTokenExpired($userDetail['id'])){
                    return $this->createUpdateToken($userDetail['id'],self::getRefreshTokenPayload(),$expiredToken[0]['id']);
                } else {
                    return $userInfo[0]['salt'];
                }
            }
        } catch (Exception $e) {
            return 0;
        }
    }
    public static function getRefreshTokenPayload () {
        $salt = uniqid(mt_rand(), true);
        return $salt;
    }
    private function createUpdateToken($userId,$dataSalt,$id=null){
        if(isset($this->config['refreshTokenPeriod'])){
            $refreshTokenPeriod = $this->config['refreshTokenPeriod'];
        } else {
            $refreshTokenPeriod = 1;
        }
        $date = strtotime(Date("Y-m-d H:i:s"));
        $data['expiry_date'] = Date("Y-m-d H:i:s", strtotime("+$refreshTokenPeriod day", $date));
        $data['salt'] = $dataSalt;
        $data['date_created'] = Date("Y-m-d H:i:s");
        $data['date_modified'] = Date("Y-m-d H:i:s");
        $data['user_id'] = $userId;
        if($id){
            $data['id'] = $id;
        }
        $form = new UserToken();
        $form->exchangeArray($data);
        $form->validate();
        $count = $this->table->save($form);
        if ($count == 0) {
            return 0;
        }
        return $dataSalt;
    }
    
    protected function checkUserInfo($userDetail) {
         $queryString = "select * from ox_user_refresh_token";
        $where = "where user_id = " . $userDetail['id'];
        $resultSet = $this->executeQuerywithParams($queryString, $where);
        return $queryResult = $resultSet->toArray();
    }
    protected function checkRefreshTokenExpired($userId) {
         $queryString = "select * from ox_user_refresh_token";
        $where = "where user_id = " . $userId." AND expiry_date < now()";
        $resultSet = $this->executeQuerywithParams($queryString, $where);
        return $queryResult = $resultSet->toArray();
    }

    public function checkExpiredTokenInfo($userId) {
        $adapter = $this->dbAdapter;
        $queryString = "select * from ox_user_refresh_token";
        $where = "where expiry_date > now()";
        $resultSet = $this->executeQuerywithParams($queryString, $where);
        return $resultSet->toArray();
    }
}

?>