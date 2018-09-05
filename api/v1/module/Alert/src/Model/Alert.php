<?php

namespace Alert\Model;

use Oxzion\Model\Entity;

class Alert extends Entity {

    protected $data = array(
        'id' => 0,
        'name' => NULL,
        'org_id' => NULL,
        'status' => NULL,
        'description' => NULL,
        'start_date' => NULL,
        'end_date' => NULL,
        'created_date' => 0,
        'created_id' => 0,
        'media_type' => NULL,
        'media_location' => NULL
    );

}
