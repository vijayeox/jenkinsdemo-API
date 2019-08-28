<?php
namespace Oxzion\Document;

use Oxzion\Service\TemplateService;
use Oxzion\Utils\ArtifactUtils;
use Oxzion\Document\DocumentGenerator;

class DocumentBuilder {
    private $templateService;
    private $documentGenerator;
    private $config;

    public function __construct($config, TemplateService $templateService, DocumentGenerator $documentGenerator){
        $this->config = $config;
        $this->templateService = $templateService;
        $this->documentGenerator = $documentGenerator;
    }

    /**
    *  $template - string - template name
    *  $data - array - the context data to process the template
    *               orgUuid - if present will be used else current logged in org uuid 
    *                         will be used 
    *  $destination - string - the file path of the destination file
    *  $options - array 
    *               header - name of the header file (path will be discovered using conventions)
    *               footer - name of the footer file (path will be discovered using conventions)
    */
    public function generateDocument($template, $data, $destination, $options){
        $content = $this->templateService->getContent($template, $data);
        if($options && isset($options['header'])){
            $header = $options['header'];
            $header = ArtifactUtils::getTemplatePath($this->config, $header, $data);
        }
        if($options && isset($options['footer'])){
            $footer = $options['footer'] ;
            $footer = ArtifactUtils::getTemplatePath($this->config, $footer, $data);
        }
        
        return $this->documentGenerator->generatePdfDocumentFromHtml($content, $destination, $header, $footer);
    }
}