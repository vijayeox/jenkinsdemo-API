<?php
namespace Oxzion\PDF;

use setasign\fpdf;
use setasign\Fpdi\Fpdi;

class PDF_Rotate extends Fpdi
{
    public $angle=0;
    public $extgstates = array();

    public function Rotate($angle, $x=-1, $y=-1)
    {
        if ($x==-1) {
            $x=$this->x;
        }
        if ($y==-1) {
            $y=$this->y;
        }
        if ($this->angle!=0) {
            $this->_out('Q');
        }
        $this->angle=$angle;
        if ($angle!=0) {
            $angle*=M_PI/180;
            $c=cos($angle);
            $s=sin($angle);
            $cx=$x*$this->k;
            $cy=($this->h-$y)*$this->k;
            $this->_out(sprintf('q %.5F %.5F %.5F %.5F %.2F %.2F cm 1 0 0 1 %.2F %.2F cm', $c, $s, -$s, $c, $cx, $cy, -$cx, -$cy));
        }
    }

    public function _endpage()
    {
        if ($this->angle!=0) {
            $this->angle=0;
            $this->_out('Q');
        }
        parent::_endpage();
    }

    public function SetAlpha($alpha, $bm='Normal')
    {
        // set alpha for stroking (CA) and non-stroking (ca) operations
        $gs = $this->AddExtGState(array('ca'=>$alpha, 'CA'=>$alpha, 'BM'=>'/'.$bm));
        $this->SetExtGState($gs);
    }

    public function AddExtGState($parms)
    {
        $n = count($this->extgstates)+1;
        $this->extgstates[$n]['parms'] = $parms;
        return $n;
    }

    public function SetExtGState($gs)
    {
        $this->_out(sprintf('/GS%d gs', $gs));
    }

    public function _enddoc()
    {
        if (!empty($this->extgstates) && $this->PDFVersion<'1.4') {
            $this->PDFVersion='1.4';
        }
        parent::_enddoc();
    }

    public function _putextgstates()
    {
        for ($i = 1; $i <= count($this->extgstates); $i++) {
            $this->_newobj();
            $this->extgstates[$i]['n'] = $this->n;
            $this->_out('<</Type /ExtGState');
            foreach ($this->extgstates[$i]['parms'] as $k=>$v) {
                $this->_out('/'.$k.' '.$v);
            }
            $this->_out('>>');
            $this->_out('endobj');
        }
    }

    public function _putresourcedict()
    {
        parent::_putresourcedict();
        $this->_out('/ExtGState <<');
        foreach ($this->extgstates as $k=>$extgstate) {
            $this->_out('/GS'.$k.' '.$extgstate['n'].' 0 R');
        }
        $this->_out('>>');
    }

    public function _putresources()
    {
        $this->_putextgstates();
        parent::_putresources();
    }
}
