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
        $orgData['created_by'] = AuthContext::get(AuthConstants::USER_ID) ? AuthContext::get(AuthConstants::USER_ID) : 1;
        $orgData['date_created'] = date('Y-m-d H:i:s');
        $orgData['labelfile'] = isset($data['labelfile'])?$data['labelfile']:'en';
        $orgData['languagefile'] = isset($data['languagefile'])?$data['languagefile']:'en';
        $addressid = $this->addressService->addAddress($orgData);
        $orgData['address_id'] = $addressid;
        $form = new Organization($this->table);
        $form->assign($orgData);
        try {
            $this->beginTransaction();
            $form->save();
            $this->commit();
            $temp = $form->getGenerated(true);
            $data['organization_id'] = $temp['id'];
        } catch (Exception $e) {
            $this->rollback();
            throw $e;
        }
    }

    public function updateOrganization($id, $data)
    {
        $form = new Organization($this->table);
        $obj = $form->loadById($id);
        $data['modified_by'] = AuthContext::get(AuthConstants::USER_ID);
        $data['date_modified'] = date('Y-m-d H:i:s');
        if (isset($data['address_id'])) {
            $this->addressService->updateAddress($data['address_id'], $data);
        }else{
            $addressid = $this->addressService->addAddress($data);
            $data['address_id'] = $addressid;
        }
        $form->assign($data);
        try {
            $this->beginTransaction();
            $form->save();
            $this->commit();
        } catch (Exception $e) {
            $this->rollback();
            throw $e;
        }
    }

}
