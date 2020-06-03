<?php
namespace Oxzion\Service;

use Exception;
use Oxzion\Auth\AuthConstants;
use Oxzion\Auth\AuthContext;
use Oxzion\EntityNotFoundException;
use Oxzion\Messaging\MessageProducer;
use Oxzion\Model\File;
use Oxzion\Model\FileTable;
use Oxzion\ServiceException;
use Oxzion\Service\FieldService;
use Oxzion\Utils\UuidUtil;
use Oxzion\Utils\ArrayUtils;
use Oxzion\Model\FileAttachment;
use Oxzion\Model\FileAttachmentTable;
use Oxzion\Utils\FileUtils;

class FileService extends AbstractService
{
    protected $fieldService;
    protected $fieldDetails;
    /**
     * @ignore __construct
     */
    public function __construct($config, $dbAdapter, FileTable $table, FormService $formService, MessageProducer $messageProducer, FieldService $fieldService,FileAttachmentTable $attachmentTable)
    {
        parent::__construct($config, $dbAdapter);
        $this->messageProducer = $messageProducer;
        $this->table = $table;
        $this->config = $config;
        $this->dbAdapter = $dbAdapter;
        $this->fieldService = $fieldService;
        $this->fieldDetails=[];
        $this->attachmentTable = $attachmentTable;
        // $emailService = new EmailService($config, $dbAdapter, Oxzion\Model\Email);
    }

    /**
     * Create File Service
     * @method createFile
     * @param array $data Array of elements as shown
     * <code> {
     *               id : integer,
     *               name : string,
     *               formid : integer,
     *               Fields from Form
     *   } </code>
     * @return array Returns a JSON Response with Status Code and Created File.
     */
    public function createFile(&$data, $ensureDir = false)
    {
        $baseFolder = $this->config['APP_DOCUMENT_FOLDER'];
        $this->logger->info("Data CreateFile- " . json_encode($data));
        if (isset($data['uuid'])) {
            $fileId = $this->getIdFromUuid('ox_file', $data['uuid']);
            if ($fileId) {
                unset($data['uuid']);
            }
        }
        if (isset($data['form_id'])) {
            $formId = $this->getIdFromUuid('ox_form', $data['form_id']);
        } else {
            $formId = null;
        }

        $data['uuid'] = $uuid = isset($data['uuid']) && UuidUtil::isValidUuid($data['uuid']) ? $data['uuid'] : UuidUtil::uuid();

        $entityId = isset($data['entity_id']) ? $data['entity_id'] : null;

        if (!$entityId && isset($data['entity_name'])) {
            $select = "select id from ox_app_entity where name = :entityName";
            $params = array('entityName' => $data['entity_name']);
            $result = $this->executeQuerywithBindParameters($select, $params)->toArray();
            if (count($result) > 0) {
                $entityId = $result[0]['id'];
            }
        }
        unset($data['uuid']);
        $fields = $data = $this->cleanData($data);
        $this->logger->info("Data From Fileservice before encoding - " . print_r($data, true));
        $jsonData = json_encode($data);
        $data['uuid'] = $uuid;
        $data['org_id'] = AuthContext::get(AuthConstants::ORG_ID);
        $data['created_by'] = AuthContext::get(AuthConstants::USER_ID);
        $data['modified_by'] = AuthContext::get(AuthConstants::USER_ID);
        $data['date_created'] = date('Y-m-d H:i:s');
        $data['form_id'] = $formId;
        $data['date_modified'] = date('Y-m-d H:i:s');
        $data['entity_id'] = $entityId;
        $data['data'] = $jsonData;
        $file = new File();
        $file->exchangeArray($data);
        $this->logger->info("Data From Fileservice - " . print_r($data, true));
        $this->logger->info("File data From Fileservice - " . print_r($file->toArray(), true));
        // $fields = array_diff_assoc($data, $file->toArray());
        $file->validate();
        $this->beginTransaction();

        $count = 0;
        try {
            $count = $this->table->save($file);

            $this->logger->info("COUNT  FILE DATA----" . $count);
            if ($count == 0) {
                throw new ServiceException("File Creation Failed", "file.create.failed");
            }
            $id = $this->table->getLastInsertValue();
            $this->logger->info("FILE ID DATA" . $id);
            $data['id'] = $id;
            $this->logger->info("FILE DATA ----- " . json_encode($data));
            $validFields = $this->checkFields($data['entity_id'], $fields, $id);
            $fields = array_merge($fields, array_intersect_key($validFields['data'], $fields));
            unset($validFields['data']);
            $data['data'] = $fields;
            $file->exchangeArray($data);
            $this->updateFileData($id, $fields);
            if (!$validFields || empty($validFields)) {
                $this->logger->info("FILE Validation ----- ");
                throw new ValidationException("Validation Errors" . json_encode($fields));
            }
            $this->logger->info("Check Fields - " . json_encode($validFields));
            $this->multiInsertOrUpdate('ox_file_attribute', $validFields);
            $this->logger->info("Created successfully  - file record");
            $this->commit();
            // IF YOU DELETE THE BELOW TWO LINES MAKE SURE YOU ARE PREPARED TO CHECK THE ENTIRE INDEXER FLOW
            if (isset($data['id'])) {
                $this->messageProducer->sendTopic(json_encode(array('id' => $data['id'])), 'FILE_ADDED');
            }
            $data = array_merge($file->toArray(),$fields);
        } catch (Exception $e) {
            $this->logger->info("erorororor  - file record");
            $this->rollback();
            $this->logger->error($e->getMessage(), $e);
            throw $e;
        }
        return $count;
    }

    private function updateFileData($id, $data)
    {
        $query = "update ox_file set data = :data where id = :id";
        $params = array('data' => json_encode($data), 'id' => $id);
        $result = $this->executeUpdateWithBindParameters($query, $params);
        return $result->getAffectedRows() > 0;
    }

    /**
     * Update File Service
     * @method updateFile
     * @param array $id ID of File to update
     * @param array $data
     * @return array Returns a JSON Response with Status Code and Created File.
     */
    public function updateFile(&$data, $id)
    {
        // print_r($data['workflow_instance_id']);exit;
        $baseFolder = $this->config['APP_DOCUMENT_FOLDER'];
        if (isset($data['workflow_instance_id'])) {
            $select = "SELECT ox_file.* from ox_file join ox_workflow_instance on ox_workflow_instance.file_id = ox_file.id where ox_workflow_instance.id = " . $data['workflow_instance_id'];
            $obj = $this->executeQuerywithParams($select)->toArray();
            if(!empty($obj)&& !is_null($obj)) {
                $obj = $obj[0];
            }
            else {
                throw new EntityNotFoundException("File Id not found -- " . $id);
            }
        } else {
            $obj = $this->table->getByUuid($id);
            if (is_null($obj)) {
                return $this->createFile($data);
            }
            $obj = $obj->toArray();

        }
        $latestcheck = 0;
        if (isset($data['islatest']) && $data['islatest'] == 0) {
            $latestcheck = 1;
        }

        $fileObject = json_decode($obj['data'], true);

        foreach ($fileObject as $key => $fileObjectValue) {
            if (is_array($fileObjectValue)) {
                $fileObject[$key] = json_encode($fileObjectValue);
            }
        }

        foreach ($data as $key => $dataelement) {
            if (is_array($dataelement) || is_bool($dataelement)) {
                $data[$key] = json_encode($dataelement);
            }
        }

        //TODO avoid doing array merge here instead replace the incoming data as is
        $fields = array_merge($fileObject, $data);
        $file = new File();
        $id = $this->getIdFromUuid('ox_file', $id);
        $validFields = $this->checkFields(isset($obj['entity_id']) ? $obj['entity_id'] : null, $fields, $id);
        $fields = array_merge($fields, array_intersect_key($validFields['data'], $fields));
        unset($validFields['data']);
        $dataArray = array_merge($fileObject, $fields);
        $fileObject = $obj;
        $dataArray = $this->cleanData($dataArray);
        $this->updateFileData($id, $dataArray);
        $fileObject['data'] = json_encode($dataArray);
        $fileObject['modified_by'] = AuthContext::get(AuthConstants::USER_ID);
        $fileObject['date_modified'] = date('Y-m-d H:i:s');
        $this->beginTransaction();
        try {
            $this->logger->info("Entering to Update File -" . json_encode($fileObject) . "\n");

            $file->exchangeArray($fileObject);
            $file->validate();
            $count = $this->table->save($file);
            $this->logger->info(json_encode($validFields) . "are the list of valid fields.\n");
            if ($validFields && !empty($validFields)) {
                $query = "delete from ox_file_attribute where file_id = :fileId";
                $queryWhere = array("fileId" => $id);
                $result = $this->executeQueryWithBindParameters($query, $queryWhere);
                $this->multiInsertOrUpdate('ox_file_attribute', $validFields);
            }
            $this->logger->info("Leaving the updateFile method \n");
            $this->commit();
            // IF YOU DELETE THE BELOW TWO LINES MAKE SURE YOU ARE PREPARED TO CHECK THE ENTIRE INDEXER FLOW
            if (($latestcheck == 1) && isset($id)) {
                $this->messageProducer->sendTopic(json_encode(array('id' => $id)), 'FILE_DELETED');
            } else {
                if (isset($id)) {
                    $this->messageProducer->sendTopic(json_encode(array('id' => $id)), 'FILE_UPDATED');
                }
            }

        } catch (Exception $e) {
            $this->rollback();
            $this->logger->error($e->getMessage(), $e);
            throw $e;
        }
        return $obj['id'];
    }

    /**
     * Delete File Service
     * @method deleteFile
     * @param $id ID of File to Delete
     * @return array success|failure response
     */
    public function deleteFile($id)
    {
        $params['org_id'] = AuthContext::get(AuthConstants::ORG_ID);
        $sql = $this->getSqlObject();
        $params = array();
        try {
            $params['uuid'] = $id;
            $update = $sql->update();
            $update->table('ox_file')
                ->set(array('is_active' => 0))
                ->where($params);
            $response = $this->executeUpdate($update);
            $id = $this->getIdFromUuid('ox_file', $id);
            // IF YOU DELETE THE BELOW TWO LINES MAKE SURE YOU ARE PREPARED TO CHECK THE ENTIRE INDEXER FLOW
            if (isset($id)) {
                $this->messageProducer->sendTopic(json_encode(array('id' => $id)), 'FILE_DELETED');
            }

            return 1;
        } catch (Exception $e) {
            $this->logger->error($e->getMessage(), $e);
            throw $e;
        }
    }

    /**
     * GET File Service
     * @method getFile
     * @param $id ID of File
     * @return array $data
     * @return array Returns a JSON Response with Status Code and Created File.
     */
    public function getFile($id, $latest = false)
    {
        try {
            $this->logger->info("FILE ID  ------" . json_encode($id));
            $params = array('id' => $id,
                'orgId' => AuthContext::get(AuthConstants::ORG_ID));
            $select = "SELECT id, uuid, data, entity_id  from ox_file where uuid = :id AND org_id = :orgId";
            $this->logger->info("Executing query $select with params " . json_encode($params));
            $result = $this->executeQueryWithBindParameters($select, $params)->toArray();
            $this->logger->info("FILE DATA ------" . json_encode($result));
            if (count($result) > 0) {
                    $this->logger->info("FILE ID  ------" . json_encode($result));
                    if ($result[0]['data']) {
                        $result[0]['data'] = json_decode($result[0]['data'], true);
                    }
                    unset($result[0]['id']);
                    $this->logger->info("FILE DATA SUCCESS ------" . json_encode($result));
                    return $result[0];
            }
            return 0;
        } catch (Exception $e) {
            $this->logger->error($e->getMessage(), $e);
            throw $e;
        }
    }

    public function getFileByWorkflowInstanceId($workflowInstanceId, $isProcessInstanceId = true)
    {
        if ($isProcessInstanceId) {
            $where = "ox_workflow_instance.process_instance_id=:workflowInstanceId";
        } else {
            $where = "ox_workflow_instance.id=:workflowInstanceId";
        }
        try {
            $select = "SELECT ox_file.id,ox_file.uuid as fileId,ox_file.data from ox_file
            inner join ox_workflow_instance on ox_workflow_instance.file_id = ox_file.id
            where ox_file.org_id=:orgId and $where and ox_file.is_active =:isActive";
            $whereQuery = array("orgId" => AuthContext::get(AuthConstants::ORG_ID),
                "workflowInstanceId" => $workflowInstanceId,
                "isActive" => 1);
            $result = $this->executeQueryWithBindParameters($select, $whereQuery)->toArray();
            if (count($result) > 0) {
                $result[0]['data'] = json_decode($result[0]['data'], true);
                $result[0]['data']['fileId'] = $result[0]['fileId'];
                foreach ($result[0]['data'] as $key => $value) {
                    if(is_string($value)){
                        $tempValue = json_decode($value,true);
                        if(isset($tempValue)){
                            $result[0]['data'][$key] = $tempValue;
                        }
                    }
                }
                $result[0]['data'] = json_encode($result[0]['data']);
                return $result[0];
            }
            return 0;
        } catch (Exception $e) {
            $this->logger->log(Logger::ERR, $e->getMessage());
            throw $e;
        }
    }

    /**
     * @ignore checkFields
     */
    protected function checkFields($entityId, &$fieldData, $fileId)
    {
        $this->logger->info("Entering into checkFields method---EntityId : " . $entityId);
        $required = array();
        if (isset($entityId)) {
            $query = "SELECT ox_field.* from ox_field
            left join ox_app_entity on ox_app_entity.id = ox_field.entity_id
            where ox_app_entity.id=?";
            $where = array($entityId);
            $this->logger->info("Executing query - $query with  params" . json_encode($where));
            $fields = $this->executeQueryWithBindParameters($query, $where)->toArray();
            $this->logger->info("Query result got " . count($fields) . " fields");
        } else {
            $this->logger->info("No Entity ID");
            return 0;
        }
        $sqlQuery = "SELECT * from ox_file_attribute where ox_file_attribute.file_id=?";
        $whereParams = array($fileId);
        $this->logger->info("Executing query - $sqlQuery with  params" . json_encode($whereParams));
        $fileArray = $this->executeQueryWithBindParameters($sqlQuery, $whereParams)->toArray();
        $this->logger->info("Query result got " . count($fileArray) . " records");
        $keyValueFields = array();
        $i = 0;
        if (!empty($fields)) {
            //Remove All Protected fields
            // foreach ($fieldData as $k => $v) {
            //     if (($key = array_search($k, array_column($fields, 'name')) > -1)) {
            //         continue;
            //     } else {
            //         unset($fieldData[$k]);
            //     }
            // }
            foreach ($fields as $field) {
                if(!in_array($field['name'], array_keys($fieldData))){
                    continue;
                }
                if (($key = array_search($field['id'], array_column($fileArray, 'field_id'))) > -1) {
                    // Update the existing record
                    $keyValueFields[$i]['id'] = $fileArray[$key]['id'];
                } else {
                    // Insert the Record
                    $keyValueFields[$i]['id'] = null;
                }

                if (isset($fieldData[$field['name']]) && is_array($fieldData[$field['name']])) {
                    $fieldData[$field['name']] = json_encode($fieldData[$field['name']]);
                }
                $keyValueFields[$i]['file_id'] = $fileId;
                $keyValueFields[$i]['field_id'] = $field['id'];
                $fieldvalue = isset($fieldData[$field['name']]) ? $fieldData[$field['name']] : null;
                $keyValueFields[$i]['field_value']=$fieldvalue;
                $keyValueFields[$i]['org_id'] = (empty($fileArray[$key]['org_id']) ? AuthContext::get(AuthConstants::ORG_ID) : $fileArray[$key]['org_id']);
                $keyValueFields[$i]['created_by'] = (empty($fileArray[$key]['created_by']) ? AuthContext::get(AuthConstants::USER_ID) : $fileArray[$key]['created_by']);
                $keyValueFields[$i]['modified_by'] = AuthContext::get(AuthConstants::USER_ID);
                $keyValueFields[$i]['date_created'] = (!isset($fileArray[$key]['date_created']) ? date('Y-m-d H:i:s') : $fileArray[$key]['date_created']);
                $keyValueFields[$i]['date_modified'] = date('Y-m-d H:i:s');
                if(isset($field['data_type'])){
                    switch ($field['data_type']) {
                        case 'text':
                            $keyValueFields[$i]['field_value_type'] = 'TEXT';
                            $keyValueFields[$i]['field_value_text'] = $fieldvalue;
                            $keyValueFields[$i]['field_value_numeric'] = NULL;
                            $keyValueFields[$i]['field_value_boolean'] = NULL;
                            $keyValueFields[$i]['field_value_date'] = NULL;
                            $keyValueFields['data'][$field['name']] = $fieldvalue;
                            break;
                        case 'numeric':
                            $keyValueFields[$i]['field_value_type'] = 'NUMERIC';
                            $keyValueFields[$i]['field_value_text'] = NULL;
                            $keyValueFields[$i]['field_value_numeric'] = (double)$fieldvalue;
                            $keyValueFields['data'][$field['name']] = $keyValueFields[$i]['field_value_numeric'];
                            $keyValueFields[$i]['field_value_boolean'] = NULL;
                            $keyValueFields[$i]['field_value_date'] = NULL;
                            break;
                        case 'boolean':
                            if(isset($boolVal)){
                                unset($boolVal);
                            }
                            $boolVal = false;
                            if((is_bool($fieldvalue) && $fieldvalue == true) || (is_string($fieldvalue) && $fieldvalue == "true") || (is_int($fieldvalue) && $fieldvalue == 1)) {
                                $boolVal = true;
                                $fieldvalue = 1;
                            } else {
                                $boolVal = false;
                                $fieldvalue = 0;
                            }
                            $keyValueFields[$i]['field_value']=$boolVal;
                            $keyValueFields[$i]['field_value_type'] = 'BOOLEAN';
                            $keyValueFields[$i]['field_value_text'] = NULL;
                            $keyValueFields[$i]['field_value_numeric'] = NULL;
                            $keyValueFields[$i]['field_value_boolean'] = $fieldvalue;
                            $keyValueFields[$i]['field_value_date'] = NULL;
                            $keyValueFields['data'][$field['name']] = $boolVal;
                            break;
                        case 'date':
                        case 'datetime':
                            $keyValueFields[$i]['field_value_type'] = 'DATE';
                            $keyValueFields[$i]['field_value_text'] = NULL;
                            $keyValueFields[$i]['field_value_numeric'] = NULL;
                            $keyValueFields[$i]['field_value_boolean'] = NULL;
                            $keyValueFields[$i]['field_value_date'] = date_format(date_create($fieldvalue),'Y-m-d H:i:s');
                            $keyValueFields['data'][$field['name']] = $keyValueFields[$i]['field_value_date'];
                            break;
                        case 'list':
                            if($field['type']=='file'){
                                $attachmentsArray = json_decode($fieldvalue,true);
                                if(is_array($attachmentsArray)){
                                    $finalAttached = array();
                                    foreach ($attachmentsArray as $attachment) {
                                        $finalAttached[] = $this->appendAttachmentToFile($attachment,$field,$fileId);
                                    }
                                    $keyValueFields[$i]['field_value']=json_encode($finalAttached);
                                    $keyValueFields['data'][$field['name']] = $finalAttached;
                                }
                                $this->logger->info("Field Created with File- " . json_encode($keyValueFields[$i]));
                                $keyValueFields[$i]['field_value_type'] = 'OTHER';
                                $keyValueFields[$i]['field_value_text'] = NULL;
                                $keyValueFields[$i]['field_value_numeric'] = NULL;
                                $keyValueFields[$i]['field_value_boolean'] = NULL;
                                $keyValueFields[$i]['field_value_date'] = NULL;
                                break;
                            } else {
                                $keyValueFields[$i]['field_value_type'] = 'OTHER';
                                $keyValueFields[$i]['field_value_text'] = NULL;
                                $keyValueFields[$i]['field_value_numeric'] = NULL;
                                $keyValueFields[$i]['field_value_boolean'] = NULL;
                                $keyValueFields[$i]['field_value_date'] = NULL;
                                $keyValueFields['data'][$field['name']] = $fieldvalue;
                                break;
                            }
                        default:
                            $keyValueFields[$i]['field_value_type'] = 'OTHER';
                            $keyValueFields[$i]['field_value_text'] = NULL;
                            $keyValueFields[$i]['field_value_numeric'] = NULL;
                            $keyValueFields[$i]['field_value_boolean'] = NULL;
                            $keyValueFields[$i]['field_value_date'] = NULL;
                            $keyValueFields['data'][$field['name']] = $fieldvalue;
                            break;
                    }
                }
                unset($fieldvalue);
                $i++;
            }
        }
        $this->logger->info("Key Values - " . json_encode($keyValueFields));
        return $keyValueFields;
    }

    public function checkFollowUpFiles($appId, $data)
    {
        try {
            $fieldWhereQuery = $this->generateFieldWhereStatement($data);
            // print_r($fieldWhereQuery);exit;
            if (!empty($fieldWhereQuery['joinQuery'] && !empty($fieldWhereQuery['whereQuery']))) {
                $queryStr = "Select * from ox_file as a
                join ox_form as b on (a.entity_id = b.entity_id)
                join ox_form_field as c on (c.form_id = b.id)
                join ox_field as d on (c.field_id = d.id)
                join ox_app as f on (f.id = b.app_id)
                " . $fieldWhereQuery['joinQuery'] . "
                where f.id = " . $data['app_id'] . " and b.id = " . $data['form_id'] . " and (" . $fieldWhereQuery['whereQuery'] . ") group by a.id";
                $this->logger->info("Executing query - $queryStr");
                $resultSet = $this->executeQuerywithParams($queryStr);
                return $dataSet = $resultSet->toArray();
            } else {
                return 0;
            }
            // $this->email->sendRemainderEmail($appId, $dataList); //Commenting this line
        } catch (Exception $e) {
            $this->logger->error($e->getMessage(), $e);
            throw $e;
        }
        return $dataList;
    }

    private function generateFieldWhereStatement($data)
    {
        $prefix = 1;
        $whereQuery = "";
        $joinQuery = "";
        $returnQuery = array();
        $fieldList = $data['field_list'];
        try {
            if (!empty($fieldList)) {
                foreach ($fieldList as $key => $val) {
                    $tablePrefix = "tblf" . $prefix;
                    $fieldDetails = $this->getFieldDetails($key, $data['entity_id']);
                    $valueColumn = $this->getValueColumn($fieldDetails);
                    if (!empty($val) && !empty($fieldId)) {
                        $joinQuery .= "left join ox_file_attribute as " . $tablePrefix . " on (a.id =" . $tablePrefix . ".file_id) ";
                        $whereQuery .= $tablePrefix . ".field_id =" . $fieldDetails['id'] . " and " . $tablePrefix . ".".$valueColumn." ='" . $val . "' and ";
                    }
                    $prefix += 1;
                }
            }
            $whereQuery .= '1';
        } catch (Exception $e) {
            $this->logger->error($e->getMessage(), $e);
            throw $e;
        }
        return $returnQuery = array("joinQuery" => $joinQuery, "whereQuery" => $whereQuery);
    }

    public function getValueColumn($field) {
        $type = $field['data_type'];
        if ($type=='numeric' || $type=='Date') {
            $valueColumn = 'field_value_'.strtolower($type);
        } elseif ($type=='textarea' || $type=='form') {
            $valueColumn='field_value';
        } else {
            $valueColumn= 'field_value_text';
        }
        return $valueColumn;
    }

    public function getFieldDetails($fieldName, $entityId = null)
    {
        try {
            if (isset($this->fieldDetails[$fieldName])) {
                return $this->fieldDetails[$fieldName];
            }
            if ($entityId) {
                $entityWhere = "entity_id = " . $entityId . "";
            } else {
                $entityWhere = "1";
            }
            $queryStr = "select * from ox_field where name = '" . $fieldName . "' and " . $entityWhere . "";
            $resultSet = $this->executeQuerywithParams($queryStr);
            $dataSet = $resultSet->toArray();
            if (count($dataSet) == 0) {
                return 0;
            }
        } catch (Exception $e) {
            $this->logger->error($e->getMessage(), $e);
            throw $e;
        }
        $this->fieldDetails[$fieldName]=$dataSet[0];
        return $dataSet[0];
    }

    public function getFileList($appUUid, $params, $filterParams = null)
    {
        $this->logger->info("Inside File List API - with params - " . json_encode($params));
        $orgId = isset($params['orgId']) ? $this->getIdFromUuid('ox_organization', $params['orgId']) : AuthContext::get(AuthConstants::ORG_ID);
        $appId = $this->getIdFromUuid('ox_app', $appUUid);
        if (!isset($orgId)) {
            $orgId = $params['orgId'];
        }
        $select = "SELECT * from ox_app_registry where org_id = :orgId AND app_id = :appId";
        $selectQuery = array("orgId" => $orgId, "appId" => $appId);
        $result = $this->executeQuerywithBindParameters($select, $selectQuery)->toArray();
        if (count($result) > 0) {
            $queryParams = array();
            $queryParams['appId'] = $appId;
            $statusFilter = "";
            $createdFilter = "";
            $entityFilter = "";
            $whereQuery = "";
            if (isset($params['entityName'])) {
                $entityFilter = " en.name = :entityName AND ";
                $queryParams['entityName'] = $params['entityName'];
                if (isset($params['assocId'])) {
                    if ($queryParams['assocId'] = $this->getIdFromUuid('ox_file', $params['assocId'])) {
                        $entityFilter .= " of.assoc_id = :assocId AND ";
                    }

                }
            }
            $workflowJoin = "";
            $workflowFilter = "";
            if (isset($params['workflowId'])) {

                // Code to get the entityID from appId, we need this to get the correct fieldId for the filters
                $select1 = "SELECT * from ox_workflow where uuid = :uuid";
                $selectQuery1 = array("uuid" => $params['workflowId']);
                $worflowArray = $this->executeQuerywithBindParameters($select1, $selectQuery1)->toArray();

                $workflowId = $this->getIdFromUuid('ox_workflow', $params['workflowId']);
                if (!$workflowId) {
                    throw new ServiceException("Workflow Does not Exist", "app.forworkflownot.found");
                } else {
                    $workflowFilter = " ow.id = :workflowId AND ";
                    $queryParams['workflowId'] = $workflowId;
                    $workflowJoin = "left join ox_workflow_deployment as wd on wd.id = wi.workflow_deployment_id left join ox_workflow as ow on ow.id = wd.workflow_id";
                }
            }
            if (isset($params['gtCreatedDate'])) {
                $createdFilter .= " of.date_created >= :gtCreatedDate AND ";
                $params['gtCreatedDate'] = str_replace('-', '/', $params['gtCreatedDate']);
                $queryParams['gtCreatedDate'] = date('Y-m-d', strtotime($params['gtCreatedDate']));
            }
            if (isset($params['ltCreatedDate'])) {
                $createdFilter .= " of.date_created < :ltCreatedDate AND ";
                $params['ltCreatedDate'] = str_replace('-', '/', $params['ltCreatedDate']);
                /* modified date: 2020-02-11, today's date: 2020-02-11, if we use the '<=' operator then
                 the modified date converts to 2020-02-11 00:00:00 hours. Inorder to get all the records
                 till EOD of 2020-02-11, we need to use 2020-02-12 hence [+1] added to the date. */
                $queryParams['ltCreatedDate'] = date('Y-m-d', strtotime($params['ltCreatedDate'] . "+1 days"));
            }
            $where = " $workflowFilter $entityFilter $createdFilter";
            $fromQuery = " from ox_file as `of`
            inner join ox_app_entity as en on en.id = `of`.entity_id
            inner join ox_app as oa on (oa.id = en.app_id AND oa.id = :appId) ";
            if (isset($params['userId'])) {
                if ($params['userId'] == 'me') {
                    $userId = AuthContext::get(AuthConstants::USER_ID);
                } else {
                    $userId = $this->getIdFromUuid('ox_user', $params['userId']);
                    if (!$userId) {
                        throw new ServiceException("User Does not Exist", "app.forusernot.found");
                    }
                }
                $identifierQuery = "select identifier_name,identifier from ox_wf_user_identifier where user_id=:userId and app_id = :appId";
                $identifierParams = array('userId'=>$userId,'appId'=>$appId);
                $getIdentifier = $this->executeQueryWithBindParameters($identifierQuery, $identifierParams)->toArray();
                if(isset($getIdentifier) && count($getIdentifier)>0){
                    $fromQuery .= " INNER JOIN ox_file_attribute ofa on (ofa.file_id = of.id) inner join ox_field as d on (ofa.field_id = d.id and d.name= :fieldName)  ";
                    $queryParams['fieldName'] = $getIdentifier[0]['identifier_name'];
                    $queryParams['identifier'] = $getIdentifier[0]['identifier'];
                    $whereQuery = " ofa.field_value = :identifier AND ";
                }
            } else {
                $whereQuery = "";
            }
        //TODO INCLUDING WORKFLOW INSTANCE SHOULD BE REMOVED. THIS SHOULD BE PURELY ON FILE TABLE
            $fromQuery .= "left join ox_workflow_instance as wi on (`of`.last_workflow_instance_id = wi.id) $workflowJoin";
            $prefix = 1;
            if (isset($params['workflowStatus'])) {
                $whereQuery .= " wi.status = '" . $params['workflowStatus'] . "'  AND ";
            } else {
                $whereQuery .= "";
            }
            $joinQuery = "";
            $sort = "";
            $field = "";
            $pageSize = " LIMIT 10";
            $offset = " OFFSET 0";
            $sortjoinQuery = "";
            if (!empty($filterParams)) {
                if (isset($filterParams['filter']) && !is_array($filterParams['filter'])) {
                    $jsonParams = json_decode($filterParams['filter'], true);
                    if (isset($filterParamsArray['filter'])) {   // This is not correct. Please check
                        $filterParamsArray[0] = $jsonParams;
                    } else {
                        $filterParamsArray = $jsonParams;
                    }
                } else {
                    if (isset($filterParams['filter'])) {
                        $filterParamsArray = $filterParams['filter'];
                    } else {
                        $filterParamsArray = $filterParams;
                    }
                }
                
                $filterlogic = isset($filterParamsArray[0]['filter']['logic']) ? $filterParamsArray[0]['filter']['logic'] : " AND ";
                $cnt = 1;
                $fieldParams = array();
                $tableFilters = "";
                if (isset($filterParamsArray[0]['filter'])) {
                    $filterData = $filterParamsArray[0]['filter']['filters'];
                    $subQuery = "";
                    foreach ($filterData as $val) {
                        $tablePrefix = "tblf" . $prefix;
                        if (!empty($val)) {
                            $fromQuery .= " inner join ox_file_attribute as ".$tablePrefix." on (`of`.id =" . $tablePrefix . ".file_id) inner join ox_field as ".$val['field'].$tablePrefix." on(".$val['field'].$tablePrefix.".id = ".$tablePrefix.".field_id and ". $val['field'].$tablePrefix.".name='".$val['field']."')";
                            $filterOperator = $this->processFilters($val);
                            $queryString = $filterOperator["operation"] . "'" . $filterOperator["operator1"] . "" . $val['value'] . "" . $filterOperator["operator2"] . "'";
                            $whereQuery .= "(CASE WHEN (" .$tablePrefix . ".field_value_type='DATE') THEN " . $tablePrefix . ".field_value_date $queryString WHEN (" .$tablePrefix . ".field_value_type='NUMERIC') THEN " . $tablePrefix . ".field_value_numeric $queryString WHEN (" .$tablePrefix . ".field_value_type='BOOLEAN') THEN " . $tablePrefix . ".field_value_boolean $queryString  WHEN (" .$tablePrefix . ".field_value_type='TEXT') THEN " . $tablePrefix . ".field_value_text $queryString  ELSE (" . $tablePrefix . ".field_value $queryString) END ) $filterlogic";
                        }
                        $prefix += 1;
                    }
                    $whereQuery = rtrim($whereQuery, $filterlogic);
                }
                if (isset($filterParamsArray[0]['sort']) && !empty($filterParamsArray[0]['sort'])) {
                    //TODO Sort Fixes
                    $sortCount = 0;
                    $sortTable = "tblf" . $sortCount;
                    $sort = " ORDER BY ";

                    foreach ($filterParamsArray[0]['sort'] as $key => $value) {
                        $tablePrefix = "sorttblf".$prefix;
                        $fieldName = $value['field'];
                        if($fieldName == 'date_created'){
                            $sort .= "`of`.date_created ".$value['dir'].",";
                        }else{
                            $fromQuery .= " inner join ox_file_attribute as ".$tablePrefix." on (`of`.id =" . $tablePrefix . ".file_id) inner join ox_field as ".$fieldName.$tablePrefix." on(".$fieldName.$tablePrefix.".id = ".$tablePrefix.".field_id and ". $fieldName.$tablePrefix.".name='".$fieldName."')";
                            $sort .= " (CASE WHEN ".$tablePrefix.".field_value_type='DATE' THEN ".$tablePrefix.".field_value_date WHEN ".$tablePrefix.".field_value_type='NUMERIC' THEN " . $tablePrefix.".field_value_numeric WHEN ".$tablePrefix.".field_value_type='BOOLEAN' THEN ".$tablePrefix.".field_value_boolean WHEN ".$tablePrefix.".field_value_type='TEXT' THEN ".$tablePrefix.".field_value_text ELSE (".$tablePrefix.".field_value) END )".$value['dir'].",";
                        }
                        $sortCount += 1;
                    }
                    $sort = rtrim($sort, ",");
                }
                $pageSize = " LIMIT " . (isset($filterParamsArray[0]['take']) ? $filterParamsArray[0]['take'] : 10);
                $offset = " OFFSET " . (isset($filterParamsArray[0]['skip']) ? $filterParamsArray[0]['skip'] : 0);
            }
            $whereQuery = rtrim($whereQuery, " AND ");
            if($whereQuery==" WHERE "){
                $where = "";
            } else {
                $where .= " " . $whereQuery ;
            }
            $where = trim($where) != "" ? "WHERE $where" : "";
            $where = rtrim($where, " AND ");
            $fromQuery .= " " . $joinQuery . " " . $sortjoinQuery;
            try {
                $select = "SELECT DISTINCT SQL_CALC_FOUND_ROWS  of.id,of.data, of.uuid, wi.status, wi.process_instance_id as workflowInstanceId,of.date_created,en.name as entity_name $fromQuery $where $sort $pageSize $offset";
                $this->logger->info("Executing query - $select with params - " . json_encode($queryParams));
                $resultSet = $this->executeQueryWithBindParameters($select, $queryParams)->toArray();
                $countQuery = "SELECT FOUND_ROWS();";
                $this->logger->info("Executing query - $countQuery with params - " . json_encode($queryParams));
                $countResultSet = $this->executeQueryWithBindParameters($countQuery, $queryParams)->toArray();
                if (isset($filterParams['columns'])) {
                    $filterParams['columns'] = json_decode($filterParams['columns'],true);
                }
                if ($resultSet) {
                    $i = 0;
                    foreach ($resultSet as $file) {
                        if ($file['data']) {
                            $content = json_decode($file['data'], true);
                            if ($content) {
                                if (isset($filterParams['columns'])) {
                                    foreach ($filterParams['columns'] as $column){
                                        isset($content[$column]) ? $file[$column] = $content[$column] : null;
                                    }                                    
                                    if(isset($file["data"])){
                                        unset($file["data"]);
                                    }
                                    $resultSet[$i] = ($file);
                                } else{
                                    $resultSet[$i] = array_merge($file, $content);
                                }
                            }
                        }
                        $i++;
                    }
                }
                return array('data' => $resultSet, 'total' => $countResultSet[0]['FOUND_ROWS()']);
            } catch (Exception $e) {
                throw new ServiceException($e->getMessage(), "app.mysql.error");
            }
        } else {
            throw new ServiceException("App Does not belong to the org", "app.fororgnot.found");
        }
    }

    public function getFileDocumentList($params)
    {
        $selectQuery = 'select distinct ox_field.text, ox_file_attribute.* from ox_file
        inner join ox_file_attribute on ox_file_attribute.file_id = ox_file.id
        inner join ox_field on ox_field.id = ox_file_attribute.field_id
        inner join ox_app on ox_field.app_id = ox_app.id
        where ox_file.org_id=:organization and ox_app.uuid=:appUuid and ox_field.type in (:dataType1 , :dataType2)
        and ox_file.uuid=:fileUuid';
        $selectQueryParams = array('organization' => AuthContext::get(AuthConstants::ORG_ID),
            'appUuid' => $params['appId'],
            'fileUuid' => $params['fileId'],
            'dataType1' => 'document',
            'dataType2' => 'file');
        $this->logger->info("Executing query $selectQuery File with params - " . json_encode($selectQueryParams));
        $documentsArray = array();
        try {
            $selectResultSet = $this->executeQueryWithBindParameters($selectQuery, $selectQueryParams)->toArray();
            $this->logger->info("GET Document List- " . json_encode($selectResultSet));
            foreach ($selectResultSet as $result) {
                if(!empty($result['field_value'])){
                    $jsonValue =  json_decode($result['field_value'], true);
                    if(!isset($documentsArray[$result['text']])){
                        $documentsArray[$result['text']] =  $jsonValue;
                    }
                    else{
                        $documentsArray[$result['text']] = array_merge($documentsArray[$result['text']],$jsonValue);
                    }
                }
            }
            foreach ($documentsArray as $key=>$docItem) {
                if(isset($docItem) && !isset($docItem[0]['file']) ){
                     $parseDocData = array();
                    foreach ($docItem as $document) {
                        if(is_array($document) && isset($document[0])){
                            foreach ($document as $doc) {
                                $this->parseDocumentData($parseDocData,$doc);
                            }
                        } else{
                            $this->parseDocumentData($parseDocData,$document);
                        }
                    }
                   $documentsArray[$key] =$parseDocData;
                   } else {
                    $documentsArray[$key] =$docItem;
                }
            }
            return $documentsArray;
        } catch (Exception $e) {
            $this->logger->error($e->getMessage(), $e);
            return 0;
        }
    }

    private function parseDocumentData(&$parseArray,$documentItem)
    {
        if(empty($documentItem)){
            return;
        }
        if(is_string($documentItem)){            
            $fileType = explode(".", $documentItem);
            $fileName = explode("/", $documentItem);
            if(isset($fileType[1])){
                array_push($parseArray, 
                    array('file' => $documentItem, 
                      'type'=> 'file/' . $fileType[1],
                      'originalName'=> end($fileName)
                  ));
            }
        } else{
        	$this->logger->info("ParseDocument data- " . json_encode($documentItem));
            array_push($parseArray, $documentItem);
        }
    }

    public function getFieldType($value, $prefix)
    {
        switch ($value['data_type']) {
            case 'Date':
            case 'date':
                $castString = "CAST($prefix.field_value AS DATETIME)";
                break;
            case 'int':
                $castString = "CAST($prefix.field_value AS INT)";
                break;
            default:
                $castString = "($prefix.field_value)";
        }
        return $castString;
    }

    public function processFilters($filterList)
    {
        $operator = $filterList['operator'];
        $field = $filterList['field'];
        $operatorp1 = '';
        $operatorp2 = '';
        if ($operator == 'startswith') {
            $operatorp2 = '%';
            $operation = ' like ';
            $integerOperation = "=";
        } elseif ($operator == 'endswith') {
            $operatorp1 = '%';
            $operation = ' like ';
            $integerOperation = "=";
        } elseif ($operator == 'eq') {
            $operation = ' = ';
            $integerOperation = "=";
        } elseif ($operator == 'neq') {
            $operation = ' <> ';
            $integerOperation = "<>";
        } elseif ($operator == 'contains') {
            $operatorp1 = '%';
            $operatorp2 = '%';
            $operation = ' like ';
            $integerOperation = "=";
        } elseif ($operator == 'doesnotcontain') {
            $operatorp1 = '%';
            $operatorp2 = '%';
            $operation = ' NOT LIKE ';
            $integerOperation = "<>";
        } elseif ($operator == 'isnull' || $operator == 'isempty') {
            $value = '';
            $operation = ' = ';
            $integerOperation = "=";
        } elseif ($operator == 'isnotnull' || $operator == 'isnotempty') {
            $value = '';
            $operation = ' <> ';
            $integerOperation = "=";
        } elseif ($operator == 'lte') {
            $operation = ' <= ';
            $integerOperation = "<=";
        } elseif ($operator == 'lt') {
            $operation = ' < ';
            $integerOperation = "<";
        } elseif ($operator == 'gt') {
            $operation = ' > ';
            $integerOperation = ">";
        } elseif ($operator == 'gte') {
            $operation = ' >= ';
            $integerOperation = ">=";
        } else {
            $operatorp1 = '%';
            $operatorp2 = '%';
            $operation = ' like ';
        }

        return $returnData = array(
            "operation" => $operation,
            "operator1" => $operatorp1,
            "operator2" => $operatorp2,
            "integerOperation" => $integerOperation,
        );
    }

    private function cleanData($params)
    {
        unset($params['workflowInstanceId']);
        unset($params['activityInstanceId']);
        unset($params['workflow_instance_id']);
        unset($params['formId']);
        unset($params['workflow_uuid']);
        unset($params['page']);
        unset($params['parentWorkflowInstanceId']);
        unset($params['activityId']);
        unset($params['workflowId']);
        unset($params['form_id']);
        unset($params['fileId']);
        unset($params['app_id']);
        unset($params['org_id']);
        unset($params['orgId']);
        unset($params['created_by']);
        unset($params['date_modified']);
        unset($params['entity_id']);
        unset($params['parent_id']);
        unset($params['submit']);
        unset($params['controller']);
        unset($params['method']);
        unset($params['action']);
        unset($params['access']);
        unset($params['uuid']);
        unset($params['commands']);
        return $params;
    }

    private function transformValue($value, $fieldDetail)
    {
        $fieldType = $fieldDetail['data_type'];
        if (strtolower($fieldType) === 'date') {
            switch ($value) { //Based on the type of value, we can fetch the date
                case 'today':
                    return Date("Y-m-d");
                    break;
                default:
                    return $value;
            }
        }
        return $value;
    }

    public function getWorkflowInstanceByFileId($fileId,$status=null){
        $select = " SELECT ox_workflow_instance.process_instance_id,ox_workflow_instance.status,ox_workflow_instance.date_created,ox_file.entity_id from ox_workflow_instance INNER JOIN ox_file on ox_file.id = ox_workflow_instance.file_id WHERE ox_file.uuid =:fileId";
        $params = array('fileId' => $fileId);
        if($status){
            $select .= " AND ox_workflow_instance.status =:status";
            $params['status'] = $status;
        }
        $select .= " ORDER BY ox_workflow_instance.date_created DESC";
        $result = $this->executeQuerywithBindParameters($select,$params)->toArray();
        return $result;
    }

    public function getChangeLog($entityId,$startData,$completionData,$labelMapping){
        $fieldSelect = "SELECT ox_field.name,ox_field.template,ox_field.type,ox_field.text,ox_field.data_type,COALESCE(parent.name,'') as parentName,COALESCE(parent.text,'') as parentText,parent.data_type as parentDataType FROM ox_field 
                    left join ox_field as parent on ox_field.parent_id = parent.id WHERE ox_field.entity_id=:entityId AND ox_field.type NOT IN ('hidden','file','document','documentviewer') ORDER BY parentName, ox_field.name ASC";
                    
        $fieldParams = array('entityId' => $entityId);
        $resultSet = $this->executeQueryWithBindParameters($fieldSelect,$fieldParams)->toArray();
        
        $resultData = array();
        $gridResult = array();
        foreach ($resultSet as $key => $value) {
            if($value['data_type'] == 'json'){
                continue;
            }
            $initialparentData = null;
            $submissionparentData = null;
            if($value['parentName'] !="") {
                if(isset($gridResult[$value['parentName']])){
                    $gridResult[$value['parentName']]['fields'][] = $value;
                } else {
                    $initialParentData =  isset($startData[$value['parentName']]) ? $startData[$value['parentName']] : '[]';
                    $initialParentData =   is_string($initialParentData) ? json_decode($initialParentData, true) : $initialParentData;
                    // checkbox check 
                    // coverage check within grid
                    $submissionparentData = isset($completionData[$value['parentName']]) ? $completionData[$value['parentName']] : '[]';
                    $submissionparentData =   is_string($submissionparentData) ? json_decode($submissionparentData, true) : $submissionparentData;
                    $gridResult[$value['parentName']] = array("initial" => $initialParentData, "submission" => $submissionparentData, 'fields' => array($value));
                }
                
            } else{
                $this->buildChangeLog($startData, $completionData, $value, $labelMapping, $resultData);
            }         
        }
        if(count($gridResult) > 0){    
            foreach($gridResult as $parentName => $data){
                $initialDataset = $data['initial'];
                $submissionDataset = $data['submission'];
                $count = max(count($initialDataset), count($submissionDataset));
                for($i = 0; $i < $count; $i++) {
                    $initialRowData = isset($initialDataset[$i]) ? $initialDataset[$i] : array();
                    $submissionRowData = isset($submissionDataset[$i]) ? $submissionDataset[$i] : array();
                    foreach($data['fields'] as $key => $field) {
                        $this->buildChangeLog($initialRowData, $submissionRowData, $field, $labelMapping, $resultData,$i+1);
                    }
                }
            }
         }
        return $resultData;
    }

    public function getFieldValue($startDataTemp,$value,$labelMapping=null){
        if(!isset($startDataTemp[$value['name']])){
            return "";
        }
        $initialData = $startDataTemp[$value['name']];
        if($value['data_type'] == 'text'){
            //handle string data being sent
            if(is_string($initialData)){
                $fieldValue = json_decode($initialData, true);
            } else {
                $fieldValue = $initialData;
            }
            //handle select component values having an object with keys value and label 
            if(!empty($fieldValue) && is_array($fieldValue)){
                //Add Handler for default Labels
                if(isset($fieldValue['label'])){
                    $initialData = $fieldValue['label'];
                } else {
                    // Add for single values array
                    if(isset($fieldValue[0]) && count($fieldValue) == 1){
                        $initialData = $fieldValue[0];
                    } else {
                        //Case multiple values allowed
                        // print_r($fieldValue);exit;
                        if(count($fieldValue) > 1){
                            foreach ($fieldValue as $k => $v) {//print_r($v);exit;
                                $initialData .= $v;
                            } 
                        }
                    }
                }
            }
       
        }else if($value['data_type'] == 'boolean'){
            if((is_bool($initialData) && $initialData == false) || (is_string($initialData) && ($initialData=="false" || $initialData=="0"))){
                $initialData = "No";
            } else {
                $initialData = "Yes";
            }
        }else if($value['data_type'] =='list'){
            $radioFields =json_decode($value['template'],true);
            if(is_string($initialData)){
                $selectValues = json_decode($initialData,true);
            } else {
                if(is_array($initialData)){
                    $selectValues = $initialData;
                }
            }
            $initialData = "";
            $processed =0;
            if(isset($selectValues) && is_string($selectValues)){
                $selectValues = json_decode($selectValues,true);
            }
            if(isset($selectValues) && is_array($selectValues)){
                foreach ($selectValues as $key => $value) {
                    if($value == 1){
                        if($processed == 0){
                         $radioFields = ArrayUtils::convertListToMap($radioFields['values'],'value','label');
                         $processed = 1;
                     }
                     if(isset($radioFields[$key])){
                        if($initialData !=""){
                            $initialData = $initialData . ",";
                        }
                        $initialData .= $radioFields[$key];
                    }
                }
            }
            }
        }
        
        if($value['type'] =='radio'){
            $radioFields =json_decode($value['template'],true);
            if(isset($radioFields['values'])){
                foreach ($radioFields['values'] as $key => $radiovalues) {
                    if($initialData == $radiovalues['value']){
                        $initialData = $radiovalues['label'];
                        break;
                    }
                }
            }
        }
        // print_r($initialData);exit;
        if($labelMapping && !empty($initialData) && isset($labelMapping[$initialData])){
            $initialData = $labelMapping[$initialData];
        }
        return $initialData;
    }

    private function buildChangeLog($startData, $completionData, $value, $labelMapping, &$resultData,$rowNumber=""){
        $initialData =  $this->getFieldValue($startData,$value,$labelMapping);
        $submissionData = $this->getFieldValue($completionData,$value,$labelMapping);
        if((isset($initialData) && ($initialData != '[]') && (!empty($initialData))) || 
                (isset($submissionData) && ($submissionData != '[]') && (!empty($submissionData)))){
                $resultData[] = array('name' => $value['name'],
                                       'text' => $value['text'],
                                       'dataType' => $value['data_type'],
                                       'parentName' => $value['parentName'],
                                       'parentText' => $value['parentText'],
                                       'parentDataType' => $value['parentDataType'],
                                       'initialValue' => $initialData,
                                       'submittedValue' => $submissionData,
                                        'rowNumber' => $rowNumber);
        }
    }
    public function addAttachment($data,$file)
    {
        $fileArray = array();
        $data = array();
        $fileStorage = "organization/" . AuthContext::get(AuthConstants::ORG_UUID) . "/files/";
        $data['org_id'] = AuthContext::get(AuthConstants::ORG_ID);
        $data['created_id'] = AuthContext::get(AuthConstants::USER_ID);
        $data['uuid'] = UuidUtil::uuid();
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $tempname = str_replace(".".$ext, "", $file['name']);
        $data['name'] = $tempname.".".$ext;
        $data['originalName'] = $tempname.".".$ext;
        $data['extension'] = $ext;
        $folderPath = $this->config['UPLOAD_FOLDER'].$fileStorage.$data['uuid']."/";
        $form = new FileAttachment();
        $data['created_date'] = isset($data['start_date']) ? $data['start_date'] : date('Y-m-d H:i:s');
        $path = realpath($folderPath . $data['name']) ? realpath($folderPath.$data['name']) : FileUtils::truepath($folderPath.$data['name']);
        $data['path'] = $path;
        $data['type'] = $file['type'];
        $data['url'] = $this->config['baseUrl']."/data/uploads/".$fileStorage.$data['uuid']."/".$data['name'];
        $form->exchangeArray($data);
        $form->validate();
        $count = $this->attachmentTable->save($form);
        $id = $this->attachmentTable->getLastInsertValue();
        $data['id'] = $id;
        $file['name'] = $data['name'];
        $fileStored = FileUtils::storeFile($file, $folderPath);
        $data['size'] = filesize($data['path']);
        return $data;
    }
    public function appendAttachmentToFile($fileAttachment,$field,$fileId){
        if(!isset($fileAttachment['file'])){
            $fileUuid = $this->getUuidFromId('ox_file',$fileId);
            $fileLocation = $fileAttachment['path'];
            $targetLocation = $this->config['APP_DOCUMENT_FOLDER'].AuthContext::get(AuthConstants::ORG_UUID) . '/' . $fileUuid . '/';
            $this->logger->info("Data CreateFile- " . json_encode($fileLocation));
            $tempname = str_replace(".".$fileAttachment['extension'], "", $fileAttachment['name']);
            $fileAttachment['name'] = $tempname."-".$fileAttachment['uuid'].".".$fileAttachment['extension'];
            $fileAttachment['originalName'] = $tempname.".".$fileAttachment['extension'];
            $this->logger->info("attachment- " . json_encode($fileAttachment));
            if(file_exists($fileLocation)){
                FileUtils::copy($fileLocation,$fileAttachment['name'],$targetLocation);
                // FileUtils::deleteFile($fileAttachment['originalName'],dirname($fileLocation)."/");
                $fileAttachment['file'] = AuthContext::get(AuthConstants::ORG_UUID) . '/' . $fileUuid . '/'.$fileAttachment['name'];
                $fileAttachment['url'] = $this->config['baseUrl']."/".AuthContext::get(AuthConstants::ORG_UUID) . '/' . $fileUuid . '/'.$fileAttachment['name'];
                $fileAttachment['path'] = FileUtils::truepath($targetLocation."/".AuthContext::get(AuthConstants::ORG_UUID) . '/' . $fileUuid . '/'.$fileAttachment['name']);
                $this->logger->info("File Moved- " . json_encode($fileAttachment));
                // $count = $this->attachmentTable->delete($fileAttachment['id'], []);
            }
            $this->logger->info("File Deleted- " . json_encode($fileAttachment));
        }
        return $fileAttachment;
    }

}
