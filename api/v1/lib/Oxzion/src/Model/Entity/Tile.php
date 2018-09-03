<?php

namespace Oxzion\Model\Entity;
use Oxzion\Model\Entity;

class Tile extends Entity{

    protected $data = array(
        'id' => NULL,
        'name' => NULL,
        'class' => NULL,
        'label' => 'Status Box',
        'color' => 'green',
        'link' => NULL,
        'imageicon' => 'icon-bell',
        'linklabel' => 'Status Box',
        'class_method' => NULL,
        'description' => NULL,
        'popup' => NULL,
        'popuptitle' => NULL,
        'showinpopup' => '0',
        'style' => NULL,
        'linkclass' => NULL,
        'subtile' => NULL,
        'embed' => NULL,
        'props' => NULL,
        'sequence_no' => NULL,
        'force_add_avatar' => NULL,
    );

    public function __construct($data=null){
        $this->tablename = 'statusboxes';
        parent::__construct($data,$this);
    }
}