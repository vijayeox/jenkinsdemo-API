<?php

use Oxzion\AppDelegate\AbstractDocumentAppDelegate;
use Oxzion\Db\Persistence\Persistence;
use Oxzion\Utils\ArtifactUtils;
use Oxzion\AppDelegate\FileTrait;
use Oxzion\Auth\AuthContext;
use Oxzion\Auth\AuthConstants;
use Oxzion\AppDelegate\AccountTrait;
use Oxzion\Utils\FileUtils;

class GenerateQuote extends AbstractDocumentAppDelegate
{
    use FileTrait;
    use AccountTrait;

    public function __construct()
    {
        parent::__construct();
    }

    public function execute(array $data, Persistence $persistenceService)
    {
        $template = "ExcessQuote";
        $quotePdfName = 'Quote_'.$data['insuredName'].".pdf";
        $generatedQuotePDF = array();
        $fileUUID = isset($data['uuid']) ? $data['uuid'] : $data['fileId'];
        $currentAccount = isset($data['accountId']) ? $data['accountId'] : null;
        $accountId = isset($data['accountName']) ? $this->getAccountByName($data['accountName']) : (isset($currentAccount) ? $currentAccount : AuthContext::get(AuthConstants::ACCOUNT_UUID));
        $fileData = $this->getFile($data['fileId'],false,$data['accountId']);
        $data = array_merge($data,$fileData['data']);
        $this->logger->info("ACCOUT IS ____" . $accountId);
        $path = "https://i.imgur.com/1zkaS1p.jpeg";
        $img = file_get_contents($path);
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data['avantImageSrc'] = 'data:image/' . $type . ';base64,' . base64_encode($img);
        $folderDestination =  ArtifactUtils::getDocumentFilePath($this->destination, $fileUUID, array('accountId' => $accountId));
        $fileDestination = $folderDestination['absolutePath'].$quotePdfName;
        if(FileUtils::fileExists($fileDestination)) {
            FileUtils::deleteFile($quotePdfName,$folderDestination['absolutePath']);
        }
        $doc = $this->documentBuilder->generateDocument($template,$data,$fileDestination);
        $documentpdf = $folderDestination['relativePath'] . $quotePdfName;
        if(isset($data['documents'])){
            $data['documents'] = is_string($data['documents']) ? json_decode($data['documents'],true) : $data['documents'];
        }else{
            $data['documents'] = array();
        }
        $data['documents']['quote_pdf'] = array(
            "name" => $quotePdfName,
            "fullPath" => $fileDestination,
            "file" => $documentpdf,
            "originalName" => $quotePdfName,
            "type" => "file/pdf",
        );
        $data['policyStatus'] = "Quoted";
        if(isset($data['avantImageSrc'])){
            unset($data['avantImageSrc']);
        }
        $this->saveFile($data, $fileUUID);
        return $data;
    }
}