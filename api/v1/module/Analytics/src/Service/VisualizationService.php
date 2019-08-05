<?php
namespace Analytics\Service;

use Oxzion\Service\AbstractService;
use Analytics\Model\VisualizationTable;
use Analytics\Model\Visualization;
use Oxzion\Auth\AuthContext;
use Oxzion\Auth\AuthConstants;
use Oxzion\ValidationException;
use Zend\Db\Sql\Expression;
use Oxzion\Utils\FilterUtils;
use Ramsey\Uuid\Uuid;
use Exception;

class VisualizationService extends AbstractService
{

    private $table;

    public function __construct($config, $dbAdapter, VisualizationTable $table)
    {
        parent::__construct($config, $dbAdapter);
        $this->table = $table;
    }

    public function createVisualization(&$data)
    {
        $form = new Visualization();
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

    public function updateVisualization($uuid, &$data)
    {
        $obj = $this->table->getByUuid($uuid, array());
        if (is_null($obj)) {
            return 0;
        }
        $form = new Visualization();
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

    public function deleteVisualization($uuid)
    {
        $obj = $this->table->getByUuid($uuid, array());
        if (is_null($obj)) {
            return 0;
        }
        $form = new Visualization();
        $data['isdeleted'] = 1;
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

    public function getVisualization($uuid)
    {
        $sql = $this->getSqlObject();
        $select = $sql->select();
        $select->from('visualization')
            ->columns(array('uuid','type','created_by','date_created','org_id','isdeleted'))
            ->where(array('visualization.uuid' => $uuid,'org_id' => AuthContext::get(AuthConstants::ORG_ID),'isdeleted' => 0));
        $response = $this->executeQuery($select)->toArray();
        if (count($response) == 0) {
            return 0;
        }
        return $response[0];
    }

    public function getVisualizationList($params = null)
    {

            $paginateOptions = FilterUtils::paginate($params);
            $where = $paginateOptions['where'];
            $where .= empty($where) ? "WHERE isdeleted <>1 AND org_id =".AuthContext::get(AuthConstants::ORG_ID) : " AND isdeleted <>1 AND org_id =".AuthContext::get(AuthConstants::ORG_ID);
            $sort = " ORDER BY ".$paginateOptions['sort'];
            $limit = " LIMIT ".$paginateOptions['pageSize']." offset ".$paginateOptions['offset'];

            $cntQuery ="SELECT count(id) as 'count' FROM `visualization` ";
            $resultSet = $this->executeQuerywithParams($cntQuery.$where);
            $count=$resultSet->toArray()[0]['count'];

            $query ="SELECT * FROM `visualization`".$where." ".$sort." ".$limit;
            $resultSet = $this->executeQuerywithParams($query);
            $result = $resultSet->toArray();
            foreach ($result as $key => $value) {
                $result[$key]['connection_string'] = json_decode($result[$key]['connection_string']);
                unset($result[$key]['id']);
            }
            return array('data' => $result,
                     'total' => $count);
    }
}
