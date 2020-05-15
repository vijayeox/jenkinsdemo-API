<?php
namespace Oxzion\Document\Parser\Spreadsheet;

use PhpOffice\PhpSpreadsheet\Reader\IReadFilter;

class SpreadsheetFilter implements IReadFilter{

    private $startRow = 0;
    private $endRow   = -1; //no limit
    private $columns  = [];

    public function setRows($startRow, $chunkSize = -1) {
        $this->startRow = $startRow;
        $this->endRow   = $chunkSize == -1 ? $chunkSize : $startRow + $chunkSize;
    }

    public function setColumns(array $columns){
        $this->columns = $columns;
    }

    public function readCell($column, $row, $worksheetName = '') {
        //  Only read the rows and columns that were configured
        if (($this->endRow  < 0 && $row >= $this->startRow) || ($row >= $this->startRow && $row < $this->endRow)) {
            if (empty($this->columns) || in_array($column,$this->columns)) {
                return true;
            }
        }
        return false;
    }
}
