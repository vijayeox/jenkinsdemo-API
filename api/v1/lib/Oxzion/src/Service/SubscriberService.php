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
        $obj['account_id'] = $data['account_id'] ? $data['account_id'] : AuthContext::get(AuthConstants::ACCOUNT_ID);
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

    public function updateSubscriber($data,$fileId)
    {
        try {
            $this->beginTransaction();
            $fId = $this->getIdFromUuid("ox_file", $fileId);
            $userList = is_array($data['subscribers']) ? "'" . implode ( "','", $data['subscribers'] ) . "'" : trim($data['subscribers'],"[]");
            $deleteQuery = "DELETE FROM ox_subscriber WHERE file_id=:fileId AND user_id not in(SELECT id FROM ox_user where uuid in($userList))";
            $queryParams = ["fileId" => $fId];
            $this->logger->info("Subscriber Delete Query-- $deleteQuery with params".print_r($queryParams,true));
            $resultSet = $this->executeUpdateWithBindParameters($deleteQuery, $queryParams);

            $insertQuery = "INSERT INTO ox_subscriber (`user_id`,`account_id`,`file_id`,
                            `uuid`,`created_by`,`date_created`)
                            (SELECT u.id,:accountId,:fileId,UUID(),:createdBy,now() 
                            FROM ox_user as u 
                            LEFT JOIN ox_subscriber as s on s.user_id=u.id and s.file_id =:fileId
                            WHERE u.uuid in ($userList) 
                            AND s.user_id is NULL)";
            $insertParams = array("accountId" => $data['account_id'] ? $data['account_id'] :            AuthContext::get(AuthConstants::ACCOUNT_ID) ,
                            "fileId" => $fId , "createdBy" => AuthContext::get(AuthConstants::USER_ID));
            $this->logger->info("Subscriber Insert Query-- $insertQuery with params".print_r($insertParams,true));
            $result = $this->executeUpdateWithBindParameters($insertQuery, $insertParams);
            $this->commit();
        } catch (Exception $e) {
            $this->rollback();
            throw $e;
        }
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

    private function getSubscribersInternal($fileId, $id = null, $userId = null){
        $idClause = "";
        $userFilter = "";
        $params = array("fileId"=>$fileId); 
        if($id){
            $idClause = "AND s.uuid = :subscriberId";
            $params['subscriberId'] = $id;
        }
        if ($userId) {
            $userFilter = "AND s.user_id = :userId";
            $params['userId'] = $userId;
        }
        $query = "select up.firstname, up.lastname, u.username, u.uuid as user_id, oxa.uuid as account_id from ox_subscriber s 
                        inner join ox_account oxa on oxa.id = s.account_id
                        inner join ox_file of on s.file_id = of.id
                        inner join ox_user u on u.id = s.user_id
                        inner join ox_person up on up.id = u.person_id
                        where of.uuid = :fileId $idClause $userFilter ORDER by up.firstname";
        $this->logger->info("Executing Query $query with params - ".print_r($params, true));
        $resultSet = $this->executeQueryWithBindParameters($query, $params);
        return $resultSet->toArray();
    }

    public function getUserSubscriber($fileId,$id = null,$userId)
    {
        return $this->getSubscribersInternal($fileId,$id,$userId);
    }

}
