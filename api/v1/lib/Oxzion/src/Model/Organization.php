<?php
namespace Oxzion\Model;

use Oxzion\Model\Entity;
class Organization extends Entity {

    protected $data = array(
        'id' => NULL,
        'uuid' => NULL,
        'name' => NULL,
        'address' => NULL,
        'city' => NULL,
        'state' => NULL,
        'country' => NULL,
        'zip' => NULL,
        'labelfile' => NULL,
        'languagefile' => 'en',
        'contactid' => NULL,
        'preferences' => NULL,
        'theme' => 0,
        'status' => 'Active'
    );

    public function __construct($data = array()) {
        if ($data) {
            $this->exchangeArray($data);
        }
    }

    public function validate() {
        $required = array(
            'name',
            'status',
            'preferences'
        );
        $this->validateWithParams($required);
    }

}
?>