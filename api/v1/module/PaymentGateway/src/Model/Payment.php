<?php

namespace PaymentGateway\Model;

use Oxzion\Model\Entity;

class Payment extends Entity
{
    protected $data = array(
        'id' => 0,
        'app_id' => null,
        'org_id' => null,
        'payment_client' => null,
        'api_url' => null,
        'server_instance_name' => null,
        'payment_config' => null,
        'created_date' => null,
        'created_id' => null,
        'modified_date' => null,
        'modified_id' => null,
    );

    public function validate()
    {
        $dataArray = array("app_id", "payment_client", "api_url");
        $this->validateWithParams($dataArray);
    }
}
