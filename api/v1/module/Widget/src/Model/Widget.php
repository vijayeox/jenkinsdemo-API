<?php
namespace Widget\Model;

use Oxzion\Model\Entity;

class Widget extends Entity
{
    protected $data = array(
            'id' => null,
            'name' => null,
            'defaultwidth' => null ,
            'defaultheight' => null ,
            'applicationguid' => null
    );
}
