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
class CommentService extends AbstractService {
    /**
    * @var CommentService Instance of Comment Service
    */
    private $commentService;
    /**
    * @ignore __construct
    */

    public function __construct($config, $dbAdapter, CommentTable $table) {
        parent::__construct($config, $dbAdapter);
        $this->table = $table;
    }

    public function createComment(&$data,$fileid) {
		$form = new Comment();
    //Additional fields that are needed for the create    
		$data['text'] = isset($data['text']) ? $data['text'] : NULL;
		$data['file_id'] = $fileid;
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
			if($count == 0) {
				$this->rollback();
				return 0;
			}
			$id = $this->table->getLastInsertValue();
			$data['id'] = $id;
			$this->commit();
		} catch(Exception $e) {
			$this->rollback();
			return 0;
		}
		return $count;
	}

	public function updateComment ($id, &$data) {
		$obj = $this->table->get($id,array());
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
        	if($count == 0) {
        		$this->rollback();
        		return 0;
        	}
        } catch(Exception $e) {
        	$this->rollback();
        	return 0;
        }
        return $count;
    }

    public function deleteComment($id) {
    	$obj = $this->table->get($id,array());
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
            if($count == 0) {
                $this->rollback();
                return 0;
            }
        } catch(Exception $e) {
            $this->rollback();
            return 0;
        }
        return $count;
    }

    public function getComments() {
    	$queryString = "select * from ox_comment";
    	$where = "where ox_comment.org_id=".AuthContext::get(AuthConstants::ORG_ID)." AND ox_comment.isdeleted!=1"; 
    	$order = "order by ox_comment.id";
    	$resultSet = $this->executeQuerywithParams($queryString, $where, null, $order);
    	return $resultSet->toArray();
    }

    public function getchildren($id) {
    	$queryString = "select * from ox_comment";
    	$where = "where ox_comment.id =".$id." AND ox_comment.org_id=".AuthContext::get(AuthConstants::ORG_ID)." AND ox_comment.isdeleted!=1";
    	$order = "order by ox_comment.id";
    	$resultSet = $this->executeQuerywithParams($queryString, $where, null, $order)->toArray();
    	if($resultSet) {
    		$where = "where ox_comment.parent =".$id." AND ox_comment.org_id=".AuthContext::get(AuthConstants::ORG_ID)." AND ox_comment.isdeleted!=1";
    		$result = $this->executeQuerywithParams($queryString, $where, null, $order)->toArray();
    		if($result)
    			return $result;
    	}
    	return 0;
    }
}
?>