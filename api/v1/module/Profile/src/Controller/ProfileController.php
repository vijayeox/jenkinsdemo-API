<?php

namespace Profile\Controller;

use Exception;
use Oxzion\Model\Profile;
use Oxzion\Model\ProfileTable;
use Oxzion\Service\ProfileService;
use Oxzion\Controller\AbstractApiController;
use Oxzion\Service\AccountService;
use Zend\Db\Adapter\AdapterInterface;
use Oxzion\Auth\AuthConstants;
use Oxzion\Auth\AuthContext;


class ProfileController extends AbstractApiController
{
    private $profileService;
    private $accountService;

    /**
     * @ignore __construct
     */
    public function __construct(ProfileTable $table, ProfileService $profileService, AdapterInterface $dbAdapter, AccountService $accountService)
    {
        parent::__construct($table, Profile::class);
        $this->setIdentifierName('profileId');
        $this->profileService = $profileService;
        $this->accountService = $accountService;
        $this->log = $this->getLogger();
    }

    /**
     * @api
     * @link /profile/getProfilesforUser/:userId
     * @method GET
     * @param $id ID of Profile
     * @return array $data
     * @return array Returns a JSON Response with Status Code and Created Profile.
     */

     
    public function getProfileforUserAction()
    {
        $params = $this->params()->fromRoute();
        $data = $this->params()->fromQuery();
        if (isset($params['userId'])) {
            $userId = $this->getIdFromUuid('ox_user', $params['userId']);
        } else {
            $userId = AuthContext::get(AuthConstants::USER_ID);
        }
        try {
            $profileList = $this->profileService->getProfilesforUser($userId, $data);
        } catch (Exception $e) {
            $this->log->error($e->getMessage(), $e);
            return $this->exceptionToResponse($e);
        }
        return $this->getSuccessResponseWithData($profileList);
    }


    /**
     * Create Profile API
     * @api
     * @link /profile
     * @method POST
     * @param array $data Array of elements as shown
     * <code> {
     *               id : integer,
     *               name : string,
     *               Fields from Profile
     *   } </code>
     * @return array Returns a JSON Response with Status Code and Created Profile.
     */
    public function create($data)
    {
        $id = $this->params()->fromRoute();
        $this->log->info(__CLASS__ . "-> Create Profile - " . json_encode($data, true));
        try {
            if (!isset($id['profileId'])) {
                $this->profileService->addProfile($data);
            } else {
                $this->profileService->updateProfile($id['profileId'], $data);
            }
        } catch (Exception $e) {
            $this->log->error($e->getMessage(), $e);
            return $this->exceptionToResponse($e);
        }
        return $this->getSuccessResponseWithData($data, 201);
    }

    /**
     * Update Profile API
     * @api
     * @link /profile[/:profileId]
     * @method PUT
     * @param array $id ID of Profile to update
     * @param array $data
     * @return array Returns a JSON Response with Status Code and Created Profile.
     */
    public function update($id, $data)
    {
        $this->log->info(__CLASS__ . "-> Update Profile - " . json_encode($data, true));
        try {
            $this->profileService->updateProfile($id, $data);
        } catch (Exception $e) {
            $this->log->error($e->getMessage(), $e);
            return $this->exceptionToResponse($e);
        }
        return $this->getSuccessResponseWithData($data, 200);
    }





}
