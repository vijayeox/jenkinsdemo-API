<?php
/**
* File Api
*/
namespace Oxzion\Service;

use Oxzion\Service\AbstractService;
use Oxzion\Model\CommentTable;
use Oxzion\Model\Comment;
use Oxzion\Auth\AuthContext;
use Oxzion\Auth\AuthConstants;
use Oxzion\ValidationException;
use Oxzion\Utils\UuidUtil;
use Zend\Db\Sql\Expression;
use Exception;
use Oxzion\Messaging\MessageProducer;

/**
 * Comment Controller
 */
class CommentService extends AbstractService
{
    /**
    * @var CommentService Instance of Comment Service
    */
    private $commentService;
    private $messageProducer;
    /**
    * @ignore __construct
    */

    public function setMessageProducer($messageProducer)
    {
        $this->messageProducer = $messageProducer;
    }

    public function __construct($config, $dbAdapter, CommentTable $table, MessageProducer $messageProducer)
    {
        parent::__construct($config, $dbAdapter);
        $this->table = $table;
        $this->messageProducer = $messageProducer;
    }

    public function createComment($data, $fileId)
    {
        $form = new Comment();
        //Additional fields that are needed for the create
        $data['text'] = isset($data['text']) ? $data['text'] : null;
        $data['file_id'] = $this->getIdFromUuid('ox_file', $fileId);
        $data['account_id'] = AuthContext::get(AuthConstants::ACCOUNT_ID);
        $data['uuid'] = UuidUtil::uuid();
        $data['created_by'] = AuthContext::get(AuthConstants::USER_ID);
        $data['modified_by'] = AuthContext::get(AuthConstants::USER_ID);
        $data['date_created'] = date('Y-m-d H:i:s');
        $data['date_modified'] = date('Y-m-d H:i:s');
        if (isset($data['parent'])) {
            $ret = $this->getParentId($data, $fileId);
            if (!$ret) {
                return 0;
            }
        }
        $data['isdeleted'] = false;
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
            $postId = isset($data['postId']) ? $data['postId'] : '';
            $this->logger->info("Comments Data from CS---".print_r(array('message' => $data['text'], 'fileId' => $fileId, 'commentId' =>$data['uuid'], 'fileIds' => $postId ,'from' => AuthContext::get(AuthConstants::USERNAME)),true));
            $this->messageProducer->sendTopic(json_encode(array('message' => $data['text'], 'fileId' => $fileId, 'commentId' =>$data['uuid'], 'fileIds' => $postId ,'from' => AuthContext::get(AuthConstants::USERNAME))), 'CHAT_APPBOT_NOTIFICATION');
            $this->commit();
        } catch (Exception $e) {
            $this->rollback();
            throw $e;
        }
        return $data;
    }

    private function getParentId(&$data, $fileId)
    {
        $fId = $this->getIdFromUuid("ox_file", $fileId);
        $obj = $this->getIdFromUuid('ox_comment', $data['parent'], array("file_id" => $fId, "account_id" => AuthContext::get(AuthConstants::ACCOUNT_ID)));
        if (!$obj) {
            return 0;
        }
        $data['parent'] = $obj;
        return 1;
    }
    public function updateComment($id, $fileId, $data)
    {
        $fId = $this->getIdFromUuid("ox_file", $fileId);
        $accountId = isset($data['accountId']) && !is_numeric($data['accountId']) ? $this->getIdFromUuid("ox_account", $data['accountId']) :AuthContext::get(AuthConstants::ACCOUNT_ID);
        $obj = $this->table->getByUuid($id, array("file_id" => $fId, "account_id" => $accountId));
        if (!$obj) {
            return 0;
        }
        if (isset($data['parent'])) {
            $ret = $this->getParentId($data, $fileId);
            if (!$ret) {
                return 0;
            }
        }
        $obj = $obj->toArray();
        $form = new Comment();
        $data = array_merge($obj, $data); //Merging the data from the db for the ID
        $data['modified_by'] = AuthContext::get(AuthConstants::USER_ID);
        $data['date_modified'] = date('Y-m-d H:i:s');
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
            $this->commit();
        } catch (Exception $e) {
            $this->rollback();
            throw $e;
        }
        return $data;
    }

    public function deleteComment($id, $fileId)
    {
        $fId = $this->getIdFromUuid("ox_file", $fileId);
        $obj = $this->table->getByUuid($id, array("file_id" => $fId, "account_id" => AuthContext::get(AuthConstants::ACCOUNT_ID)));
        if (is_null($obj)) {
            return 0;
        }
        $form = new Comment();
        $data = $obj->toArray();
        $data['modified_by'] = AuthContext::get(AuthConstants::USER_ID);
        $data['date_modified'] = date('Y-m-d H:i:s');
        $data['isdeleted'] = 1;
        $form->exchangeArray($data);
        $form->validate();
        $count = 0;
        try {
            $this->beginTransaction();
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
        return $count;
    }

    public function getComment($id, $fileId)
    {
        $result = $this->getCommentsInternal($fileId, $id);
        if (count($result) > 0) {
            return $result[0];
        }

        return 0;
    }
    public function getComments($fileId)
    {
        return $this->getCommentsInternal($fileId);
    }

    private function getCommentsInternal($fileId, $id = null)
    {
        $fileClause = "";
        $queryParams = array("accountId"=>AuthContext::get(AuthConstants::ACCOUNT_ID),"fileId"=>$fileId);
        if ($id) {
            $fileClause = "AND ox_comment.uuid = :commentId";
            $queryParams['commentId'] = $id;
        }
        $query = "select text,ou.name as name,ou.icon as icon,ou.uuid as userId,ox_comment.date_created as time, ox_comment.uuid as commentId, ox_comment.attachments 
                    from ox_comment 
                    inner join ox_user ou on ou.id = ox_comment.created_by 
                    inner join ox_file of on of.id = ox_comment.file_id 
                    where ox_comment.account_id = :accountId AND of.uuid = :fileId $fileClause order by ox_comment.date_created desc";
        $this->logger->info("Executing Query $query with params - ".print_r($queryParams, true));
        $resultSet = $this->executeQueryWithBindParameters($query, $queryParams)->toArray();
        if (count($resultSet) >0) {
            for ($i=0; $i < count($resultSet); $i++) {                 
                $attachment = json_decode($resultSet[$i]['attachments'],true);
                $resultSet[$i]['attachments'] = $attachment['attachments'];
            }
        }
        return $resultSet;
    }

    public function getchildren($id, $fileId)
    {
        $queryString = "select ox_comment.text, ou.name, ou.icon, ou.uuid as userId, ox_comment.date_created as time, 
                        ox_comment.uuid as commentId from ox_comment 
                        inner join ox_comment as parent on parent.id = ox_comment.parent
                        inner join ox_user ou on ou.id = ox_comment.created_by 
                        inner join ox_file of on of.id = ox_comment.file_id
                        where parent.uuid = :commentId AND ox_comment.account_id=".AuthContext::get(AuthConstants::ACCOUNT_ID)." AND ox_comment.isdeleted!=1 AND of.uuid = :fileId order by ox_comment.id";
        $queryParams = ["commentId" => $id, "fileId" => $fileId];
        $result = $this->executeQueryWithBindParameters($queryString, $queryParams)->toArray();
        return $result;
    }
}
