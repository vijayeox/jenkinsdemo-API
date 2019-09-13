<?php

namespace Oxzion\Utils;

use Symfony\Component\Yaml\Parser;

class YMLUtils {
    /**
     * @param $file
     * @return mixed
     */
    static public function ymlToArray($file)
    {
        $yml = new Parser();
        return $parsed = $yml->parse($file);
    }
}