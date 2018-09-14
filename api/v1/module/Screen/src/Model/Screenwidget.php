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
}