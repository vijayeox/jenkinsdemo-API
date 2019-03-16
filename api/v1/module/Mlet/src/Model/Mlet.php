<?php
namespace Mlet\Model;
use Bos\Model\Entity;

class Mlet extends Entity{

    protected $data = array(
            'id' => NULL,
            'uuid' => NULL,
            'appid'=>NULL,
            'name' => NULL,
            'description' => NULL,
            'questiontext' => NULL , 
            'parameters' => NULL , 
            'orgid' => NULL,
            'mletlist' => NULL,
            'templateid' => NULL,
            'querytext' => NULL,
            'html' => NULL,
            'doctype' => NULL,
            'date_created' => NULL,
            'date_modified' => NULL,            
            'created_id' => NULL,            
            'modified_id' => NULL,            
    );
}