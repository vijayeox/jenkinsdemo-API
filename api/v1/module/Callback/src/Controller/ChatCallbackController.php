<?php
namespace Callback\Controller;

    use Zend\Log\Logger;
    use Oxzion\Controller\AbstractApiControllerHelper;
    use Bos\ValidationException;
    use Zend\Db\Adapter\AdapterInterface;
    use Oxzion\Utils\RestClient;
    use Callback\Service\ChatService;

    class ChatCallbackController extends AbstractApiControllerHelper {

        private $chatService;
        protected $log;
        // /**
        // * @ignore __construct
        // */
        public function __construct(ChatService $chatService, Logger $log) {
            $this->chatService = $chatService;  
            $this->log = $log;      
        }
        
        public function setChatService($chatService){
            $this->chatService = $chatService;
        }

        public function addOrgAction() {
            $params = $this->params()->fromPost();
            $this->log->info(ChatCallbackController::class.":Organization Add Action- ".json_encode($params));
            $response = $this->chatService->createTeam($params['orgname']);
            if($response){
                $this->log->info(ChatCallbackController::class.":Organization Added");
                return $this->getSuccessResponseWithData(json_decode($response['body'],true));
            }
            return $this->getErrorResponse("Org Creation Failed", 400);
        }

        public function updateOrgAction(){
            $params = $this->params()->fromPost();
            $response = $this->chatService->updateTeam($params['old_name'],$params['new_name']);
            if($response){
            return $this->getSuccessResponseWithData(json_decode($response,true));
            }
            return $this->getErrorResponse("Org Update Failure", 404);
        }

        public function deleteOrgAction(){
            $params = $this->params()->fromPost();
            $response = $this->chatService->deleteOrg($params['name']);
            if($response){
            return $this->getSuccessResponseWithData(json_decode($response,true));
            }
            return $this->getErrorResponse("Org Deletion Failed", 400);
        }

        public function addUserAction(){
            $params = $this->params()->fromPost();
            $response = $this->chatService->addUserToTeam($params['username'],$params['teamname']);
            if($response){
            return $this->getSuccessResponseWithData($response);
            }
            return $this->getErrorResponse("Adding User To Team Failure ", 400);
        }

        public function removeUserAction(){
            $params = $this->params()->fromPost();
            $response = $this->chatService->removeUserFromTeam($params['username'],$params['teamname']);
            if($response){
            return $this->getSuccessResponseWithData(json_decode($response,true));
            }
            return $this->getErrorResponse("Remove User From Team Failure ", 404);
        } 

        public function createChannelAction(){
            $params = $this->params()->fromPost();
            $response = $this->chatService->createChannel($params['channelname'],$params['teamname']);
            if($response){
            return $this->getSuccessResponseWithData(json_decode($response['body'],true));
            }
            return $this->getErrorResponse("Creation of Channel Failed", 400);
        } 

        public function deleteChannelAction(){
            $params = $this->params()->fromPost();
            $response = $this->chatService->deleteChannel($params['channelname'],$params['teamname']);
            if($response){
            return $this->getSuccessResponseWithData(json_decode($response,true));
            }
            return $this->getErrorResponse("Channel Deletion Failed", 400);
        } 
        
        public function updateChannelAction(){
            $params = $this->params()->fromPost();
            $response = $this->chatService->updateChannel($params['old_channelname'],$params['new_channelname'],$params['team_name']);
            if($response){
            return $this->getSuccessResponseWithData(json_decode($response,true));
            }
            return $this->getErrorResponse("Update to Channel Failed", 404);
        }

        public function adduserToChannelAction(){
            $params = $this->params()->fromPost();
            $response = $this->chatService->addUserToChannel($params['username'],$params['channelname'],$params['teamname']);    
            if($response){
            return $this->getSuccessResponseWithData(json_decode($response['body'],true));
            }
            return $this->getErrorResponse("Add User to Channel Failed", 400);
        }

        public function removeUserFromChannelAction(){
            $params = $this->params()->fromPost();
            $response = $this->chatService->removeUserFromChannel($params['username'],$params['channelname'],$params['teamname']);
            if($response){
            return $this->getSuccessResponseWithData(json_decode($response,true));
            }
            return $this->getErrorResponse("Removing User from Channel Failed", 400);
        }
    }