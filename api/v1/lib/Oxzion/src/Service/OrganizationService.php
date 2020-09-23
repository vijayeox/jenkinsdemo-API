<?php
namespace Oxzion\Service;

use Exception;
use Oxzion\Model\Organization;
use Oxzion\Model\OrganizationTable;
use Oxzion\ServiceException;
use Oxzion\Service\AbstractService;
use Oxzion\Utils\UuidUtil;
use Oxzion\Auth\AuthConstants;
use Oxzion\Auth\AuthContext;

class OrganizationService extends AbstractService
{
    protected $table;
    private $addressService;
    /**
     * @ignore __construct
     */
    public function __construct($config, $dbAdapter,AddressService $addressService, OrganizationTable $table)
    {
        parent::__construct($config, $dbAdapter);
        $this->table = $table;
        $this->addressService = $addressService;
    }

    public function addOrganization(&$data)
    {
        $this->logger->info("Adding Organization - " . print_r($data, true));
        $orgData = $data;
        $orgData['uuid'] = UuidUtil::uuid();
        $orgData['created_by'] = AuthContext::get(AuthConstants::USER_ID) ? AuthContext::get(AuthConstants::USER_ID) : 1;
        $orgData['date_created'] = date('Y-m-d H:i:s');
        $orgData['labelfile'] = isset($data['labelfile'])?$data['labelfile']:'en';
        $orgData['languagefile'] = isset($data['languagefile'])?$data['languagefile']:'en';
        $addressid = $this->addressService->addAddress($orgData);
        $orgData['address_id'] = $addressid;
        $form = new Organization($orgData);
        $form->validate();
        $this->beginTransaction();
        $count = 0;
        try {
            $count = $this->table->save($form);
            if ($count == 0) {
                $this->rollback();
                throw new ServiceException("Failed to add the Organization", "failed.add.Organization");
            }
            $this->commit();
        } catch (Exception $e) {
            $this->rollback();
            throw $e;
        }
        $data['organization_id'] = $this->table->getLastInsertValue();
        return $count;
    }

    public function updateOrganization($id, $data)
    {
        $obj = $this->table->get($id, array());
        if (is_null($obj)) {
            throw new ServiceException("Organization not found", "organization.not.found");
        }
        $org = $obj->toArray();        
        $data['modified_by'] = AuthContext::get(AuthConstants::USER_ID);
        $data['date_modified'] = date('Y-m-d H:i:s');
        if (isset($data['address_id'])) {
            $this->addressService->updateAddress($data['address_id'], $data);
        }else{
            $addressid = $this->addressService->addAddress($data);
            $data['address_id'] = $addressid;
        }
        $form = new Organization();
        $changedArray = array_merge($obj->toArray(), $data);
        $form->exchangeArray($changedArray);
        $this->beginTransaction();
        $count = 0;
        try {
            $count = $this->table->save($form);
            $this->commit();
        } catch (Exception $e) {
            throw $e;
        }
    }

}
