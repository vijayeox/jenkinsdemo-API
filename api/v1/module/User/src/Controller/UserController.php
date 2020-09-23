<?php
namespace User\Controller;

use Oro\Component\MessageQueue\Transport\Exception\Exception;
use Oxzion\AccessDeniedException;
use Oxzion\Auth\AuthConstants;
use Oxzion\Auth\AuthContext;
use Oxzion\Controller\AbstractApiController;
use Oxzion\Model\User;
use Oxzion\Model\UserTable;
use Oxzion\ServiceException;
use Oxzion\Service\EmailService;
use Oxzion\Service\UserService;
use Oxzion\ValidationException;
use Project\Service\ProjectService;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Sql\Ddl\Column\Datetime;
use Zend\View\Model\JsonModel;

class UserController extends AbstractApiController
{
    private $dbAdapter;

    /**
     * @ignore __construct
     */
    public function __construct(UserTable $table, UserService $userService, AdapterInterface $adapterInterface, EmailService $emailService, ProjectService $projectService)
    {
        parent::__construct($table, User::class, EmailService::class);
        $this->setIdentifierName('userId');
        $this->userService = $userService;
        $this->emailService = $emailService;
        $this->projectService = $projectService;
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
            $params = $this->params()->fromRoute();
            $count = $this->userService->createUser($params, $data);
        } catch (ValidationException $e) {
            $response = ['data' => $data, 'errors' => $e->getErrors()];
            /*
            PLease see the html error codes. https://www.restapitutorial.com/httpstatuscodes.html
            Not found = 406
            While this is not exactly not found we don't have a better HTML error code for create.
             */
            return $this->getErrorResponse("Validation Errors", 406, $response);
        } catch (AccessDeniedException $e) {
            return $this->getErrorResponse($e->getMessage(), 403);
        } catch (ServiceException $e) {
            return $this->getErrorResponse($e->getMessage(), 404);
        }
        if (is_string($count)) {
            $data['uuid'] = $count;
            return $this->getSuccessResponseWithData($data, 201);
        }
        return $this->getSuccessResponseWithData($data, 201);
        /*
    PLease see the html error codes. https://www.restapitutorial.com/httpstatuscodes.html
    Successful create = 201
     */
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
        // This API should use the UUID
        try {
            // $data = $this->table->getByUuid($id,array());
            $data = $this->userService->getUserByUuid($id);
            return $this->getUserInfo($data, $params);
        } catch (Exception $e) {
            return $this->getErrorResponse("User not found", 404, ['id' => $id]);
        }
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
        $params = $this->params()->fromRoute();
        $id = AuthContext::get(AuthConstants::USER_ID);
        return $this->getUserInfo($id, $params);
    }

    public function getUserInfoByIdAction()
    {
        $params = $this->params()->fromRoute();
        $id = $params['userId'];
        return $this->getUserInfo($id, $params);
    }

    public function saveMeAction()
    {
        $data = $this->extractPostData();
        $id = AuthContext::get(AuthConstants::USER_UUID);
        try {
            $result = $this->update($id, $data);
        } catch (ValidationException $e) {
            $response = ['data' => $data, 'errors' => $e->getErrors()];
            return $this->getErrorResponse("Validation Errors", 406, $response);
        } catch (ServiceException $e) {
            return $this->getErrorResponse($e->getMessage(), 404);
        }
        return $result;
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
        $filterParams = $this->params()->fromQuery(); // empty method call
        $result = $this->userService->getUsers($filterParams, $this->getBaseUrl());
        return $this->getSuccessResponseDataWithPagination($result['data'], $result['total']);
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
            $id = $this->params()->fromRoute();
            $response = $this->userService->deleteUser($id);
        } catch (ServiceException $e) {
            return $this->getErrorResponse($e->getMessage(), 404);
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

    public function usersListAction()
    {
        $filterParams = array_merge($this->extractPostData(), $this->params()->fromQuery());
        $params = $this->params()->fromRoute();
        $result = $this->userService->getUsers($filterParams, $this->getBaseUrl(), $params);
        return $this->getSuccessResponseDataWithPagination($result['data'], $result['total']);
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
        $data = $this->extractPostData();
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
        try {
            $userInfo = array();
            $type = (isset($params['type'])) ? ($params['type']) : 'm';
            $params['orgId'] = isset($params['orgId']) ? $params['orgId'] : null;
            $options = explode('+', $type);
            if (in_array('a', $options)) {
                $userInfo = $this->userService->getUser($id);
                $pos = array_search('m', $options);
                if ($pos) {
                    unset($options[$pos]);
                }
            } else {
                $params['orgId'] = isset($params['orgId']) ? $params['orgId'] : null;
                $userInfo = $this->userService->getUserWithMinimumDetails($id, $params['orgId']);
            }
            foreach ($options as $key => $value) {
                switch ($value) {
                    case "p":
                        $userInfo['privileges'] = $this->userService->getPrivileges($id);
                        break;
                    case "e":
                        $userInfo['emails'] = $this->emailService->getEmailAccountsByUserId($id);
                        break;
                    case "ewp":
                        $userInfo['emails'] = $this->emailService->getEmailAccountsByUserId($id, true);
                        break;
                    case "o":
                        $userInfo['organization'] = $this->userService->getOrganizationByUserId($id);
                        break;
                    case "ap":
                        $userInfo['apps'] = $this->userService->getAppsByUserId($id);
                        break;
                    case "bapp":
                        $userInfo['blackListedApps'] = $this->userService->getAppsWithoutAccessForUser();
                        break;
                    case "pr":
                        $userInfo['projects'] = $this->projectService->getProjectsOfUserById($id, $params['orgId']);
                        break;
                    case "role":
                        $userInfo['role'] = $this->userService->getRolesofUser($userInfo['orgid'], $id);
                        break;
                }
            }
            if ($userInfo) {
                $baseUrl = $this->getBaseUrl();
                $icon = $userInfo['icon'];
                $userInfo['icon'] = $baseUrl . "/user/profile/" . $userInfo["uuid"];
            }
        } catch (ValidationException $e) {
            $response = ['data' => $data, 'errors' => $e->getErrors()];

            return $this->getErrorResponse("Validation Errors", 404, $response);
        }
        if (($userInfo == 0) || (empty($userInfo))) {
            return $this->getErrorResponse("User Does not Exist", 404);
        }
        return $this->getSuccessResponseWithData($userInfo);
    }

    public function changePasswordAction()
    {
        $data = $this->extractPostData();
        $userId = AuthContext::get(AuthConstants::USER_ID);
        $userDetail = $this->userService->getUser($userId, true);
        $oldPassword = md5(sha1($data['old_password']));
        $newPassword = md5(sha1($data['new_password']));
        $confirmPassword = md5(sha1($data['confirm_password']));
        if (($oldPassword == $userDetail['password']) && ($newPassword == $confirmPassword)) {
            $formData = array('password' => $newPassword, 'password_reset_date' => Date("Y-m-d H:i:s"), 'otp' => null);
            $result = $this->update($userDetail['uuid'], $formData);
            return $this->getSuccessResponse("Password changed successfully!");
        } else if (($oldPassword != $userDetail['password'])) {
            $response = ['id' => $userId];
            return $this->getErrorResponse("Old password is not valid.", 404, $response);
        } else if (($newPassword != $confirmPassword)) {
            $response = ['id' => $userId];
            return $this->getErrorResponse("Confirm password missmatch.", 404, $response);
        } else {
            $response = ['id' => $userId];
            return $this->getErrorResponse("Failed to Update Password.", 404, $response);
        }
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
        $params = $this->params()->fromRoute();
        $params['orgId'] = isset($params['orgId']) ? $params['orgId'] : null;
        try {
            $response = $this->userService->updateUser($id, $data, $params['orgId']);
        } catch (ValidationException $e) {
            $response = ['data' => $data, 'errors' => $e->getErrors()];
            return $this->getErrorResponse("Validation Errors", 406, $response);
        } catch (ServiceException $e) {
            return $this->getErrorResponse($e->getMessage(), 404);
        }
        return $this->getSuccessResponseWithData($response, 200);
    }

    /**
     * GET User Access API
     * @api
     * @link /user/me/access'
     * @method GET
     * @return JsonModel
     */
    public function getUserAppsAndPrivilegesAction()
    {
        $responseData = $this->userService->getUserAppsAndPrivileges();
        return $this->getSuccessResponseWithData($responseData, 200);
    }

    /**
     * GET List Project of Current User API
     * @api
     * @link /project
     * @method GET
     * @return array $dataget list of Projects by User
     * <code>status : "success|error",
     *       data :  {
     *               string name,
     *               string description,
     *               integer orgid,
     *               integer created_by,
     *               integer modified_by,
     *               dateTime date_created (ISO8601 format yyyy-mm-ddThh:mm:ss),
     *               dateTime date_modified (ISO8601 format yyyy-mm-ddThh:mm:ss),
     *               boolean isdeleted,
     *               integer id,
     *               }
     * </code>
     */
    // DEPRECATED
    public function getUserProjectAction()
    {
        $params = $this->params()->fromRoute();
        $id = $params['userId'];
        $result = $this->projectService->getProjectsOfUserById($id);
        return $this->getSuccessResponseWithData($result);
    }

    public function getUserDetailListAction()
    {
        $params = $this->params()->fromRoute();
        $result = $this->userService->userProfile($params);
        $data = $this->getSuccessResponseWithData($result, 200);
        return $data;
    }

    public function getPolicyTermsAction()
    {
        $result = $this->userService->getPolicyTerm();
        if (!empty($result[0])) {
            return $this->getSuccessResponseWithData($result, 200);
        } else {
            return $this->getSuccessResponseWithData(array(), 200);
        }
    }

     public function updatePolicyTermsAction()
    {
        if(AuthContext::get(AuthConstants::USER_ID)){
            try {
                $count = $this->userService->updatePolicyTerms();
            } catch (Exception $e) {
                return $this->getErrorResponse("Update Failure", 404, array("message" -> $e->getMessage()));
            }
            return $this->getSuccessResponseWithData(array(), 200);
        }else{
            return $this->getErrorResponse("invalid username.", 401); 
        }
    }
}
