<?php

use Oxzion\AppDelegate\AbstractAppDelegate;
use Oxzion\Db\Persistence\Persistence;
use Oxzion\Utils\Country;

class CSRPadiValidation extends AbstractAppDelegate
{
  public function __construct()
  {
    parent::__construct();
  }

  public function execute(array $data, Persistence $persistenceService)
  {
    $this->logger->info("CSR Padi Validation");
    if (isset($data['padi']) && $data['padi'] != '') {
      $data['member_number'] = $data['padi'];
    }
    if (!isset($data['member_number'])) {
      $data['padi_empty'] = true;
      $data['padiNotFound'] = false;
      $data['verified'] = false;
      return $data;
    }
    $select =
      "Select firstname, MI as initial, lastname, email, address1, address2, city, state, country_code, zip, home_phone, work_phone, num as mobilephone, rating ,business_name FROM padi_data WHERE member_number ='" .
      $data['member_number'] .
      "'";
    $result = $persistenceService->selectQuery($select);
    if ($result->count() > 0) {
      $response = array();
      while ($result->next()) {
        $response[] = $result->current();
      }
      $response[0]['certificateLevel'] = implode(',',array_column($response, 'rating'));
      $returnArray = array_merge($data, $response[0]);
      if (isset($returnArray['country_code'])) {
        $returnArray['country'] = (Country::codeToCountryName($response[0]['country_code']) != false) ? Country::codeToCountryName($response[0]['country_code']) : $response[0]['country_code'];
      }
      if (isset($returnArray['state'])) {
        $selectQuery =
          "Select state FROM state_license WHERE state_in_short ='" .
          $returnArray['state'] .
          "'";
        $resultSet = $persistenceService->selectQuery($selectQuery);
        $stateDetails = array();
        while ($resultSet->next()) {
          $stateDetails[] = $resultSet->current();
        }
        if (isset($stateDetails) && count($stateDetails) > 0) {
            $returnArray['state'] = $stateDetails[0]['state'];
        }
      }
      $returnArray['padiNotFound'] = false;
      $returnArray['verified'] = true;
      $returnArray['padi_empty'] = false;
      unset($returnArray['member_number']);
      return $returnArray;
    } else {
      $returnArray['verified'] = false;
      $returnArray['padi_empty'] = false;
      $returnArray['padiNotFound'] = true;
      $data = array_merge($data, $returnArray);
      unset($data['member_number']);
      return $data;
    }
  }
}
