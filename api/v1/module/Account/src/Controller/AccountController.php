<?php
namespace Account\Controller;

use Exception;
use Oxzion\AccessDeniedException;
use Oxzion\Controller\AbstractApiController;
use Oxzion\Model\Account;
use Oxzion\Model\AccountTable;
use Oxzion\ServiceException;
use Oxzion\Service\AccountService;
use Oxzion\ValidationException;
use Zend\Db\Adapter\AdapterInterface;

class AccountController extends AbstractApiController
{
    private $accountService;

    /**
     * @ignore __construct
     */
    public function __construct(AccountTable $table, AccountService $accountService, AdapterInterface $dbAdapter)
    {
        parent::__construct($table, Account::class);
        $this->setIdentifierName('accountId');
        $this->accountService = $accountService;
    }

    /**
     * Create Account API
     * @api
     * @method POST
     * @link /account
     * @param array $data Array of elements as shown
     * <code> {
     *               id : integer,
     *               name : string,
     *               logo : string,
     *               status : String(Active|Inactive),
     *   } </code>
     * @return array Returns a JSON Response with Status Code and Created Account.
     */
    public function create($data)
    {
        $files = $this->params()->fromFiles('logo') ? $this->params()->fromFiles('logo') : null;
        $id = $this->params()->fromRoute();
        $this->log->info("Create Account - " . print_r($data, true) . "\n Files - " . print_r($files, true));
        try {
            if (!isset($id['accountId'])) {
                $count = $this->accountService->createAccount($data, $files);
            } else {
                $count = $this->accountService->updateAccount($id['accountId'], $data, $files);
            }
        } catch (ValidationException $e) {
            $response = ['data' => $data, 'errors' => $e->getErrors()];
            $this->log->error($e->getMessage(), $e);
            return $this->getErrorResponse("Validation Errors", 404, $response);
        } catch (ServiceException $e) {
            $this->log->error($e->getMessage(), $e);
            return $this->getErrorResponse($e->getMessage(), 500);
        } catch (Exception $e) {
            $this->log->error($e->getMessage(), $e);
            return $this->getErrorResponse($e->getMessage(), 500);
        }
        return $this->getSuccessResponseWithData($data, 201);
    }

    /**
     * GET List Account API
     * @api
     * @link /account
     * @method GET
     * @return array Returns a JSON Response with Invalid Method/
     */
    public function getList()
    {
        $filterParams = $this->params()->fromQuery(); // empty method call
        $this->log->info("Get Account List - " . print_r($filterParams, true));
        try {
            $result = $this->accountService->getAccounts($filterParams);
            if ($result) {
                for ($x = 0; $x < sizeof($result['data']); $x++) {
                    $baseUrl = $this->getBaseUrl();
                    $result['data'][$x]['logo'] = $baseUrl . "/account/logo/" . $result['data'][$x]['uuid'];
                    $result['data'][$x]['preferences'] = json_decode($result['data'][$x]['preferences'], true);
                }
            }
        } catch (Exception $e) {
            $this->log->error($e->getMessage(), $e);
            return $this->getErrorResponse($e->getMessage(), 500);
        }
        return $this->getSuccessResponseDataWithPagination($result['data'], $result['total']);
    }

    /**
     * Update Account API
     * @api
     * @link /account[/:accountId]
     * @method PUT
     * @param array $id ID of Account to update
     * @param array $data
     * @return array Returns a JSON Response with Status Code and Created Account.
     */
    public function update($id, $data)
    {
        $files = $this->params()->fromFiles('logo');
        try {
            $count = $this->accountService->updateAccount($id, $data, $files);
        } catch (ValidationException $e) {
            $response = ['data' => $data, 'errors' => $e->getErrors()];
            return $this->getErrorResponse("Validation Errors", 404, $response);
        }
        if ($count == 0) {
            return $this->getErrorResponse("Entity not found for id - $id", 404);
        }
        return $this->getSuccessResponseWithData($data, 200);
    }

    /**
     * Delete Account API
     * @api
     * @link /account[/:accountId]
     * @method DELETE
     * @param $id ID of Account to Delete
     * @return array success|failure response
     */
    public function delete($id)
    {
        try {
            $response = $this->accountService->deleteAccount($id);
            if ($response == 0) {
                return $this->getErrorResponse("Account not found", 404, ['id' => $id]);
            }
        } catch (Exception $e) {
            $this->log->error($e->getMessage(), $e);
            return $this->getErrorResponse($e->getMessage(), 500);
        }
        return $this->getSuccessResponse();
    }

    /**
     * GET Account API
     * @api
     * @link /account[/:accountId]
     * @method GET
     * @param array $dataget of Account
     * @return array $data
     * <code> {
     *               id : integer,
     *               name : string,
     *               logo : string,
     *               status : String(Active|Inactive),
     *   } </code>
     * @return array Returns a JSON Response with Status Code and Created Account.
     */
    public function get($id)
    {
        try {
            $result = $this->accountService->getAccountByUuid($id);
            if (!$result) {
                return $this->getErrorResponse("Account not found", 404, ['id' => $id]);
            } else {
                $baseUrl = $this->getBaseUrl();
                $result['logo'] = $baseUrl . "/account/logo/" . $result["uuid"];
                $result['preferences'] = json_decode($result['preferences'], true);
            }
        } catch (Exception $e) {
            $this->log->error($e->getMessage(), $e);
            return $this->getErrorResponse($e->getMessage(), 500);
        }
        return $this->getSuccessResponseWithData($result);
    }

    /**
     * Add User To Account API
     * @api
     * @link /user/:userId/account/:accountId'
     * @method POST
     * @param $id and $accountid that adds a particular user to a account
     * @return array success|failure response
     */
    public function addUserToAccountAction()
    {
        $params = $this->params()->fromRoute();
        $id = $params['accountId'];
        $data = $this->extractPostData();
        try {
            $count = $this->accountService->saveUser($id, $data);
            if ($count == 0) {
                return $this->getErrorResponse("Entity not found", 404);
            }
            if ($count == 2) {
                return $this->getErrorResponse("Enter User Ids", 404);
            }
        } catch (ValidationException $e) {
            $response = ['data' => $data, 'errors' => $e->getErrors()];
            return $this->getErrorResponse("Validation Errors", 404, $response);
        }
        return $this->getSuccessResponseWithData($data, 200);
    }

    /**
     * GET all users in a particular Account API
     * @api
     * @link /account/:accountId/users
     * @method GET
     * @return array $dataget list of Account by User
     * <code>status : "success|error",
     *       data : all user id's in the Account passed back in json format
     * </code>
     */
    public function getListOfAccountUsersAction()
    {
        $account = $this->params()->fromRoute();
        $id = $account[$this->getIdentifierName()];
        $filterParams = $this->params()->fromQuery(); // empty method call
        try {
            $count = $this->accountService->getAccountUserList($account[$this->getIdentifierName()], $filterParams, $this->getBaseUrl());
        } catch (ValidationException $e) {
            $response = ['data' => $account, 'errors' => $e->getErrors()];
            return $this->getErrorResponse("Validation Errors", 404, $response);
        }
        if ($count == 0) {
            return $this->getErrorResponse("Entity not found for id - $id", 404);
        }
        return $this->getSuccessResponseDataWithPagination($count['data'], $count['total']);
    }

    public function getListofAdminUsersAction()
    {
        $data = $this->params()->fromRoute();
        $filterParams = $this->params()->fromQuery();
        $accountId = isset($data['accountId']) ? $data['accountId'] : null;
        try {
            $result = $this->accountService->getAdminUsers($filterParams, $accountId);
        } catch (AccessDeniedException $e) {
            return $this->getErrorResponse($e->getMessage(), 403);
        }
        return $this->getSuccessResponseDataWithPagination($result['data'], $result['total']);
    }

    /**
     * GET Account Groups API
     * @api
     * @link /account/:accountId/groups
     * @method GET
     **/
    public function getListofAccountGroupsAction()
    {
        $params = $this->params()->fromRoute();
        $filterParams = $this->params()->fromQuery();
        $accountId = isset($params['accountId']) ? $params['accountId'] : null;
        try {
            $result = $this->accountService->getAccountGroupsList($accountId, $filterParams);
            if (!$result) {
                return $this->getErrorResponse("Account not found", 404);
            }
        } catch (Exception $e) {
            return $this->getErrorResponse($e->getMessage(), 404);
        }
        return $this->getSuccessResponseDataWithPagination($result['data'], $result['total']);
    }

    /**
     * GET Account Projects API
     * @api
     * @link /account/:accountId/projects
     * @method GET
     **/
    public function getListofAccountProjectsAction()
    {
        $params = $this->params()->fromRoute();
        $filterParams = $this->params()->fromQuery();
        $accountId = isset($params['accountId']) ? $params['accountId'] : null;
        try {
            $result = $this->accountService->getAccountProjectsList($accountId, $filterParams);
            if (!$result) {
                return $this->getErrorResponse("Account not found", 404);
            }
        } catch (Exception $e) {
            return $this->getErrorResponse($e->getMessage(), 404);
        }
        return $this->getSuccessResponseDataWithPagination($result['data'], $result['total']);
    }

    /**
     * GET Account Announcements API
     * @api
     * @link /account/:accountId/announcements
     * @method GET
     **/
    public function getListofAccountAnnouncementsAction()
    {
        $params = $this->params()->fromRoute();
        $filterParams = $this->params()->fromQuery();
        $accountId = isset($params['accountId']) ? $params['accountId'] : null;
        try {
            $result = $this->accountService->getAccountAnnouncementsList($accountId, $filterParams);
            if (!$result) {
                return $this->getErrorResponse("Account not found", 404);
            }
        } catch (Exception $e) {
            return $this->getErrorResponse($e->getMessage(), 404);
        }
        return $this->getSuccessResponseDataWithPagination($result['data'], $result['total']);
    }

    /**
     * GET Account Roles API
     * @api
     * @link /account/:accountId/roles
     * @method GET
     **/
    public function getListofAccountRolesAction()
    {
        $params = $this->params()->fromRoute();
        $filterParams = $this->params()->fromQuery();
        $accountId = isset($params['accountId']) ? $params['accountId'] : null;
        try {
            $result = $this->accountService->getAccountRolesList($accountId, $filterParams);
            if (!$result) {
                return $this->getErrorResponse("Account not found", 404);
            }
        } catch (Exception $e) {
            return $this->getErrorResponse($e->getMessage(), 404);
        }
        return $this->getSuccessResponseDataWithPagination($result['data'], $result['total']);
    }
}
