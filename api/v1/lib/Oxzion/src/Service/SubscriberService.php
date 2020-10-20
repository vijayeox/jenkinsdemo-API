<?php
/**
* File Api
*/
namespace Oxzion\Service;

use Oxzion\Service\AbstractService;
use Oxzion\Model\SubscriberTable;
use Oxzion\Model\Subscriber;
use Oxzion\Auth\AuthContext;
use Oxzion\Auth\AuthConstants;
use Oxzion\ValidationException;
use Zend\Db\Sql\Expression;
use Oxzion\Utils\UuidUtil;
use Exception;

/**
 * Comment Controller
 */
class SubscriberService extends AbstractService
{
    /**
    * @var SubscriberService Instance of Subscriber Service
    */
    private $subscriberService;
    /**
    * @ignore __construct
    */

    public function __construct($config, $dbAdapter, SubscriberTable $table)
    {
        parent::__construct($config, $dbAdapter);
        $this->table = $table;
    }

    public function createSubscriber($data, $fileId)
    {
        //Additional fields that are needed for the create
        if (!isset($data['user_id'])) {
            return -1;
        }
        $data['commentId'] = !isset($data['commentId']) ? UuidUtil::uuid() : $data['commentId'];
        $obj = $data;
        $obj['user_id'] = $this->getIdFromUuid('ox_user', $data['user_id']);
        if(!$obj['user_id']){
            return -1;
        }
        $obj['file_id'] = $this->getIdFromUuid('ox_file', $fileId);
        if(!$obj['file_id']){
            return 0;
        }
        $obj['uuid'] = $data['commentId'];
        $obj['org_id'] = AuthContext::get(AuthConstants::ORG_ID);
        $obj['created_by'] = AuthContext::get(AuthConstants::USER_ID);
        $obj['date_created'] = date('Y-m-d H:i:s');
        $form = new Subscriber();
        $form->exchangeArray($obj);
        $form->validate();
        
        $this->beginTransaction();
        $count = 0;
        try {
            $count = $this->table->save($form);
            if ($count == 0) {
                $this->rollback();
                return 0;
            }
            $this->commit();
        } catch (Exception $e) {
            $this->rollback();
            throw $e;
        }
        return $data;
    }

    public function updateSubscriber($id, $fileId, $data)
    {
        $fId = $this->getIdFromUuid("ox_file", $fileId);
        $obj = $this->table->getByUuid($id, array("file_id" => $fId, "org_id" => AuthContext::get(AuthConstants::ORG_ID)));
        if (is_null($obj)) {
            return 0;
        }
        $data['user_id'] = $this->getIdFromUuid('ox_user', $data['user_id']);
        if(!$data['user_id']){
            return -1;
        }
        $form = new Subscriber();
        $data = array_merge($obj->toArray(), $data); //Merging the data from the db for the ID
        $data['modified_id'] = AuthContext::get(AuthConstants::USER_ID);
        $data['date_modified'] = date('Y-m-d H:i:s');
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
            throw $e;
        }
        return $count;
    }

    public function deleteSubscriber($id, $fileId)
    {
        $fId = $this->getIdFromUuid("ox_file", $fileId);
        $obj = $this->table->getByUuid($id, array("file_id" => $fId, "org_id" => AuthContext::get(AuthConstants::ORG_ID)));
        if (is_null($obj)) {
            return 0;
        }
        $form = new Subscriber();
        $data = $obj->toArray();
        $data['modified_id'] = AuthContext::get(AuthConstants::USER_ID);
        $data['date_modified'] = date('Y-m-d H:i:s');
        $data['isdeleted'] = 1;
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
            throw $e;
        }
        return $count;
    }

    public function getSubscriber($id, $fileId){
        $result = $this->getSubscribersInternal($fileId, $id);
        if(count($result) > 0){
            return $result[0];
        }

        return 0;
    }
    public function getSubscribers($fileId)
    {
        return $this->getSubscribersInternal($fileId);
    }

    private function getSubscribersInternal($fileId, $id = null){
        $idClause = "";
        $params = array("orgId"=>AuthContext::get(AuthConstants::ORG_ID),"fileId"=>$fileId);
        if($id){
            $idClause = "AND s.uuid = :subscriberId";
            $params['subscriberId'] = $id;
        }
        $query = "select u.firstname, u.lastname, u.uuid as user_id from ox_subscriber s 
                        inner join ox_file of on s.file_id = of.id
                        inner join ox_user u on u.id = s.user_id
                        where s.org_id = :orgId and of.uuid = :fileId $idClause ORDER by u.firstname";
        $this->logger->info("Executing Query $query with params - ".print_r($params, true));
        $resultSet = $this->executeQueryWithBindParameters($query, $params);
        return $resultSet->toArray();
    }

}
