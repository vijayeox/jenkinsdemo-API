<?php
namespace User\Controller;

use Zend\Log\Logger;
use Oxzion\Model\User;
use Oxzion\Model\UserTable;
use Oxzion\Service\UserService;
use Oxzion\Service\GroupController;
use Bos\Auth\AuthContext;
use Bos\Auth\AuthConstants;
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
        parent::__construct($table, $log, __class__, User::class);
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
     * @Route Info: (a=>All Fields, m=>Minimum Fields, d=>Detailed); In future we are planning to add "Detailed" type
     * with more fields to load.
     */
    public function get($id)
    {
        $params = $this->params()->fromRoute();
        return $this->getUserInfo($id, $params);
    }

    /**
     * GET User API
     * @api
     * @link /user[/:userId]
     * @method GET
     * @param $id ID of User to Delete
     * @return array $data
     * @return array Returns a JSON Response with Status Code and Created User.
     * @Route Info: (a=>All Fields, m=>Minimum Fields, d=>Detailed); In future we are planning to add "Detailed" type
     * with more fields to load.
     */
    public function getUserDetailAction()
    {
        $id = AuthContext::get(AuthConstants::USER_ID);
        $params = $this->params()->fromRoute();
        return $this->getUserInfo($id, $params);

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
            $response = $this->userService->updateUser($id, $data);
        } catch (ValidationException $e) {
            $response = ['data' => $data, 'errors' => $e->getErrors()];
            return $this->getErrorResponse("Validation Errors", 406, $response);
        }
        if ($response == 0) {
            return $this->getErrorResponse("Entity not found for id - $id", 404);
        }
        return $this->getSuccessResponseWithData($response, 200);
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
        $response = $this->userService->deleteUser($id);
        if($response == 0){
            return $this->getErrorResponse("User not found", 404, ['id' => $id]);
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
    /**
     * Add User To Group API
     * @api
     * @link /user/:userId/addusertogroup/:groupId'
     * @method POST
     * @param $id and $groupid that adds a particular user to a group
     * @return array success|failure response
     */
    public function addUserToGroupAction()
    {
        $params = $this->params()->fromRoute();
        $id = $params['userId'];
        $groupId = $params['groupId'];
        try {
            $response = $this->userService->addUserToGroup($params['userId'], $params['groupId']);
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

    /**
     * Add User To Project API
     * @api
     * @link /user/:userId/addusertoproject/:projectId'
     * @method POST
     * @param $id and $groupid that adds a particular user to a project
     * @return array success|failure response
     */
    public function addUserToProjectAction()
    {
        $params = $this->params()->fromRoute();
        try {
            $response = $this->userService->addUserToProject($params['userId'], $params['projectId']);
            if ($response == 0) {
                return $this->getErrorResponse("Entity not found for id", 404);
            }
            return $this->getSuccessResponse();
        } catch (ValidationException $e) {
            $response = ['data' => $data, 'errors' => $e->getErrors()];
            return $this->getErrorResponse("Validation Errors", 406, $response);
        }
    }

    /**
     * Remove User from Project API
     * @api
     * @link /user/:userId/removeuserfromproject/:projectId'
     * @method POST
     * @param $id and $groupid that removes a particular user to a project
     * @return array success|failure response
     */
    public function removeUserFromProjectAction()
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
                return $this->getSuccessResponse("Not able to get the Username! Please check with the Administrator");
            }
            if ($result['blackListedApps'] == null || empty($result['blackListedApps'])) {
                return $this->getSuccessResponse("Not able to get the BlackListed Apps!");
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

    /**
     * @param $id
     * @param $params
     * @return JsonModel
     */
    private function getUserInfo($id, $params)
    {
        try{
            $type = (isset($params['typeId'])) ? ($params['typeId']) : 'm';
            if ($type === 'a') {
                $result = $this->userService->getUser($id);
            } else if ($type === 'm') {
                $result = $this->userService->getUserWithMinimumDetails($id);
            } else {
            $result = $this->userService->getUserWithMinimumDetails($id); // Currently using the minimum information
            // for the user. When we get another condition then we will use tem
            }
        }
        catch (ValidationException $e) {
            $response = ['data' => $data, 'errors' => $e->getErrors()];
            return $this->getErrorResponse("Validation Errors",404, $response);
        }
        if (($result == 0)||(empty($result))) {
            $response = ['id' => $id];
            return $this->getErrorResponse("Failed to find User", 404, $response);
        }
        return $this->getSuccessResponseWithData($result);
    }

    public function changePasswordAction()
    {
        $data = $this->params()->fromPost();
        $user = $this->params()->fromRoute();
        $userId = AuthContext::get(AuthConstants::USER_ID);
        $userDetail = $this->userService->getUser($userId);
        $oldPassword = md5(sha1($data['old_password']));
        $newPassword = md5(sha1($data['new_password']));
        $confirmPassword = md5(sha1($data['confirm_password']));

        if (($oldPassword == $userDetail['password']) && ($newPassword == $confirmPassword)) {
            $formData = array('id' => $userDetail['id'], 'password' => $newPassword, 'password_reset_date' => Date("Y-m-d H:i:s"), 'otp' => null);
            $result = $this->update($userDetail['id'], $formData);
            return $this->getSuccessResponse("Password changed successfully!");
        } else {
            $response = ['id' => $userDetail['id']];
            return $this->getErrorResponse("Failed to Update Password", 404, $response);
        }
    }
     /**
     * Add User To Organization API
     * @api
     * @link /user/:userId/organization/:organizationId'
     * @method POST
     * @param $id and $orgid that adds a particular user to a organization
     * @return array success|failure response
     */
    public function addOrganizationToUserAction() 
    {
        $params = $this->params()->fromRoute();
        $id = $params['userId'];
        $organizationId = $params['organizationId'];
        try {
            $response = $this->userService->addUserToOrg($params['userId'], $params['organizationId']);
            if ($response == 0) {
                return $this->getErrorResponse("Entity not found for id -$id", 404);
            } elseif ($response == 2) {
                return $this->getErrorResponse("Entity not found for organizationid -$organizationId", 404);
            } elseif ($response == 3) {
                return $this->getErrorResponse("Entity exists and therefore unable to add", 404);
            }
            return $this->getSuccessResponse();
        } catch (ValidationException $e) {
            $response = ['data' => $data, 'errors' => $e->getErrors()];
            return $this->getErrorResponse("Validation Errors", 406, $response);
        }
    }

    /**
    * GET User Access API
    * @api
    * @link /user/me/access'
    * @method GET
    * @return JsonModel
    */
    public function getUserAppsAndPrivilegesAction() {
        $params = $this->params()->fromRoute();
        try {
            $responseData = $this->userService->getUserAppsAndPrivileges();
        } catch (ValidationException $e) {
            $response = ['data' => $data, 'errors' => $e->getErrors()];
            return $this->getErrorResponse("Validation Errors",404, $response);
        }
        return $this->getSuccessResponseWithData($responseData,200);
    }
}