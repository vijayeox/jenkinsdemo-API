<?php

namespace Team\Controller;

use Exception;
use Team\Model\Team;
use Team\Model\TeamTable;
use Team\Service\TeamService;
use Oxzion\AccessDeniedException;
use Oxzion\Controller\AbstractApiController;
use Oxzion\ServiceException;
use Oxzion\Service\AccountService;
use Oxzion\ValidationException;
use Zend\Db\Adapter\AdapterInterface;

class TeamController extends AbstractApiController
{
    private $teamService;
    private $accountService;

    /**
     * @ignore __construct
     */
    public function __construct(TeamTable $table, TeamService $teamService, AdapterInterface $dbAdapter, AccountService $accountService)
    {
        parent::__construct($table, Team::class);
        $this->setIdentifierName('teamId');
        $this->teamService = $teamService;
        $this->accountService = $accountService;
        $this->log = $this->getLogger();
    }

    /**
     * ! DEPRECATED
     * GET Team API The code is to get the list of all the teams for the user. I am putting this function here, but Im not sure whether this has to be here or in the User Module. We can move that later when it is required.
     * @api
     * @link /team/getTeamsforUser/:userId
     * @method GET
     * @param $id ID of Team
     * @return array $data
     * @return array Returns a JSON Response with Status Code and Created Team.
     */
    public function getTeamsforUserAction()
    {
        $params = $this->params()->fromRoute();
        $data = $this->params()->fromQuery();
        $userId = $params['userId'];
        try {
            $teamList = $this->teamService->getTeamsforUser($userId, $data);
        } catch (Exception $e) {
            $this->log->error($e->getMessage(), $e);
            return $this->exceptionToResponse($e);
        } 
        return $this->getSuccessResponseWithData($teamList);
    }
    /**
     * Create Team API
     * @api
     * @link /team
     * @method POST
     * @param array $data Array of elements as shown
     * <code> {
     *               id : integer,
     *               name : string,
     *               Fields from Team
     *   } </code>
     * @return array Returns a JSON Response with Status Code and Created Team.
     */
    public function create($data)
    {
        $files = $this->params()->fromFiles('logo');
        $id = $this->params()->fromRoute();
        $id['accountId'] = isset($id['accountId']) ? $id['accountId'] : null;
        $this->log->info(__CLASS__ . "-> Create Team - " . json_encode($data, true));
        try {
            if (!isset($id['teamId'])) {
                $this->teamService->createTeam($data, $files, $id['accountId']);
            } else {
                $this->teamService->updateTeam($id['teamId'], $data, $files, $id['accountId']);
            }
        } catch (Exception $e) {
            $this->log->error($e->getMessage(), $e);
            return $this->exceptionToResponse($e);
        } 
        return $this->getSuccessResponseWithData($data, 201);
    }

    /**
     * Update Team API
     * @api
     * @link /team[/:teamId]
     * @method PUT
     * @param array $id ID of Team to update
     * @param array $data
     * @return array Returns a JSON Response with Status Code and Created Team.
     */
    public function update($id, $data)
    {
        $this->log->info(__CLASS__ . "-> Update Team - " . json_encode($data, true));
        try {
            $this->teamService->updateTeam($id, $data);
        } catch (Exception $e) {
            $this->log->error($e->getMessage(), $e);
            return $this->exceptionToResponse($e);
        } 
        return $this->getSuccessResponseWithData($data, 200);
    }

    /**
     * Delete Team API
     * @api
     * @link /team[/:teamId]
     * @method DELETE
     * @param $id ID of Team to Delete
     * @return array success|failure response
     */
    public function delete($id)
    {
        $params = $this->params()->fromRoute();
        $this->log->info(__CLASS__ . "-> Delete Team - " . json_encode($params, true) . " for ID " .json_encode($id, true));
        try {
            $response = $this->teamService->deleteTeam($params);
        } catch (Exception $e) {
            $this->log->error($e->getMessage(), $e);
            return $this->exceptionToResponse($e);
        } 
        return $this->getSuccessResponse();
    }

    /**
     * GET Team API
     * @api
     * @link /team[/:teamId]
     * @method GET
     * @param array $dataget of Team
     * @return array $data
     * <code> {
     *               id : integer,
     *               name : string,
     *               logo : string,
     *               status : String(Active|Inactive),
     *   } </code>
     * @return array Returns a JSON Response with Status Code and Created Team.
     */
    public function get($id)
    {
        $params = $this->params()->fromRoute();
        try {
            $result = $this->teamService->getTeamByUuid($id, $params);
            if (count($result) == 0) {
                return $this->getSuccessResponseWithData($result);
            }
            $account = $this->accountService->getAccount($result['accountId']);
            if ($result) {
                $baseUrl = $this->getBaseUrl();
                $logo = $result['logo'];
                $result['logo'] = $baseUrl . "/team/" . $account['uuid'] . "/logo/" . $result["uuid"];
            }
        } catch (Exception $e) {
            $this->log->error($e->getMessage(), $e);
            return $this->exceptionToResponse($e);
        } 
        return $this->getSuccessResponseWithData($result);
    }

    /**
     * GET List Team API
     * @api
     * @link /team
     * @method GET
     * @return array Returns a JSON Response with Invalid Method/
     */
    public function getList()
    {
        $filterParams = $this->params()->fromQuery();
        $params = $this->params()->fromRoute();
        $this->log->info(__CLASS__ . "-> Get List - " . json_encode($params, true));
        try {
            $result = $this->teamService->getTeamList($filterParams, $params);
            if ($result) {
                for ($x = 0; $x < sizeof($result['data']); $x++) {
                    $baseUrl = $this->getBaseUrl();
                    $logo = $result['data'][$x]['logo'];
                    $account = $this->accountService->getAccount($result['data'][$x]['accountId']);
                    $result['data'][$x]['logo'] = $baseUrl . "/team/" . $account['uuid'] . "/logo/" . $result['data'][$x]["uuid"];
                }
            }
        } catch (Exception $e) {
            $this->log->error($e->getMessage(), $e);
            return $this->exceptionToResponse($e);
        } 
        return $this->getSuccessResponseDataWithPagination($result['data'], $result['total']);
    }

    public function teamsListAction()
    {
        $filterParams = $this->extractPostData();
        $params = $this->params()->fromRoute();
        $this->log->info(__CLASS__ . "-> Team Listing - " . json_encode($params, true));
        try {
            $result = $this->teamService->getTeamList($filterParams, $params);
            if ($result) {
                for ($x = 0; $x < sizeof($result['data']); $x++) {
                    $baseUrl = $this->getBaseUrl();
                    $logo = $result['data'][$x]['logo'];
                    $account = $this->accountService->getAccount($result['data'][$x]['accountId']);
                    $result['data'][$x]['logo'] = $baseUrl . "/team/" . $account['uuid'] . "/logo/" . $result['data'][$x]["uuid"];
                }
            }
        } catch (Exception $e) {
            $this->log->error($e->getMessage(), $e);
            return $this->exceptionToResponse($e);
        } 
        return $this->getSuccessResponseDataWithPagination($result['data'], $result['total']);
    }

    /**
     * Save users in a Team API
     * @api
     * @link /team/:teamid/save
     * @method Post
     * @param json object of userid
     * @return array $dataget list of teams by User
     * <code>status : "success|error",
     *       data : all user id's passed back in json format
     * </code>
     */
    public function saveUserAction()
    {
        $params = $this->params()->fromRoute();
        $data = $this->extractPostData();
        $this->log->info(__CLASS__ . "-> Save User to Teams - " . json_encode($params, true));
        try {
            $count = $this->teamService->saveUser($params, $data);
        } catch (Exception $e) {
            $this->log->error($e->getMessage(), $e);
            return $this->exceptionToResponse($e);
        } 
        return $this->getSuccessResponseWithData($data, 200);
    }

    /**
     * GET all users in a particular Team API
     * @api
     * @link /team/:teamid/users
     * @method GET
     * @return array $dataget list of teams by User
     * <code>status : "success|error",
     *       data : all user id's in the team passed back in json format
     * </code>
     */
    public function getuserlistAction()
    {
        $params = $this->params()->fromRoute();
        $filterParams = $this->params()->fromQuery(); // empty method call
        $this->log->info(__CLASS__ . "-> Get user list for the team - " . json_encode($params, true));
        try {
            $count = $this->teamService->getUserList($params, $filterParams);
        } catch (Exception $e) {
            $this->log->error($e->getMessage(), $e);
            return $this->exceptionToResponse($e);
        } 
        return $this->getSuccessResponseDataWithPagination($count['data'], $count['total']);
    }
    public function getSubteamsAction()
    {
        $params = $this->params()->fromRoute();
        $filterParams = $this->params()->fromQuery(); // empty method call
        $this->log->info(__CLASS__ . "-> \nGet Team - " . print_r($params, true) . "Parameters - " . print_r($params, true));
        try {
            $result = $this->teamService->getSubteams($params,$filterParams);
            return $this->getSuccessResponseWithData($result);
        } catch (Exception $e) {
            $this->log->error($e->getMessage(), $e);
            return $this->exceptionToResponse($e);
        } 
    }
}
