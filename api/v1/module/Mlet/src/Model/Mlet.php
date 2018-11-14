<?php
namespace Mlet\Model;
use Oxzion\Model\Entity;

class Mlet extends Entity{

    protected $data = array(
            'id' => NULL,
            'name' => NULL,
            'questiontext' => NULL , 
            'parameters' => NULL , 
            'queryconfigid' => NULL,
            'html' => NULL,
            'groupid' => NULL,
            'orgid' => NULL,
            'mletlist' => NULL,
            'where_used' => NULL,
            'description' => NULL,
            'templateid' => NULL,
            'directsql' => NULL
            
    );
}