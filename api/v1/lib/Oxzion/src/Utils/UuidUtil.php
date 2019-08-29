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

use Ramsey\Uuid\Uuid;

class UuidUtil
{
    public static function uuid()
    {
        return Uuid::uuid4()->toString();
    }
}
