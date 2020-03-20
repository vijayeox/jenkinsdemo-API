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
                     'roaster' => 'Roaster',
                     'roasterHeader' => 'Roaster_header.html',
                     'roasterFooter' => 'Roaster_footer.html',
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
                     'lheader' => 'letter_header.html',
                     'lfooter' => 'letter_footer.html',
                     'policy' => array('liability' => 'Dive_Store_Liability_Policy.pdf','property' => 'Dive_Store_Property_Policy.pdf'),
                     'gtemplate' => 'Group_PL_COI',
                     'gheader' => 'Group_header.html',
                     'gfooter' => 'Group_footer.html'
                    ));   
    }
}
