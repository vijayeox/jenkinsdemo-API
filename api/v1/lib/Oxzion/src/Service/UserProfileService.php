<?php
namespace Oxzion\Service;

use Exception;
use Oxzion\Model\UserProfile;
use Oxzion\Model\UserProfileTable;
use Oxzion\ServiceException;
use Oxzion\Service\AbstractService;
use Oxzion\Utils\UuidUtil;
use Oxzion\Auth\AuthConstants;
use Oxzion\Auth\AuthContext;

class UserProfileService extends AbstractService
{
    protected $table;
    protected $modelClass;
    private $addressService;
    /**
     * @ignore __construct
     */
    public function __construct($config, $dbAdapter,AddressService $addressService, UserProfileTable $table)
    {
        parent::__construct($config, $dbAdapter);
        $this->table = $table;
        $this->modelClass = new UserProfile();
        $this->addressService = $addressService;
    }

    public function addUserProfile(&$data)
    {
        $this->logger->info("Adding UserProfile - " . print_r($data, true));
        $userProfileData = $data;
        $userProfileData['uuid'] = UuidUtil::uuid();
        $userProfileData['created_by'] = AuthContext::get(AuthConstants::USER_ID);
        $userProfileData['date_created'] = date('Y-m-d H:i:s');
        $addressid = $this->addressService->addAddress($userProfileData);
        $userProfileData['address_id'] = $addressid;
        $userProfileData['org_id'] = $userProfileData['orgid'];
        $form = new UserProfile($userProfileData);
        $form->validate();
        $this->beginTransaction();
        $count = 0;
        try {
            $count = $this->table->save($form);
            if ($count == 0) {
                $this->rollback();
                throw new ServiceException("Failed to add the UserProfile", "failed.add.UserProfile");
            }
            $this->commit();
        } catch (Exception $e) {
            $this->rollback();
            throw $e;
        }
        $data['user_profile_id'] = $this->table->getLastInsertValue();
        return $data;
    }

    public function updateUserProfile($id, $data)
    {
        $obj = $this->table->get($id, array());
        if (is_null($obj)) {
            throw new ServiceException("UserProfile not found", "userProfile.not.found");
        }      
        unset($data['id']);
        unset($data['uuid']);
        $this->logger->info("USER PROFILE DATA--------\n".print_r($data,true));
        $updateProfile = $data;
        $updateProfile['modified_by'] = AuthContext::get(AuthConstants::USER_ID);
        $updateProfile['date_modified'] = date('Y-m-d H:i:s');
        $updateProfile['org_id'] = $updateProfile['orgid'];
        if (isset($updateProfile['address_id'])) {
            $this->addressService->updateAddress($updateProfile['address_id'], $updateProfile);
        }else{
            $addressid = $this->addressService->addAddress($updateProfile);
            $data['address_id'] = $addressid;
        }
        $form = new UserProfile();
        $changedArray = array_merge($obj->toArray(), $updateProfile);
        $this->logger->info("PROFILE FORM changedArray".print_r($changedArray,true));
        $form->exchangeArray($changedArray);
        $this->beginTransaction();
        $count = 0;
        try {
            $this->logger->info("PROFILE FORM".print_r($form,true));
            $count = $this->table->save($form);
            $this->commit();
        } catch (Exception $e) {
            $this->rollback();
            throw $e;
        }
    }

}
