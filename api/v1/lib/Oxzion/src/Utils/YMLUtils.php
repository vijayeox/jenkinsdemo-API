<?php

namespace Oxzion\Utils;

use Symfony\Component\Yaml\Parser;

class YMLUtils
{
    /**
     * @param $file
     * @return mixed
     */
    public static function ymlToArray($file)
    {
        $yml = new Parser();
        return $parsed = $yml->parse($file);
    }
}
