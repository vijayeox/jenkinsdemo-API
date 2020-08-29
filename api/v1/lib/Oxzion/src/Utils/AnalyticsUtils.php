<?php

namespace Oxzion\Utils;
use Oxzion\Auth\AuthContext;
use Oxzion\Auth\AuthConstants;

class AnalyticsUtils
{
    static function checkSessionValue($value) {
        if (strtolower(substr($value,0,8))=="session:") {
            $sessionvar = substr($value,8);
            switch($sessionvar) {
                case "username":
                    $value = AuthContext::get(AuthConstants::USERNAME);
                    break;
                case "name":
                    $value = AuthContext::get(AuthConstants::NAME);
                    break;
                case "orgid":
                    $value = AuthContext::get(AuthConstants::ORG_ID);
                    break;
                case "userid":
                    $value = AuthContext::get(AuthConstants::USER_ID);
                    break;
            }
        }
        return $value;
    }
}
