<?php


require_once __DIR__."/PolicyDocument.php";

class EndorsementDocument extends PolicyDocument
{
    public function __construct(){
        parent::__construct();
        $this->type = 'endorsement';
        $this->template = array(
        'Dive Boat' 
            => array(
                     'cover_letter' => 'Dive_Boat_Quote_Cover_Letter',
                     'lheader' => 'letter_header.html',
                     'lfooter' => 'letter_footer.html',
                     'template' => 'DiveBoat_Endorsement',
                     'header' => 'DB_Endorsement_header.html',
                     'footer' => 'DB_Endorsement_footer.html',
                     'aniTemplate' => 'DiveBoat_ANI',
                     'aniheader' => 'DB_Quote_ANI_header.html',
                     'anifooter' => null,
                     'policy' => 'Dive_Boat_Policy.pdf',
                     'gtemplate' => 'Group_PL_COI',
                     'gheader' => 'Group_header.html',
                     'gfooter' => 'Group_footer.html',
                     'nTemplate' => 'Group_PL_NI',
                     'nheader' => 'Group_NI_header.html',
                     'nfooter' => 'Group_NI_footer.html',
                     'lpTemplate' => 'DiveBoat_LP',
                     'lpheader' => 'DiveBoat_LP_header.html',
                     'lpfooter' => 'DiveBoat_LP_footer.html',
                     'waterEndorsement' => 'DB_In_Water_Crew_Endorsement.pdf',
                     'blanketForm' => 'DB_AI_Blanket_Endorsement.pdf',
                     'groupExclusions' => 'Group_Exclusions.pdf'),
        'Dive Store' 
            => array(
                     'template' => 'DiveStoreEndorsement', 
                     'header' => 'DiveStoreEndorsement_header.html',
                     'footer' => 'DiveStoreEndorsement_footer.html', 
                     'cover_letter' => 'Dive_Store_Cover_Letter',
                     'lheader' => 'letter_header.html',
                     'lfooter' => 'letter_footer.html',
                     'policy' => array('liability' => 'Dive_Store_Liability_Policy.pdf','property' => 'Dive_Store_Property_Policy.pdf'),
                     'gtemplate' => 'Group_PL_COI',
                     'gheader' => 'Group_header.html',
                     'gfooter' => 'Group_footer.html',
                     'groupExclusions' => 'Group_Exclusions.pdf'
                    ));        
    }
}
