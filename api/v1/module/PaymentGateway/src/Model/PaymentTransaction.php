<?php

namespace PaymentGateway\Model;

use Oxzion\Model\Entity;

class PaymentTransaction extends Entity
{
    protected $data = array(
        'id' => 0,
        'payment_id' => null,
        'transaction_id' => null,
        'transaction_status' => null,
        'data' => null,
        'date_created' => null,
        'created_by' => null,
        'date_modified' => null,
        'modified_by' => null,
    );

    public function validate()
    {
        $dataArray = array("payment_id", "data");
        $this->validateWithParams($dataArray);
    }
}
