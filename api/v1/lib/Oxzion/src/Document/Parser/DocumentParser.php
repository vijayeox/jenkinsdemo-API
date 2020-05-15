<?php
namespace Oxzion\Document\Parser;

interface DocumentParser
{
    public function init($file);
    /**
    *   parserOptions(array) - Specific to the kind of Parser used 
    *   e.g., Spreadsheet Parser will have following options
    *   worksheet(string/array) - worksheet name or array of names to parse. Default is first work sheet
    *   rowMapper (RowMapper) - Optional to map row data to a custom data structure
    *   filter (SpreadsheetFilter) - Optional to filter out columns or rows
    *   
    */
    public function parseDocument(array $parserOptions);

}
