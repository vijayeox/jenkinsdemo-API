<?php

namespace Announcement\Model;

use Oxzion\Model\Entity;

class Announcement extends Entity{
	protected $data = array(
		'id' => 0,
		'name' => NULL,
		'avatarid' => NULL,
		'date_created' => NULL,
		'startdate' => NULL,
		'enddate' => NULL,
		'text' => NULL,
		'groupid' => 0,
		'orgid' => 0,
		'enabled' => NULL,
		'media_location' => NULL,
		'media_type' => NULL
	);
}