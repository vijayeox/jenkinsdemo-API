<?php
namespace Resource\Service;

use Bos\Service\AbstractService;
use Oxzion\Utils\FileUtils;
use Bos\ValidationException;
use Bos\Auth\AuthContext;
use Bos\Auth\AuthConstants;
use Exception;

class ResourceService extends AbstractService{
    /**
    * @ignore __construct
    */
    public function __construct($config, $dbAdapter){
        parent::__construct($config, $dbAdapter);
    }

    /**
    * GET Resource Service
    * @method GET
    * @param $id ID of Resource to Delete
    * @return array $data 
    * <code>
    * {
    *  integer id,
    *  string file_name,
    *  integer extension,
    *  string uuid,
    *  string type,
    *  dateTime path Full Path of File,
    * }
    * </code>
    * @return array Returns a JSON Response with Status Code and Created Resource.
    */
    public function getResource($id){
        $sql = $this->getSqlObject();
        $select = $sql->select();
        $select->from('ox_attachment')
        ->columns(array("path"))
        ->where(array('uuid' => $id));
        $result = $this->executeQuery($select)->toArray();
        return $result[0]['path'];
    }
}
?>