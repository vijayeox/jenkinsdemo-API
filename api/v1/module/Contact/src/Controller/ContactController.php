<?php

namespace Contact\Controller;

use Contact\Model\Contact;
use Contact\Model\ContactTable;
use Contact\Service\ContactService;
use Exception;
use Oxzion\Controller\AbstractApiController;
use Oxzion\ServiceException;
use Oxzion\Utils\BosUtils;
use Oxzion\Utils\FileUtils;
use Oxzion\ValidationException;
use Zend\Db\Adapter\AdapterInterface;

class ContactController extends AbstractApiController
{
    private $contactService;

    /**
     * @ignore __construct
     */
    public function __construct(ContactTable $table, ContactService $contactService, AdapterInterface $dbAdapter)
    {
        parent::__construct($table, __class__, Contact::class);
        $this->setIdentifierName('contactId');
        $this->contactService = $contactService;
        $this->log = $this->getLogger();
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
        $this->log->info(__CLASS__ . "-> Create Contact - " . json_encode($data, true));
        $count = 0;
        try {
            if (!isset($id['contactId'])) {
                $this->contactService->createContact($data, $files);
            } else {
                $this->contactService->updateContact($id, $data, $files);
            }
            return $this->getSuccessResponseWithData($data, 201);
        } catch (Exception $e) {
            $this->log->error($e->getMessage(), $e);
            return $this->exceptionToResponse($e);
        } 
        
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
            $this->contactService->updateContact($id, $data, $files);
        } catch (Exception $e) {
            $this->log->error($e->getMessage(), $e);
            return $this->exceptionToResponse($e);
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
        try{
            $this->contactService->deleteContact($id);
            return $this->getSuccessResponse();
        }catch (Exception $e) {
            $this->log->error($e->getMessage(), $e);
            return $this->exceptionToResponse($e);
        } 
    }

    public function get($id)
    {
        $this->log->info(__CLASS__ . "-> Get Contact- " . json_encode($id, true));
        try {
            $result = $this->contactService->getContactsByUuid($id);
            $uuid = $this->contactService->getUuidById($result[0]['owner_id']);
            if (empty($result)) {
                return $this->getErrorResponse("Contact not found", 404, ['id' => $id]);
            } else {
                if (isset($result[0]['phone_list']) && $result[0]['phone_list'] != "null" && !empty($result[0]['phone_list'])) {
                    $result[0]['phone_list'] = json_decode($result[0]['phone_list'], true);
                }
                if (isset($result[0]['email_list']) && $result[0]['email_list'] != "null" && !empty($result[0]['email_list'])) {
                    $result[0]['email_list'] = json_decode($result[0]['email_list'], true);
                }
                $baseUrl = $this->getBaseUrl();
                if ($result[0]['icon_type']) {
                    $userId = $this->contactService->getUuidById($result[0]['user_id']);
                    $result[0]['icon'] = $baseUrl . "/user/profile/" . $userId;
                } else {
                    $result[0]['icon'] = $baseUrl . "/contact/icon/" . $result[0]["uuid"];
                }
            }
        } catch (Exception $e) {
            $this->log->error($e->getMessage(), $e);
            return $this->exceptionToResponse($e);
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
        try{
            $result = $this->contactService->getContacts();
            return $this->getSuccessResponseWithData($result);
        }catch (Exception $e) {
            $this->log->error($e->getMessage(), $e);
            return $this->exceptionToResponse($e);
        } 
    }

    public function getContactsAction()
    {
        $data = $this->params()->fromQuery();
        $this->log->info(__CLASS__ . "-> Get Contacts - " . json_encode($data, true));
        try{
            if (isset($data['filter'])) {
                $result = $this->contactService->getContacts($data['column'], $data['filter']);
            } else {
                $result = $this->contactService->getContacts($data['column']);

            }
            return $this->getSuccessResponseWithData($result);
        }catch (Exception $e) {
            $this->log->error($e->getMessage(), $e);
            return $this->exceptionToResponse($e);
        } 
    }

    public function contactImportAction()
    {
        $columns = ['Given Name', 'Family Name', 'E-mail 1 - Type', 'E-mail 1 - Value', 'Phone 1 - Type', 'Phone 1 - Value', 'Organization 1 - Name', 'Organization 2 - Title', 'Address 1 - Street', 'Address 1 - Extended Address', 'Address 1 - City', 'Address 1 - Region', 'Address 1 - Country', 'Address 1 - Postal Code'];
        $this->log->info(__CLASS__ . "-> Imporing Contacts - " . json_encode($_FILES['file'], true));
        try {
            if (!isset($_FILES['file'])) {
                return $this->getErrorResponse("Add file to import", 404);
            }
            $result = $this->contactService->importContactCSV($_FILES['file']);
            $filename = BosUtils::randomPassword();
            $response = $this->convertToCsv($result, $filename . '.csv');
            return $this->getSuccessStringResponse("Validate and Import the downloaded file", 200, $response);
        } catch (Exception $e) {
            $this->log->error($e->getMessage(), $e);
            return $this->exceptionToResponse($e);
        } 
        return $this->getSuccessResponse("Imported Successfully", 200);
    }

    public function convertToCsv($data, $filename)
    {
        if(empty($data)){
            return "";
        }
        FileUtils::createDirectory('/tmp/oxzion');
        $file = '/tmp/oxzion/' . $filename;
        $fp = fopen($file, 'x');
        try {
            foreach ($data as $line) {
                fputcsv($fp, $line);
            }
            fclose($fp);
            $fp = fopen($file, 'rb');
            $data = file_get_contents($file);
            return $data;
        } catch (Exception $e) {
            $this->log->error($e->getMessage(), $e);
            return $this->exceptionToResponse($e);
        } finally{
            fclose($fp);
        }
    }

    public function contactExportAction()
    {
        $id = $this->extractPostData();
        try{
            if (isset($id)) {
                $result = $this->contactService->exportContactCSV($id);
            } else {
                $result = $this->contactService->exportContactCSV();
            }
            if (isset($result['data'])) {
                $filename = BosUtils::randomPassword();
                $response = $this->convertToCsv($result['data'], $filename . '.csv');
                return $this->getSuccessStringResponse("Exported CSV Data", 200, $response);
            }
        }catch (Exception $e) {
            $this->log->error($e->getMessage(), $e);
            return $this->exceptionToResponse($e);
        } 
    }

    public function contactsDeleteAction()
    {
        $data = $this->extractPostData();
        try {
            $response = $this->contactService->mutipleContactsDelete($data);
        } catch (Exception $e) {
            $this->log->error($e->getMessage(), $e);
            return $this->exceptionToResponse($e);
        } 
        return $this->getSuccessResponse();
    }
}
