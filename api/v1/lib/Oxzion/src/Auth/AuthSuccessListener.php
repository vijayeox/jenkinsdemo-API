<?php
namespace Oxzion\Auth;

use Bos\Auth\AuthContext;
use Bos\Auth\AuthConstants;

class AuthSuccessListener{
    private $userService;

    public function __construct($userService){
        $this->userService = $userService;
    }

    public function loadUserDetails($params){
        foreach ($params as $key => $value) {
            AuthContext::put($key, $value);
            if($key == AuthConstants::USERNAME){
                $result = $this->userService->getUserContextDetails($value);
                AuthContext::put(AuthConstants::USER_ID,  $result['id']);
                AuthContext::put(AuthConstants::NAME,  $result['name']);
                AuthContext::put(AuthConstants::ORG_ID, $result['orgid']);
                AuthContext::put(AuthConstants::USER_UUID, $result['uuid']);
                AuthContext::put(AuthConstants::PRIVILEGES,$this->userService->getPrivileges($result['id']));
                AuthContext::put(AuthConstants::API_KEY,null);
            }
            elseif($key == AuthConstants::API_KEY){
                AuthContext::put(AuthConstants::API_KEY, $value);
            }
        }
    }
}
?>