<?php

namespace Oxzion\Workflow\Camunda;

use Oxzion\Model\Entity;
use Oxzion\ValidationException;

class Process extends Entity {

    protected $data = array(
        'id' => 0,
        'name'=> 0,
        'tenantId' => 0
    );
}
?>