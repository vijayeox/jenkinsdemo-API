<?php
namespace Mlet\Model;

use Oxzion\Model\Entity;

class Mlet extends Entity
{
    protected $data = array(
            'id' => null,
            'uuid' => null,
            'appuuid'=>null,
            'name' => null,
            'description' => null,
            'questiontext' => null ,
            'parameters' => null ,
            'orgid' => null,
            'mletlist' => null,
            'templateid' => null,
            'querytext' => null,
            'html' => null,
            'doctype' => null,
            'date_created' => null,
            'date_modified' => null,
            'created_id' => null,
            'modified_id' => null,
    );
}
