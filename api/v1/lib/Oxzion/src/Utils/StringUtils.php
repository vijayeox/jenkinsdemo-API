<?php

namespace Oxzion\Utils;

class StringUtils
{
	public static function startsWith($string, $startString, $caseSensitive = false)
	{
		$len = strlen($startString); 
		if(!$caseSensitive){
			$string = strtoupper($string);
			$startString = strtoupper($startString);
		}
    	return (substr($string, 0, $len) === $startString); 
	}
}