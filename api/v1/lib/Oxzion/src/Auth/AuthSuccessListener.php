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
                $result = $this->userService->getUserContextDetails($value);
                if(isset($result) && count($result)==0){
                        return [];
                }
                AuthContext::put(AuthConstants::USER_ID, $result['id']);
                AuthContext::put(AuthConstants::NAME, $result['name']);
                AuthContext::put(AuthConstants::ORG_ID, $result['orgid']);
                AuthContext::put(AuthConstants::USER_UUID, $result['user_uuid']);
                AuthContext::put(AuthConstants::ORG_UUID, $result['org_uuid']);
                AuthContext::put(AuthConstants::PRIVILEGES, $this->userService->getPrivileges($result['id']));
                AuthContext::put(AuthConstants::API_KEY, null);
            } elseif ($key == AuthConstants::API_KEY) {
                AuthContext::put(AuthConstants::API_KEY, $value);
            }
        }
    }
}
