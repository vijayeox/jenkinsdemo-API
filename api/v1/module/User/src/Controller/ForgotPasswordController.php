<?php
namespace User\Controller;

use Oxzion\Controller\AbstractApiControllerHelper;
use Oxzion\Service\UserService;
use Oxzion\Auth\AuthContext;
use Oxzion\Auth\AuthConstants;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Adapter\AdapterInterface;
use Oxzion\ValidationException;
use Oxzion\ServiceException;


class ForgotPasswordController extends AbstractAPIControllerHelper
{

    private $userService;
    
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function forgotPasswordAction()
    {
        $data = $this->extractPostData();
        $username = $data['username'];
        try {
            $responseData = $this->userService->sendResetPasswordCode($username);
            if ($responseData === 0) {
                return $this->getErrorResponse("The username entered does not match your profile username", 404);
            }
        }catch (ValidationException $e) {
                $response = ['data' => $data, 'errors' => $e->getMessage()];
                return $this->getErrorResponse("We do not have an email on your account", 417, $response);
        }catch (Exception $e) {
            $response = ['data' => $data, 'errors' => $e->getErrors()];
            return $this->getErrorResponse("Something went wrong with password reset, please contact your administrator", 500);
        }
        return $this->getSuccessResponseWithData($responseData, 200);

    }
    public function resetPasswordAction()
    {
        $data = $this->extractPostData();
        $newPassword = $data['new_password'];
        $confirmPassword = $data['confirm_password'];
        if($newPassword != $confirmPassword){
            return $this->getErrorResponse("Passwords do not match", 400);
        }
        try{
            $this->userService->resetPassword($data);
        }catch(ServiceException $e){
            return $this->getErrorResponse("The password reset link has expired, please try resetting again", 404);
        }
        return $this->getSuccessResponse("Password reset successful", 200);
        
    }
    

}
    