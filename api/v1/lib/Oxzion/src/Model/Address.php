<?php
namespace Oxzion\Model;

use Oxzion\Model\Entity;

class Address extends Entity
{
    protected $data = array(
        'id' => null,
        'address1' => null,
        'address2' => null,
        'city' => null,
        'state' => null,
        'country' => 'en',
        'zip' => null,
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
            'address1',
            'city',
            'state',
            'country',
            'zip'
        );
        $this->validateWithParams($required);
    }
}
