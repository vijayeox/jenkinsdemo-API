<?php
use Oxzion\Db\Persistence\Persistence;
use Oxzion\Utils\ArtifactUtils;
use Oxzion\Utils\FileUtils;
use Oxzion\Auth\AuthConstants;
use Oxzion\Auth\AuthContext;
require_once __DIR__ . "/PolicyDocument.php";

class LapseLetter extends PolicyDocument
{
    public function __construct()
    {
        parent::__construct();
        $this->type = 'lapse';
        $this->template = array(
            'Individual Professional Liability' => array('lheader' => 'letter_header.html',
                'lfooter' => 'letter_footer.html',
                'ltemplate' => 'Individual_PL_Lapse_Letter'),
            'Emergency First Response' => array('lheader' => 'letter_header.html',
                'lfooter' => 'letter_footer.html',
                'ltemplate' => 'EFR_Lapse_Letter'),
            'Dive Store' => array('lheader' => 'letter_header.html',
                'lfooter' => 'letter_footer.html',
                'ltemplate' => 'Dive_Store_Lapse_Letter'),
        );
    }

    public function execute(array $data,Persistence $persistenceService){
        $orgUuid = isset($data['orgUuid']) ? $data['orgUuid'] : ( isset($data['orgId']) ? $data['orgId'] :AuthContext::get(AuthConstants::ORG_UUID));  

        $dest = ArtifactUtils::getDocumentFilePath($this->destination,$data['uuid'],array('orgUuid' => $orgUuid));
        $this->logger->info('the  destination consists of : '.print_r($dest, true));
        $data['license_number'] = $this->getLicenseNumber($data,$persistenceService);
        $temp = $data;
        foreach ($temp as $key => $value) {
            if(is_array($temp[$key])){
                $temp[$key] = json_encode($value);
            }
        }
        
        $this->logger->info("DOCUMENT lapse");
         $data['documents'] = is_string($data['documents']) ? json_decode($data['documents'],true) : $data['documents'];
        $data['documents']['lapseLetter'] =  $this->generateDocuments($temp,$dest,array(),'ltemplate','lheader','lfooter');
        $this->logger->info("LAPSE LETTER --- ".print_r($data['documents'],true));
        return $data;
    }
}
