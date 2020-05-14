<?php
namespace Oxzion\Document\Parser\Spreadsheet;

use PhpOffice\PhpSpreadsheet\Reader\IReadFilter;

interface RowMapper {
    public function mapRow($rowData);
    public function getData();
    public function resetData();
}
