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
             'aiTemplate' => 'DiveBoat_AI',
             'aiheader' => 'DiveBoat_AI_header.html',
             'aifooter' => 'DiveBoat_AI_footer.html',
             'aniTemplate' => 'DiveBoat_ANI',
             'aniheader' => 'DB_Quote_ANI_header.html',
             'anifooter' => null,
             'policy' => 'Dive_Boat_Policy.pdf',
             'roster' => 'Roster',
             'rosterHeader' => 'Roster_header.html',
             'rosterFooter' => 'Roster_footer.html',
             'lpTemplate' => 'DiveBoat_LP',
             'lpheader' => 'DiveBoat_LP_header.html',
             'lpfooter' => 'DiveBoat_LP_footer.html',
             'waterEndorsement' => 'DB_In_Water_Crew_Endorsement.pdf'),
            'Dive Store' 
            => array(
             'template' => 'DiveStoreEndorsement', 
             'header' => 'DiveStoreEndorsement_header.html',
             'footer' => 'DiveStoreEndorsement_footer.html', 
             'cover_letter' => 'Dive_Store_Cover_Letter',
             'aiTemplate' => 'DiveStore_AI',
             'aiheader' => 'DiveStore_AI_header.html',
             'lpTemplate' => 'DiveStore_LP',
             'lpheader' => 'DiveStore_LP_header.html',
             'lpfooter' => 'DiveStore_LP_footer.html',
             'aifooter' => 'DiveStore_AI_footer.html',
             'lheader' => 'letter_header.html',
             'lfooter' => 'letter_footer.html',
             'nTemplate' => 'Group_PL_NI',
             'nheader' => 'Group_NI_header.html',
             'nfooter' => 'Group_NI_footer.html',
             'aniTemplate' => 'DiveStore_ANI',
             'aniheader' => 'DS_Quote_ANI_header.html',
             'anifooter' => null,
             'policy' => array('liability' => 'Dive_Store_Liability_Policy.pdf','property' => 'Dive_Store_Property_Policy.pdf'),
             'alheader' => 'DiveStore_AL_header.html',
             'alfooter' => 'DiveStore_AL_footer.html',
             'alTemplate' => 'DiveStore_AdditionalLocations',
             'gtemplate' => 'Group_PL_COI',
             'gheader' => 'Group_header.html',
             'gfooter' => 'Group_footer.html',
             'travelAgentEO' => 'Travel_Agents_PL_Endorsement.pdf'
         ));   
    }
}
