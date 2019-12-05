<?php

require_once __DIR__."/PolicyDocument.php";

class LapseLetter extends PolicyDocument
{
     public function __construct(){
        parent::__construct();
        $this->type = 'lapse';
        $this->template = array(
        'Individual Professional Liability' 
            => array('lheader' => 'letter_header.html',
                     'lfooter' => 'letter_footer.html',
                     'ltemplate' => 'Individual_PL_Lapse_Letter')
        );
    }
}
