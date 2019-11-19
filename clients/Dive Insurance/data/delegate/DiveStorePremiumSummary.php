<?php
use Oxzion\Db\Persistence\Persistence;

require_once __DIR__."/PolicyDocument.php";


class DiveStorePremiumSummary extends PolicyDocument
{
    public function __construct(){
        parent::__construct();
        $this->type = 'premiumSummary';
        $this->template = array(
        'Dive Store'
            => array('template' => 'PocketCard',
                     'header' => 'letter_header.html',
                     'footer' => 'letter_footer.html'));
        }

    public function execute(array $data,Persistence $persistenceService) 
    {     
        $this->setPolicyInfo($data,$persistenceService);
        $dest = $data['dest'];
        unset($data['dest']);

        $options = array();
        $documents = array();
        if(isset($this->template[$data['product']]['header'])){
            $options['header'] = $this->template[$data['product']]['header'];
        }
        if(isset($this->template[$data['product']]['footer'])){
            $options['footer'] = $this->template[$data['product']]['footer'];
        }

        $template = $this->template[$data['product']]['template'];
        
        $destAbsolute = $dest['absolutePath'].'Premium_Summary.pdf';

        $this->documentBuilder->generateDocument($template, $data, $destAbsolute, $options);

        $data['documents']['premium_summary'] = $dest['relativePath'].'Premium_Summary.pdf';

        return $data;
    }

 
}
