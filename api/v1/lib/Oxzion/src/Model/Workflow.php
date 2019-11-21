<?php
namespace Oxzion\Model;

use Oxzion\Model\Entity;

class Workflow extends Entity
{
    protected $data = array(
        'id' => 0,
        'name' => null,
        'process_id' => null,
        'app_id' => null,
        'entity_id'=> null,
        'file'=> null,
        'uuid'=> null,
        'created_by' => null,
        'modified_by' => null,
        'date_created' => null,
        'date_modified' => null
    );
    public function validate()
    {
        $required = array('name','app_id');
        $this->validateWithParams($required);
    }
}
