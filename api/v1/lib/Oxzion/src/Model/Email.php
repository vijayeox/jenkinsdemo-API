<?php
namespace Oxzion\Model;

use Bos\Model\Entity;
use Bos\ValidationException;

class Email extends Entity {
    public function __construct(){
        $this->data = array(
            'id' => NULL,
            'userid'=> NULL,
            'email' => NULL,
            'password' => NULL,
            'host' => NULL,
            'token' => NULL,
            'isdefault'=> NULL,
        );
    }

    public function validate() {

        try{
            $dataArray = Array("email", "host");
            $this->validateWithParams($dataArray);

        }catch(ValidationException $e){
            $errors = $e->getErrors();
        }finally{
            if (($this->data['password'] === null || $this->data['password'] === "") && 
                ($this->data['token'] === null || $this->data['token'] === "") 
                || empty($this->data)) {
                if(!isset($errors)){
                    $errors = array();
                    $e = new ValidationException();
                }
                $errors['password'] = 'required';
                $e->setErrors($errors);
                throw $e;
            }
        }
        
    }
}
