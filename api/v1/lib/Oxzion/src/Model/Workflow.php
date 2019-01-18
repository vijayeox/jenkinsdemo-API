<?php
namespace Oxzion\Model;

use Bos\Model\Entity;
class Workflow extends Entity{

    protected $data = array(
        'id' => NULL,
        'name' => NULL,
        'process_ids' => NULL,
        'app_id' => NULL,
        'form_ids' => NULL,
        'file'=>NULL
    );
    public function validate(){
        $required = array('name','app_id');
        $this->validateWithParams($required);
    }
} 