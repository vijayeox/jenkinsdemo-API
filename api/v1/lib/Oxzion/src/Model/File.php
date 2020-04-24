<?php

namespace Oxzion\Model;

class File extends Entity
{
    protected $data = array(
        'id'=> null,
        'org_id' => 0,
        'uuid' => null,
        'data' => null,
        'form_id' => null,
        'created_by' => null,
        'modified_by' => null,
        'date_created' => null,
        'date_modified' => null,
        'entity_id'=>null,
        'assoc_id'=>null,
        'is_active'=>1
    );
    protected $attributes = array();

    public function validate()
    {
        $required = array('uuid', 'org_id','data', 'created_by', 'date_created', 'entity_id');
        $this->validateWithParams($required);
    }
}
