<?php
namespace Oxzion\Auth;

use Oxzion\Auth\AuthContext;
use Oxzion\Auth\AuthConstants;

class AuthSuccessListener
{
    private $userService;

    public function __construct($userService)
    {
        $this->userService = $userService;
    }

    public function loadUserDetails($params)
    {
        foreach ($params as $key => $value) {
            AuthContext::put($key, $value);
            if ($key == AuthConstants::USERNAME) {
                $accountId = null;
                if(isset($params[AuthConstants::ACCOUNT_ID])){
                    $accountId = $params[AuthConstants::ACCOUNT_ID];
                }
                $result = $this->userService->getUserContextDetails($value, $accountId);
                if(isset($result) && count($result)==0){
                        return [];
                }

                AuthContext::put(AuthConstants::USER_ID, $result['id']);
                AuthContext::put(AuthConstants::NAME, $result['name']);
                AuthContext::put(AuthConstants::ACCOUNT_ID, $result['account_id']);
                AuthContext::put(AuthConstants::ACCOUNT_UUID, $result['accountId']);
                AuthContext::put(AuthConstants::ORG_ID, $result['organization_id']);
                AuthContext::put(AuthConstants::USER_UUID, $result['userId']);
                AuthContext::put(AuthConstants::ORG_UUID, $result['organizationId']);
                AuthContext::put(AuthConstants::PRIVILEGES, $this->userService->getPrivileges($result['id']));
                AuthContext::put(AuthConstants::API_KEY, null);
            } elseif ($key == AuthConstants::API_KEY) {
                AuthContext::put(AuthConstants::API_KEY, $value);
            }
        }
    }
}
