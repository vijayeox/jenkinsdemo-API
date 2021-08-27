<?php
use Oxzion\Test\DelegateTest;
use PHPUnit\DbUnit\DataSet\DefaultDataSet;
use Zend\Db\Adapter\Adapter;
use Oxzion\Utils\SOAPUtils;

class FtniApisTest extends DelegateTest
{   

    private $soapClient;

    public function setUp() : void
    {
        $this->loadConfig();
        parent::setUp();
        $config = $this->getApplicationConfig();
        $this->adapter = new Adapter($config['db']);
        $this->soapClient = new SOAPUtils('https://test.ftnirdc.com/RDCServiceNP/RDCService.svc?wsdl');
        $this->soapClient->setWsseHeader('HDOLWebServicesAcct@EPICU', 'Hd0LW3bSerV!ce5');
    }

    public function getDataSet() {
        return new DefaultDataSet();
    }

    private function getMockClient() {
        $mockRestClient = Mockery::mock('Oxzion\Utils\SOAPUtils');
        $this->setSoapClient($mockRestClient);
        return $mockRestClient;
    }

    private function setSoapClient($soapClient) {
        $this->soapClient = $soapClient;
    }

    public function testGetEpayCustomerBySite()
    {
        $requestData = [
            'epay_customer_id' => 0,
            'customer_id' => 'IGGI000-01',
            'site_key' => '11',
            'b_search_children' => 1,
            'b_get_pay_methods' => 1
        ];
        if(enableSoapClient == 0){
            $mockSoapClient = $this->getMockClient();
            $mockData = [
                "GetEPayCustomerBySiteResult" => [
                      "Address1" => "17515 W. 9 Mile Road, Suite -", 
                      "Address2" => "-", 
                      "City" => "Southfield", 
                      "Country" => "USA", 
                      "CustomerID" => "IGGI000-01", 
                      "CustomerType" => "0", 
                      "DriversLicense" => "", 
                      "EMail" => "jeremy.simon@hubinternational.com", 
                      "EPayBanks" => [
                         "EPayBank" => [
                            "ABANumber" => "071923284", 
                            "AccountNumber" => "8765032729", 
                            "AccountType" => "1", 
                            "Address1" => "?", 
                            "Address2" => "?", 
                            "BankName" => "BANK OF AMERICA, N.A.               ", 
                            "City" => "?", 
                            "CompanyName" => "", 
                            "Country" => "USA", 
                            "CustNum" => "", 
                            "EPayBankID" => "102846711", 
                            "EPayCustomerID" => "9985022", 
                            "Name1" => "IGGI I'll Go Get It LLC", 
                            "Name2" => "", 
                            "SiteID" => "316729", 
                            "State" => "?   ", 
                            "UseCIAddress" => "0", 
                            "UseCIName" => "1", 
                            "ZipCode" => "?           " 
                         ] 
                      ], 
                      "EPayCustomerID" => "9985022", 
                      "Fax" => "", 
                      "Field1" => "", 
                      "Field2" => "", 
                      "Field3" => "", 
                      "FlagAcceptChecks" => "1", 
                      "FlagFreqMonOrders" => "0", 
                      "FlagFreqRetFees" => "0", 
                      "FlagNoChecks" => "0", 
                      "FlagRequireCCard" => "0", 
                      "FlagRequireCash" => "0", 
                      "LicenseState" => "", 
                      "Name1" => "IGGI I'll Go Get It LLC", 
                      "Name2" => "", 
                      "Notes" => "", 
                      "Phone1" => "", 
                      "Phone2" => "", 
                      "SSN" => "", 
                      "SiteID" => "316729", 
                      "State" => "MI  ", 
                      "ZipCode" => "48075       " 
                   ] 
             ]; 
            $mockSoapClient->expects('makeCall')->with('GetEPayCustomerBySite',$requestData, true)->once()->andReturn($mockData);
        }
        $response = $this->soapClient->makeCall('GetEPayCustomerBySite', $requestData, true);
        $responseArray = $response['GetEPayCustomerBySiteResult'];
        $this->assertEquals($responseArray['Address1'],"17515 W. 9 Mile Road, Suite -");
        $this->assertEquals($responseArray['City'],"Southfield");
        $this->assertEquals($responseArray['Country'],"USA");
        $this->assertEquals($responseArray['CustomerID'],"IGGI000-01");
        $this->assertEquals($responseArray['EMail'],"jeremy.simon@hubinternational.com");
        $this->assertEquals($responseArray['EPayCustomerID'],"9985022");
        $this->assertEquals($responseArray['Name1'],"IGGI I'll Go Get It LLC");
        $this->assertEquals($responseArray['SiteID'],"316729");
        $this->assertEquals($responseArray['State'],"MI  ");
        $this->assertEquals($responseArray['ZipCode'],"48075       ");
        $this->assertEquals($responseArray['EPayBanks']['EPayBank']['ABANumber'],"071923284");
        $this->assertEquals($responseArray['EPayBanks']['EPayBank']['AccountNumber'],"8765032729");
        $this->assertEquals($responseArray['EPayBanks']['EPayBank']['BankName'],"BANK OF AMERICA, N.A.               ");
        $this->assertEquals($responseArray['EPayBanks']['EPayBank']['EPayCustomerID'],"9985022");
    }

    public function testProcessACHSaleUnified() {
        $requestData = [
            'epayCustomerID' => 9985022,
            'customerID' => 'IGGI000-01',
            'bankToken' => '102846711',
            'secCode' => 'WEB',
            'bankName' => '120000-MWW001 - Midwest West Trust',
            'accountType' => 'Savings',
            'amount' => 1000.00,
            'siteAbbreviation' => 'MWWH',
            'settlement' => 'HUB Drive Online Fees',
            'ledgerData' => [
                'LedgerRow' => [
                    [
                        'Column1' => 'IGGI000-01',
                        'Column2' => 'Vendor 1',
                        'Column3' => 'HDOL Fees',
                        'Column5' => '300'
                    ],
                    [
                        'Column1' => 'IGGI000-01',
                        'Column2' => 'Vendor 1',
                        'Column3' => 'HDOL Fees',
                        'Column5' => '300'
                    ]
                ],
            ],
            'statusQueue' => 'Review',
            'source' => 1107
        ];
        if(enableSoapClient == 0){
            $mockSoapClient = $this->getMockClient();
            $mockData = [
            "ProcessACHSaleUnifiedResult" => [
                    "BankToken" => 102846711, 
                    "EPayCustomerID" => 9985022, 
                    "ItemNumber" => 54654959 
                ] 
            ]; 
            $mockSoapClient->expects('makeCall')->with('ProcessACHSaleUnified',$requestData, true)->once()->andReturn($mockData);
        }
        $response = $this->soapClient->makeCall('ProcessACHSaleUnified', $requestData, true);
        $responseArray = $response['ProcessACHSaleUnifiedResult'];
        $this->assertEquals($responseArray['BankToken'],"102846711");
        $this->assertEquals($responseArray['EPayCustomerID'],"9985022");
        $this->assertEquals($responseArray['ItemNumber'],"54654959");
    }

    public function testGetEPayInvoice() {
        $requestData = [
            'siteID' => '1',
            'epayCustomerID' => '0',
            'customerID' => 'IGGI000-01',
            'status' => 01
        ];
        if(enableSoapClient == 0){
            $mockSoapClient = $this->getMockClient();
            $mockData = [
            "GetEPayInvoiceResult" => [
                    "GetEPayInvoicesResult" => [
                        [
                            "ClosedAmount" => 0, 
                            "CustomerID" => "IGGI000-01", 
                            "DueDate" => "02/04/2021", 
                            "EPayCustomerID" => 9985022, 
                            "Field1" => "MWE", 
                            "Field2" => "23717752", 
                            "InvoiceDate" => "12/02/2020", 
                            "InvoiceNumber" => "2167397 / CL3578324A / 23717752", 
                            "OpenAmount" => -10000, 
                            "SiteID" => 316729, 
                            "UsedInConfirmation" => 0 
                        ], 
                        [
                            "ClosedAmount" => 0, 
                            "CustomerID" => "IGGI000-01", 
                            "DueDate" => "02/11/2021", 
                            "EPayCustomerID" => 9985022, 
                            "Field1" => "MWE", 
                            "Field2" => "23831844", 
                            "InvoiceDate" => "02/01/2021", 
                            "InvoiceNumber" => "2175414 / 103 GL 0029480-00 / 23831844", 
                            "OpenAmount" => 200000, 
                            "SiteID" => 316729, 
                            "UsedInConfirmation" => 0 
                        ], 
                        [
                            "ClosedAmount" => 0, 
                            "CustomerID" => "IGGI000-01", 
                            "DueDate" => "02/11/2021", 
                            "EPayCustomerID" => 9985022, 
                            "Field1" => "MWE", 
                            "Field2" => "23831856", 
                            "InvoiceDate" => "02/01/2021", 
                            "InvoiceNumber" => "2175414 / 103 GL 0029480-00 / 23831856", 
                            "OpenAmount" => 10000, 
                            "SiteID" => 316729, 
                            "UsedInConfirmation" => 0 
                        ], 
                        [
                            "ClosedAmount" => 0, 
                            "CustomerID" => "IGGI000-01", 
                            "DueDate" => "02/11/2021", 
                            "EPayCustomerID" => 9985022, 
                            "Field1" => "MWE", 
                            "Field2" => "23831871", 
                            "InvoiceDate" => "02/01/2021", 
                            "InvoiceNumber" => "2175414 / AR4236103 / 23831871", 
                            "OpenAmount" => 100000, 
                            "SiteID" => 316729, 
                            "UsedInConfirmation" => 0 
                        ], 
                        [
                            "ClosedAmount" => 0, 
                            "CustomerID" => "IGGI000-01", 
                            "DueDate" => "02/11/2021", 
                            "EPayCustomerID" => 9985022, 
                            "Field1" => "MWE", 
                            "Field2" => "23831882", 
                            "InvoiceDate" => "02/01/2021", 
                            "InvoiceNumber" => "2175414 / AR4236103 / 23831882", 
                            "OpenAmount" => 5000, 
                            "SiteID" => 316729, 
                            "UsedInConfirmation" => 0 
                        ], 
                        [
                            "ClosedAmount" => 0, 
                            "CustomerID" => "IGGI000-01", 
                            "DueDate" => "03/11/2021", 
                            "EPayCustomerID" => 9985022, 
                            "Field1" => "MWE", 
                            "Field2" => "24254264", 
                            "InvoiceDate" => "03/01/2021", 
                            "InvoiceNumber" => "2204505 / 103 GL 0029480-00 / 24254264", 
                            "OpenAmount" => 221300, 
                            "SiteID" => 316729, 
                            "UsedInConfirmation" => 0 
                        ], 
                        [
                            "ClosedAmount" => 0, 
                            "CustomerID" => "IGGI000-01", 
                            "DueDate" => "03/11/2021", 
                            "EPayCustomerID" => 9985022, 
                            "Field1" => "MWE", 
                            "Field2" => "24254271", 
                            "InvoiceDate" => "03/01/2021", 
                            "InvoiceNumber" => "2204505 / 103 GL 0029480-00 / 24254271", 
                            "OpenAmount" => 11065, 
                            "SiteID" => 316729, 
                            "UsedInConfirmation" => 0 
                        ], 
                        [
                            "ClosedAmount" => 0, 
                            "CustomerID" => "IGGI000-01", 
                            "DueDate" => "05/15/2021", 
                            "EPayCustomerID" => 9985022, 
                            "Field1" => "MWE", 
                            "Field2" => "21266835", 
                            "InvoiceDate" => "08/15/2020", 
                            "InvoiceNumber" => "1979544 / SCC2105618 / 21266835", 
                            "OpenAmount" => 745800, 
                            "SiteID" => 316729, 
                            "UsedInConfirmation" => 0 
                        ], 
                        [
                            "ClosedAmount" => 0, 
                            "CustomerID" => "IGGI000-01", 
                            "DueDate" => "05/15/2021", 
                            "EPayCustomerID" => 9985022, 
                            "Field1" => "MWE", 
                            "Field2" => "21266844", 
                            "InvoiceDate" => "08/15/2020", 
                            "InvoiceNumber" => "1979544 / SCC2105618 / 21266844", 
                            "OpenAmount" => 700, 
                            "SiteID" => 316729, 
                            "UsedInConfirmation" => 0 
                        ], 
                        [
                            "ClosedAmount" => 0, 
                            "CustomerID" => "IGGI000-01", 
                            "DueDate" => "06/02/2021", 
                            "EPayCustomerID" => 9985022, 
                            "Field1" => "MWE", 
                            "Field2" => "24241272", 
                            "InvoiceDate" => "03/02/2021", 
                            "InvoiceNumber" => "2202994 / WPP1635533 / 24241272", 
                            "OpenAmount" => 710570, 
                            "SiteID" => 316729, 
                            "UsedInConfirmation" => 0 
                        ], 
                        [
                            "ClosedAmount" => 0, 
                            "CustomerID" => "IGGI000-01", 
                            "DueDate" => "07/02/2021", 
                            "EPayCustomerID" => 9985022, 
                            "Field1" => "MWE", 
                            "Field2" => "24241273", 
                            "InvoiceDate" => "03/02/2021", 
                            "InvoiceNumber" => "2202995 / WPP1635533 / 24241273", 
                            "OpenAmount" => 710570, 
                            "SiteID" => 316729, 
                            "UsedInConfirmation" => 0 
                        ], 
                        [
                            "ClosedAmount" => 0, 
                            "CustomerID" => "IGGI000-01", 
                            "DueDate" => "08/02/2021", 
                            "EPayCustomerID" => 9985022, 
                            "Field1" => "MWE", 
                            "Field2" => "24241274", 
                            "InvoiceDate" => "03/02/2021", 
                            "InvoiceNumber" => "2202996 / WPP1635533 / 24241274", 
                            "OpenAmount" => 710570, 
                            "SiteID" => 316729, 
                            "UsedInConfirmation" => 0 
                        ], 
                        [
                            "ClosedAmount" => 0, 
                            "CustomerID" => "IGGI000-01", 
                            "DueDate" => "09/02/2021", 
                            "EPayCustomerID" => 9985022, 
                            "Field1" => "MWE", 
                            "Field2" => "24241275", 
                            "InvoiceDate" => "03/02/2021", 
                            "InvoiceNumber" => "2202997 / WPP1635533 / 24241275", 
                            "OpenAmount" => 710570, 
                            "SiteID" => 316729, 
                            "UsedInConfirmation" => 0 
                        ], 
                        [
                            "ClosedAmount" => 0, 
                            "CustomerID" => "IGGI000-01", 
                            "DueDate" => "10/02/2021", 
                            "EPayCustomerID" => 9985022, 
                            "Field1" => "MWE", 
                            "Field2" => "24241276", 
                            "InvoiceDate" => "03/02/2021", 
                            "InvoiceNumber" => "2202998 / WPP1635533 / 24241276", 
                            "OpenAmount" => 710570, 
                            "SiteID" => 316729, 
                            "UsedInConfirmation" => 0 
                        ], 
                        [
                            "ClosedAmount" => 0, 
                            "CustomerID" => "IGGI000-01", 
                            "DueDate" => "11/02/2021", 
                            "EPayCustomerID" => 9985022, 
                            "Field1" => "MWE", 
                            "Field2" => "24241277", 
                            "InvoiceDate" => "03/02/2021", 
                            "InvoiceNumber" => "2202999 / WPP1635533 / 24241277", 
                            "OpenAmount" => 710570, 
                            "SiteID" => 316729, 
                            "UsedInConfirmation" => 0 
                        ], 
                        [
                            "ClosedAmount" => 0, 
                            "CustomerID" => "IGGI000-01", 
                            "DueDate" => "12/02/2021", 
                            "EPayCustomerID" => 9985022, 
                            "Field1" => "MWE", 
                            "Field2" => "24241278", 
                            "InvoiceDate" => "03/02/2021", 
                            "InvoiceNumber" => "2203000 / WPP1635533 / 24241278", 
                            "OpenAmount" => 710570, 
                            "SiteID" => 316729, 
                            "UsedInConfirmation" => 0 
                        ] 
                    ] 
                ] 
            ]; 
            $mockSoapClient->expects('makeCall')->with('GetEPayInvoice',$requestData, true)->once()->andReturn($mockData);
        }
        $response = $this->soapClient->makeCall('GetEPayInvoice', $requestData, true);
        $responseArray = $response['GetEPayInvoiceResult']['GetEPayInvoicesResult'];
        $this->assertEquals($responseArray[0]['InvoiceNumber'],"2167397 / CL3578324A / 23717752");
        $this->assertEquals($responseArray[0]['Field1'],"MWE");
        $this->assertEquals($responseArray[5]['OpenAmount'],221300);
        $this->assertEquals($responseArray[5]['InvoiceNumber'],'2204505 / 103 GL 0029480-00 / 24254264');
        $this->assertEquals($responseArray[15]['OpenAmount'],710570);
        $this->assertEquals($responseArray[15]['InvoiceNumber'],'2203000 / WPP1635533 / 24241278');
    }
}