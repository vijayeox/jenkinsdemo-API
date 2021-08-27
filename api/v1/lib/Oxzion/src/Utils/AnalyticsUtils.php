<?php

namespace Oxzion\Utils;

use Oxzion\Auth\AuthConstants;
use Oxzion\Auth\AuthContext;

class AnalyticsUtils
{
    public static function checkSessionValue($value)
    {
        if (isset($value)) {
            if (is_string($value)) {
                if (strtolower(substr($value, 0, 8)) == "session:") {
                    $sessionvar = substr($value, 8);
                    switch ($sessionvar) {
                        case "username":
                            $value = AuthContext::get(AuthConstants::USERNAME);
                            break;
                        case "name":
                            $value = AuthContext::get(AuthConstants::NAME);
                            break;
                        case "accountid":
                            $value = AuthContext::get(AuthConstants::ACCOUNT_ID);
                            break;
                        case "userid":
                            $value = AuthContext::get(AuthConstants::USER_ID);
                            break;
                        case "useruuid":
                            $value = AuthContext::get(AuthConstants::USER_UUID);
                            break;
                    }
                }
            }
        }
        return $value;
    }
}
