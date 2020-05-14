<?php
namespace Oxzion\Document\Parser\Spreadsheet;

use PhpOffice\PhpSpreadsheet\Reader\IReadFilter;

class BaseRowMapper implements RowMapper{

    protected $mappedData = array();
    /**
    *   This method needs to be overridden by the custom implementations 
    */
    public function mapRow($rowData){
        $this->mappedData[] = $rowData;
    }
    public function getData(){
        return $this->mappedData;
    }
    public function resetData(){
        $this->mappedData = array();
    }
}
