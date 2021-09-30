<?php

use Oxzion\AppDelegate\AbstractDocumentAppDelegate;
use Oxzion\Db\Persistence\Persistence;
use Oxzion\Utils\ArtifactUtils;
use Oxzion\AppDelegate\FileTrait;
use Oxzion\Auth\AuthContext;
use Oxzion\Auth\AuthConstants;
use Oxzion\AppDelegate\AccountTrait;
use Oxzion\Utils\FileUtils;
use Oxzion\AppDelegate\CommentTrait;


class ExcessLiabilityPDF extends AbstractDocumentAppDelegate
{
    use FileTrait;
    use AccountTrait;
    use CommentTrait;

    public function __construct()
    {
        parent::__construct();
        $this->noteKey = array('hubNote','avantNote');
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
        $fileUUID = isset($data['uuid']) ? $data['uuid'] : $data['fileId'];
        $currentAccount = isset($data['accountId']) ? $data['accountId'] : null;
        $data['policyStatus'] = isset($data['policyStatus']) ? $data['policyStatus'] : "New";
        if($data['policyStatus'] != "UW Review"){
            $this->logger->info("Excess Liability PDF generation ----".json_encode($data));
            $excessLiabilityPDFTemplate = "ABExcessApp";
            $excessLiabilityPDFName = $excessLiabilityPDFTemplate.".pdf";
            $generatedExcessLiabilityPDF = array();
            $accountId = isset($data['accountName']) ? $this->getAccountByName($data['accountName']) : (isset($currentAccount) ? $currentAccount : AuthContext::get(AuthConstants::ACCOUNT_UUID));
            $this->logger->info("ACCOUT IS ____" . $accountId);
            $path = "https://i.imgur.com/1zkaS1p.jpeg";
            $img = file_get_contents($path);
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $data['avantImageSrc'] = 'data:image/' . $type . ';base64,' . base64_encode($img);
            $this->getAppropriateDataForPDF($data);
            $folderDestination =  ArtifactUtils::getDocumentFilePath($this->destination, $fileUUID, array('accountId' => $accountId));
            $fileDestination = $folderDestination['absolutePath'].$excessLiabilityPDFName;
            if(FileUtils::fileExists($fileDestination)) {
                FileUtils::deleteFile($excessLiabilityPDFName,$folderDestination['absolutePath']);
            }
            $doc = $this->documentBuilder->generateDocument($excessLiabilityPDFTemplate,$data,$fileDestination);
            $documentpdf = $folderDestination['relativePath'] . $excessLiabilityPDFTemplate . ".pdf";
            if(isset($data['attachments'])){
                $data['attachments'] = is_string($data['attachments']) ? json_decode($data['attachments'],true) : $data['attachments'];
            }else{
                $data['attachments'] = array();
            }
            $data['attachments']['excess_pdf'] = array(
                "name" => $excessLiabilityPDFName,
                "fullPath" => $fileDestination,
                "file" => $documentpdf,
                "originalName" => $excessLiabilityPDFName,
                "type" => "file/pdf",
            );
            if(isset($data['avantImageSrc'])){
                unset($data['avantImageSrc']);
            }
        }
        foreach($this->noteKey as $value){
            if(isset($data[$value]) && $data[$value] != ""){
                $comments = array();
                $comments['text'] = $data[$value];
                $this->createComment($comments, $fileUUID);
                $data[$value] = "";
            }
        }
        $this->saveFile($data, $fileUUID);
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