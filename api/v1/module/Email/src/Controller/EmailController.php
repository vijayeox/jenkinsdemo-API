<?php

namespace Email\Controller;

use Exception;
use Oxzion\Controller\AbstractApiController;
use Oxzion\Model\Email;
use Oxzion\Model\EmailTable;
use Oxzion\Service\EmailService;
use Oxzion\ValidationException;
use Zend\Db\Adapter\AdapterInterface;

class EmailController extends AbstractApiController
{
    /**
     * @var EmailService Instance of Email Service
     */
    private $emailService;
    /**
     * @ignore __construct
     */
    public function __construct(EmailTable $table, EmailService $emailService, AdapterInterface $dbAdapter)
    {
        parent::__construct($table, Email::class);
        $this->setIdentifierName('emailId');
        $this->emailService = $emailService;
        $this->log = $this->getLogger();
    }
    /**
     * GET Email API
     * @api
     * @link /email/emailid
     * @method GET
     * @return array $dataget of Emails by User
     * <code>status : "success|error",
     *       data :  {
     * string email,
     * string username,
     * string host,
     * integer isdefault,
     * integer userid,
     * integer id
     * }
     * </code>
     */
    public function get($id)
    {
        $result = $this->emailService->getEmailAccountById($id);
        if ($result == 0 || empty($result)) {
            return $this->getErrorResponse("File not found", 404, ['id' => $id]);
        }
        return $this->getSuccessResponseWithData($result);
    }

    /**
     * Create Email API
     * @api
     * @link /email
     * @method POST
     * @param array $data Array of elements as shown</br>
     * <code> name : string,
     * description : string,
     * </code>
     * @return array Returns a JSON Response with Status Code and Created Email.</br>
     * <code> status : "success|error",
     *        data : array Created Email Object
     * string email,
     * string username,
     * string host,
     * integer isdefault,
     * integer userid,
     * integer id
     * </code>
     */
    public function create($data)
    {
        $this->log->info(__CLASS__ . "->Create Email Account - " . json_encode($data, true));
        try {
            $count = $this->emailService->createOrUpdateEmailAccount($data);
            if ($count == 0) {
                return $this->getFailureResponse("Failed to create a new entity", $data);
            }
        } catch (ValidationException $e) {
            $response = ['data' => $data, 'errors' => $e->getErrors()];
            return $this->getErrorResponse("Validation Errors", 404, $response);
        } catch (Exception $e) {
            $this->log->error($e->getMessage(), $e);
        }
        unset($data['password']);
        return $this->getSuccessResponseWithData($data, 201);
    }

    /**
     * ? The below functions are commented out, not sure why. We need to revisit them
     **/

    // public function update($id, $data)
    // {
    //     try {
    //         $count = $this->emailService->updateEmailAccount($id, $data);
    //     } catch (ValidationException $e) {
    //         $response = ['data' => $data, 'errors' => $e->getErrors()];
    //         return $this->getErrorResponse("Validation Errors", 404, $response);
    //     }
    //     unset($data['password']);
    //     if ($count == 0) {
    //         return $this->getErrorResponse("Entity not found for id - $id", 404);
    //     }
    //     return $this->getSuccessResponseWithData($data, 200);
    // }

    // public function delete($id)
    // {
    //     $response = $this->emailService->deleteEmail($id);
    //     if ($response == 0) {
    //         return $this->getErrorResponse("Email not found", 404, ['id' => $id]);
    //     }
    //     return $this->getSuccessResponse();
    // }

    /**
     * GET List Email API
     * @api
     * @link /email
     * @method GET
     * @return array $dataget list of Emails by User
     * <code>status : "success|error",
     *       data :  {
     * string email,
     * string username,
     * string host,
     * integer isdefault,
     * integer userid,
     * integer id
     * }
     * </code>
     */
    public function getList()
    {
        $result = $this->emailService->getEmailAccountsByUserId();
        return $this->getSuccessResponseWithData($result);
    }

    /**
     * GET default email API
     * @api
     * @link /email/:emailId/default
     * @method get
     * @param json object of userid
     * @return array $dataget list of default emails
     * <code>status : "success|error",
     *       data : all default email data passed back in json format
     * </code>
     */
    public function emailDefaultAction()
    {
        $id = $this->params()->fromRoute()['emailId'];
        $this->log->info(__CLASS__ . "-> Default email action - " . json_encode($id, true));

        try {
            $responseData = $this->emailService->emailDefault($id);
        } catch (ValidationException $e) {
            $response = ['data' => $responseData, 'errors' => $e->getErrors()];
            return $this->getErrorResponse("Validation Errors", 404, $response);
        } catch (Exception $e) {
            $this->log->error($e->getMessage(), $e);
            return $this->getErrorResponse("Unknown Exception", 500);
        }
        if ($responseData == 0) {
            return $this->getErrorResponse("Entity not found for id - $id", 404);
        }
        return $this->getSuccessResponseWithData($responseData, 200);
    }

    /**
     * Delete Email API
     * @api
     * @link /email[/:emailId]
     * @method DELETE
     * @param $id ID of Email to Delete
     * @return array success|failure response
     */
    public function deleteEmailAction()
    {
        $email = $this->params()->fromRoute()['address'];
        try {
            $responseData = $this->emailService->deleteEmail($email);
        } catch (ValidationException $e) {
            $response = ['data' => $data, 'errors' => $e->getErrors()];
            return $this->getErrorResponse("Validation Errors", 404, $response);
        }
        if ($responseData == 0) {
            return $this->getErrorResponse("Entity not found", 404);
        }
        return $this->getSuccessResponseWithData($responseData, 200);
    }

    /**
     * Update Email API
     * @api
     * @link /email[/:emailId]
     * @method PUT
     * @param array $id ID of Email to update
     * @param array $data
     * <code> status : "success|error",
     *        data : {
     * string email,
     * string username,
     * string host,
     * integer isdefault,
     * integer userid,
     * integer id
     * }
     * </code>
     * @return array Returns a JSON Response with Status Code and Created Email.
     */
    public function updateEmailAction()
    {
        $request = $this->getRequest();
        $data = $this->processBodyContent($request);
        $email = $this->params()->fromRoute()['address'];
        try {
            $responseData = $this->emailService->updateEmail($email, $data);
        } catch (ValidationException $e) {
            $response = ['data' => $data, 'errors' => $e->getErrors()];
            return $this->getErrorResponse("Validation Errors", 404, $response);
        }
        if ($responseData == 0) {
            return $this->getErrorResponse("Entity not found", 404);
        }
        return $this->getSuccessResponseWithData($responseData, 200);
    }
}
