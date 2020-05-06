<?php

namespace Oxzion\Model;

use Oxzion\Model\Entity;

class Job extends Entity
{
    protected $data = array(
        'id' => null,
        'app_id' => null,
        'org_id' => null,
        'name' => null,
        'job_id' => null,
        'group_name' => null,
        'config' => null,
    );

    public function validate()
    {
        $required = array('name', 'job_id', 'group_name', 'config', 'app_id', 'org_id');
        $this->validateWithParams($required);
    }
}
