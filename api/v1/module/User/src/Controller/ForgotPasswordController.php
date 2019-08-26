<?php
namespace User\Controller;

use Oxzion\Controller\AbstractApiControllerHelper;
use Oxzion\Service\UserService;
use Zend\Log\Logger;
use Oxzion\Auth\AuthContext;
use Oxzion\Auth\AuthConstants;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Adapter\AdapterInterface;
use Oxzion\ServiceException;



class ForgotPasswordController extends AbstractAPIControllerHelper
{

    private $userService;
    protected $log;
    
    public function __construct(Logger $log,UserService $userService)
    {
        $this->userService = $userService;
        $this->log = $log;
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
        } catch (Exception $e) {
            $response = ['data' => $data, 'errors' => $e->getErrors()];
            return $this->getErrorResponse("Something went wrong with password reset, please contact your administrator", 500);
        }
        return $this->getSuccessResponseWithData($data, 200);

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
    