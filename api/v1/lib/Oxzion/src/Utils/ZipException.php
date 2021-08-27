<?php

namespace Oxzion\Utils;

use Exception;

class ZipException extends Exception
{
    private $context = null;

    public function __construct($message, $context = null)
    {
        parent::__construct($message);
        $this->context = $context;
    }

    public function getContext()
    {
        return $this->context;
    }
}
