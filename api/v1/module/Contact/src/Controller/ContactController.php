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
use Oxzion\Auth\AuthContext;
use Oxzion\Auth\AuthConstants;
use Zend\Db\Sql\Expression;
use Oxzion\Utils\FileUtils;
use Oxzion\Service\AbstractService;
use Oxzion\Utils\BosUtils;

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
        $files = $this->params()->fromFiles('icon');
        $id = $this->params()->fromRoute();

        try {
            if (!isset($id['contactId'])) {
                $count = $this->contactService->createContact($data, $files);
            } else {
                $count = $this->contactService->updateContact($id, $data, $files);
            }
        } catch (ValidationException $e) {
            $response = ['data' => $data, 'errors' => $e->getErrors()];
            return $this->getErrorResponse("Validation Errors", 404, $response);
        }
        if ($count == 0) {
            return $this->getErrorResponse("Failed to create a new entity", 404, $data);
        }
        if ($count == 2) {
            return $this->getErrorResponse("Entity not found for UUID", 404, $id);
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
        $files = $this->params()->fromFiles('icon');
        try {
            $count = $this->contactService->updateContact($id, $data, $files);
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


    public function get($id)
    {
        $result = $this->contactService->getContactsByUuid($id);
        $uuid = $this->contactService->getUuidById($result[0]['owner_id']);
        if (sizeof($result) == 0) {
            return $this->getErrorResponse("Contact not found", 404, ['id' => $id]);
        } else {
            if (isset($result[0]['phone_list']) && $result[0]['phone_list'] != "null" && !empty($result[0]['phone_list'])) {
                $result[0]['phone_list']=json_decode($result[0]['phone_list'], true);
            }
            if (isset($result[0]['email_list']) && $result[0]['email_list'] != "null" && !empty($result[0]['email_list'])) {
                $result[0]['email_list']=json_decode($result[0]['email_list'], true);
            }
            $baseUrl =$this->getBaseUrl();
            if ($result[0]['icon_type']) {
                $userId = $this->contactService->getUuidById($result[0]['user_id']);
                $result[0]['icon'] = $baseUrl . "/user/profile/" . $userId;
            } else {
                $result[0]['icon'] = $baseUrl . "/contact/icon/" . $result[0]["uuid"];
            }
        }
        return $this->getSuccessResponseWithData($result);
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
        $result = $this->contactService->getContacts();
        return $this->getSuccessResponseWithData($result);
    }

    
    public function getContactsAction()
    {
        $data = $this->params()->fromQuery();
        if (isset($data['filter'])) {
            $result = $this->contactService->getContacts($data['column'], $data['filter']);
        } else {
            $result = $this->contactService->getContacts($data['column']);
            ;
        }
        return $this->getSuccessResponseWithData($result);
    }

    public function contactImportAction()
    {
        $columns = ['Given Name','Family Name','E-mail 1 - Type','E-mail 1 - Value','Phone 1 - Type','Phone 1 - Value','Organization 1 - Name','Organization 2 - Title','Location'];
        if (!isset($_FILES['file'])) {
            return $this->getErrorResponse("Add file to import", 404);
        }
        $result = $this->contactService->importContactCSV($_FILES['file']);

        if ($result ==  3) {
            return $this->getErrorResponse("Column Headers donot match...", 404, $columns);
        }

        if ($result == 0) {
            return $this->getErrorResponse("Failed to insert", 404);
        }
        if (is_array($result)) {
            $filename = BosUtils::randomPassword();
            $response = $this->convertToCsv($result, $filename.'.csv');
            return $this->getSuccessStringResponse("Validate and Import the downloaded file", 200, $response);
        }

        return $this->getSuccessResponse("Imported Successfully", 200);
    }


    public function convertToCsv($data, $filename)
    {
        FileUtils::createDirectory('/tmp/oxzion');
        
        $file = '/tmp/oxzion/'.$filename;
        
        try {
            $fp = fopen($file, 'x');
            foreach ($data as $line) {
                fputcsv($fp, $line);
            }
            fclose($fp);
            $fp = fopen($file, 'rb');
            $data = file_get_contents($file);
            fclose($fp);
            return $data;
        } catch (Exception $e) {
            return $this->getErrorResponse("Resource not Found", 404);
        }
    }

    public function contactExportAction()
    {
        $id = $this->extractPostData();

        if (isset($id['contactUuid'])) {
            $result = $this->contactService->exportContactCSV($id['contactUuid']);
        } else {
            $result = $this->contactService->exportContactCSV();
        }
        if (isset($result['data'])) {
            $filename = BosUtils::randomPassword();
            $response = $this->convertToCsv($result['data'], $filename.'.csv');
            return $this->getSuccessStringResponse("Exported CSV Data", 200, $response);
        }
    }
}
