<?php
namespace Oxzion\Service;

use Bos\Auth\AuthContext;
use Bos\Auth\AuthConstants;
use Bos\Service\AbstractService;
use Bos\ValidationException;
use Oxzion\Model\UserToken;
use Oxzion\Model\UserTokenTable;

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

    public function generateRefreshToken($userDetail,$dataSalt){
        if(isset($this->config['db']['refreshTokenPeriod'])){
            $refreshTokenPeriod = $this->config['db']['refreshTokenPeriod'];
        } else {
            $refreshTokenPeriod = 1;
        }
        try {
            $date = strtotime(Date("Y-m-d H:i:s"));
            if ($userDetail['id'] === null || $userDetail['id'] === '') {
                return 0;
            }
            $userInfo = $this->checkUserInfo($userDetail);
            if(!isset($userInfo[0])){
                $data['expiry_date'] = Date("Y-m-d H:i:s", strtotime("+$refreshTokenPeriod day", $date));
                $data['salt'] = $dataSalt;
                $data['user_id'] = $userDetail['id'];
                $data['date_created'] = Date("Y-m-d H:i:s");
                $data['date_modified'] = Date("Y-m-d H:i:s");
                $form = new UserToken();
                $form->exchangeArray($data);
                $form->validate();
                $count = $this->table->save($form);
                if ($count == 0) {
                    return 0;
                }
                return $dataSalt;
            } else {
                return $userInfo[0]['salt'];
            }
        } catch (Exception $e) {
            return 0;
        }
    }
    
    protected function checkUserInfo($userDetail) {
         $queryString = "select * from ox_user_refresh_token";
        $where = "where user_id = " . $userDetail['id'] . "";
        $resultSet = $this->executeQuerywithParams($queryString, $where);
        return $queryResult = $resultSet->toArray();
    }

    public function checkExpiredTokenInfo($refreshtoken) {
        $queryString = "select * from ox_user_refresh_token";
        $where = "where salt = '".$refreshtoken."' and expiry_date > now()";
        $resultSet = $this->executeQuerywithParams($queryString, $where);
        return $queryResult = $resultSet->toArray();
    }
}

?>