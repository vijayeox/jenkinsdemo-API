<?php
namespace Oxzion\Model;

use Oxzion\Model\Entity;

class Organization extends Entity
{
    const BUSINESS = 'BUSINESS';
    const INDIVIDUAL = 'INDIVIDUAL';

    protected $data = array(
        'id' => null,
        'name' => null,
        'uuid' => null,
        'subdomain' => null,
        'contactid' => null,
        'preferences' => null,
        'theme' => 0,
        'org_profile_id' => null,
        'status' => 'Active',
        'type' => 'BUSINESS'
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
            'name',
            'status',
            'preferences'
        );
        $this->validateWithParams($required);
    }
}
