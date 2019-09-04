<?php

namespace Contact\Model;

use Oxzion\Model\Entity;
use Oxzion\ValidationException;

class Contact extends Entity
{
    protected $data = array(
        'id' => 0,
        'uuid' => null,
        'user_id' => null,
        'first_name' => 0,
        'last_name' => null,
        'phone_1' => null,
        'phone_list' => null,
        'email' => null,
        'email_list' => null,
        'icon_type' => null,
        'company_name' => null,
        'designation' => null,
        'address1' => null,
        'address2' => null,
        'city' => null,
        'state' => null,
        'country' => null,
        'zip' => null,
        'owner_id' => 0,
        'date_created' => 0,
        'date_modified' => null
    );

    public function validate()
    {
        $dataArray = array("first_name", "owner_id");
        $this->validateWithParams($dataArray);
    }
}
