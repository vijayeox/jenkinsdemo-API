<?php
namespace Oxzion\Model;

use Oxzion\Model\Entity;

class OrganizationProfile extends Entity
{
    protected $data = array(
        'id' => null,
        'uuid' => null,
        'name' => null,
        'address_id' => null,
        'labelfile' => null,
        'languagefile' => null,
        'date_created' => null,
        'date_modified' => null,
        'created_by' => null,
        'modified_by' => null,
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
            'name'
        );
        $this->validateWithParams($required);
    }
}
