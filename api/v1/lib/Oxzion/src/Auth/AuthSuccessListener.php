<?php
namespace Oxzion\Auth;

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
                AuthContext::put(AuthConstants::ROLES,$this->userService->getPriveleges($result['id']));
            }
        }
    }
}
?>