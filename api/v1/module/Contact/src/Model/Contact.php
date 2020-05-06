<?php

namespace Contact\Model;

use Oxzion\Model\Entity;

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
        'address_1' => null,
        'address_2' => null,
        'country' => null,
        'owner_id' => 0,
        'date_created' => 0,
        'date_modified' => null,
    );

    public function validate()
    {
        $dataArray = array("first_name", "owner_id");
        $this->validateWithParams($dataArray);
    }
}
