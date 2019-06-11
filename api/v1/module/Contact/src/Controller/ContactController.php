<?php

namespace Contact\Controller;

use Zend\Log\Logger;
use Contact\Model\ContactTable;
use Contact\Model\Contact;
use Contact\Service\ContactService;
use Oxzion\Controller\AbstractApiController;
use Zend\Db\Adapter\AdapterInterface;
use Oxzion\ValidationException;
use Zend\InputFilter\Input;


class ContactController extends AbstractApiController
{

    private $contactService;

    /**
     * @ignore __construct
     */
    public function __construct(ContactTable $table, ContactService $contactService, Logger $log, AdapterInterface $dbAdapter)
    {
        parent::__construct($table, $log, __class__, Contact::class);
        $this->setIdentifierName('contactId');
        $this->contactService = $contactService;
    }

    /**
     * Create Contact API
     * @api
     * @link /contact
     * @method POST
     * @param array $data Array of elements as shown
     * <code> {
     *               id : integer,
     *               name : string,
     *               Fields from Contact
     *   } </code>
     * @return array Returns a JSON Response with Status Code and Created Contact.
     */
    public function create($data)
    {
        $data = $this->params()->fromPost();
        try {
            $count = $this->contactService->createContact($data);
        } catch (ValidationException $e) {
            $response = ['data' => $data, 'errors' => $e->getErrors()];
            return $this->getErrorResponse("Validation Errors", 404, $response);
        }
        if ($count == 0) {
            return $this->getFailureResponse("Failed to create a new entity", $data);
        }
        return $this->getSuccessResponseWithData($data, 201);
    }

    /**
     * Update Contact API
     * @api
     * @link /contact[/:contactId]
     * @method PUT
     * @param array $id ID of Contact to update
     * @param array $data
     * @return array Returns a JSON Response with Status Code and Created Contact.
     */
    public function update($id, $data)
    {
        try {
            $count = $this->contactService->updateContact($id, $data);
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
     * Delete Contact API
     * @api
     * @link /contact[/:contactId]
     * @method DELETE
     * @param $id ID of Contact to Delete
     * @return array success|failure response
     */
    public function delete($id)
    {
        $response = $this->contactService->deleteContact($id);
        if ($response == 0) {
            return $this->getErrorResponse("Contact not found", 404, ['id' => $id]);
        }
        return $this->getSuccessResponse();
    }


    /**
     * GET Contact List API
     * @api
     * @link /contact
     * @method GET
     * @return array $dataget list of Contacts by Owner in the ascending order of firstName
     * <code>status : "success|error",
     *       data :  {
     * string first_name,
     * string last_name,
     * string phone_1,
     * string phone_2,
     * string address_1,
     * string address_2,
     * string email,
     * string company_name,
     * int user_id,
     * int owner_id,
     * int created_id,
     * dateTime date_created (ISO8601 format yyyy-mm-ddThh:mm:ss),
     * dateTime date_modified (ISO8601 format yyyy-mm-ddThh:mm:ss),
     * integer id
     * }
     * </code>
     */
    public function getList()
    {
        $result = $this->contactService->getContactByOwnerId();
        // if ($result == null || empty($result)) {
        //     return $this->getErrorResponse("There is nothing in your contact list!");
        // }
        return $this->getSuccessResponseWithData($result);
    }

    public function getContactListByOrgAction()
    {
        $result = $this->contactService->getContactByOrgId();
        // if ($result == null || empty($result)) {
        //     return $this->getErrorResponse("There are no contacts in your organization!");
        // }
        return $this->getSuccessResponseWithData($result);
    }

    public function getContactsAction()
    {
        $data = $this->params()->fromQuery();
        if(isset($data['filter'])){
            $result = $this->contactService->getContacts($data['column'],$data['filter']);
        } else {
            $result = $this->contactService->getContacts($data['column']);;
        }
        // if ($result == null || empty($result)) {
        //     return $this->getErrorResponse("There is nothing in your contact list!");
        // }
        return $this->getSuccessResponseWithData($result);
    }
}

