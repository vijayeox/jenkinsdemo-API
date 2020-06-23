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

    public function createComment($data, $fileId)
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
        if(isset($data['parent'])){
            $ret = $this->getParentId($data, $fileId);
            if(!$ret){
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
            $this->commit();
        } catch (Exception $e) {
            $this->rollback();
            throw $e;
        }
        return $count;
    }

    private function getParentId(&$data, $fileId){
        $fId = $this->getIdFromUuid("ox_file", $fileId);
        $obj = $this->getIdFromUuid('ox_comment', $data['parent'], array("file_id" => $fId));
        if(!$obj){
            return 0;
        }
        $data['parent'] = $obj;
        return 1;    
    }
    public function updateComment($id, $fileId, $data)
    {
        $fId = $this->getIdFromUuid("ox_file", $fileId);
        $obj = $this->table->getByUuid($id, array("file_id" => $fId));
        if (!$obj) {
            return 0;
        }
        if(isset($data['parent'])){
            $ret = $this->getParentId($data, $fileId);
            if(!$ret){
                return 0;
            }
        }
        $obj = $obj->toArray();

        $form = new Comment();
        $data = array_merge($obj, $data); //Merging the data from the db for the ID
        $data['id'] = $id;
        $data['modified_by'] = AuthContext::get(AuthConstants::USER_ID);
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

    public function deleteComment($id, $fileId)
    {
        $fId = $this->getIdFromUuid("ox_file", $fileId);
        $obj = $this->table->getByUuid($id, array("file_id" => $fId));
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

    public function getComment($id, $fileId){
        $result = $this->getCommentsInternal($fileId, $id);
        if(count($result) > 0){
            return $result[0];
        }

        return 0;
    }
    public function getComments($fileId)
    {
        return $this->getCommentsInternal($fileId);
    }

    private function getCommentsInternal($fileId, $id = null){
        $fileClause = "";
        $queryParams = array("orgId"=>AuthContext::get(AuthConstants::ORG_ID),"fileId"=>$fileId);
        if($id){
            $fileClause = "AND ox_comment.uuid = :commentId";
            $queryParams['commentId'] = $id;
        }
        $query = "select text,ou.name as name,ou.icon as icon,ou.uuid as userId,ox_comment.date_created as time, ox_comment.uuid as commentId 
                    from ox_comment 
                    inner join ox_user ou on ou.id = ox_comment.created_by 
                    inner join ox_file of on of.id = ox_comment.file_id 
                    where ox_comment.org_id = :orgId AND of.uuid = :fileId $fileClause order by ox_comment.date_created desc";
        $this->logger->info("Executing Query $query with params - ".print_r($queryParams, true));
        $resultSet = $this->executeQueryWithBindParameters($query, $queryParams)->toArray();
        return $resultSet;
    }

    public function getchildren($id, $fileId)
    {
        $queryString = "select ox_comment.text, ou.name, ou.icon, ou.uuid as userId, ox_comment.date_created as time, 
                        ox_comment.uuid as commentId from ox_comment 
                        inner join ox_comment as parent on parent.id = ox_comment.parent
                        inner join ox_user ou on ou.id = ox_comment.created_by 
                        inner join ox_file of on of.id = ox_comment.file_id
                        where parent.uuid = :commentId AND ox_comment.org_id=".AuthContext::get(AuthConstants::ORG_ID)." AND ox_comment.isdeleted!=1 AND of.uuid = :fileId order by ox_comment.id";
        $queryParams = ["commentId" => $id, "fileId" => $fileId];
        $result = $this->executeQueryWithBindParameters($queryString, $queryParams)->toArray();
        return $result;
    }
}
