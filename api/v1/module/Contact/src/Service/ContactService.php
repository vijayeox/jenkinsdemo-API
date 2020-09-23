<?php
namespace Contact\Service;

use Contact\Model\Contact;
use Contact\Model\ContactTable;
use Exception;
use Oxzion\Auth\AuthConstants;
use Oxzion\Auth\AuthContext;
use Oxzion\ServiceException;
use Oxzion\Service\AbstractService;
use Oxzion\Utils\FileUtils;
use Oxzion\Utils\UuidUtil;

class ContactService extends AbstractService
{
    private $table;
    public const ALL_FIELDS = "-1";

    public function __construct($config, $dbAdapter, ContactTable $table)
    {
        parent::__construct($config, $dbAdapter);
        $this->table = $table;
    }

    /**
     * @param $data
     * @return int|string
     *
     */
    public function createContact(&$data, $files = null)
    {
        $form = new Contact();

        if (isset($data['uuid'])) {
            $data['user_id'] = $this->getUserByUuid($data['uuid']);
        } else {
            $data['user_id'] = null;
        }
        unset($data['uuid']);
        $data['uuid'] = UuidUtil::uuid();
        $data['user_id'] = (isset($data['user_id'])) ? $data['user_id'] : null;
        $data['icon_type'] = (isset($data['icon_type'])) ? $data['icon_type'] : false;
        $data['owner_id'] = (isset($data['owner_id'])) ? $data['owner_id'] : AuthContext::get(AuthConstants::USER_ID);
        $data['created_id'] = (isset($data['created_id'])) ? $data['created_id'] : AuthContext::get(AuthConstants::USER_ID);
        $data['date_created'] = date('Y-m-d H:i:s');
        $form->exchangeArray($data);
        $form->validate();
        $this->beginTransaction();
        $this->logger->info("Data modified before create - " . json_encode($data, true));
        $count = 0;
        try {
            $count = $this->table->save($form);
            if (isset($files)) {
                $this->uploadContactIcon($data['uuid'], $data['owner_id'], $files);
            }
            if ($count == 0) {
                $this->rollback();
                throw new ServiceException("Could not create the contact", "could.not.create");
            }
            $id = $this->table->getLastInsertValue();
            $data['id'] = $id;
            $this->commit();
        } catch (Exception $e) {
            $this->rollback();
            throw $e;
        }
        return $count;
    }

    public function updateContact($id, &$data, $files)
    {
        $obj = $this->table->getByUuid($id, array());
        if (is_null($obj)) {
            throw new ServiceException("Could not update the group", "contact.not.found");
        }
        $form = new Contact();
        $data = array_merge($obj->toArray(), $data);
        $data['owner_id'] = ($data['owner_id']) ? $data['owner_id'] : AuthContext::get(AuthConstants::USER_ID);
        $data['date_modified'] = date('Y-m-d H:i:s');
        $form->exchangeArray($data);
        $this->logger->info("Data modified before update - " . json_encode($data, true));
        $form->validate();
        $count = 0;
        try {
            $count = $this->table->save($form);
            if (isset($files)) {
                $this->uploadContactIcon($data['uuid'], $data['owner_id'], $files);
            }
            if ($count == 0) {
                throw new ServiceException("Could not update the contact", "could.not.update");
            }
        } catch (Exception $e) {
            throw $e;
        }
        return $count;
    }

    public function getContactsByUuid($uuid)
    {
        $select = "SELECT * from `ox_contact` where uuid = '" . $uuid . "'";
        $result = $this->executeQuerywithParams($select)->toArray();
        if ($result == 0) {
            return 0;
        }
        return $result;
    }

    public function deleteContact($id)
    {
        $sql = $this->getSqlObject();
        $count = 0;
        try {
            $delete = $sql->delete('ox_contact');
            $delete->where(['uuid' => $id]);
            $result = $this->executeUpdate($delete);
            if ($result->getAffectedRows() == 0) {
                $this->rollback();
                return 0;
            } else {
                return 1;
            }
        } catch (Exception $e) {
            $this->rollback();
        }
        return $count;
    }

    public function getContacts($column = ContactService::ALL_FIELDS, $filter = null)
    {
        $userId = AuthContext::get(AuthConstants::USER_ID);
        $orgId = AuthContext::get(AuthConstants::ORG_ID);
        try {
            $queryString1 = "SELECT * from (";
            if ($column == ContactService::ALL_FIELDS) {
                $queryString2 = "SELECT oxc.uuid as uuid, user_id, oxc.first_name, oxc.last_name, oxc.phone_1, oxc.phone_list, oxc.email, oxc.email_list, oxc.company_name, oxc.icon_type,oxc.designation, oxc.address_1,oxc.address_2,oxc.city,oxc.state,oxc.country,oxc.zip, '1' as contact_type from ox_contact as oxc";
            } else {
                $queryString2 = "SELECT oxc.uuid as uuid,user_id, oxc.first_name, oxc.last_name, oxc.icon_type, '1' as contact_type  from ox_contact as oxc";
            }
            $where1 = " WHERE oxc.owner_id = " . $userId . " ";
            if ($filter == null) {
                $and1 = '';
            } else {
                $and1 = " AND (LOWER(oxc.first_name) like '%" . $filter . "%' OR LOWER(oxc.last_name) like '%" . $filter . "%' OR LOWER(oxc.email) like '%" . $filter . "%' OR lower(oxc.phone_1) like '%" . $filter . "%')";
            }
            $union = " UNION ";
            if ($column == "-1") {
                $queryString3 = "SELECT ou.uuid as uuid, ou.id as user_id, up.firstname as first_name, up.lastname as last_name, up.phone as phone_1, 
                                 null as phone_list, up.email, null as email_list, org.name as company_name, null as icon_type, oe.designation,
                                 oa.address1,oa.address2,oa.city,oa.state,oa.country, oa.zip,'2' as contact_type 
                                 from ox_user as ou inner join ox_user_profile up on up.id = ou.user_profile_id
                                 inner join ox_organization as org on ou.orgid = org.id 
                                 Left join ox_employee oe on oe.user_profile_id = up.id and oe.org_id = org.id
                                 left join ox_address as oa on up.address_id = oa.id";
            } else {
                $queryString3 = "SELECT ou.uuid as uuid, ou.id as user_id, up.firstname as first_name, up.lastname as last_name,null as icon_type, 
                                '2' as contact_type  
                                from ox_user as ou inner join ox_user_profile up on up.id = ou.user_profile_id
                                inner join ox_organization as org on ou.orgid = org.id";
            }
            $where2 = " WHERE ou.orgid = " . $orgId . " AND ou.status = 'Active' and org.status = 'Active' ";
            if ($filter == null) {
                $and2 = '';
            } else {
                $and2 = " AND (LOWER(up.firstname) like '%" . $filter . "%' OR LOWER(up.lastname) like '%" . $filter . "%' OR LOWER(up.email) like '%" . $filter . "%')";
            }
            $queryString4 = ") as a ORDER BY a.first_name, a.last_name";
            $finalQueryString = $queryString1 . $queryString2 . $where1 . $and1 . $union . $queryString3 . $where2 . $and2 . $queryString4;
            $resultSet = $this->executeQuerywithParams($finalQueryString);
            $resultSet = $resultSet->toArray();
            $myContacts = array();
            $orgContacts = array();
            foreach ($resultSet as $key => $row) {
                if ($row['contact_type'] == 1) {
                    array_push($myContacts, $row);
                } else {
                    array_push($orgContacts, $row);
                }
            }
            $resultSet1 = ['myContacts' => $myContacts, 'orgContacts' => $orgContacts];
            $this->processIcons($resultSet1);
        } catch (Exception $e) {
            throw $e;
        }
        return $resultSet1;
    }

    private function processIcons(&$result)
    {
        $uuid = $this->getUuidById(AuthContext::get(AuthConstants::USER_ID));
        $baseUrl = $this->getBaseUrl();
        for ($x = 0; $x < sizeof($result['myContacts']); $x++) {
            $result['myContacts'][$x]['phone_list'] = isset($result['myContacts'][$x]['phone_list']) ? $result['myContacts'][$x]['phone_list'] : null;
            $result['myContacts'][$x]['phone_list'] = json_decode($result['myContacts'][$x]['phone_list'], true);
            $result['myContacts'][$x]['email_list'] = isset($result['myContacts'][$x]['email_list']) ? $result['myContacts'][$x]['email_list'] : null;
            $result['myContacts'][$x]['email_list'] = json_decode($result['myContacts'][$x]['email_list'], true);
            if ($result['myContacts'][$x]['icon_type']) {
                $userId = $this->getUuidById($result['myContacts'][$x]['user_id']);
                $result['myContacts'][$x]['icon'] = $baseUrl . "/user/profile/" . $userId;
            } else {
                $result['myContacts'][$x]['icon'] = $baseUrl . "/contact/" . $uuid . "/" . $result['myContacts'][$x]["uuid"];
            }
        }
        for ($x = 0; $x < sizeof($result['orgContacts']); $x++) {
            $result['orgContacts'][$x]['icon'] = $baseUrl . "/user/profile/" . $result['orgContacts'][$x]['uuid'];
        }
    }

    public function getContactIconPath($ownerId, $ensureDir = false)
    {
        $baseFolder = $this->config['UPLOAD_FOLDER'];
        //TODO : Replace the User_ID with USER uuid
        $folder = $baseFolder . "contact/";
        if (isset($ownerId)) {
            $folder = $folder . $ownerId . "/";
        }

        if ($ensureDir && !file_exists($folder)) {
            FileUtils::createDirectory($folder);
        }

        return $folder;
    }

    public function getUuidById($userId)
    {
        $select = "SELECT uuid from ox_user where id = " . $userId . " AND orgid = " . AuthContext::get(AuthConstants::ORG_ID);
        $result = $this->executeQuerywithParams($select)->toArray();
        if ($result) {
            return $result[0]['uuid'];
        }
    }

    public function getUserByUuid($uuid)
    {
        $select = "SELECT id from `ox_user` where uuid = '" . $uuid . "'";
        $result = $this->executeQueryWithParams($select)->toArray();
        if ($result) {
            return $result[0]['id'];
        }
    }

    /**
     * createUpload
     *
     * Upload files from Front End and store it in temp Folder
     *
     *  @param files Array of files to upload
     *  @return JSON array of filenames
     */
    public function uploadContactIcon($uuid, $owner_id, $file)
    {
        $id = $this->getUuidById($owner_id);

        if (isset($file)) {
            $destFile = $this->getContactIconPath($id, true);
            $image = FileUtils::convetImageTypetoPNG($file);
            if ($image) {
                if (FileUtils::fileExists($destFile)) {
                    imagepng($image, $destFile . '/' . $uuid . '.png');
                    $image = null;
                } else {
                    mkdir($destFile);
                    imagepng($image, $destFile . '/' . $uuid . '.png');
                    $image = null;
                }
            }
        }
    }

    private function getPhoneEmailList($data, $fieldname)
    {
        if ($data[$fieldname] == "null" || empty($data[$fieldname]) || $data[$fieldname] == "NULL") {
            $data[$fieldname] = null;
        } else {
            $field = array();
            $field_list = explode(",", $data[$fieldname]);
            foreach ($field_list as $key => $value) {
                $fieldlist = array();
                $fieldlist = explode(":", $value);
                if (count($fieldlist) == 1) {
                    $field['other'] = $fieldlist[0];
                } else {
                    $field[$fieldlist[0]] = $fieldlist[1];
                }
            }
            $data[$fieldname] = json_encode($field);
        }
        return $data[$fieldname];
    }

    // Import Custom CSV Format
    public function importContactCustomFormat($files)
    {
        $error_list = array();
        $error = array();
        $contact = array();
        $file = FileUtils::storeFile($files, '/tmp/oxzion/');
        $file_handle = fopen('/tmp/oxzion/' . $file, 'r');
        while (!feof($file_handle)) {
            $line_of_text[] = fgetcsv($file_handle);
        }
        fclose($file_handle);
        array_pop($line_of_text);
        $columns = array_shift($line_of_text);
        $requiredHeaders = array('first_name', 'last_name', 'phone_1', 'phone_list', 'email', 'email_list', 'company_name', 'designation', 'country');
        if ($columns !== $requiredHeaders) {
            return 3;
        }
        array_push($error_list, $columns);

        $line = array_chunk($line_of_text, 1000);
        foreach ($line as $key => $val) {
            $data = array();
            for ($y = 0; $y < sizeof($val); $y++) {
                $data[] = array_combine($columns, $val[$y]);
            }

            for ($x = 0; $x < sizeof($data); $x++) {
                if (empty($data['first_name']) || $data['first_name'] == "null"
                    || ($data['phone_1'] == "null" && $data['email'] == "null")
                    || (empty($data['phone_1']) && empty($data['email']))
                    || ($data['phone_1'] == "null" && empty($data['email']))
                    || ($data['email'] == "null" && empty($data['phone_1']))) {
                    array_push($error_list, $data);
                } else {
                    $regex = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z0-9]{2,5})$/';
                    $pregex = '/^[a-zA-Z]*$/';
                    $flag = 0;
                    if (isset($data['email']) && $data['email'] != "null" && !empty($data['email'])) {
                        if (!preg_match($regex, $data['email'])) {
                            array_push($error_list, $data);
                            $flag = 1;
                        }
                    } elseif (isset($data['phone_1']) && $data['phone_1'] != "null" && !empty($data['phone_1'])) {
                        if (preg_match($pregex, $data['phone_1'])) {
                            array_push($error_list, $data);
                            $flag = 1;
                        }
                    }
                    if ($flag == 0) {
                        $data['phone_list'] = $this->getPhoneEmailList($data, 'phone_list');
                        $data['email_list'] = $this->getPhoneEmailList($data, 'email_list');
                        $data['uuid'] = Uuid::uuid4()->toString();
                        $data['icon_type'] = 0;
                        $data['owner_id'] = AuthContext::get(AuthConstants::USER_ID);
                        array_push($contact, $data);
                    }
                }
            }
            $insert = $this->multiInsertOrUpdate('ox_contact', $contact);
            if ($insert->getAffectedRows() == 0) {
                return 0;
            }
        }
        if (count($error_list) > 1) {
            return $error_list;
        }
        return 1;
    }

    // Export Custom CSV Format
    public function exportContactCustomFormat($id = null)
    {
        $csv = array();
        $export = array();
        if (isset($id)) {
            $uuidArray = array_map('current', $id);
            $select = "SELECT first_name,last_name,phone_1,phone_list,email,email_list,company_name,designation,country FROM ox_contact where uuid in ('" . implode("','", $uuidArray) . "')";
        } else {
            $select = "SELECT first_name,last_name,phone_1,phone_list,email,email_list,company_name,designation,country FROM ox_contact where owner_id = " . AuthContext::get(AuthConstants::USER_ID);
        }

        $result = $this->executeQueryWithParams($select)->toArray();

        $requiredHeaders = array('first_name', 'last_name', 'phone_1', 'phone_list', 'email', 'email_list', 'company_name', 'designation', 'country');
        array_push($export, $requiredHeaders);

        for ($x = 0; $x < sizeof($result); $x++) {
            if (isset($result[$x]['phone_list']) && !empty($result[$x]['phone_list']) && $result[$x]['phone_list'] != "null" && $result[$x]['phone_list'] != "NULL") {
                $phoneArray = array();
                $phone = json_decode($result[$x]['phone_list'], true);
                foreach ($phone as $key => $value) {
                    $phoneArray[] = "$key: $value";
                }
                $result[$x]['phone_list'] = implode(',', $phoneArray);
            }
            if (isset($result[$x]['email_list']) && !empty($result[$x]['email_list']) && $result[$x]['email_list'] != "null" && $result[$x]['email_list'] != "NULL") {
                $emailArray = array();
                $email = json_decode($result[$x]['email_list'], true);
                foreach ($email as $key => $value) {
                    $emailArray[] = "$key: $value";
                }
                $result[$x]['email_list'] = implode(',', $emailArray);
            }
            array_push($export, $result[$x]);
        }
        if (count($export) > 1) {
            return array('data' => $export);
        }
    }

    private function getPhoneEmailArray($list)
    {
        $data = array();
        $list = array_pop($list);
        $list = array_chunk($list, 2);
        foreach ($list as $key => $val) {
            $data[] = ["type" => $val[0], "value" => $val[1]];
        }
        return json_encode($data);
    }

    //Import Google CSV Format
    public function importContactCSV($files)
    {
        set_time_limit(300);
        $error_list = array();
        $error = array();
        $contact = array();
        $addressJson = file_get_contents(__DIR__ . '/../countryCode.json');
        $addressJson = json_decode($addressJson, true);
        $file = FileUtils::storeFile($files, '/tmp/oxzion/');
        $file_handle = fopen('/tmp/oxzion/' . $file, 'r');
        $line = 1;
        $data = array();
        try {
            while (!feof($file_handle)) {
                $line_of_text = fgetcsv($file_handle);
                if ($line == 1) {
                    $columns = $line_of_text;
                    $requiredHeaders = array('Given Name', 'Family Name', 'E-mail 1 - Type', 'E-mail 1 - Value', 'Phone 1 - Type', 'Phone 1 - Value', 'Organization 1 - Name', 'Organization 2 - Title', 'Address 1 - Street', 'Address 1 - Extended Address', 'Address 1 - City', 'Address 1 - Region', 'Address 1 - Country', 'Address 1 - Postal Code');
                    $result = array_intersect($requiredHeaders, $columns);
                    if (count($result) != 13) {
                        return 3;
                    }
                    $line++;
                } else {
                    unset($data);
                    $dataExists = false;
                    foreach ($columns as $key => $value) {
                        if (!$dataExists && !empty($line_of_text[$key]) && strtolower($line_of_text[$key]) != "null") {
                            $dataExists = true;
                        }
                        $data[$value] = $line_of_text[$key];
                    }
                    if (!$dataExists) {
                        continue;
                    }
                    $emailArray = array();
                    $phoneArray = array();
                    $finalArray = array();
                    $keys = array_keys($data);
                    if (empty($data['Given Name']) || $data['Given Name'] == "null"
                        || ($data['Phone 1 - Value'] == "null" && $data['E-mail 1 - Value'] == "null")
                        || (empty($data['Phone 1 - Value']) && empty($data['E-mail 1 - Value']))
                        || ($data['Phone 1 - Value'] == "null" && empty($data['E-mail 1 - Value']))
                        || ($data['E-mail 1 - Value'] == "null" && empty($data['Phone 1 - Value']))) {
                        $data['Comments'] = "Given Name and Phone 1 - Value or Email 1 - Value Fields are required";
                        array_push($error_list, $data);
                        continue;
                    } else {
                        if (isset($data['E-mail 1 - Value']) && $data['E-mail 1 - Value'] != "null" && !empty($data['E-mail 1 - Value'])) {
                            if (!preg_match('/[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z0-9]{2,5})/', $data['E-mail 1 - Value'])) {
                                $data['Comments'] = "Invalid Email ID";
                                array_push($error_list, $data);
                                continue;
                            }
                        }
                        if (isset($data['Phone 1 - Value']) && $data['Phone 1 - Value'] != "null" && !empty($data['Phone 1 - Value'])) {
                            if (preg_match('/[A-Za-z]/', $data['Phone 1 - Value'])) {
                                $data['Comments'] = "Invalid Phone Number";
                                array_push($error_list, $data);
                                continue;
                            }
                        }
                        for ($y = 0; $y < count($keys); $y++) {
                            if (preg_match('/E-mail\s[2-9]\s[-]\s[A-Za-z]/', $keys[$y])) {
                                $email[$keys[$y]] = $data[$keys[$y]];
                                array_push($emailArray, $email);
                            }
                            if (preg_match('/Phone\s[2-9]\s[-]\s[A-Za-z]/', $keys[$y])) {
                                $phone[$keys[$y]] = $data[$keys[$y]];
                                array_push($phoneArray, $phone);
                            }
                        }
                        unset($email, $phone);
                        $finalArray['uuid'] = UuidUtil::uuid();
                        $finalArray['first_name'] = $data['Given Name'] . " " . $data['Additional Name'];
                        $finalArray['last_name'] = $data['Family Name'];
                        $finalArray['phone_1'] = $data['Phone 1 - Value'];
                        if (count($phoneArray) > 1) {
                            $finalArray['phone_list'] = $this->getPhoneEmailArray($phoneArray);
                        }
                        $finalArray['email'] = $data['E-mail 1 - Value'];
                        if (count($emailArray) > 1) {
                            $finalArray['email_list'] = $this->getPhoneEmailArray($emailArray);
                        }
                        $finalArray['company_name'] = $data['Organization 1 - Name'];
                        $finalArray['designation'] = $data['Organization 1 - Title'];
                        $finalArray['address_1'] = $data['Address 1 - Street'];
                        $finalArray['address_2'] = $data['Address 1 - Extended Address'];
                        $finalArray['city'] = $data['Address 1 - City'];
                        $finalArray['state'] = $data['Address 1 - Region'];
                        $finalArray['country'] = isset($addressJson[$data['Address 1 - Country']]) ? $addressJson[$data['Address 1 - Country']] : $data['Address 1 - Country'];
                        $finalArray['zip'] = $data['Address 1 - Postal Code'];
                        $finalArray['icon_type'] = 0;
                        $finalArray['owner_id'] = AuthContext::get(AuthConstants::USER_ID);
                        $finalArray['date_created'] = date('Y-m-d H:i:s');
                        array_push($contact, $finalArray);
                    }
                    if (count($contact) % 1000 == 0) {
                        $this->persistContacts($contact, $error_list);
                        unset($contact);
                        $contact = array();
                    }
                }
            }
            if (count($contact) > 0) {
                $this->persistContacts($contact, $error_list);
            }
        } catch (Exception $e) {
            throw $e;
        } finally {
            fclose($file_handle);
        }
        if (count($error_list) > 1) {
            return $error_list;
        }
        return 1;
    }

    private function persistContacts($contact, &$error_list)
    {
        $this->beginTransaction();
        $insert = $this->multiInsertOrUpdate('ox_contact', $contact);
        $this->commit();
        if ($insert->getAffectedRows() == 0) {
            array_push($error_list, $contact);
        }
    }

    // Export Google CSV Format
    public function exportContactCSV($id = null)
    {
        $finalArray = array();
        $emailArray = array();
        $finalList = array();
        try {
            if (count($id) > 1) {
                $select = "SELECT first_name,last_name,phone_1,phone_list,email,email_list,company_name,designation,country,address_1 FROM ox_contact where uuid in ('" . implode("','", $id) . "')";
            } else {
                $select = "SELECT first_name,last_name,phone_1,phone_list,email,email_list,company_name,designation,country,address_1 FROM ox_contact where owner_id = " . AuthContext::get(AuthConstants::USER_ID);
            }
            $result = $this->executeQueryWithParams($select)->toArray();
            $maxPhone = 0;
            $maxEmail = 0;
            for ($x = 0; $x < sizeof($result); $x++) {
                $finalArray[$x]['Given Name'] = $result[$x]['first_name'];
                $finalArray[$x]['Family Name'] = $result[$x]['last_name'];
                $finalArray[$x]['E-mail 1 - Type'] = null;
                $finalArray[$x]['E-mail 1 - Value'] = isset($result[$x]['email']) ? $result[$x]['email'] : null;
                if (isset($result[$x]['email_list']) && !empty($result[$x]['email_list']) && $result[$x]['email_list'] != "null" && $result[$x]['email_list'] != "NULL") {
                    $emailArray = array();
                    $emailArray = json_decode($result[$x]['email_list'], true);
                    $count = 2;
                    foreach ($emailArray as $key => $value) {
                        $index = 'E-mail ' . $count . ' - Type';
                        $val = 'E-mail ' . $count . ' - Value';
                        $finalArray[$x][$index] = isset($value['type']) ? $value['type'] : null;
                        $finalArray[$x][$val] = isset($value['value']) ? $value['value'] : null;
                        $count++;
                    }
                    $max = $count - 2;
                    if ($maxEmail < $max) {
                        $maxEmail = $max;
                    }
                }
                $finalArray[$x]['Phone 1 - Type'] = null;
                $finalArray[$x]['Phone 1 - Value'] = isset($result[$x]['phone_1']) ? $result[$x]['phone_1'] : null;
                if (isset($result[$x]['phone_list']) && !empty($result[$x]['phone_list']) && $result[$x]['phone_list'] != "null" && $result[$x]['phone_list'] != "NULL") {
                    $phoneArray = array();
                    $phoneArray = json_decode($result[$x]['phone_list'], true);
                    $param = 2;
                    foreach ($phoneArray as $key => $value) {
                        $index = 'Phone ' . $param . ' - Type';
                        $val = 'Phone ' . $param . ' - Value';
                        $finalArray[$x][$index] = isset($value['type']) ? $value['type'] : null;
                        $finalArray[$x][$val] = isset($value['value']) ? $value['value'] : null;
                        $param++;
                    }
                    $max = $param - 2;
                    if ($maxPhone < $max) {
                        $maxPhone = $max;
                    }
                }
                $finalArray[$x]['Address 1 - Formatted'] = isset($result[$x]['address_1']) ? $result[$x]['address_1'] : null;
                $finalArray[$x]['Organization 1 - Name'] = isset($result[$x]['company_name']) ? $result[$x]['company_name'] : null;
                $finalArray[$x]['Organization 1 - Title'] = isset($result[$x]['designation']) ? $result[$x]['designation'] : null;
                $finalArray[$x]['Location'] = isset($result[$x]['country']) ? $result[$x]['country'] : null;
                $finalArray[$x]['Name Prefix'] = null;
                $finalArray[$x]['Name Suffix'] = null;
                $finalArray[$x]['Initials'] = null;
                $finalArray[$x]['Nickname'] = null;
                $finalArray[$x]['Short Name'] = null;
                $finalArray[$x]['Maiden Name'] = null;
            }

            $headers = array('Given Name', 'Family Name', 'E-mail 1 - Type', 'E-mail 1 - Value');
            $count = 2;
            for ($x = 0; $x < $maxEmail; $x++) {
                $index = 'E-mail ' . $count . ' - Type';
                $val = 'E-mail ' . $count . ' - Value';
                array_push($headers, $index);
                array_push($headers, $val);
                $count++;
            }
            array_push($headers, 'Phone 1 - Type', 'Phone 1 - Value');
            $count = 2;
            for ($x = 0; $x < $maxPhone; $x++) {
                $index = 'Phone ' . $count . ' - Type';
                $val = 'Phone ' . $count . ' - Value';
                array_push($headers, $index);
                array_push($headers, $val);
                $count++;
            }

            array_push($headers, 'Address 1 - Formatted');
            array_push($headers, 'Organization 1 - Name', 'Organization 1 - Title', 'Location', 'Name Prefix', 'Name Suffix', 'Initials', 'Nickname', 'Short Name', 'Maiden Name');

            array_push($finalList, $headers);
            for ($l = 0; $l < sizeof($finalArray); $l++) {
                $export = array();
                for ($k = 0; $k < sizeof($headers); $k++) {
                    $newdata = array();
                    $newdata[$k] = isset($finalArray[$l][$headers[$k]]) ? $finalArray[$l][$headers[$k]] : null;
                    array_push($export, $newdata[$k]);
                }
                array_push($finalList, $export);
            }
            if (count($finalList) > 1) {
                return array('data' => $finalList);
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function mutipleContactsDelete($data)
    {
        try {
            if (count($data) < 1) {
                throw new ServiceException("No Contacts to Delete", "failed.create.user");
            }
            $delete = "DELETE FROM ox_contact where uuid in ('" . implode("','", $data) . "') AND owner_id = " . AuthContext::get(AuthConstants::USER_ID);
            $result = $this->executeQuerywithParams($delete);
        } catch (Exception $e) {
            throw $e;
        }
        return;
    }
}
