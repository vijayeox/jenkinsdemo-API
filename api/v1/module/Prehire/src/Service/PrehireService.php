<?php
namespace Prehire\Service;

use Exception;
use Oxzion\EntityNotFoundException;
use Oxzion\ServiceException;
use Oxzion\OxServiceException;
use Oxzion\Service\AbstractService;
use Prehire\Model\Prehire;
use Prehire\Model\PrehireTable;

class PrehireService extends AbstractService
{
    private $table;
    /**
     * @ignore __construct
     */

    public function __construct($config, $dbAdapter, PrehireTable $table)
    {
        parent::__construct($config, $dbAdapter);
        $this->table = $table;
    }


    public function createRequest(&$data)
    {
        $form = new Prehire($this->table);
        $data['user_id'] = $this->getIdFromUuid('ox_user',$data['referenceId']);
        $data['implementation'] = $data['implementation'];
        $form->assign($data);
        try {
            $this->beginTransaction();
            $form->save();
            $this->commit();
        } catch (Exception $e) {
            $this->rollback();
            throw $e;
        }
        return $form->getGenerated();
    }

    public function updateRequest($uuid, $data)
    {
        $form = new Prehire($this->table);
        $form->loadByUuid($uuid);
        $form->assign($data);
        try {
            $this->beginTransaction();
            $form->save();
            $this->commit();
        } catch (Exception $e) {
            $this->rollback();
            throw $e;
        }
        return $form->getGenerated();
    }
    
    public function deleteRequest($uuid)
    {
        try {
            $this->beginTransaction();
            $sql = $this->getSqlObject();
            $delete = $sql->delete('ox_prehire');
            $delete->where(['uuid' => $uuid]);
            $result = $this->executeUpdate($delete);
            if ($result->getAffectedRows() == 0) {
                throw new ServiceException("Prehire entry not found", "entry.not.found", OxServiceException::ERR_CODE_NOT_FOUND);
            }
            $this->commit();
        } catch (Exception $e) {
            $this->rollback();
            $this->logger->error($e->getMessage(), $e);
            throw $e;
        }
     
    }

    public function getPrehireRequestData($uuid) {
        try {
            $sql = "SELECT p.uuid as uuid,u.uuid as user_id,p.request_type,p.request,p.implementation,p.date_created,p.date_modified 
            from ox_prehire p
            inner join ox_user u on p.user_id = u.uuid
            where p.uuid = :uuid";
            $params = ['uuid' => $uuid];
            $response = $this->executeQueryWithBindParameters($sql, $params)->toArray();
            if (count($response) == 0) {
                throw new EntityNotFoundException('The uuid $uuid provided does not exist',['entity' => 'ox_widget', 'uuid' => $uuid]);
            }
            return $response[0];
        } catch (Exception $e) {
            throw $e;
        }
    }
}
