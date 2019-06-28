<?php
namespace Oxzion\Model;

use Oxzion\Model\Entity;
class Workflow extends Entity{

    protected $data = array(
        'id' => NULL,
        'name' => NULL,
        'process_ids' => NULL,
        'process_keys' =>0,
        'app_id' => NULL,
        'form_id' => NULL,
        'file'=>NULL
    );
    public function validate(){
        $required = array('name','app_id');
        $this->validateWithParams($required);
    }
}