<?php
namespace Oxzion\Document;

interface DocumentGenerator
{
    public function generateDocument($htmlContent, $destination, array $options);

    public function generatePdfDocumentFromHtml($htmlContent, $destination, $header = null,$footer = null,array $append = null,array $prepend = null);
    // public function generateDocumentFromFile($filePath,$destination);

    public function mergeDocuments(array $sourceArray,$destination);

    public function fillPDFForm($template,$data,$destination);

    // public function overlayDocument($sourcePdf,$destination,$data);
}
