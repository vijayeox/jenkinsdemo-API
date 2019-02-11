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

    public function getRefreshTokenPayload($userDetail, $dataSalt)
    {
        $count = 0;
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
            if ($userInfo) {
                $checkToken = $this->checkExpiredTokenInfo($userDetail);
                if (!empty($checkToken) && $checkToken !== null) {
                    $data['id'] = $checkToken[0]['id'];
                    $data['expiry_date'] = Date("Y-m-d H:i:s", strtotime("+$refreshTokenPeriod day", $date));
                    $data['salt'] = $checkToken[0]['salt'];
                } else {
                    $data['id'] = $checkToken[0]['id'];
                    $data['expiry_date'] = Date("Y-m-d H:i:s", strtotime("+$refreshTokenPeriod day", $date));
                    $data['salt'] = $dataSalt;
                }
            } else {
                $data['expiry_date'] = Date("Y-m-d H:i:s", strtotime("+$refreshTokenPeriod day", $date));
                $data['salt'] = $dataSalt;
            }
            $form = new UserToken();
            $data['user_id'] = $userDetail['id'];
            $data['date_created'] = Date("Y-m-d H:i:s");
            $data['date_modified'] = Date("Y-m-d H:i:s");
            $form->exchangeArray($data);
            $form->validate();
            $count = $this->table->save($form);
            if ($count == 0) {
                return 0;
            }
        } catch (Exception $e) {
            return 0;
        }
        return $count;
    }

    public function checkUserInfo($userDetail)
    {
        //Code to check if the User already has a refresh token. Only if he does not have it then we need to add it.
        $queryString = "select * from ox_user_refresh_token";
        $where = "where user_id = " . $userDetail['id'] . "";
        $resultSet = $this->executeQuerywithParams($queryString, $where);
        return $queryResult = $resultSet->toArray();
    }

    public function checkExpiredTokenInfo($userDetail)
    {
        //Code to check if the User already has a refresh token. Only if he does not have it then we need to add it.
        $queryString = "select * from ox_user_refresh_token";
        $where = "where user_id = " . $userDetail['id'] . " and expiry_date > now()";
        $resultSet = $this->executeQuerywithParams($queryString, $where);
        return $queryResult = $resultSet->toArray();
    }
}

?>