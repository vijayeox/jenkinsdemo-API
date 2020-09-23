<?php

namespace Oxzion\Utils;

use Exception;

class ZipException extends Exception {
    private $context = NULL;

    public function __construct($message, $context = NULL) {
        parent::__construct($message);
        $this->context = $context;
    }

    public function getContext() {
        return $this->context;
    }
}

?>