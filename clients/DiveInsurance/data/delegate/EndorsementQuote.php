<?php


require_once __DIR__."/PolicyDocument.php";

class EndorsementQuote extends PolicyDocument
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
         ));   
    }
}
