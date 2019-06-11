<?php

namespace Contact\Model;

use Oxzion\Model\Entity;
use Oxzion\ValidationException;

class Contact extends Entity
{
    protected $data = array(
        'id' => 0,
        'user_id' => null,
        'first_name' => 0,
        'last_name' => null,
        'phone_1' => null,
        'phone_list' => null,
        'email' => null,
        'email_list' => null,
        'company_name' => null,
        'address_1' => 0,
        'address_2' => null,
        'country' => null,
        'owner_id' => 0,
        'org_id' => 0,
        'created_id' => 0,
        'date_created' => 0,
        'date_modified' => null,
        'modified_id' => null,
        'other' => null
    );

    public function validate()
    {
        $dataArray = array("first_name", "owner_id", "org_id");
        $this->validateWithParams($dataArray);
    }
}