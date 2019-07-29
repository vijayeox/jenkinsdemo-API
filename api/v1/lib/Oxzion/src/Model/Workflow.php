<?php
namespace Oxzion\Model;

use Oxzion\Model\Entity;
class Workflow extends Entity{

    protected $data = array(
        'id' => 0,
        'name' => NULL,
        'process_ids' => NULL,
        'app_id' => NULL,
        'form_id' => NULL,
        'file'=>NULL
    );
    public function validate(){
        $required = array('name','app_id');
        $this->validateWithParams($required);
    }
}