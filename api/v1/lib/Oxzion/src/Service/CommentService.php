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
use Zend\Db\Sql\Expression;
use Exception;

/**
 * Comment Controller
 */
class CommentService extends AbstractService
{
    /**
    * @var CommentService Instance of Comment Service
    */
    private $commentService;
    /**
    * @ignore __construct
    */

    public function __construct($config, $dbAdapter, CommentTable $table)
    {
        parent::__construct($config, $dbAdapter);
        $this->table = $table;
    }

    public function createComment(&$data, $fileId)
    {
        $form = new Comment();
        //Additional fields that are needed for the create
        $data['text'] = isset($data['text']) ? $data['text'] : null;
        $data['file_id'] = $this->getIdFromUuid('ox_file', $fileId);
        $data['org_id'] = AuthContext::get(AuthConstants::ORG_ID);
        $data['created_by'] = AuthContext::get(AuthConstants::USER_ID);
        $data['modified_by'] = AuthContext::get(AuthConstants::USER_ID);
        $data['date_created'] = date('Y-m-d H:i:s');
        $data['date_modified'] = date('Y-m-d H:i:s');
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
            $this->commit();
        } catch (Exception $e) {
            $this->rollback();
            throw $e;
        }
        return $count;
    }

    public function updateComment($id, &$data)
    {
        $obj = $this->table->get($id, array());
        if (is_null($obj)) {
            return 0;
        }
        $form = new Comment();
        $data = array_merge($obj->toArray(), $data); //Merging the data from the db for the ID
        $data['id'] = $id;
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

    public function deleteComment($id)
    {
        $obj = $this->table->get($id, array());
        if (is_null($obj)) {
            return 0;
        }
        $form = new Comment();
        $data = $obj->toArray();
        $data['id'] = $id;
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

    public function getComments($fileId)
    {
        $query = "select text,ou.name as name,ou.icon as icon,ou.uuid as userId,ox_comment.date_created as time from ox_comment inner join ox_user ou on ou.id = ox_comment.created_by where ox_comment.org_id = :orgId AND ox_comment.file_id = :fileId order by ox_comment.id desc";
        $fileId = $this->getIdFromUuid('ox_file', $fileId);
        $queryParams = array("orgId"=>AuthContext::get(AuthConstants::ORG_ID),"fileId"=>$fileId);
        $resultSet = $this->executeQueryWithBindParameters($query, $queryParams)->toArray();
        return $resultSet;
    }

    public function getchildren($id)
    {
        $queryString = "select * from ox_comment";
        $where = "where ox_comment.id =".$id." AND ox_comment.org_id=".AuthContext::get(AuthConstants::ORG_ID)." AND ox_comment.isdeleted!=1";
        $order = "order by ox_comment.id";
        $resultSet = $this->executeQuerywithParams($queryString, $where, null, $order)->toArray();
        if ($resultSet) {
            $where = "where ox_comment.parent =".$id." AND ox_comment.org_id=".AuthContext::get(AuthConstants::ORG_ID)." AND ox_comment.isdeleted!=1";
            $result = $this->executeQuerywithParams($queryString, $where, null, $order)->toArray();
            if ($result) {
                return $result;
            }
        }
        return 0;
    }
}
