<?php
namespace Analytics\Service;

use Oxzion\Service\AbstractService;
use Analytics\Model\QueryTable;
use Analytics\Model\Query;
use Oxzion\Auth\AuthContext;
use Oxzion\Auth\AuthConstants;
use Oxzion\ValidationException;
use Zend\Db\Sql\Expression;
use Oxzion\Utils\FilterUtils;
use Ramsey\Uuid\Uuid;
use Exception;

class QueryService extends AbstractService
{

    private $table;

    public function __construct($config, $dbAdapter, QueryTable $table)
    {
        parent::__construct($config, $dbAdapter);
        $this->table = $table;
    }

    public function createQuery(&$data)
    {
        $form = new Query();
        $data['uuid'] = Uuid::uuid4()->toString();
        $data['created_by'] = AuthContext::get(AuthConstants::USER_ID);
        $data['date_created'] = date('Y-m-d H:i:s');
        $data['org_id'] = AuthContext::get(AuthConstants::ORG_ID);
        $form->exchangeArray($data);
        $form->validate();
        $this->beginTransaction();
        $count = 0;
        try {
            $count = $this->table->save($form);
            if ($count == 0) {
                $this->rollback();
                return 0;
            }
            $id = $this->table->getLastInsertValue();
            $data['id'] = $id;
            $this->commit();
        } catch (Exception $e) {
            $this->rollback();
            return 0;
        }
        return $count;
    }

    public function updateQuery($id, &$data)
    {
        $obj = $this->table->get($id, array());
        if (is_null($obj)) {
            return 0;
        }
        $form = new Query();
        $data = array_merge($obj->toArray(), $data);
        $form->exchangeArray($data);
        $form->validate();
        $count = 0;
        try {
            $count = $this->table->save($form);
            if ($count == 0) {
                $this->rollback();
                return 0;
            }
        } catch (Exception $e) {
            $this->rollback();
            return 0;
        }
        return $count;
    }

    public function deleteQuery($id)
    {
        $count = 0;
        try {
            $count = $this->table->delete($id);
            if ($count == 0) {
                return 0;
            }
        } catch (Exception $e) {
            $this->rollback();
        }
        return $count;
    }

    public function getQuery($id)
    {
        $statement = "Select * from query where (id =".$id." and org_id =".AuthContext::get(AuthConstants::ORG_ID).") and (created_by = ".AuthContext::get(AuthConstants::USER_ID)." OR ispublic = 1";
        $resultSet = $this->executeQuerywithParams($query);
        $result = $resultSet->toArray();
        if (count($result) == 0) {
            return 0;
        }
        return array('data' => $result);
    }

    public function getQueryList($params = null)
    {

            $paginateOptions = FilterUtils::paginate($params);
            $where = $paginateOptions['where'];
            $where .= empty($where) ? "WHERE org_id =".AuthContext::get(AuthConstants::ORG_ID) : " AND org_id =".AuthContext::get(AuthConstants::ORG_ID);
            $sort = " ORDER BY ".$paginateOptions['sort'];
            $limit = " LIMIT ".$paginateOptions['pageSize']." offset ".$paginateOptions['offset'];

            $cntQuery ="SELECT count(id) as 'count' FROM `query` ";
            $resultSet = $this->executeQuerywithParams($cntQuery.$where);
            $count=$resultSet->toArray()[0]['count'];

            $query ="SELECT * FROM `query`".$where." ".$sort." ".$limit;
            $resultSet = $this->executeQuerywithParams($query);
            $result = $resultSet->toArray();
            foreach ($result as $key => $value) {
                $result[$key]['query_json'] = json_decode($result[$key]['query_json']);
            }
            return array('data' => $result,
                     'total' => $count);
    }
}
