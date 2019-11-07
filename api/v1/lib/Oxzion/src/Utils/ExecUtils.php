<?php
/**
 * Code to execute the command in commandline
 * @api
 * @method execCommand
 * @param $command command to be executed in commandline
 * @return Json Array of Username and List of Apps
 */
namespace Oxzion\Utils;

class ExecUtils
{
    public static function execCommand($command){
        return exec($command, $output, $return);
    }
}
