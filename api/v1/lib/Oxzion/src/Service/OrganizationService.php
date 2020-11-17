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
        $orgData['parent_id'] = $this->getParentId($orgData);
        $form = new Organization($this->table);
        $form->assign($orgData);
        try {
            $this->beginTransaction();
            $form->save();
            $temp = $form->getGenerated(true);
            $data['organization_id'] = $temp['id'];
            $this->processOrgHeirarchy($temp['id'], $form->parent_id, null);
            $this->commit();
        } catch (Exception $e) {
            $this->rollback();
            throw $e;
        }
    }

    private function getParentId($orgData){
        $id = null;
        if(isset($orgData['parentId']) && !empty($orgData['parentId'])){
            $id =  $this->getIdFromUuid('ox_organization', $orgData['parentId']);
        }
        return $id;
    }

    public function updateOrganization($id, $data)
    {
        $form = new Organization($this->table);
        $form->loadById($id);
        $data['modified_by'] = AuthContext::get(AuthConstants::USER_ID);
        $data['date_modified'] = date('Y-m-d H:i:s');
        if (isset($data['address_id'])) {
            $this->addressService->updateAddress($data['address_id'], $data);
        }else{
            $addressid = $this->addressService->addAddress($data);
            $data['address_id'] = $addressid;
        }
        $oldParentId = $form->parent_id;
        $data['parent_id'] = $this->getParentId($data);
        $form->assign($data);
        try {
            $this->beginTransaction();
            $form->save();
            $this->processOrgHeirarchy($form->id, $form->parent_id, $oldParentId);
            $this->commit();
        } catch (Exception $e) {
            $this->rollback();
            throw $e;
        }
    }

    private function processOrgHeirarchy($id, $newParentId, $oldParentId){
        if($oldParentId == $newParentId ) {
            return;
        }
        $this->removeOrgHeirarchy($id);
        $this->addOrgHeirarchy($id, $newParentId);
    }

    private function removeOrgHeirarchy($id){
        $query = "DELETE from ox_org_heirarchy where child_id = :childId";
        $params = ['childId' => $id];
        $this->executeUpdateWithBindParameters($query, $params);
    }

    private function addOrgHeirarchy($id, $parentId){
        $mainOrgId = $id;
        $params = ['parentId' => $parentId];
        if($parentId){
            $query = "SELECT main_org_id from ox_org_heirarchy where parent_id = :parentId OR child_id = :parentId";
            $result = $this->executeQueryWithBindParameters($query, $params)->toArray();
            if(!empty($result)){
                $mainOrgId = $result[0]['main_org_id'];
            }
        }
        $query = "INSERT into ox_org_heirarchy (main_org_id, parent_id, child_id) 
                    VALUES(:mainOrgId, :parentId, :childId)";
        $params['mainOrgId'] = $mainOrgId;
        $params['childId'] = $id;
        $this->executeUpdateWithBindParameters($query, $params);
    }
}
