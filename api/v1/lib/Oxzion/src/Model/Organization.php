<?php
namespace Oxzion\Model;

use Bos\Model\Entity;
class Organization extends Entity {

    protected $data = array(
        'id' => NULL,
        'name' => NULL,
        'address' => NULL,
        'city' => NULL,
        'state' => NULL,
        'zip' => NULL,
        'logo' => NULL,
        'labelfile' => NULL,
        'languagefile' => 'en',
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
            'logo',
            'status'
        );
        $this->validateWithParams($required);
    }

}
?>