<?php
namespace Oxzion\PDF;

use Oxzion\PDF\PDF_Rotate;

class PDF_Watermarker extends PDF_Rotate {
    protected $_outerText1;// dynamic text
    protected $font;
    protected $height;

    private function setDynamicParameters($txt1="",$font = 54, $height = 254){
        $this->_outerText1 = $txt1;
        $this->font = $font*0.75;
        $this->height = $height*0.75;
    }

    public function Header(){
    //Put the watermark
        $this->SetFont('Arial','B',$this->font);
        $this->SetTextColor(255,192,203);
        //USE the below commented line to add opacity but remember it adds it recursively everytime its called
        //$this->SetAlpha(0.8);
        $this->RotatedText(35,$this->height, $this->_outerText1, 45);
    }

    private function RotatedText($x, $y, $txt, $angle){
    //Text rotated around its origin
        $this->Rotate($angle,$x,$y);
        $this->Text($x,$y,$txt);
        $this->Rotate(0);
    }

    public function watermarkPDF($filePath, $text){
        $file = $filePath;// path: file name
        $pdf = new PDF_Watermarker();

        if (file_exists($file)){
            $pagecount = $pdf->setSourceFile($file);
        } else {
            return FALSE;
        }

        /* loop for multipage pdf */
        for($i=1; $i <= $pagecount; $i++) {
          $tpl = $pdf->importPage($i);
          $size = $pdf->getTemplateSize($tpl);
          $pdf->setDynamicParameters($text,$size['width'],$size['height']);
          if ($size['width'] > $size['height']) {
            $pdf->AddPage('L', array($size['width'], $size['height']));
          }
          else {
            $pdf->AddPage('P', array($size['width'], $size['height']));
          }
          $pdf->useTemplate($tpl, 0, 0, $size['width'], $size['height'], TRUE);
        }
      $pdf->Output($file,'F');
   }
}
