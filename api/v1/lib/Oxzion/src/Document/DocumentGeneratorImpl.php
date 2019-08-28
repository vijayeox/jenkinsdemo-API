<?php
namespace Oxzion\Document;

use Oxzion\Service\TemplateService;
use TCPDF;
use Knp\Snappy\Pdf;

class DocumentGeneratorImpl implements DocumentGenerator
{
    public function generateDocument($htmlContent, $destination, array $options, $signatureCerticate=null)
    {
        // if (!$options) {
        //     $file = '/var/www/lib/Oxzion/test/DocumentTest/headerInfo.json';
        //     $json = file_get_contents($file);
        //     $options = json_decode($json, true);
        // }
        
        // create new PDF document
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
       
        // set default header data
        if(!empty($options)){
             $pdf->SetHeaderData($options['pdf_header_logo'], $options['pdf_header_logo_width'], $options['initial_title'], $options['second_title'], $options['header_text_color'], $options['header_line_color']);
        
           $pdf->setFooterData($options['footer_text_color'], $options['footer_line_color']);
        }else{
            $pdf->setPrintHeader(false);
            $pdf->setPrintFooter(false);    
        }
       
        // set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        // set auto page breaks
        $pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);

        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        $pdf->setFontSubsetting(true);

        // Add a page
        // This method has several options, check the source code documentation for more information.
        $pdf->AddPage();
        
         $pdf->writeHTMLCell(0, 0, '', '', $htmlContent, 0, 1, 0, true, '', true);
        // $pdf->writeHTML($htmlContent); 
        // TO DO DIGITAL SIGNATURE CERTIFICATE
        // Refer https://tcpdf.org/examples/example_052/
        /* if($signatureCerticate){
        // set additional information
        $info = array(
            'Name' => 'TCPDF',
            'Location' => 'Office',
            'Reason' => 'Testing TCPDF',
            'ContactInfo' => 'http://www.tcpdf.org',
        );

        // set document signature
        $pdf->setSignature($signatureCerticate, $signatureCerticate, 'tcpdfdemo', '', 2, $info);

        $pdf->AddPage();

        // print a line of text
        $pdf->writeHTML($htmlContent, true, 0, true, 0);

        // create content for signature (image and/or text)
        $pdf->Image('images/tcpdf_signature.png', 180, 60, 15, 15, 'PNG');

        // define active area for signature appearance
        $pdf->setSignatureAppearance(180, 60, 15, 15);

        // *** set an empty signature appearance ***
        $pdf->addEmptySignatureAppearance(180, 80, 15, 15);

        } */

        $path = $pdf->output($destination, 'F');
        return $destination;

        
    }

    public function generatePdfDocumentFromHtml($htmlContent,$header = null,$footer = null,$destination){
        $myProjectDirectory = __DIR__."/../../../..";
        $snappy = new Pdf($myProjectDirectory . '/vendor/h4cc/wkhtmltopdf-amd64/bin/wkhtmltopdf-amd64');
        $snappy->setOption("header-html",$header);
        $snappy->setOption("footer-html",$footer);
        $snappy->generateFromHtml($htmlContent,$destination);
        return $destination;
    }
    // public function generateDocumentFromFile($filePath,$destination){
        
    // }

    // public function mergeDocument($sourceArray,$destination){
        
    // }

    // public function generateDocumentWithData($sourcePdf,$destination,$data){
        
    // }
}
