<?php
namespace Oxzion\Service;

use Exception;
use Oxzion\Auth\AuthConstants;
use Oxzion\Auth\AuthContext;
use Oxzion\Model\BusinessRole;
use Oxzion\Model\BusinessRoleTable;
use Oxzion\ServiceException;
use Oxzion\Service\AbstractService;
use Oxzion\EntityNotFoundException;

class BusinessRoleService extends AbstractService
{
    protected $table;
    
    public function __construct($config, $dbAdapter, BusinessRoleTable $table)
    {
        parent::__construct($config, $dbAdapter);
        $this->table = $table;
    }

    public function saveBusinessRole($appId, &$businessRole){
        $count = 0;
        $data = $businessRole;
        $data['app_id'] = $this->getIdFromUuid('ox_app', $appId);
        $bRole = new BusinessRole($this->table);
        if(isset($data['uuid'])){
            try{
                $bRole->loadByUuid($data['uuid']);
                if(!isset($data['version'])){
                    $data['version'] = $bRole->getProperty('version');
                }
            }catch(EntityNotFoundException $e){
                unset($data['uuid']);
            }
        }
        $bRole->assign($data);
        $bRole->validate();
        try {
            $this->beginTransaction();
            $bRole->save2();
            $this->commit();
        } catch (Exception $e) {
            $this->logger->error($e->getMessage(), $e);
            $this->rollback();
            throw $e;
        }
        $data = $bRole->getGenerated();
        $businessRole['uuid'] = $data['uuid'];
        $businessRole['version'] = $data['version'];
    }

    public function getBusinessRole($id){
        $businessRole = new BusinessRole($this->table);
        $businessRole->loadByUuid($id);
        return $businessRole->toArray();
    }

    public function getBusinessRoleByName($appId, $name){
        $query = "select br.* from ox_business_role br 
                    inner join ox_app a on a.id = br.app_id 
                    where a.uuid = :appId and br.name = :name";
        $params = ["appId" => $appId, "name" => $name];
        $result = $this->executeQueryWithBindParameters($query, $params)->toArray();
        return $result;
    }
}