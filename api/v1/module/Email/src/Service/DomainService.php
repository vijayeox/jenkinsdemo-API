<?php
namespace Email\Service;

use Email\Model\Domain;
use Email\Model\DomainTable;
use Exception;
use Oxzion\Auth\AuthConstants;
use Oxzion\Auth\AuthContext;
use Oxzion\ServiceException;
use Oxzion\Service\AbstractService;

class DomainService extends AbstractService
{
    private $table;

    public function __construct($config, $dbAdapter, DomainTable $table)
    {
        parent::__construct($config, $dbAdapter);
        $this->table = $table;
    }

    public function createDomain($data)
    {
        $form = new Domain();
        $checkDuplicateID = $this->checkFieldValueExists('name', $data['name']);
        if ($checkDuplicateID === 0) {
            $data['created_by'] = AuthContext::get(AuthConstants::USER_ID);
            $data['date_created'] = date('Y-m-d H:i:s');
            $form->exchangeArray($data);
            $form->validate();
            $this->beginTransaction();
            $this->logger->info("Data modification before Domain creation- " . json_encode($data, true));
            $count = 0;
            try {
                $count = $this->table->save($form);
                if ($count == 0) {
                    $this->rollback();
                    throw new ServiceException("Could not create the domain", 'could.not.create');
                }
                $id = $this->table->getLastInsertValue();
                $data['id'] = $id;
                $this->commit();
            } catch (Exception $e) {
                $this->rollback();
                throw $e;
            }
        } else {
            return $this->updateDomain($checkDuplicateID, $data);
        }
        return $count;
    }

    public function updateDomain($id, &$data)
    {
        $obj = $this->table->get($id, array());
        if (is_null($obj)) {
            return 0;
        }
        $form = new Domain();
        $data = array_merge($obj->toArray(), $data);
        $data['modified_id'] = AuthContext::get(AuthConstants::USER_ID);
        $data['date_modified'] = date('Y-m-d H:i:s');
        $form->exchangeArray($data);
        $form->validate();
        $this->logger->info("Data modification before Domain update- " . json_encode($data, true));
        $count = 0;
        try {
            $count = $this->table->save($form);
            if ($count == 0) {
                $this->rollback();
                throw new ServiceException("Could not update the domain", 'could.not.update');
            }
        } catch (Exception $e) {
            $this->rollback();
            throw $e;
        }
        return $count;
    }

    public function deleteDomain($domainName)
    {
        $id = 0;
        try {
            if ($domainName) {
                $queryString = "select id, name from ox_email_domain";
                $where = "where name = '" . $domainName . "'";
                $order = "order by id";
                $resultSet = $this->executeQuerywithParams($queryString, $where, null, $order);
                if ($resultSet) {
                    $domain = array_column($resultSet->toArray(), 'name', 'id');
                    foreach ($domain as $key => $value) {
                        if ($domainName === $value) {
                            $id = $key;
                        }
                    }
                }
                if ($id) {
                    $response = $this->deleteDomainAccount($id);
                    return array($response);
                }
            }
        } catch (Exception $e) {
            throw $e;
        }
        return 0;
    }

    public function deleteDomainAccount($id)
    {
        $count = 0;
        try {
            $count = $this->table->delete($id);
            if ($count == 0) {
                return 0;
            }
        } catch (Exception $e) {
            return 0;
        }
        return $count;
    }

    public function checkFieldValueExists($fieldName, $fieldValue)
    {
        if ($fieldValue) {
            $queryString = "select id, name from ox_email_domain";
            $where = "where $fieldName = '" . $fieldValue . "'";
            $resultSet = $this->executeQuerywithParams($queryString, $where, null, null);
            if ($resultSet) {
                $domain = array_column($resultSet->toArray(), 'name', 'id');
                foreach ($domain as $key => $value) {
                    if ($fieldValue === $value) {
                        return $id = $key;
                    }
                }
            }
            return 0;
        }
    }
}
