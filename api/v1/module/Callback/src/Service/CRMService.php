<?php
namespace Callback\Service;

use Contact\Service\ContactService;
use Exception;
use Oxzion\ServiceException;
use Oxzion\Service\AbstractService;
use Oxzion\Service\UserService;

class CRMService extends AbstractService
{
    protected $dbAdapter;
    protected $contactService;
    protected $userService;

    public function __construct($config, ContactService $contactService, UserService $userService)
    {
        parent::__construct($config, null);
        $this->contactService = $contactService;
        $this->userService = $userService;
    }
    public function addContact($data)
    {
        $params = array();
        $params['first_name'] = isset($data['firstName']) ? $data['firstName'] : null;
        $params['last_name'] = isset($data['lastName']) ? $data['lastName'] : null;
        try {
            if (isset($data['phones']) && !empty($data['phones'])) {
                $params['phone_1'] = $data['phones'][0];
            }
            $params['email'] = isset($data['email']) ? $data['email'] : null;
            if (isset($data['accounts']) && !empty($data['accounts'])) {
                $params['company_name'] = $data['accounts']['name'];
            }
            if (isset($data['addresses']) && !empty($data['addresses'])) {
                $params['address1'] = $data['addresses'][0]['name'];
                $params['address2'] = isset($data['addresses'][1]['name']) ? $data['addresses'][1]['name'] : null;
            }
            $data['owner']['username'] = isset($data['owner']['username']) ? $data['owner']['username'] : null;
            $data['assignedTo']['username'] = isset($data['assignedTo']['username']) ? $data['assignedTo']['username'] : null;
            $assignedTo = $this->userService->getUserDetailsbyUserName($data['owner']['username'], array('uuid'));
            $owner = $this->userService->getUserDetailsbyUserName($data['assignedTo']['username'], array('id'));
            $assignedTo['uuid'] = isset($assignedTo['uuid']) ? $assignedTo['uuid'] : null;
            $params['owner_id'] = isset($owner['id']) ? $owner['id'] : null;
            $params['created_id'] = isset($owner['id']) ? $owner['id'] : null;
            $params['uuid'] = $assignedTo['uuid'];
            $result = $this->contactService->createContact($params);
            if ($result) {
                return array('body' => $params);
            } else {
                return 0;
            }
        } catch (Exception $e) {
            $this->logger->error($e->getMessage(), $e);
            throw new ServiceException("Could not add contact to the CRM", "could.not.add.contact.to.CRM");
        }
    }
}
