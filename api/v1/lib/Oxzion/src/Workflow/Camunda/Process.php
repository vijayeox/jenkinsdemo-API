<?php

namespace Oxzion\Workflow\Camunda;

use Bos\Model\Entity;
use Bos\ValidationException;

class Process extends Entity {

    protected $data = array(
        'id' => 0,
        'name'=> 0,
        'tenantId' => 0
    );
}
?>