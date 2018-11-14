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
    /**
    * @ignore table
    */
    private $table;

    /**
    * @ignore __construct
    */
    public function __construct($config, $dbAdapter, BookmarkTable $table){
        parent::__construct($config, $dbAdapter);
        $this->table = $table;
    }
    /**
    * Create Bookmark Service
    * @api
    * @method createBookmark
    * @param array $data Array of elements as shown
    * <code> {
    *               id : integer,
    *               name : string,
    *               url : string,
    *   } </code>
    * @return array Returns a JSON Response with Status Code and Created Bookmark.
    */
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

    /**
    * Update Bookmark Service
    * @method updateBookmark
    * @param array $id ID of Bookmark to update 
    * @param array $data 
    * @return array Returns a JSON Response with Status Code and Created Bookmark.
    */
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

    /**
    * Delete Bookmark Service
    * @method deleteBookmark
    * @param $id ID of Bookmark to Delete
    * @return array success|failure response
    */
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

    /**
    * GET List Bookmark Service
    * @method getBookmarks
    * @return array Returns a JSON Response with Array of Bookmarks
    */
    public function getBookmarks() {
        $sql = $this->getSqlObject();
        $select = $sql->select()
                ->from('links')
                ->columns(array("*"))
                ->where(array('links.org_id' => AuthContext::get(AuthConstants::ORG_ID)));
        return $this->executeQuery($select)->toArray();
    }
}