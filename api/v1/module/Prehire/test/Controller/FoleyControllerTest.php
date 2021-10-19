<?php 

namespace Prehire;

use Oxzion\Test\ControllerTest;
use Prehire\Controller\FoleyController;
use PHPUnit\DbUnit\DataSet\YamlDataSet;

class FoleyControllerTest extends ControllerTest
{
    public function setUp(): void
    {
        $this->loadConfig();
        parent::setUp();
    }

    public function getDataSet()
    {
        $dataset = new YamlDataSet(dirname(__FILE__) . "/../Dataset/Prehire.yml");
        return $dataset;
    }

    protected function setDefaultAsserts()
    {
        $this->assertModuleName('Prehire');
        $this->assertControllerName(FoleyController::class); // as specified in router's controller name alias
        $this->assertControllerClass('FoleyController');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
    }


    public function testApplicationShell()
    {
        $this->initAuthToken($this->adminUser);
        $data=array (
            'CreateApplicant' => 
            array (
              'Account' => 
              array (
                'FoleyAccountCode' => '0000136757',
                'AccountName' => 'Hub International Limited',
                'DOTNumber' => 22222,
                'FoleyAccountID' => '',
                'ClientCode' => '',
              ),
              'Results' => 
              array (
                'Status' => '',
                'Result' => '',
                'DateReceived' => '2021/01/01',
              ),
              'ClientReferences' => 
              array (
                'Subcode' => '',
                'Location' => '',
                'CustomerReference' => '2345',
                'RequireMVR' => 'True',
                'RequireDrug' => 'True',
                'JobCategory' => '',
                'JobCode' => '',
                'JobDescription' => '',
                'LocationCode' => '',
                'WorkCountry' => 'US',
                'RequestorName' => 'IC Name',
                'RequestorPhone' => 'IC Phone',
                'RequestorEmail' => 'IC Email',
                'TalentCoordinator' => '',
                'TalentCoordinatorEmail' => '',
              ),
              'driver_applicant' => 
              array (
                'id' => 'driver1234',
                'report_id' => 'driverreposrt1234',
              ),
              'PersonalData' => 
              array (
                'ClientReferenceId' => '9876',
                'FirstName' => 'Megha',
                'MiddleName' => '',
                'LastName' => 'Gupta',
                'ZipCode' => '12456',
                'State' => 'GA',
                'City' => 'Glastonbury',
                'StreetAddress' => '12 Main Street',
                'PhoneNumber' => '5555555555',
                'EmailAddress' => 'test@foleytest.com',
                'IDCountry' => 'US',
                'IDType' => 'SSN',
                'IDNumber' => '0123452278',
                'DateofBirth' => '09/18/1992',
                'GenderCode' => 'F',
              ),
              'Screenings' => 
              array (
                'SearchType' => 
                array (
                  '@type' => 'x:mvr',
                  'DriverLicenseNumber' => '026409105',
                  'DriverLicenseState' => 'GA',
                  'MVRCurrentState' => 'True',
                  'CDLFlag' => 'True',
                  'ScreeningReferenceID' => 'hello',
                  'ClientReferenceId' => '9876',
                  'ScreeningStatus' => 'NEW',
                ),
              ),
            ),
        );
        //$this->setJsonContent(json_encode($data));
        $this->dispatch('/foley/endpoint/ApplicantShell', 'POST',$data); 
        $content = json_decode($this->getResponse()->getContent(), true);
        print_r($content);exit;
        $this->assertResponseStatusCode(201);
        $this->assertMatchedRouteName('foley');
        $this->assertEquals($content['status'], 'success');
        

    }

}

?>