<?php

namespace Oxzion\Model\Entity;
use Oxzion\Model\Entity;

class Calendar extends Entity{

    protected $data = array(
        'id' => NULL,
        'name' => NULL,
        'summary' => NULL,
        'startdate' => '0000-00-00 00:00:00',
        'enddate' => '0000-00-00 00:00:00',
        'organizer' => NULL,
        'orgid' => NULL,
        'type' => NULL,
        'instanceformid' => NULL,
        'groupid' => NULL,
        'reid' => NULL,
        'rrule' => NULL,
        'rexception' => NULL,
        'location' => NULL,
        'reminderperiod' => NULL,
        'emails' => NULL,
    );

    public function __construct($data=null){
        $this->tablename = 'operatingrhythm';
        parent::__construct($data,$this);
    }
}