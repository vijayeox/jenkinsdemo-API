<?php
namespace Privilege\Service;

use Bos\Auth\AuthConstants;
use Bos\Auth\AuthContext;
use Bos\Service\AbstractService;
use Bos\ValidationException;
use Privilege\Model\PrivilegeTable;

class PrivilegeService extends AbstractService
{

    private $table;

    public function __construct($config, $dbAdapter, PrivilegeTable $table)
    {
        parent::__construct($config, $dbAdapter);
        $this->table = $table;
    }

    public function getAppPrivilegeForUser($appId)
    {
        try {
            $userId = AuthContext::get(AuthConstants::USER_ID);
            $queryString = "select op.name, op.permission_allowed from ox_privilege as op
                            LEFT JOIN ox_role_privilege as orp on orp.app_id = op.app_id
                            LEFT JOIN ox_role as orl on orl.id = orp.role_id
                            LEFT JOIN ox_user_role as our on our.role_id = orl.id
                            LEFT JOIN  ox_user as av on av.id = our.user_id ";
            $where = "where av.id = " . $userId . " and op.app_id = '" . $appId . "'";
            $order = "order by av.firstname";
            $group = "group by op.name";
            $resultSet = $this->executeQuerywithParams($queryString, $where, $group, $order);
        } catch (ValidationException $e) {
            return $response = ['data' => $appId, 'errors' => $e->getErrors()];
        }
        return $resultSet->toArray();
    }

    public function getAppId() 
    {
        try{
            $userId = AuthContext::get(AuthConstants::USER_ID);
            $query = "select ox_role_privilege.app_id from ox_role_privilege 
                      RIGHT JOIN ox_user_role on ox_role_privilege.role_id = ox_user_role.role_id";
            $where = "where ox_user_role.user_id = ".$userId." AND ox_role_privilege.privilege_name = 'MANAGE_ROLE'";
            $resultSet = $this->executeQuerywithParams($query, $where);
            $appIdArray= $resultSet->toArray();
            $appId = array_unique(array_column($appIdArray,'app_id'));
            return $appId;
        }
        catch (ValidationException $e) {
            return 0;
        }
    }
}