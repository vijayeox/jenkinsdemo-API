<?php
namespace Bookmark\Service;

use Oxzion\Service\AbstractService;
use Bookmark\Model\BookmarkTable;
use Bookmark\Model\Bookmark;
use Oxzion\Auth\AuthContext;
use Oxzion\Auth\AuthConstants;
use Oxzion\ValidationException;
use Exception;

class BookmarkService extends AbstractService{

    private $table;

    public function __construct($config, $dbAdapter, BookmarkTable $table){
        parent::__construct($config, $dbAdapter);
        $this->table = $table;
    }

    public function preDispatch($value='')
    {
        echo "<pre>";print_r(func_get_args());exit();
    }

    public function createBookmark(&$data){
        $form = new Bookmark();
        $data['org_id'] = AuthContext::get(AuthConstants::ORG_ID);
        $data['avatar_id'] = AuthContext::get(AuthConstants::USER_ID);
        $form->exchangeArray($data);
        $form->validate();
        $count = 0;
        try{
            $count = $this->table->save($form);
            if($count == 0){
                return 0;
            }
            $data['id'] = $this->table->getLastInsertValue();
        }catch(Exception $e){
            return 0;
        }
        return $count;
    }

    public function updateBookmark($id,&$data){
        $obj = $this->table->get($id,array());
        if(is_null($obj)){
            return 0;
        }
        $data = array_merge($obj->toArray(), $data);
        $form = new Bookmark();
        $form->exchangeArray($data);
        $form->validate();
        try{
            $this->table->save($form);
        }catch(Exception $e){
            return 0;
        }
        return $id;
    }

    public function deleteBookmark($id){
        $count = 0;
        try{
            $count = $this->table->delete($id, ['org_id' => AuthContext::get(AuthConstants::ORG_ID)]);
            if($count == 0){
                return 0;
            }
        }catch(Exception $e){
            return 0;
        }
        return $count;
    }

    public function getBookmarks() {
        $sql = $this->getSqlObject();
        $select = $sql->select()
                ->from('links')
                ->columns(array("*"))
                ->where(array('links.org_id' => AuthContext::get(AuthConstants::ORG_ID)));
        return $this->executeQuery($select)->toArray();
    }
}