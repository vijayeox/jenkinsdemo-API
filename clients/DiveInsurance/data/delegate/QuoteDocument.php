<?php


require_once __DIR__."/PolicyDocument.php";

class QuoteDocument extends PolicyDocument
{
    public function __construct(){
        parent::__construct();
        $this->type = 'quote';
        $this->template = array(
        'Dive Boat' 
            => array(
                     'cover_letter' => 'Dive_Boat_Quote_Cover_Letter',
                     'lheader' => 'letter_header.html',
                     'lfooter' => 'letter_footer.html',
                     'template' => 'DiveBoat_Quote',
                     'header' => 'DB_Quote_header.html',
                     'footer' => 'DB_Quote_footer.html',
                     'aiTemplate' => 'DiveBoat_AI',
                     'aiheader' => 'DB_Quote_AI_header.html',
                     'aifooter' => null,
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
                     'lpfooter' => 'DiveBoat_LP_footer.html'),
        'Dive Store' 
            => array(
                     'template' => 'DiveCenterProposal_Template', 
                     'header' => 'DiveCenterProposal_header.html',
                     'footer' => 'DiveCenterProposal_footer.html', 
                     'cover_letter' => 'Dive_Store_Cover_Letter',
                     'lheader' => 'letter_header.html',
                     'lfooter' => 'letter_footer.html',
                     'aiTemplate' => 'DiveStore_AI',
                     'aiheader' => 'DS_Quote_AI_header.html',
                     'aifooter' => null,
                     'aniTemplate' => 'DiveStore_ANI',
                     'aniheader' => 'DS_Quote_ANI_header.html',
                     'anifooter' => null,
                     'lpTemplate' => 'DS_Quote_LP',
                     'lpheader' => 'DS_LP_header.html',
                     'lpfooter' => null,
                     'policy' => array('liability' => 'Dive_Store_Liability_Policy.pdf','property' => 'Dive_Store_Property_Policy.pdf'),
                     'gtemplate' => 'Group_PL_COI',
                     'gheader' => 'Group_header.html',
                     'gfooter' => 'Group_footer.html',
                    ));
        
    }
}
