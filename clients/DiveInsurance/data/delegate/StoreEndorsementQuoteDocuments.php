<?php


require_once __DIR__."/PolicyDocument.php";
use Oxzion\Db\Persistence\Persistence;
use Oxzion\Utils\ArtifactUtils;
use Oxzion\Utils\FileUtils;

class StoreEndorsementQuoteDocuments extends PolicyDocument
{

    public function __construct(){
        parent::__construct();
        $this->type = 'endorsementQuote';
        $this->template = array(
            'Dive Boat'
            => array(
             'cover_letter' => 'Dive_Boat_Quote_Cover_Letter',
             'lheader' => 'letter_header.html',
             'lfooter' => 'letter_footer.html',
             'template' => 'DiveBoat_Endorsement',
             'header' => 'DB_EndorsementQuote_header.html',
             'footer' => 'DB_Endorsement_footer.html',
             'aniTemplate' => 'DiveBoat_ANI',
             'aniheader' => 'DB_Quote_ANI_header.html',
             'anifooter' => null,
             'policy' => 'Dive_Boat_Policy.pdf',
             'roster' => 'Roster_Certificate',
             'rosterHeader' => 'Roster_header.html',
             'rosterFooter' => 'Roster_footer.html',
             'rosterPdf' => 'Roster.pdf',
             'lpTemplate' => 'DiveBoat_LP',
             'lpheader' => 'DB_Quote_LP_header.html',
             'lpfooter' => 'DiveBoat_LP_footer.html',
             'waterEndorsement' => 'DB_In_Water_Crew_Endorsement.pdf',
             'boatAcknowledgement' => 'CREW_EXCLUSION_ACKNOWLEDGEMENT.pdf',
             'waterAcknowledgement' => 'CREW_EXCLUSION_ACKNOWLEDGEMENT_InWater.pdf',
             'hurricaneQuestionnaire' => 'PADI_Hurricane_Questionnaire',
             'groupExclusions' => 'Group_Exclusions.pdf'),
            'Dive Store' 
            => array(
             'template' => 'DiveStoreEndorsement', 
             'header' => 'DiveStoreEndorsement_header.html',
             'footer' => 'DiveStoreEndorsement_footer.html', 
             'cover_letter' => 'DS_Quote_Cover_Letter',
             'aiTemplate' => 'DiveStore_AI',
             'aiheader' => 'DiveStore_AI_header.html',
             'lpTemplate' => 'DiveStore_LP',
             'lpheader' => 'DiveStore_Quote_LP_header.html',
             'lpfooter' => 'DiveStore_LP_footer.html',
             'aifooter' => 'DiveStore_AI_footer.html',
             'lheader' => 'letter_header.html',
             'lfooter' => 'letter_footer.html',
             'nTemplate' => 'Group_PL_NI',
             'nheader' => 'Group_DS_NI_header.html',
             'nfooter' => 'Group_NI_footer.html',
             'gaitemplate' => 'Group_AI',
             'gaiheader' => 'Group_Quote_AI_header.html',
             'gaifooter' => 'Group_AI_footer.html',
             'aniTemplate' => 'DiveStore_ANI',
             'aniheader' => 'DS_Quote_ANI_header.html',
             'anifooter' => null,
             'alheader' => 'DiveStore_AL_Proposal_header.html',
             'alfooter' => 'DiveStore_AL_footer.html',
             'alTemplate' => 'DiveStore_AdditionalLocations',
             'roster' => 'Roster_Certificate',
             'rosterHeader' => 'Roster_header_DS.html',
             'rosterFooter' => 'Roster_footer.html',
             'rosterPdf' => 'Roster.pdf',
             'travelAgentEO' => 'Travel_Agents_PL_Endorsement.pdf',
             'groupExclusions' => 'Group_Exclusions.pdf'
         ),
        'Group Professional Liability' 
            => array(
             'header' => 'DiveStoreEndorsement_header.html',
             'footer' => 'DiveStoreEndorsement_footer.html', 
             'cover_letter' => 'GPL_Quote_Cover_Letter',
             'aiTemplate' => 'DiveStore_AI',
             'aiheader' => 'DiveStore_AI_header.html',
             'lpTemplate' => 'DiveStore_LP',
             'lpheader' => 'DiveStore_Quote_LP_header.html',
             'lpfooter' => 'DiveStore_LP_footer.html',
             'aifooter' => 'DiveStore_AI_footer.html',
             'lheader' => 'letter_header.html',
             'lfooter' => 'letter_footer.html',
             'nTemplate' => 'Group_PL_NI',
             'nheader' => 'Group_DS_NI_header.html',
             'nfooter' => 'Group_NI_footer.html',
             'gaitemplate' => 'Group_AI',
             'gaiheader' => 'Group_Quote_AI_header.html',
             'gaifooter' => 'Group_AI_footer.html',
             'aniTemplate' => 'DiveStore_ANI',
             'aniheader' => 'DS_Quote_ANI_header.html',
             'anifooter' => null,
             'alheader' => 'DiveStore_AL_Proposal_header.html',
             'alfooter' => 'DiveStore_AL_footer.html',
             'alTemplate' => 'DiveStore_AdditionalLocations',
             'roster' => 'Roster_Certificate',
             'rosterHeader' => 'Roster_header_DS.html',
             'rosterFooter' => 'Roster_footer.html',
             'rosterPdf' => 'Roster.pdf',
             'travelAgentEO' => 'Travel_Agents_PL_Endorsement.pdf',
             'groupExclusions' => 'Group_Exclusions.pdf'
         ));   
    }

    public function execute(array $data,Persistence $persistenceService)
    {
        $originalData = $data;
        $options = array();
        if(isset($data['endorsement_options'])){
            $endorsementOptions = is_array($data['endorsement_options']) ?  $data['endorsement_options'] : json_decode($data['endorsement_options'],true);
        }else{
            $endorsementOptions = null;
        }
        $orgUuid = isset($data['orgUuid']) ? $data['orgUuid'] : ( isset($data['orgId']) ? $data['orgId'] :AuthContext::get(AuthConstants::ORG_UUID));
        $data['orgUuid'] = $orgUuid;
        $liabilityPolicyDetails = $this->getPolicyDetails($data,$persistenceService,$data['product'],'LIABILITY');
        if($liabilityPolicyDetails){
            $data['liability_policy_id'] = $liabilityPolicyDetails['policy_number'];
            $data['liability_carrier'] = $liabilityPolicyDetails['carrier'];
        }

        $propertyPolicyDetails = $this->getPolicyDetails($data,$persistenceService,$data['product'],'PROPERTY');
        if($propertyPolicyDetails){
            $data['property_policy_id'] = $propertyPolicyDetails['policy_number'];
            $data['property_carrier'] = $propertyPolicyDetails['carrier'];
        }
        $dest = ArtifactUtils::getDocumentFilePath($this->destination,$data['fileId'],array('orgUuid' => $orgUuid));
        if(!is_null($endorsementOptions)){
            $workflowInstUuid = $this->getWorkflowInstanceByFileId($data['fileId'],'In Progress');
            if( count($workflowInstUuid) > 0 && (isset($workflowInstUuid[0]['process_instance_id']))){
                $dest['absolutePath'] .= $workflowInstUuid[0]['process_instance_id']."/";
                $dest['relativePath'] .= $workflowInstUuid[0]['process_instance_id']."/";
                FileUtils::createDirectory($dest['absolutePath']);
            }
        }
        $data['dest'] = $dest;
        FileUtils::deleteDirectoryContents($dest['absolutePath'].'Quote/');
        $dest['relativePath'] = $dest['relativePath'].'Quote/';
        $dest['absolutePath'] = $dest['absolutePath'].'Quote/';
        $documents = array();
        $temp = $data;
        foreach ($temp as $key => $value) {
            if(is_array($temp[$key])){
                $temp[$key] = json_encode($value);
            }
        }
        if(isset($data['previous_policy_data'])){
            $previous_data = array();
            $previous_data = is_string($data['previous_policy_data']) ? json_decode($data['previous_policy_data'],true) : $data['previous_policy_data'];
            $length = sizeof($previous_data);
        }else{
            $previous_data = array();
            $length = 0;
        }
        $this->diveStoreEndorsement($data,$temp);
        $this->diveStoreEnorsementQuoteDocuments($data,$documents,$temp,$dest,$options,$previous_data,$endorsementOptions,$length);
        $originalData['documents']=$documents;
        $originalData['quoteDocuments'] = $documents;
        $originalData['policyStatus'] = "Quote Approval Pending";
        return $originalData;
    }
}
