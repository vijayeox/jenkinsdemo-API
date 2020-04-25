<?php
namespace PDFFillTest;

use Oxzion\Document\DocumentGeneratorImpl;
use Oxzion\Test\ServiceTest;
use Oxzion\Utils\FileUtils;

class PDFServiceTest extends ServiceTest
{
    public function setUp() : void
    {
        $this->loadConfig();
         parent::setUp();

    }

    public function testFillPDF()
    {
        $pdfform = __DIR__."/sample/w2.pdf";
        $destFolder = __DIR__."/sample/";
        $destination = $destFolder."w2filledpdf.pdf";
        $data= ['ein'=>'123456789','address'=>'1000 East St,  Cleveland, OH - 44123','firstname'=>'John','lastname'=>'Doe'];
        $generatePdf = new DocumentGeneratorImpl();
        $output = $generatePdf->fillPDFForm($pdfform,$data,$destination);
        $this->assertEquals($output, 1);
        $this->assertTrue(file_exists($destination));
        $this->assertTrue(filesize($destination)>0);
        FileUtils::deleteFile("w2filledpdf.pdf", $destFolder);

    }




}
