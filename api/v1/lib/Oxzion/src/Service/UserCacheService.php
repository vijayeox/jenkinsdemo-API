<?php
/**
* File Api
*/
namespace Oxzion\Service;

use Oxzion\Service\AbstractService;
use Oxzion\Model\UserCacheTable;
use Oxzion\Model\UserCache;
use Oxzion\Auth\AuthContext;
use Oxzion\Auth\AuthConstants;
use Oxzion\ValidationException;
use Zend\Db\Sql\Expression;
use Exception;
use Oxzion\ServiceException;

/**
 * UserCache Controller
 */
class UserCacheService extends AbstractService
{
    /**
    * @var UserCacheService Instance of UserCache Service
    */
    private $commentService;
    /**
    * @ignore __construct
    */

    public function __construct($config, $dbAdapter, UserCacheTable $table)
    {
        parent::__construct($config, $dbAdapter);
        $this->table = $table;
    }

    public function createUserCache(&$data)
    {
        $form = new UserCache();
        $data['date_created'] = date('Y-m-d H:i:s');
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
            $id = $this->table->getLastInsertValue();
            $data['id'] = $id;
            $this->commit();
        } catch (Exception $e) {
            $this->rollback();
            $this->logger->error($e->getMessage(), $e);
            throw $e;
        }
        return $count;
    }

    public function updateUserCache($id, &$data)
    {
        $obj = $this->table->get($id, array());
        if (is_null($obj)) {
            return 0;
        }
        $form = new UserCache();
        $data = array_merge($obj->toArray(), $data); //Merging the data from the db for the ID
        $data['id'] = $id;
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
            $this->logger->error($e->getMessage(), $e);
            throw $e;
        }
        return $count;
    }

    public function deleteUserCache($appId = null)
    {
        $sql = $this->getSqlObject();
        $params = array();
        $userId = AuthContext::get(AuthConstants::USER_ID);
        try {
            if (isset($userId)) {
                $params['user_id'] = $userId;
            }
            if (isset($appId)) {
                $params['app_id'] = $this->getIdFromUuid('ox_app', $appId);
                if($params['app_id'] === 0)
                {
                    throw new Exception("appId is incorrect",0);
                }
            }
            $update = $sql->update();
            $update->table('ox_user_cache')
            ->set(array('deleted'=> 1))
            ->where($params);
            $response = $this->executeUpdate($update);
            return 0;
        } catch(Exception $e){
            $this->logger->error($e->getMessage(), $e);
            throw $e;
        }
    }
    public function getCache( $id=null,$appId =null,$userId=null)
    {
        $sql = $this->getSqlObject();
        $params = array();
        if (isset($userId)) {
            $params['user_id'] = $userId;
        }
        if(isset($appId)){
            if ($app = $this->getIdFromUuid('ox_app', $appId)) {
                $appId = $app;
            } else {
                $appId = $appId;
            }
        } else {
            $appId = null;
        }
        if (isset($appId)) {
            $params['app_id'] = $appId;
        }
        if (isset($id)) {
            $params['id'] = $id;
        }
        $params['deleted']=0;
        $select = $sql->select();
        $select->from('ox_user_cache')
        ->columns(array("*"))
        ->where($params);
        $response = $this->executeQuery($select)->toArray();
        if (count($response)==0) {
            return 0;
        }
        if($content = json_decode($response[0]['content'],true)){
            return $content;
        } else {
            return array('content'=>$response[0]['content']);
        }
    }
}
