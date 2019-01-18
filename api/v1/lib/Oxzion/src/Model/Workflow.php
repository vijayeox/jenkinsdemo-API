<?php
namespace Oxzion\Model;

use Bos\Model\Entity;
class Workflow extends Entity{

    protected $data = array(
        'id' => NULL,
        'name' => NULL,
        'process_id' => NULL,
        'app_id' => NULL,
        'form_id' => NULL
    );
    public function validate(){
        $required = array('name','process_id','app_id');
        $this->validateWithParams($required);
    }
} 