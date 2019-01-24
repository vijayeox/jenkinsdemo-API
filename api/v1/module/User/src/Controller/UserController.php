<?php
namespace User\Controller;

use Zend\Log\Logger;
use Oxzion\Model\User;
use Oxzion\Model\UserTable;
use Oxzion\Service\UserService;
use Oxzion\Service\GroupController;
use Oxzion\Controller\AbstractApiController;
use Oxzion\ValidationResult;
use Bos\ValidationException;
use Zend\View\Model\JsonModel;
use Zend\Db\Sql\Sql;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Adapter\AdapterInterface;


class UserController extends AbstractApiController
{

    private $dbAdapter;

    /**
     * @ignore __construct
     */
    public function __construct(UserTable $table, Logger $log, UserService $userService)
    {
        parent::__construct($table, $log, __CLASS__, User::class);
        $this->setIdentifierName('userId');
        $this->userService = $userService;
    }

    /**
     * Create User API
     * @api
     * @link /user
     * @method POST
     * @param array $data Array of elements as shown</br>
     * <code>
     *        gamelevel : string,
     *        username : string,
     *        password : string,
     *        firstname : string,
     *        lastname : string,
     *        name : string,
     *        role : string,
     *        email : string,
     *        status : string,
     *        dob : string,
     *        designation : string,
     *        sex : string,
     *        managerid : string,
     *        cluster : string,
     *        level : string,
     *        org_role_id : string,
     *        doj : string
     * </code>
     * @return array Returns a JSON Response with Status Code and Created User.</br>
     * <code> status : "success|error",
     *        data : array Created User Object
     * </code>
     */
    public function create($data)
    {
        try {
            $count = $this->userService->createUser($data);
        } catch (ValidationException $e) {
            $response = ['data' => $data, 'errors' => $e->getErrors()];
            /*
        	PLease see the html error codes. https://www.restapitutorial.com/httpstatuscodes.html
        	Not found = 406
        	While this is not exactly not found we don't have a better HTML error code for create.
       		*/
            return $this->getErrorResponse("Validation Errors", 406, $response);
        }

        if ($count == 0) {
            return $this->getFailureResponse("Failed to create a new user", $data);
        }
        /*
        PLease see the html error codes. https://www.restapitutorial.com/httpstatuscodes.html
        Successful create = 201
        */
        return $this->getSuccessResponseWithData($data, 201);
    }

    /**
     * GET User API
     * @api
     * @link /user[/:userId]
     * @method GET
     * @param $id ID of User to Delete
     * @return array $data
     * @return array Returns a JSON Response with Status Code and Created User.
     */
    public function get($id)
    {
        $result = $this->userService->getUser($id);
        if ($result == 0) {
            return $this->getErrorResponse("Failed to find User", 404, $response);
        }
        return $this->getSuccessResponseWithData($result);
    }

    /**
     * GET List User API
     * @api
     * @link /user
     * @method GET
     * @return array $dataget list of Users
     */
    public function getList()
    {
        $result = $this->userService->getUsers();
        return $this->getSuccessResponseWithData($result);
    }

    /**
     * Update User API
     * @api
     * @link /user[/:userId]
     * @method PUT
     * @param array $id ID of User to update
     * @param array $data
     * <code>
     *        gamelevel : string,
     *        username : string,
     *        password : string,
     *        firstname : string,
     *        lastname : string,
     *        name : string,
     *        role : string,
     *        email : string,
     *        status : string,
     *        dob : string,
     *        designation : string,
     *        sex : string,
     *        managerid : string,
     *        cluster : string,
     *        level : string,
     *        org_role_id : string,
     *        doj : string
     * </code>
     * @return array Returns a JSON Response with Status Code and Created User.
     */
    public function update($id, $data)
    {
        try {
            $count = $this->userService->updateUser($id, $data);
        } catch (ValidationException $e) {
            $response = ['data' => $data, 'errors' => $e->getErrors()];
            return $this->getErrorResponse("Validation Errors", 406, $response);
        }
        if ($count == 0) {
            return $this->getErrorResponse("Entity not found for id - $id", 404);
        }
        return $this->getSuccessResponseWithData($data, 200);
    }

    /**
     * Delete User API
     * @api
     * @link /user[/:userId]
     * @method DELETE
     * @param $id ID of User to Delete
     * @return array success|failure response
     */
    public function delete($id)
    {
        try {
            $response = $this->userService->deleteUser($id);
        } catch (ValidationException $e) {
            $response = ['data' => $data, 'errors' => $e->getErrors()];
            return $this->getErrorResponse("Validation Errors", 406, $response);
        }
        if ($response == 0) {
            return $this->getErrorResponse("Entity not found for id - $id", 404);
        }
        return $this->getSuccessResponse();
    }

    /**
     * Assign Manage To User API
     * @api
     * @link /user/:userId/assignManagerToUser
     * @method DELETE
     * @param $id ID of User to Delete
     * @return array success|failure response
     */
    public function assignManagerToUserAction()
    {
        $params = $this->params()->fromRoute();
        try {
            $response = $this->userService->assignManagerToUser($params['userId'], $params['managerId']);
            if ($response == 0) {
                return $this->getErrorResponse("Entity not found", 404);
            }
            return $this->getSuccessResponse();
        } catch (ValidationException $e) {
            $response = ['data' => $data, 'errors' => $e->getErrors()];
            return $this->getErrorResponse("Validation Errors", 406, $response);
        }

    }

    /**
     * Remove Manage To  User API
     * @api
     * @link /user/:userId/removeManagerForUser
     * @method DELETE
     * @param $id ID of User to Delete
     * @return array success|failure response
     */
    public function removeManagerForUserAction()
    {
        $params = $this->params()->fromRoute();
        try {
            $response = $this->userService->removeManagerForUser($params['userId'], $params['managerId']);
            if ($response == 0) {
                return $this->getErrorResponse("Entity not found for id - $id", 404);
            }
            return $this->getSuccessResponse();
        } catch (ValidationException $e) {
            $response = ['data' => $data, 'errors' => $e->getErrors()];
            return $this->getErrorResponse("Validation Errors", 406, $response);
        }

    }

    public function addusertogroupAction()
    {
        $params = $this->params()->fromRoute();
        $id = $params['userId'];
        $groupId = $params['groupId'];
        try {
            $response = $this->userService->addusertogroup($params['userId'], $params['groupId']);
            if ($response == 0) {
                return $this->getErrorResponse("Entity not found for id -$id", 404);
            } elseif ($response == 2) {
                return $this->getErrorResponse("Entity not found for groupId -$groupId", 404);
            } elseif ($response == 3) {
                return $this->getErrorResponse("Entity exists and therefore unable to add", 404);
            }
            return $this->getSuccessResponse();
        } catch (ValidationException $e) {
            $response = ['data' => $data, 'errors' => $e->getErrors()];
            return $this->getErrorResponse("Validation Errors", 406, $response);
        }
    }

    public function addusertoprojectAction()
    {
        $params = $this->params()->fromRoute();
        try {
            $response = $this->userService->addusertoproject($params['userId'], $params['projectId']);
            if ($response == 0) {
                return $this->getErrorResponse("Entity not found for id", 404);
            }
            return $this->getSuccessResponse();
        } catch (ValidationException $e) {
            $response = ['data' => $data, 'errors' => $e->getErrors()];
            return $this->getErrorResponse("Validation Errors", 406, $response);
        }
    }

    public function removeuserfromprojectAction()
    {
        $params = $this->params()->fromRoute();
        $id = $params['userId'];
        $projectId = $params['projectId'];
        try {
            $response = $this->userService->removeUserFromProject($id, $projectId);
            if ($response == 0) {
                return $this->getErrorResponse("Entity not found for id - $id and projectid - $projectId", 404);
            }
            return $this->getSuccessResponse();
        } catch (ValidationException $e) {
            $response = ['data' => $data, 'errors' => $e->getErrors()];
            return $this->getErrorResponse("Validation Errors", 406, $response);
        }
    }

    /**
     * Code to get the Username and the List of all the apps that are not in the Userlist
     * @api
     * @link /user/:userId/usertoken
     * @method userLoginToken
     * @param $id ID of User
     * @return Json Array of Username and List of Apps
     */
    public function userLoginTokenAction()
    {
        try {
            $result['userName'] = $this->userService->getUserNameFromAuth(); // Code to get the username from AuthConstant
            $result['blackListedApps'] = $this->userService->getAppsWithoutAccessForUser();
            if ($result['userName'] == null || empty($result['userName'])) {
                return $this->getErrorResponse("Not able to get the Username! Please check with the Administrator");
            }
            if ($result['blackListedApps'] == null || empty($result['blackListedApps'])) {
                return $this->getErrorResponse("Not able to get the BlackListed Apps!");
            }
            return $this->getSuccessResponseWithData($result);
        } catch (ValidationException $e) {
            $response = ['errors' => $e->getErrors()];
            return $this->getErrorResponse("Validation Errors", 406, $response);
        }
    }

    /**
     * Code to searches for list of friends for the logged in user and then searches for all the other people from the organization
     * @api
     * @link /user/:userId/usersearch
     * @method userSearch
     * @param $id ID of User
     * @return Json Array of Friends and Other employees from the organization
     */

    public function userSearchAction()
    {
        $data = $this->params()->fromPost();
        try {
            $result = $this->userService->getUserBySearchName($data['searchVal']);
            if ($result == null || empty($result)) {
                return $this->getErrorResponse("No results found for " . $data['searchVal']);
            }
            return $this->getSuccessResponseWithData($result);
        } catch (ValidationException $e) {
            $response = ['errors' => $e->getErrors()];
            return $this->getErrorResponse("Validation Errors", 406, $response);
        }
    }
}