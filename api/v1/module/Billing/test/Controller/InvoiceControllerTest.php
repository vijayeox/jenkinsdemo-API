<?php
namespace Billing;

use Oxzion\Test\ControllerTest;
use Billing\Controller\InvoiceController;
use PHPUnit\DbUnit\DataSet\YamlDataSet;
use Oxzion\Auth\AuthConstants;
use Oxzion\Auth\AuthContext;
class InvoiceControllerTest extends ControllerTest
{
    public function setUp(): void
    {
        $this->loadConfig();
        parent::setUp();

    }

    public function getDataSet()
    {
        $dataset = new YamlDataSet(dirname(__FILE__) . "/../Dataset/Billing.yml");
        return $dataset;
    }

    protected function setDefaultAsserts()
    {
        $this->assertControllerName(InvoiceController::class); // as specified in router's controller name alias
        $this->assertControllerClass('InvoiceController');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
    }

    public function testCreate()
    {
        $this->initAuthToken($this->adminUser);
        $data = '{"accountId":"dbad359d-1619-49d5-a58c-94dd4f5bc7c1","accountNumber":"35642575346","amountPaid":200,"appId":"aff3ddd4-e411-11e9-a359-2a2ae2dbcce4","invoiceDate":"2021-11-18T00:00:00+05:30","invoiceDueDate":"2021-11-19T00:00:00+05:30","invoiceNumber":"123456453257","ledgerData":[{"description":"Service1","quantity":1,"transactionEffectiveDate":"2021-11-18T00:00:00+05:30","transactionDueDate":"2021-11-19T00:00:00+05:30","amount":60,"unitCost":60},{"description":"Service2","quantity":1,"transactionEffectiveDate":"2021-11-18T00:00:00+05:30","transactionDueDate":"2021-11-19T00:00:00+05:30","unitCost":200,"amount":200}],"subtotal":260,"tax":"0.00","total":260}';
        $this->setJsonContent($data);
        $this->dispatch('/billing/invoice', 'POST', json_decode($data,true));
        $response = $this->getResponse()->getContent();
        $response = json_decode($response,true);
        $responseData = $response['data'];
        $invoiceId = $responseData['invoiceUuid'];
        $total = $responseData['total'];

        $select = "SELECT * FROM ox_billing_invoice WHERE uuid= \"".$invoiceId.'"';
        // $select = "SELECT * FROM ox_billing_invoice";
        $result = $this->executeQueryTest($select);
        $this->assertEquals($result[0]['uuid'],$invoiceId);
        $this->assertEquals($result[0]['amount'],$total);

        $pdfPath = $this->applicationConfig['APP_DOCUMENT_FOLDER']."dbad359d-1619-49d5-a58c-94dd4f5bc7c1/invoice/aff3ddd4-e411-11e9-a359-2a2ae2dbcce4/".$invoiceId.".pdf";
        $this->assertTrue(file_exists($pdfPath));
    }

    public function testUpdateFromPost()
    {
        $this->initAuthToken($this->adminUser);
        $data = '{"fileId":"dcf9ce1e-b861-4b72-9280-9f919f11368d","invoiceUuid":"0ac9b6ee-2330-47b5-a571-e6f6d4834c88","accountId":"dbad359d-1619-49d5-a58c-94dd4f5bc7c1","accountNumber":"35642575346789","amountPaid":200,"appId":"aff3ddd4-e411-11e9-a359-2a2ae2dbcce4","invoiceDate":"2021-11-18T00:00:00+05:30","invoiceDueDate":"2021-11-19T00:00:00+05:30","invoiceNumber":"12345645325789","ledgerData":[{"description":"Service1","quantity":1,"transactionEffectiveDate":"2021-11-18T00:00:00+05:30","transactionDueDate":"2021-11-19T00:00:00+05:30","amount":60,"unitCost":60},{"description":"Service2","quantity":1,"transactionEffectiveDate":"2021-11-18T00:00:00+05:30","transactionDueDate":"2021-11-19T00:00:00+05:30","unitCost":200,"amount":200}],"subtotal":260,"tax":"40.00","total":300}';
        $this->setJsonContent($data);

        $select =  "SELECT * FROM ox_billing_invoice WHERE uuid= '0ac9b6ee-2330-47b5-a571-e6f6d4834c88'";
        $result = $this->executeQueryTest($select);
        $currentAmount = $result[0]["amount"];

        $select = "SELECT * FROM ox_file WHERE uuid= 'dcf9ce1e-b861-4b72-9280-9f919f11368d'";
        $result = $this->executeQueryTest($select);
        $fileData = json_decode($result[0]['data'],true);
        $currentAmountInFile = $fileData['total'];

        $updatedData = json_decode($data,true);

        $this->dispatch('/billing/invoice', 'POST', $updatedData);
        $response = $this->getResponse()->getContent();
        $response = json_decode($response,true);
        $responseData = $response['data'];
        $invoiceId = $responseData['invoiceUuid'];
        $total = $responseData['total'];
        $fileId = $responseData['fileId'];

        $select = "SELECT * FROM ox_billing_invoice WHERE uuid= \"".$invoiceId.'"';
        $result = $this->executeQueryTest($select);

        $this->assertNotEquals($currentAmount,$total);
        $this->assertEquals($result[0]['uuid'],$invoiceId);
        $this->assertEquals($result[0]['amount'],$total);

        $select = "SELECT * FROM ox_file WHERE uuid= \"".$fileId.'"';
        $result = $this->executeQueryTest($select);
        $updatedFileData = json_decode($result[0]['data'],true);
        $this->assertNotEquals($updatedFileData['total'],$currentAmountInFile);
        $this->assertEquals($updatedFileData['total'],$total);

        $pdfPath = $this->applicationConfig['APP_DOCUMENT_FOLDER']."dbad359d-1619-49d5-a58c-94dd4f5bc7c1/invoice/aff3ddd4-e411-11e9-a359-2a2ae2dbcce4/".$invoiceId.".pdf";
        $this->assertTrue(file_exists($pdfPath));
    }

    public function testUpdateFromPut()
    {
        $this->initAuthToken($this->adminUser);
        $data = '{"fileId":"dcf9ce1e-b861-4b72-9280-9f919f11368d","invoiceUuid":"0ac9b6ee-2330-47b5-a571-e6f6d4834c88","accountId":"dbad359d-1619-49d5-a58c-94dd4f5bc7c1","accountNumber":"35642575346789","amountPaid":200,"appId":"aff3ddd4-e411-11e9-a359-2a2ae2dbcce4","invoiceDate":"2021-11-18T00:00:00+05:30","invoiceDueDate":"2021-11-19T00:00:00+05:30","invoiceNumber":"12345645325789","ledgerData":[{"description":"Service1","quantity":1,"transactionEffectiveDate":"2021-11-18T00:00:00+05:30","transactionDueDate":"2021-11-19T00:00:00+05:30","amount":60,"unitCost":60},{"description":"Service2","quantity":1,"transactionEffectiveDate":"2021-11-18T00:00:00+05:30","transactionDueDate":"2021-11-19T00:00:00+05:30","unitCost":200,"amount":200}],"subtotal":260,"tax":"40.00","total":300}';

        $select =  "SELECT * FROM ox_billing_invoice WHERE uuid= '0ac9b6ee-2330-47b5-a571-e6f6d4834c88'";
        $result = $this->executeQueryTest($select);
        $currentAmount = $result[0]["amount"];

        $select = "SELECT * FROM ox_file WHERE uuid= 'dcf9ce1e-b861-4b72-9280-9f919f11368d'";
        $result = $this->executeQueryTest($select);
        $fileData = json_decode($result[0]['data'],true);
        $currentAmountInFile = $fileData['total'];

        $this->setJsonContent($data);
        $this->dispatch('/billing/invoice/0ac9b6ee-2330-47b5-a571-e6f6d4834c88', 'PUT');
        $response = $this->getResponse()->getContent();
        $response = json_decode($response,true);
        $responseData = $response['data'];
        $invoiceId = $responseData['invoiceUuid'];
        $total = $responseData['total'];
        $fileId = $responseData['fileId'];

        $select = "SELECT * FROM ox_billing_invoice WHERE uuid= \"".$invoiceId.'"';
        $result = $this->executeQueryTest($select);

        $this->assertNotEquals($currentAmount,$total);
        $this->assertEquals($result[0]['uuid'],$invoiceId);
        $this->assertEquals($result[0]['amount'],$total);

        $select = "SELECT * FROM ox_file WHERE uuid= \"".$fileId.'"';
        $result = $this->executeQueryTest($select);
        $updatedFileData = json_decode($result[0]['data'],true);
        $this->assertNotEquals($updatedFileData['total'],$currentAmountInFile);
        $this->assertEquals($updatedFileData['total'],$total);

        $pdfPath = $this->applicationConfig['APP_DOCUMENT_FOLDER']."dbad359d-1619-49d5-a58c-94dd4f5bc7c1/invoice/aff3ddd4-e411-11e9-a359-2a2ae2dbcce4/".$invoiceId.".pdf";
        $this->assertTrue(file_exists($pdfPath));
    }

    public function testGetCreatedInvoices()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/billing/invoice?getCreatedInvoices=1', 'GET');
        $response = $this->getResponse()->getContent();
        $response = json_decode($response,true);
        $responseData = $response['data'];

        $this->assertEquals(count($responseData),1);
        $this->assertEquals($responseData[0]['amount'],260);
        $this->assertEquals($responseData[0]['app_id'],'aff3ddd4-e411-11e9-a359-2a2ae2dbcce4');
        $this->assertEquals($responseData[0]['account_id'],'dbad359d-1619-49d5-a58c-94dd4f5bc7c1');
        $this->assertEquals($responseData[0]['uuid'],'0ac9b6ee-2330-47b5-a571-e6f6d4834c88');
        $this->assertTrue(isset($responseData[0]['data']));
    }

    public function testGetAssignedInvoices()
    {
        $this->initAuthToken($this->adminUser);

        $this->dispatch('/billing/invoice', 'GET');
        $response = $this->getResponse()->getContent();
        $response = json_decode($response,true);
        $responseData = $response['data'];
        $this->assertEquals(count($responseData),1);
        $this->assertEquals($responseData[0]['amount'],260);
        $this->assertEquals($responseData[0]['app_id'],'aff3ddd4-e411-11e9-a359-2a2ae2dbcce4');
        $this->assertEquals($responseData[0]['account_id'],'53012471-2863-4949-afb1-e69b0891c98a');
        $this->assertEquals($responseData[0]['uuid'],'0ac9b6ee-2330-47b5-a571-e6f6d4834c89');
        $this->assertTrue(isset($responseData[0]['data']));
    }

}
