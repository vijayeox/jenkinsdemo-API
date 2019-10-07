<?php

namespace Oxzion;

class VersionMismatchException extends \Oxzion\ServiceException {
    public function __construct(){
        parent::__construct('Entity version sent by client does not match the version on server.', 'VERSION_MISMATCH');
    }
}

?>

