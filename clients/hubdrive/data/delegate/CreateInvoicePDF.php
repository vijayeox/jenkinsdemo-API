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
        // $fileUUID = isset($data['uuid']) ? $data['uuid'] : $data['fileId'];

        $accountId = $data['accountId'];
        $appId = $data['appId'];

        // $this->getAppropriateDataForPDF($data);

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
        // $this->saveFile($data, $fileUUID);
        return $data;
    }

    private function getAppropriateDataForPDF(&$data) {
        $data['quoteByDateFormatted'] = isset($data['quoteByDate']) ? explode('T',$data['quoteByDate'])[0] : null;

        $data['desiredPolicyEffectiveDateFormatted'] = isset($data['desiredPolicyEffectiveDate']) ? explode('T',$data['desiredPolicyEffectiveDate'])[0] : null;

        $temp = array();
        $temp['city'] = isset($data['city']) ? $data['city'] : null;
        $temp['state'] = isset($data['state']['name']) ? $data['state']['name'] : null;
        $temp['zipCode'] = isset($data['zipCode']) ? $data['zipCode'] : null;

        $data['csz'] = $temp['city']."/".$temp['state']."/".$temp['zipCode'];
        $data['checked'] = "checked";
    }
}