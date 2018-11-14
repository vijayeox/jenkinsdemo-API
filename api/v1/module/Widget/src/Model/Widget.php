<?php
namespace Widget\Model;
use Oxzion\Model\Entity;

class Widget extends Entity{

    protected $data = array(
            'id' => NULL,
            'name' => NULL,
            'defaultwidth' => NULL , 
            'defaultheight' => NULL , 
            'applicationguid' => NULL
    );
}