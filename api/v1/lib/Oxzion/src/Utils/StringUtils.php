<?php

namespace Oxzion\Utils;

class StringUtils
{
    public static function startsWith($string, $startString, $caseSensitive = false)
    {
        $len = strlen($startString);
        if (!$caseSensitive) {
            $string = strtoupper($string);
            $startString = strtoupper($startString);
        }
        return (substr($string, 0, $len) === $startString);
    }

    public static function endsWith($string, $endString, $caseSensitive = false)
    {
        $len = strlen($endString);
        if (!$caseSensitive) {
            $string = strtoupper($string);
            $endString = strtoupper($endString);
        }
        if ($len == 0) {
            return true;
        }
        return (substr($string, -$len) === $endString);
    }

    public static function randomString($stringLength)
    {
        $sourceStr = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
        return substr(str_shuffle($sourceStr), 0, $stringLength);
    }
}
