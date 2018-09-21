<?php
namespace Screen\Model;
use Oxzion\Model\Entity;

class Screenwidget extends Entity{

    protected $data = array(
        'id' => null , 
        'userid' => null , 
        'screenid' => null , 
        'widgetid' => null , 
        'width' => null , 
        'height' => null , 
        'column' => null , 
        'row' => null
    );

    public function validate(){
        $errors = array();
        if($this->data['userid'] === null){
            $errors["userid"] = 'required';
        }
        if($this->data['screenid'] === null) {
            $errors["screenid"] = 'required';   
        }
        if($this->data['widgetid'] === null) {
            $errors["widgetid"] = 'required';  
        }

        if(count($errors) > 0){
            $validationException = new ValidationException();
            $validationException->setErrors($errors);
            throw $validationException;
        }
    }
}