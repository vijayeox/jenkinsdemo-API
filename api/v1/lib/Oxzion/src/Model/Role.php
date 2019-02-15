<?php
namespace Oxzion\Model;

use Bos\Model\Entity;
class Role extends Entity{

    protected $data = array(
        'id' => NULL,
        'name' => 0,
        'org_id' => 0,
        'description' => NULL,
    );
    public function validate(){
        $required = array('name');
        $this->validateWithParams($required);
    }
}