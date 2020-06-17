<?php
namespace Oxzion\Model;

use Oxzion\Model\Entity;

class Organization extends Entity
{
    protected $data = array(
        'id' => null,
        'uuid' => null,
        'subdomain' => null,
        'contactid' => null,
        'preferences' => null,
        'theme' => 0,
        'org_profile_id' => null,
        'status' => 'Active'
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
            'status',
            'preferences'
        );
        $this->validateWithParams($required);
    }
}
