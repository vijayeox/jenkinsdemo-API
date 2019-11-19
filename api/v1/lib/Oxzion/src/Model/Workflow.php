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
        'form_id' => null,
        'entity_id'=>0,
        'file'=> null,
        'uuid'=> 0,
        'isdeleted' => 0
    );
    public function validate()
    {
        $required = array('name','app_id');
        $this->validateWithParams($required);
    }
}
