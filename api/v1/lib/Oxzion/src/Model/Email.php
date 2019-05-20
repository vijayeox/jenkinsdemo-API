<?php
namespace Oxzion\Model;

use Bos\Model\Entity;
use Bos\ValidationException;

class Email extends Entity {
    protected $data = array(
        'id' => 0,
        'userid'=> 0,
        'email' => 0,
        'password' => 0,
        'host' => 0,
        'token' => NULL,
        'isdefault'=> NULL,
    );

    public function validate() {
        $dataArray = Array("email", "password", "host");
        $this->validateWithParams($dataArray);
    }
}
