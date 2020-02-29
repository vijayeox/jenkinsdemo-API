<?php
namespace Callback\Controller;

use Callback\Service\ChatService;
use Oxzion\Controller\AbstractApiControllerHelper;

class ChatCallbackController extends AbstractApiControllerHelper
{
    private $chatService;
    protected $log;
    /**
     * @ignore __construct
     */
    public function __construct(ChatService $chatService)
    {
        $this->chatService = $chatService;
        $this->log = $this->getLogger();
    }

    public function addOrgAction()
    {
        $params = $this->extractPostData();
        $this->log->info("Organization Add Params- " . json_encode($params));
        $params['orgname'] = isset($params['orgname']) ? $params['orgname'] : null;
        $response = $this->chatService->createTeam($params['orgname']);
        if ($response) {
            $this->log->info(ChatCallbackController::class . ":Organization Added");
            return $this->getSuccessResponseWithData(json_decode($response['body'], true));
        }
        return $this->getErrorResponse("Org Creation Failed", 400);
    }

    public function updateOrgAction()
    {
        $params = $this->extractPostData();
        $params['old_orgname'] = isset($params['old_orgname']) ? $params['old_orgname'] : null;
        $params['new_orgname'] = isset($params['new_orgname']) ? $params['new_orgname'] : null;
        $response = $this->chatService->updateTeam($params['old_orgname'], $params['new_orgname']);
        if ($response) {
            $this->log->info("Organization Updated");
            return $this->getSuccessResponseWithData(json_decode($response, true));
        }
        return $this->getErrorResponse("Org Update Failure", 404);
    }

    public function deleteOrgAction()
    {
        $params = $this->extractPostData();
        $response = $this->chatService->deleteOrg($params['orgname']);
        if ($response) {
            $this->log->info("Organization Deleted");
            return $this->getSuccessResponseWithData(json_decode($response, true));
        }
        return $this->getErrorResponse("Org Deletion Failed", 400);
    }

    public function addUserAction()
    {
        $params = $this->extractPostData();
        $params['username'] = isset($params['username']) ? $params['username'] : null;
        $params['orgname'] = isset($params['orgname']) ? $params['orgname'] : null;
        $response = $this->chatService->addUserToTeam($params['username'], $params['orgname']);
        if ($response) {
            $this->log->info("Added user to organization");
            return $this->getSuccessResponseWithData($response);
        }
        return $this->getErrorResponse("Adding User To Team Failure ", 400);
    }

    public function removeUserAction()
    {
        $params = $this->extractPostData();
        $params['username'] = isset($params['username']) ? $params['username'] : null;
        $params['orgname'] = isset($params['orgname']) ? $params['orgname'] : null;

        $response = $this->chatService->removeUserFromTeam($params['username'], $params['orgname']);
        if ($response) {
            $this->log->info("Removed user from organization");
            return $this->getSuccessResponseWithData(json_decode($response, true));
        }
        return $this->getErrorResponse("Remove User From Team Failure ", 404);
    }

    public function createChannelAction()
    {
        $params = $this->extractPostData();
        $params['groupname'] = isset($params['groupname']) ? $params['groupname'] : null;
        $params['orgname'] = isset($params['orgname']) ? $params['orgname'] : null;
        $params['channelname'] = isset($params['projectname']) ? ($params['projectname']) : ($params['groupname']);
        $this->log->info(":Channel Name- " . $params['channelname']);
        $response = $this->chatService->createChannel($params['channelname'], $params['orgname']);
        if ($response) {
            $this->log->info(ChatCallbackController::class . ":Project/Group Creation Successful");
            return $this->getSuccessResponseWithData(json_decode($response['body'], true));
        }
        return $this->getErrorResponse("Creation of Channel Failed", 400);
    }

    public function deleteChannelAction()
    {
        $params = $this->extractPostData();
        $params['groupname'] = isset($params['groupname']) ? $params['groupname'] : null;
        $params['orgname'] = isset($params['orgname']) ? $params['orgname'] : null;
        $params['channelname'] = isset($params['projectname']) ? ($params['projectname']) : ($params['groupname']);
        $response = $this->chatService->deleteChannel($params['channelname'], $params['orgname']);
        if ($response) {
            $this->log->info(":Project/Group Deleted");
            return $this->getSuccessResponseWithData(json_decode($response, true));
        }
        return $this->getErrorResponse("Channel Deletion Failed", 400);
    }

    public function updateChannelAction()
    {
        $params = $this->extractPostData();
        $params['old_groupname'] = isset($params['old_groupname']) ? $params['old_groupname'] : null;
        $params['new_groupname'] = isset($params['new_groupname']) ? $params['new_groupname'] : null;
        $params['old_channelname'] = isset($params['old_projectname']) ? ($params['old_projectname']) : ($params['old_groupname']);

        $params['new_channelname'] = isset($params['new_projectname']) ? ($params['new_projectname']) : ($params['new_groupname']);
        $response = $this->chatService->updateChannel($params['old_channelname'], $params['new_channelname'], $params['orgname']);
        if ($response) {
            $this->log->info(":Project/Group Updated Successful");
            return $this->getSuccessResponseWithData(json_decode($response, true));
        }
        return $this->getErrorResponse("Update to Channel Failed", 404);
    }

    public function adduserToChannelAction()
    {
        $params = $this->extractPostData();
        $params['username'] = isset($params['username']) ? $params['username'] : null;
        $params['channelname'] = isset($params['projectname']) ? ($params['projectname']) : ($params['groupname']);
        $response = $this->chatService->addUserToChannel($params['username'], $params['channelname'], $params['orgname']);
        if ($response) {
            $this->log->info("User to Project/Group added successfully");
            return $this->getSuccessResponseWithData(json_decode($response['body'], true));
        }
        return $this->getErrorResponse("Add User to Channel Failed", 400);
    }

    public function removeUserFromChannelAction()
    {
        $params = $this->extractPostData();
        $params['username'] = isset($params['username']) ? $params['username'] : null;
        $params['channelname'] = isset($params['projectname']) ? ($params['projectname']) : ($params['groupname']);
        $response = $this->chatService->removeUserFromChannel($params['username'], $params['channelname'], $params['orgname']);
        if ($response) {
            $this->log->info("User from Project/Group removed successfully");
            return $this->getSuccessResponseWithData(json_decode($response, true));
        }
        return $this->getErrorResponse("Removing User from Channel Failed", 400);
    }
}
