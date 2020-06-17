<?php
namespace Oxzion\Service;

use Exception;
use Oxzion\Model\OrganizationProfile;
use Oxzion\Model\OrganizationProfileTable;
use Oxzion\ServiceException;
use Oxzion\Service\AbstractService;
use Oxzion\Utils\UuidUtil;
use Oxzion\Auth\AuthConstants;
use Oxzion\Auth\AuthContext;

class OrganizationProfileService extends AbstractService
{
    protected $table;
    protected $modelClass;
    private $addressService;
    /**
     * @ignore __construct
     */
    public function __construct($config, $dbAdapter,AddressService $addressService, OrganizationProfileTable $table)
    {
        parent::__construct($config, $dbAdapter);
        $this->table = $table;
        $this->modelClass = new OrganizationProfile();
        $this->addressService = $addressService;
    }

    public function addOrganizationProfile(&$data)
    {
        $this->logger->info("Adding OrganizationProfile - " . print_r($data, true));
        $orgProfileData = $data;
        $orgProfileData['uuid'] = UuidUtil::uuid();
        $orgProfileData['createdBy'] = AuthContext::get(AuthConstants::USER_ID);
        $orgProfileData['dateCreated'] = date('Y-m-d H:i:s');
        $orgProfileData['labelfile'] = isset($data['labelfile'])?$data['labelfile']:'en';
        $orgProfileData['languagefile'] = isset($data['languagefile'])?$data['languagefile']:'en';
        $addressid = $this->addressService->addAddress($orgProfileData);
        $orgProfileData['address_id'] = $addressid;
        $form = new OrganizationProfile($orgProfileData);
        $form->validate();
        $this->beginTransaction();
        $count = 0;
        try {
            $count = $this->table->save($form);
            if ($count == 0) {
                $this->rollback();
                throw new ServiceException("Failed to add the OrganizationProfile", "failed.add.OrganizationProfile");
            }
            $this->commit();
        } catch (Exception $e) {
            $this->rollback();
            throw $e;
        }
        $data['org_profile_id'] = $this->table->getLastInsertValue();
        return $data;
    }

    public function updateOrganizationProfile($id, $data)
    {
        $obj = $this->table->get($id, array());
        if (is_null($obj)) {
            throw new ServiceException("OrganizationProfile not found", "orgProfile.not.found");
        }
        $org = $obj->toArray();        
        $data['modifiedBy'] = AuthContext::get(AuthConstants::USER_ID);
        $data['dateModified'] = date('Y-m-d H:i:s');
        if (isset($data['address_id'])) {
            $this->addressService->updateAddress($data['address_id'], $data);
        }else{
            $addressid = $this->addressService->addAddress($data);
            $data['address_id'] = $addressid;
        }
        $form = new OrganizationProfile();
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
