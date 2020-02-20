<?php
/**
 * Code to get the Username and the List of all the apps that are not in the Userlist
 * @api
 * @link /user/:userId/usertoken
 * @method userLoginToken
 * @param $id ID of User
 * @return Json Array of Username and List of Apps
 */
namespace Oxzion\Utils;

class BosUtils
{
    public static function randomUserName($string)
    {
        $pattern = " ";
        $firstPart = strstr(strtolower($string), $pattern, true);
        $secondPart = substr(strstr(strtolower($string), $pattern, false), 0, 3);
        $nrRand = rand(0, 100);
        $username = trim($firstPart) . trim($secondPart) . trim($nrRand);
        return $username;
    }

    public static function randomPassword()
    {
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < 8; $i++) {
            $n = mt_rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass); //turn the array into a string
    }

    public static function execCommand($command)
    {
        return exec($command, $output, $return);
    }
}
