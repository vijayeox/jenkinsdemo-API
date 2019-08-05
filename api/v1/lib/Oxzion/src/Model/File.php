<?php

namespace Oxzion\Model;

class File extends Entity
{
    protected $data = array(
        'id'=>0 ,
        'org_id' => null,
        'uuid' => null,
        'data' => null,
        'workflow_instance_id' =>null,
        'created_by' => null,
        'modified_by' => null,
        'date_created' => null,
        'date_modified' => null
    );
    protected $attributes = array();

    public function validate()
    {
        $required = array('uuid', 'org_id','data', 'created_by', 'date_created');
        $this->validateWithParams($required);
    }
}
