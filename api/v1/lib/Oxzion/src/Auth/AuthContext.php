<?php
namespace Oxzion\Auth;

class AuthContext
{
    const CONTEXT_KEY = 'AUTH_CONTEXT';

    public static function put($key, $value)
    {
        $context = self::getContext();
        $context[$key] = $value;
        $_REQUEST[self::CONTEXT_KEY] = $context;
    }

    public static function get($key)
    {
        $context = self::getContext();
        if (isset($context[$key])) {
            return $context[$key];
        }
        return null;
    }
    public static function getAll()
    {
        $context = self::getContext();
        return $context;
    }

    private static function getContext()
    {
        if (!isset($_REQUEST[self::CONTEXT_KEY])) {
            return array();
        }

        return $_REQUEST[self::CONTEXT_KEY];
    }

    public static function isPrivileged($privilege)
    {
        return isset(self::get(AuthConstants::PRIVILEGES)[$privilege]) ? true : false;
    }
}
