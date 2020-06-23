<?php
namespace Oxzion\Model;

use Oxzion\Model\Entity;

class Employee extends Entity
{
    protected $data = array(
        'id' => null,
        'uuid' => null,
        'org_id' => null,
        'designation' => null,
        'website' => null,
        'about' => null,
        'interest' => null,
        'hobbies' => null,
        'managerid' => null,
        'selfcontribute' => null,
        'contribute_percent' => null,
        'eid' => null,
        'date_created' => null,
        'created_by' => null,
        'modified_by' => null,
        'date_created' => null,
        'user_profile_id' => null,
        'org_profile_id' => null,        
        'date_of_join' => null,
    );

    public function __construct($data = array())
    {
        if ($data) {
            $this->exchangeArray($data);
        }
    }

    public function validate()
    {
        $required = array(
            'designation',
            'date_of_join',
        );
        $this->validateWithParams($required);
    }
}
