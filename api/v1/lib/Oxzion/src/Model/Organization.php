<?php
namespace Oxzion\Model;

use Oxzion\Model\Entity;

class Organization extends Entity
{
    protected $data = array(
        'id' => null,
        'uuid' => null,
        'name' => null,
        'subdomain' => null,
        'address_id' => null,
        'labelfile' => null,
        'languagefile' => 'en',
        'contactid' => null,
        'preferences' => null,
        'theme' => 0,
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
            'name',
            'subdomain',
            'status',
            'preferences'
        );
        $this->validateWithParams($required);
    }
}
