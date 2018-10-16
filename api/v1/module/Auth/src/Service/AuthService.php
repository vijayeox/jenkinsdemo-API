<?php
namespace Auth\Service;

use Oxzion\Service\AbstractService;
use Oxzion\Auth\AuthContext;
use Oxzion\Service\FileService;
use Oxzion\Auth\AuthConstants;
use Oxzion\File\FileConstants;
use Oxzion\ValidationException;
use Exception;

class AuthService extends AbstractService{

    public function __construct($config, $dbAdapter){
        parent::__construct($config, $dbAdapter);
    }
    public function getUserOrg($username){
        $sql = $this->getSqlObject();
        $select = $sql->select();
        $select->from('avatars')
                ->columns(array("orgid"))
                ->where(array('avatars.username' => $username));
        $response = $this->executeQuery($select)->toArray();
        return $response[0]['orgid'];
    }
}
?>