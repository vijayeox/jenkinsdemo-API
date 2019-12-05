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
    	$output = shell_exec($command." 2>&1");
    	return $output;
    }
}
