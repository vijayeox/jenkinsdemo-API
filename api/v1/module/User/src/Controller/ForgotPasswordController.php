<?php
namespace User\Controller;

use Oxzion\Controller\AbstractApiControllerHelper;
use Oxzion\Service\UserService;
use Zend\Log\Logger;
use Oxzion\Auth\AuthContext;
use Oxzion\Auth\AuthConstants;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Adapter\AdapterInterface;



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
        return $this->getSuccessResponseWithData($responseData, 200);

    }


    // public function updateNewPasswordAction()
    // {
    //     $data = $this->extractPostData();
    //     $userId = AuthContext::get(AuthConstants::USER_ID);
    //     $userDetail = $this->userService->getUser($userId,true);
    //     $resetCode = $data['password_reset_code'];
    //     $newPassword = md5(sha1($data['new_password']));
    //     $confirmPassword = md5(sha1($data['confirm_password']));
    //     $date = $userDetail['password_reset_expiry_date'];
    //     $now = Date("Y-m-d H:i:s");
    //     if ($date < $now) {
    //         return $this->getErrorResponse("The password reset code has expired, please try again", 400);
    //     } elseif ($resetCode !== $userDetail['password_reset_code']) {
    //       return $this->getErrorResponse("You have entered an incorrect code", 400);
    //     } else if (($resetCode == $userDetail['password_reset_code']) && ($newPassword == $confirmPassword)) {
    //         $formData = array('id' => $userId, 'password' => $newPassword, 'password_reset_date' => Date("Y-m-d H:i:s"), 'otp' => null, 'password_reset_code' => null, 'password_reset_expiry_date' => null);
    //         $this->update($userId, $formData);
    //         return $this->getSuccessResponseWithData($data, 200);
    //     } else {
    //         $response = ['id' => $userId];
    //         return $this->getErrorResponse("Failed to Update Password", 404, $response);
    //     }
    // }
}
    