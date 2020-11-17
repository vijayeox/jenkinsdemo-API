<?php
namespace Callback\Service;

use Exception;
use Oxzion\Service\AbstractService;
use Oxzion\Utils\RestClient;

class ChatService extends AbstractService
{
    private $restClient;
    private $authToken;
    protected $dbAdapter;

    public function setRestClient($restClient)
    {
        $this->restClient = $restClient;
    }

    public function __construct($config)
    {
        parent::__construct($config, null);
        $chatServerUrl = $this->config['chat']['chatServerUrl'];
        $this->restClient = new RestClient($this->config['chat']['chatServerUrl']);
        $this->authToken = $this->config['chat']['authToken']; //PAT
    }

    private function getAuthHeader()
    {
        $headers = array("Authorization" => "Bearer $this->authToken");
        return $headers;
    }

    private function sanitizeName($name)
    {
        return strtolower(trim(preg_replace("/[^A-Za-z0-9]/", "", $name)));
    }

    private function getTeamByName($orgName, $forceCreateteam = false)
    {
        try {
            $headers = $this->getAuthHeader();
            $orgName = $this->sanitizeName($orgName);
            $response = $this->restClient->get('api/v4/teams/name/' . $orgName, array(), $headers);
            $json = json_decode($response, true);
            return $json;
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            if ($forceCreateteam && ($e->getCode() == 404) && (strpos($e->getMessage(), 'store.sql_team.get_by_name.app_error'))) {
                $this->logger->info("Team Doesn't exist, Creting the team");
                $team = $this->createTeam($orgName);
                $team = json_decode($team['body'], true);
                return $team;
            }
            $this->logger->info("Team Doesn't exist");
        }
    }

    private function searchTeam($orgName)
    {
        $headers = $this->getAuthHeader();
        $response = $this->restClient->postWithHeader('api/v4/teams/search', array('term' => $orgName), $headers);
        $result = json_decode($response['body'], true);
        return $result;
    }

    private function getUserByUsername($userName, $forceCreateUser = false)
    {
        try {
            $headers = $this->getAuthHeader();
            $userName = $this->sanitizeName($userName);
            $userData = $this->restClient->get('api/v4/users/username/' . $userName, array(), $headers);
            return json_decode($userData, true);
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            if ($forceCreateUser && ($e->getCode() == 404) && (strpos($e->getMessage(), 'store.sql_user.get_by_username.app_error'))) {
                $this->logger->info("Unable to find an existing account matching your username, hence creating.");
                $userData = $this->addUser($userName);
                return $userData;
            }
            return $e->getCode();
        }
    }

    private function addUser($user)
    {
        try {
            $headers = $this->getAuthHeader();
            $user = $this->sanitizeName($user);
            $response = $this->restClient->postWithHeader('api/v4/users', array('email' => $user . '@gmail.com', 'username' => $user, 'first_name' => $user, 'password' => md5($user)), $headers);
            $userData = json_decode($response['body'], true);
            return $userData;
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $this->logger->error("Username doesn't exist/Username validation failure", $e);
        }
    }

    private function getChannelByName($channel, $org, $channelNameflag = false)
    {
        try {
            $team = $org;
            if (!is_array($org)) {
                $org = $this->sanitizeName($org);
                $team = $this->getTeamByName($org);
            }
            $channel = $this->sanitizeName($channel);
            $headers = $this->getAuthHeader();
            $response = $this->restClient->get('api/v4/teams/' . $team['id'] . '/channels/name/' . $channel, array(), $headers);
            $channelData = json_decode($response, true);
            return $channelData;
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            if ($channelNameflag && ($e->getCode() == 404) && (strpos($e->getMessage(), 'store.sql_channel.get_by_name.missing.app_error'))) {
                $this->logger->info("Channel does not belong to team, hence ceating channel");
                $channelData = $this->createChannel($channel, $org);
                $channelData = json_decode($channelData['body'], true);
                return $channelData;
            }
            $this->logger->error("Channel does not exist", $e);
        }
    }

    private function getTeamMember($userId, $orgId)
    {
        try {
            $headers = $this->getAuthHeader();
            $response = $this->restClient->get('api/v4/teams/' . $orgId . '/members/' . $userId, array(), $headers);
            $teamMember = json_decode($response, true);
            return json_decode($response, true);
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $this->logger->error("User not in Team", $e);
        }
    }

    public function createTeam($orgName)
    {
        try {
            $headers = $this->getAuthHeader();
            $headers["Content-type"] = "application/json";
            $orgName = $this->sanitizeName($orgName);
            if (empty($orgName)) {
                $this->logger->info(" Org Name is missing");
                return;
            }
            $response = $this->restClient->postWithHeader('api/v4/teams', array('name' => $orgName, 'display_name' => $orgName, 'type' => 'O'), $headers);
            return $response;
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $this->logger->error("A team with that name already exists", $e);
        }
    }

    public function updateTeam($oldName, $newName)
    {
        try {
            $headers = $this->getAuthHeader();
            $oldName = $this->sanitizeName($oldName);
            $newName = $this->sanitizeName($newName);
            if (empty($newName)) {
                $this->logger->info("New Team Name is missing");
                return;
            }
            if (empty($oldName)) {
                $this->logger->info("Old Team Name is missing");
                return;
            }
            $json = $this->getTeamByName($oldName);
            $response = $this->restClient->put('api/v4/teams/' . $json['id'], array('name' => $newName, 'display_name' => $newName, 'id' => $json['id']), $headers);
            return $response;
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $this->logger->error("Org Does not exist", $e);
        }
    }

    public function deleteOrg($orgName)
    {
        try {
            $headers = $this->getAuthHeader();
            $orgName = $this->sanitizeName($orgName);
            $json = $this->searchTeam($orgName);
            if (empty($json)) {
                $this->logger->info("Org with the given name does not exist");
                return;
            }
            $response = $this->restClient->delete('api/v4/teams/' . $json[0]['id'], array('permanent' => 'false'), $headers);
            return $response;
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $this->logger->error("Org Deletion Failed", $e);
        }
    }

    public function addUserToTeam($user, $orgName)
    {
        try {
            $user = $this->sanitizeName($user);
            $orgName = $this->sanitizeName($orgName);
            $headers = $this->getAuthHeader();
            if (empty($user)) {
                $this->logger->info("No User Name Found To Add to team");
                return;
            }
            if (empty($orgName)) {
                $this->logger->info("No Team Name Found To Add the user");
                return;
            }
            // Checking if team exists, if not create team
            $team = $this->getTeamByName($orgName, true);

            // Check if user exists, if not create user
            $userData = $this->getUserByUsername($user, true);
            $response = $this->restClient->postWithHeader('api/v4/teams/' . $team['id'] . '/members', array('team_id' => $team['id'], 'user_id' => $userData['id']), $headers);
            $response = json_decode($response['body'], true);
            return $response;
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $this->logger->error("User Already in team", $e);
        }
    }

    public function removeUserFromTeam($user, $org)
    {
        try {
            $headers = $this->getAuthHeader();
            $user = $this->sanitizeName($user);
            $org = $this->sanitizeName($org);
            if (empty($user)) {
                $this->logger->info("No User Name Found To Remove from team");
                return;
            }
            if (empty($org)) {
                $this->logger->info("No Team Name Found To Remove user");
                return;
            }
            $userData = $this->getUserByUsername($user);
            $team = $this->getTeamByName($org);
            $response = $this->restClient->delete('api/v4/teams/' . $team['id'] . '/members/' . $userData['id'], array(), $headers);
            return $response;
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $this->logger->error("User is not in the Team/User Or Team name is missing", $e);
        }
    }

    public function createChannel($channel, $org)
    {
        try {
            if (empty($channel)) {
                $this->logger->info("No Channel Name Found To create");
                return;
            }
            if (empty($org)) {
                $this->logger->info("No Team Name Found To create");
                return;
            }
            $team = $org;
            $headers = $this->getAuthHeader();
            $channel = $this->sanitizeName($channel);
            if (!is_array($org)) {
                $org = $this->sanitizeName($org);
                $team = $this->getTeamByName($org, true);
            }
            $response = $this->restClient->postWithHeader('api/v4/channels', array('team_id' => $team['id'], 'name' => $channel, 'display_name' => $channel, 'type' => 'P'), $headers);
            return $response;
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $this->logger->error("Create Channel Failed", $e);
        }
    }

    public function deleteChannel($channel, $org)
    {
        try {
            $headers = $this->getAuthHeader();
            $channel = $this->sanitizeName($channel);
            $org = $this->sanitizeName($org);
            if (empty($channel)) {
                $this->logger->info("Deletion Failed - Channel Name not specified");
                return;
            }
            if (empty($org)) {
                $this->logger->info("Deletion Failed - Team Name not specified");
                return;
            }
            $channelData = $this->getChannelByName($channel, $org);
            $response = $this->restClient->delete('api/v4/channels/' . $channelData['id'], array(), $headers);
            return $response;
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $this->logger->error("Channel/Team Doesn't exist", $e);
        }
    }

    public function updateChannel($oldChannel, $newChannel, $org)
    {
        try {
            $headers = $this->getAuthHeader();
            $org = $this->sanitizeName($org);
            $oldChannel = $this->sanitizeName($oldChannel);
            $newChannel = $this->sanitizeName($newChannel);
            if (empty($oldChannel)) {
                $this->logger->info("No Channel Name specified to Update");
                return;
            }
            if (empty($newChannel)) {
                $this->logger->info("No Name Found To Update");
                return;
            }
            $channelData = $this->getChannelByName($oldChannel, $org);
            $response = $this->restClient->put('api/v4/channels/' . $channelData['id'], array('id' => $channelData['id'], 'name' => $newChannel, 'display_name' => $newChannel), $headers);
            return $response;
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $this->logger->error("Update Channel Failed", $e);
        }
    }

    public function addUserToChannel($user, $channel, $org)
    {
        try {
            $headers = $this->getAuthHeader();
            $user = $this->sanitizeName($user);
            $channel = $this->sanitizeName($channel);
            $org = $this->sanitizeName($org);

            if (empty($user)) {
                $this->logger->info("No User to Add to the channel ");
                return;
            }
            $team = $this->getTeamByName($org, true);
            $channelData = $this->getChannelByName($channel, $team, true);
            $userData = $this->getUserByUsername($user, true);
            $teamMember = $this->getTeamMember($userData['id'], $team['id']);
            if (!isset($teamMember['user_id'])) {
                $this->logger->info("User not part of team, adding to the team");
                $teamMember = $this->addUserToTeam($user, $org);
            }
            $response = $this->restClient->postWithHeader('api/v4/channels/' . $channelData['id'] . '/members', array('user_id' => $userData['id']), $headers);
            return $response;
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $this->logger->error("Adding User to channel Failed", $e);
        }
    }

    public function removeUserFromChannel($user, $channel, $org)
    {
        try {
            $headers = $this->getAuthHeader();
            $user = $this->sanitizeName($user);
            $channel = $this->sanitizeName($channel);
            $org = $this->sanitizeName($org);
            if (empty($user)) {
                $this->logger->info("No User Name Found To Remove from the team");
                return;
            }
            $team = $this->getTeamByName($org);

            $channelData = $this->getChannelByName($channel, $org);

            $userData = $this->getUserByUsername($user);

            $teamMember = $this->getTeamMember($userData['id'], $team['id']);

            // User in channel check
            $channelMember = $this->restClient->get('api/v4/channels/' . $channelData['id'] . '/members/' . $userData['id'], array(), $headers);
            if (!isset($channelMember)) {
                $this->logger->info("Removal Failed - User not in channel");
                return;
            }
            $response = $this->restClient->delete('api/v4/channels/' . $channelData['id'] . '/members/' . $userData['id'], array(), $headers);
            return $response;
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $this->logger->error($e->getMessage(), $e);
            $this->logger->info("Removing User from channel Failed");
        }
    }

    public function createBot($botName)
    {
        try {
            $headers = $this->getAuthHeader();
            $headers["Content-type"] = "application/json";
            $botName = $this->sanitizeName($botName);
            if (empty($botName)) {
                $this->logger->info("Bot Name is missing");
                return;
            }
            $response = $this->restClient->postWithHeader('api/v4/bots', array('username' => $botName, 'display_name' => $botName, 'description' => 'BOT for '.$botName), $headers);
            return $response;
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $this->logger->error($e->getMessage(), $e);
            throw $e;
        }
    }

    public function updateBot($botName, $displayName)
    {
        try {
            $headers = $this->getAuthHeader();
            $botName = $this->sanitizeName($botName);
            if (empty($displayName)) {
                $this->logger->info("New Display Name is missing");
                return;
            }
            if (empty($botName)) {
                $this->logger->info("Bot Name is missing");
                return;
            }
            $userDetails = $this->getUserByUsername($botName,true);    
            $response = $this->restClient->put('api/v4/bots/' . $userDetails['id'], array('display_name' => $displayName), $headers);
            return $response;
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $this->logger->error($e->getMessage(), $e);
            throw $e;
        }
    }

}
