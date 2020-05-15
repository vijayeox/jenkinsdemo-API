<?php
namespace Oxzion\Document\Parser\Spreadsheet;

use Oxzion\Document\Parser\DocumentParser;

interface SpreadsheetParser extends DocumentParser
{
    public function getSheetNames();
    public function getSheetCount();
    public function getWorksheetInfo($sheetName);

}
