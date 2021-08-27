<?php
namespace Oxzion\Model;

use Oxzion\Model\Entity;
use Oxzion\Db\ModelTable;
use Zend\Db\TableGateway\TableGatewayInterface;

class UserToken extends Entity
{
    protected $data = array(
        'id' => 0,
        'user_id' => 1,
        'salt' => 1,
        'expiry_date' => 1,
        'date_created' => null,
        'date_modified' => null,
    );

    public function validate()
    {
        $required = array('user_id', 'salt', 'expiry_date');
        $this->validateWithParams($required);
    }
}
