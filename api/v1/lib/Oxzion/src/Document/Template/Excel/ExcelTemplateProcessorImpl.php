<?php
namespace Oxzion\Document\Template\Excel;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\DefaultReadFilter;
use Oxzion\Utils\FileUtils;
use Exception;
use alhimik1986\PhpExcelTemplator\PhpExcelTemplator;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Oxzion\Document\Template\TemplateParser;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class ExcelTemplateProcessorImpl extends PhpExcelTemplator implements TemplateParser
{
    private $excelTemplator;
    private $templateLocation;

    /**
     * Initializes the template processor
     * @method init
     * @param array $params - templateDir
     * @return none
     */
    public function init(array $params){
        $this->excelTemplator = new PhpExcelTemplator();
        $this->templateLocation = $params['templateDir'];
        if(!$this->endsWith($this->templateLocation, '/')){
            $this->templateLocation .= '/';
        }
    }

    private function endsWith($haystack, $needle)
    {
        $length = strlen($needle);
        if ($length == 0) {
            return true;
        }
        return (substr($haystack, -$length) === $needle);
    }

    /**
     * Merges the data provided with the template
     * @method getContent
     * @param $template
     * @param array $data
     * @param array $options - fileLocation, sheets (array of sheet names)
     * @return PhpOffice\PhpSpreadsheet\Spreedsheet
     */
    public function getContent($template, $data = array(), $options = array()){
        $templateFile = $this->templateLocation.$template;
        $spreadsheet = PhpExcelTemplator::getSpreadsheet($templateFile);
        if (!isset($options['sheets'])) {
            $options['sheets'] = array($spreadsheet->getSheetNames()[0]);
        }
        foreach ($options['sheets'] as $sheetName) {
            $sheet = $spreadsheet->getSheetByName($sheetName);
            $templateVarsArr = $sheet->toArray();
            PhpExcelTemplator::renderWorksheet($sheet, $templateVarsArr, $data);
        }
        if (isset($options['fileLocation'])) {
            $fileName = $options['fileLocation'];
            PhpExcelTemplator::saveSpreadsheetToFile($spreadsheet, $fileName);
        }        
        return $spreadsheet;
    }
}
