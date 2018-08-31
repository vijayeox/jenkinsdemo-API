<?php
namespace Avatar\Controller;

use Zend\Log\Logger;
use Oxzion\Model\Entity\Avatar;
use Oxzion\Model\Entity\Group;
use Zend\View\Model\JsonModel;
use Oxzion\Controller\AbstractApiController;

class GroupController extends AbstractApiController {

    public function __construct(Logger $log){
        parent::__construct($log, __CLASS__, new Avatar());
        $this->setIdentifierName('avatarId');
    }
    
    public function get($id){
    	$params = $this->params()->fromRoute();
    	if(isset($id)){
    		$avatar = new Avatar($id);
    	}
        $groups = $avatar->getGroups();
        if(is_null($groups)){
            return $this->getErrorResponse("Entity not found for id - $id", 404);
        }
        return $this->getSuccessResponseWithData($groups);
    }
    public function getList(){
        $groups = $this->currentAvatarObj->getGroups();
        if(is_null($groups)){
            return $this->getErrorResponse("Entity not found for id - $id", 404);
        }
        return $this->getSuccessResponseWithData($groups);
    }
}