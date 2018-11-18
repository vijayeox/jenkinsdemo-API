<?php
namespace Oxzion\Model;

class Organization extends Entity{

    protected $data = array(
        'id' => NULL,
        'name' => NULL,
        'address' => NULL,
        'city' => NULL,
        'state' => NULL,
        'zip' => NULL,
        'logo' => NULL,
        'labelfile' => NULL,
        'languagefile' => 'en',
        'theme' => 0,
        'status' => 'Active'
    );
    public function validate(){
        $required = array('name','logo','status');
        $this->validateWithParams($required);
    }
}