<?php
namespace User\Controller;

use DeepCopy\f007\FooDateTimeZone;
use Oro\Component\MessageQueue\Transport\Exception\Exception;
use Zend\Db\Sql\Ddl\Column\Datetime;
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
use Oxzion\Service\EmailService;
use Project\Service\ProjectService;



class UserController extends AbstractApiController
{

    private $dbAdapter;

    /**
     * @ignore __construct
     */
    public function __construct(UserTable $table, Logger $log, UserService $userService, AdapterInterface $adapterInterface, EmailService $emailService,ProjectService $projectService)
    {
        parent::__construct($table, $log, __class__, User::class, EmailService::class);
        $this->setIdentifierName('userId');
        $this->userService = $userService;
        $this->emailService = $emailService;
        $this->projectService= $projectService;
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
        $params = $this->params()->fromRoute();
        $id = AuthContext::get(AuthConstants::USER_ID);
        return $this->getUserInfo($id, $params);
    }

    public function getUserInfoByIdAction()
    {
        $params = $this->params()->fromRoute();
        $id = $params['userId'];
        return $this->getUserInfo($id,$params);
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
        $params = $this->params()->fromQuery(); // empty method call
        if(!isset($params['q'])){
            $params['q'] = "";
        }

        if(!isset($params['f'])){
            $params['f'] = "name";
        }
        if(!isset($params['pg'])){
            $params['pg'] = 1;
        }
        if(!isset($params['psz'])){
            $params['psz'] = 20;
        }
        if(!isset($params['sort'])){
            $params['sort'] = "name";
        }
        $result = $this->userService->getUsers($params['q'],$params['f'],$params['pg'],$params['psz'],$params['sort']);
        return $this->getSuccessResponseWithData($result);
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
        if ($response == 0) {
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
        try {
            $userInfo = array();
            $type = (isset($params['type'])) ? ($params['type']) : 'm';
            $options = explode('+', $type);
            if(in_array('a', $options))
            {
                $userInfo = $this->userService->getUser($id);
                $pos = array_search('m', $options);
                if($pos){
                    unset($options[$pos]);
                }
            }
            else
            {
                $userInfo = $this->userService->getUserWithMinimumDetails($id);
            }
            foreach ($options as $key => $value) {
                switch($value) {
                    case "p":
                    $userInfo['privileges'] = $this->userService->getPrivileges($id);
                    break;
                    case "e":
                    $userInfo['emails'] = $this->emailService->getEmailAccountsByUserId($id);
                    break;
                    case "ewp":
                    $userInfo['emails'] = $this->emailService->getEmailAccountsByUserId($id,true);
                    break;
                    case "o":
                    $userInfo['organization'] = $this->userService->getOrganizationByUserId($id);
                    break;
                    case "ap":
                    $userInfo['apps'] = $this->userService->getAppsByUserId($id);
                    break;
                    case "pr":
                    $userInfo['projects'] = $this->projectService->getProjectsOfUserById($id);
                    break;
                }
            }
            if ($userInfo) {
                $baseUrl =$this->getBaseUrl();
                $icon = $userInfo['icon'];
                $userInfo['icon'] = $baseUrl . "/user/profile/" . $userInfo["uuid"];
            }

        } catch (ValidationException $e) {
            $response = ['data' => $data, 'errors' => $e->getErrors()];
            return $this->getErrorResponse("Validation Errors", 404, $response);
        }
        if (($userInfo == 0) || (empty($userInfo))) {
            $response = ['id' => $id];
            return $this->getErrorResponse("Failed to find User", 404, $response);
        }
        return $this->getSuccessResponseWithData($userInfo);
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
    public function getUserAppsAndPrivilegesAction()
    {
        $params = $this->params()->fromRoute();
        try {
            $responseData = $this->userService->getUserAppsAndPrivileges();
        } catch (ValidationException $e) {
            $response = ['data' => $data, 'errors' => $e->getErrors()];
            return $this->getErrorResponse("Validation Errors", 404, $response);
        }
        return $this->getSuccessResponseWithData($responseData, 200);
    }

    public function forgotPasswordAction()
    {
        $data = $this->params()->fromPost();
        $email = $data['email'];
        try {
            $responseData = $this->userService->sendResetPasswordCode($email);
            if ($responseData === 0) {
                return $this->getErrorResponse("The email entered does not match your profile email", 404);
            }
        } catch (Exception $e) {
            $response = ['data' => $data, 'errors' => $e->getErrors()];
            return $this->getErrorResponse("Something went wrong with password reset, please contact your administrator", 500);
        }
        return $this->getSuccessResponseWithData($responseData, 200);

    }


    public function updateNewPasswordAction()
    {
        $data = $this->params()->fromPost();
        $userId = AuthContext::get(AuthConstants::USER_ID);
        $userDetail = $this->userService->getUser($userId);
        $resetCode = $data['password_reset_code'];
        $newPassword = md5(sha1($data['new_password']));
        $confirmPassword = md5(sha1($data['confirm_password']));
        $date = $userDetail['password_reset_expiry_date'];
        $now = Date("Y-m-d H:i:s");
        if ($date < $now) {
            return $this->getErrorResponse("The password reset code has expired, please try again", 400);
        } elseif ($resetCode !== $userDetail['password_reset_code']) {
            return $this->getErrorResponse("You have entered an incorrect code", 400);
        } else if (($resetCode == $userDetail['password_reset_code']) && ($newPassword == $confirmPassword)) {
            $formData = array('id' => $userDetail['id'], 'password' => $newPassword, 'password_reset_date' => Date("Y-m-d H:i:s"), 'otp' => null, 'password_reset_code' => null, 'password_reset_expiry_date' => null);
            $this->update($userDetail['id'], $formData);
            return $this->getSuccessResponseWithData($data, 200);
        } else {
            $response = ['id' => $userDetail['id']];
            return $this->getErrorResponse("Failed to Update Password", 404, $response);
        }

    }

     /**
    * GET List Project of Current User API
    * @api
    * @link /project
    * @method GET
    * @return array $dataget list of Projects by User
    * <code>status : "success|error",
    *       data :  {
                    string name,
                    string description,
                    integer orgid,
                    integer created_by,
                    integer modified_by,
                    dateTime date_created (ISO8601 format yyyy-mm-ddThh:mm:ss),
                    dateTime date_modified (ISO8601 format yyyy-mm-ddThh:mm:ss),
                    boolean isdeleted,
                    integer id,
                    }
    * </code>
    */
    public function getUserProjectAction(){
        $params = $this->params()->fromRoute();
        $id=$params['userId'];
        $result = $this->projectService->getProjectsOfUserById($id);
        return $this->getSuccessResponseWithData($result);
    }
}