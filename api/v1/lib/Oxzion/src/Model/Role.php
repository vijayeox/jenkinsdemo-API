<?php
namespace Oxzion\Model;

use Oxzion\Model\Entity;
class Role extends Entity {

    protected $data = array(
        'id' => NULL,
        'name' => NULL,
        'org_id' => 0,
        'description' => NULL,
        'is_system_role' => NULL,
        'uuid' => NULL
    );

    public function __construct($data = array()) {
        if ($data) {
            $this->exchangeArray($data);
        }
    }

    public function validate() {
        $required = array(
            'name', 'uuid'
        );
        $this->validateWithParams($required);
    }
}
?>