<?php

use Oxzion\AppDelegate\AbstractDocumentAppDelegate;
use Oxzion\Db\Persistence\Persistence;
use Oxzion\Utils\ArtifactUtils;
use Oxzion\AppDelegate\FileTrait;
use Oxzion\Auth\AuthContext;
use Oxzion\Auth\AuthConstants;
use Oxzion\AppDelegate\AccountTrait;
use Oxzion\Utils\FileUtils;

class CreateInvoicePDF extends AbstractDocumentAppDelegate
{
    use FileTrait;
    use AccountTrait;

    public function __construct()
    {
        parent::__construct();
    }

    private function isJSON($data) {
        $array = json_decode($data, true);	
        if(is_array($array) && json_last_error() == JSON_ERROR_NONE){	
            return true;	
        }	
        return false;
    }

    public function execute(array $data, Persistence $persistenceService)
    {
        $invoicePDFTemplate = "Invoice";
        $invoicePDFName = $data['invoiceUuid'].".pdf";
        $generatedInvoicePDF = array();

        $accountId = $data['accountId'];
        $appId = $data['appId'];

        $data = $this->formatInvoiceData($data);
        $folderDestination =  ArtifactUtils::getDocumentFilePath($this->destination,"invoice/".$appId, array('accountId' => $accountId));
        $fileDestination = $folderDestination['absolutePath'].$invoicePDFName;
        if(FileUtils::fileExists($fileDestination)) {
            FileUtils::deleteFile($invoicePDFName,$folderDestination['absolutePath']);
        }
        $doc = $this->documentBuilder->generateDocument($invoicePDFTemplate,$data,$fileDestination);
        $documentpdf = $folderDestination['relativePath'] . $invoicePDFName;
        array_push(
            $generatedInvoicePDF,
            array(
                "name" => $invoicePDFName,
                "fullPath" => $fileDestination,
                "file" => $documentpdf,
                "originalName" => $invoicePDFName,
                "type" => "file/pdf",
            )
        );
        $data['attachments'] = $generatedInvoicePDF;
        return $data;
    }

    public function formatInvoiceData($data)
    {
        $invoiceAmount = $data['total'];
        $amountPaid = isset($data['amountPaid'])?$data['amountPaid']:0.0;
        $amountDue = $invoiceAmount - $amountPaid;

        $data['amountDue'] = number_format($amountDue, 2, '.', ',');
        $data['amountPaid'] = isset($data['amountPaid'])?number_format($data['amountPaid'], 2, '.', ','):'0.0';
        $data['total'] = number_format($data['total'], 2, '.', ',');
        $data['subtotal'] = number_format($data['subtotal'], 2, '.', ',');
        $data['tax'] = number_format($data['tax'], 2, '.', ',');

        
        foreach($data['ledgerData'] as $key=> $lineItem)
        {
            $data['ledgerData'][$key]['amount'] = number_format($lineItem['amount'], 2, '.', ',');
            $data['ledgerData'][$key]['unitCost'] = number_format($lineItem['unitCost'], 2, '.', ',');
        } 

        
        return $data;
    }


}