<?php

namespace Announcement\Model;

use Oxzion\Model\Entity;

class Announcement extends Entity{
	protected $data = array(
		'id' => 0,
		'name' => NULL,
		'created_id' => NULL,
		'created_date' => NULL,
		'start_date' => NULL,
		'end_date' => NULL,
		'description' => NULL,
		'org_id' => 0,
		'status' => NULL,
		'media_location' => NULL,
		'media_type' => NULL
	);
}