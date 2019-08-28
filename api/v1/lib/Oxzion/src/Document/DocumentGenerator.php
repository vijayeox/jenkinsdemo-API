<?php
namespace Oxzion\Document;

interface DocumentGenerator
{
    public function generateDocument($htmlContent, $destination, array $options);

    public function generatePdfDocumentFromHtml($htmlContent, $destination, $header = null,$footer = null);
    // public function generateDocumentFromFile($filePath,$destination);

    // public function mergeDocuments($sourceArray,$destination);

    // public function overlayDocument($sourcePdf,$destination,$data);
}
