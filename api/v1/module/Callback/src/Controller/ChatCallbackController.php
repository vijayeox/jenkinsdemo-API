<?php
namespace Callback\Controller;

    use Zend\Log\Logger;
    use Oxzion\Controller\AbstractApiControllerHelper;
    use Oxzion\ValidationException;
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
            $params = $this->extractPostData();
            $this->log->info(ChatCallbackController::class.":Organization Add Params- ".json_encode($params));
            $response = $this->chatService->createTeam($params['orgname']);
            if($response){
                $this->log->info(ChatCallbackController::class.":Organization Added");
                return $this->getSuccessResponseWithData(json_decode($response['body'],true));
            }
            return $this->getErrorResponse("Org Creation Failed", 400);
        }

        public function updateOrgAction(){
            $params = $this->extractPostData();
            $response = $this->chatService->updateTeam($params['old_orgname'],$params['new_orgname']);
            if($response){
                $this->log->info(ChatCallbackController::class.":Organization Updated");
                return $this->getSuccessResponseWithData(json_decode($response,true));
            }
            return $this->getErrorResponse("Org Update Failure", 404);
        }

        public function deleteOrgAction(){
            $params = $this->extractPostData();
            $response = $this->chatService->deleteOrg($params['orgname']);
            if($response){
                $this->log->info(ChatCallbackController::class.":Organization Deleted");
                return $this->getSuccessResponseWithData(json_decode($response,true));
            }
            return $this->getErrorResponse("Org Deletion Failed", 400);
        }

        public function addUserAction(){
            $params = $this->extractPostData();
            $response = $this->chatService->addUserToTeam($params['username'],$params['orgname']);
            if($response){
                $this->log->info(ChatCallbackController::class.":Added user to organization");
                return $this->getSuccessResponseWithData($response);
            }
            return $this->getErrorResponse("Adding User To Team Failure ", 400);
        }

        public function removeUserAction(){
            $params = $this->extractPostData();
            $response = $this->chatService->removeUserFromTeam($params['username'],$params['orgname']);
            if($response){
                $this->log->info(ChatCallbackController::class.":Removed user from organization");
                return $this->getSuccessResponseWithData(json_decode($response,true));
            }
            return $this->getErrorResponse("Remove User From Team Failure ", 404);
        } 

        public function createChannelAction(){
            $params = $this->extractPostData();
            $params['channelname'] = ($params['projectname']) ? ($params['projectname']) :( $params['groupname']);
            $this->log->info(ChatCallbackController::class.":Channel Name- ".$params['channelname']);
            $response = $this->chatService->createChannel($params['channelname'],$params['orgname']);
            if($response){
                $this->log->info(ChatCallbackController::class.":Project/Group Creation Successful");
                return $this->getSuccessResponseWithData(json_decode($response['body'],true));
            }
            return $this->getErrorResponse("Creation of Channel Failed", 400);
        } 

        public function deleteChannelAction(){
            $params = $this->extractPostData();
            $params['channelname'] = ($params['projectname']) ? ($params['projectname']) :( $params['groupname']);
            $response = $this->chatService->deleteChannel($params['channelname'],$params['orgname']);
            if($response){
                $this->log->info(ChatCallbackController::class.":Project/Group Deleted");
                return $this->getSuccessResponseWithData(json_decode($response,true));
            }
            return $this->getErrorResponse("Channel Deletion Failed", 400);
        } 
        
        public function updateChannelAction(){
            $params = $this->extractPostData();
            $params['old_channelname'] = ($params['old_projectname']) ? ($params['old_projectname']) :( $params['old_groupname']);
            $params['new_channelname'] = ($params['new_projectname']) ? ($params['new_projectname']) :( $params['new_groupname']);
            $response = $this->chatService->updateChannel($params['old_channelname'],$params['new_channelname'],$params['orgname']);
            if($response){
                $this->log->info(ChatCallbackController::class.":Project/Group Updated Successful");
                return $this->getSuccessResponseWithData(json_decode($response,true));
            }
            return $this->getErrorResponse("Update to Channel Failed", 404);
        }

        public function adduserToChannelAction(){
            $params = $this->extractPostData();
            $params['channelname'] = ($params['projectname']) ? ($params['projectname']) :( $params['groupname']);
            $response = $this->chatService->addUserToChannel($params['username'],$params['channelname'],$params['orgname']);    
            if($response){
                $this->log->info(ChatCallbackController::class.":User to Project/Group added successfully");
                return $this->getSuccessResponseWithData(json_decode($response['body'],true));
            }
            return $this->getErrorResponse("Add User to Channel Failed", 400);
        }

        public function removeUserFromChannelAction(){
            $params = $this->extractPostData();
            $params['channelname'] = ($params['projectname']) ? ($params['projectname']) :( $params['groupname']);
            $response = $this->chatService->removeUserFromChannel($params['username'],$params['channelname'],$params['orgname']);
            if($response){
                $this->log->info(ChatCallbackController::class.":User from Project/Group removed successfully");
                return $this->getSuccessResponseWithData(json_decode($response,true));
            }
            return $this->getErrorResponse("Removing User from Channel Failed", 400);
        }
    }