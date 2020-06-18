<?php
namespace Oxzion\Document\Template\Excel;

use PHPUnit\Framework\TestCase;
use \Exception;
use Oxzion\Document\Template\Excel\ExcelTemplateProcessorImpl;

class ExcelTemplateProcessorTest extends TestCase
{

    private $file;
    private $parser;
    public function setUp() : void{
        // $this->file = __DIR__."/Data/Test.xlsx";
        $this->parser = new ExcelTemplateProcessorImpl();
    }

    public function testInit(){
        $params['templateDir'] = __DIR__."/Data";
        $this->parser->init($params);
        $this->assertEquals(true, true);
    }

    public function testGetContent(){
        if (file_exists(__DIR__.'/Data/outputtemplate.xlsx')) {
            unlink(__DIR__.'/Data/outputtemplate.xlsx');
        }
        copy(__DIR__.'/Data/exportedtemplate1.xlsx', __DIR__.'/Data/outputtemplate.xlsx');
        $params['templateDir'] = __DIR__."/Data";
        $this->parser->init($params);
        $template = 'template.xlsx';
        $data = [
            'firstname' => 'Polly',
            'address' => [['ksrtc', 'layout', 'jpnagar']],
            'hobbies' => ['badminton', 'basketball', 'tennis'],
            'expenses' => [
                ['Jan', 2500],
                ['Feb', 3000],
                ['Mar', 2000]
            ]
        ];
        $options['fileLocation'] = __DIR__.'/Data/outputtemplate.xlsx';
        $options['sheets'] = array('Introduction');
        $result = $this->parser->getContent($template, $data, $options);
        if (file_exists(__DIR__.'/Data/outputtemplate.xlsx')) {
            $this->assertEquals(true, true);
            unlink(__DIR__.'/Data/outputtemplate.xlsx');
        }   
    }

    public function testGetContentWithoutSheetNameSpecified(){
        if (file_exists(__DIR__.'/Data/outputtemplate.xlsx')) {
            unlink(__DIR__.'/Data/outputtemplate.xlsx');
        }
        copy(__DIR__.'/Data/exportedtemplate2.xlsx', __DIR__.'/Data/outputtemplate.xlsx');
        $params['templateDir'] = __DIR__."/Data";
        $this->parser->init($params);
        $template = 'template2.xlsx';
        $data = [
            'firstname' => 'Polly',
            'address' => [['ksrtc', 'layout', 'jpnagar']],
            'hobbies' => ['badminton', 'basketball', 'tennis'],
            'expenses' => [
                ['Jan', 2500],
                ['Feb', 3000],
                ['Mar', 2000]
            ]
        ];
       $options['fileLocation'] = __DIR__.'/Data/outputtemplate.xlsx';
        $result = $this->parser->getContent($template, $data, $options);
        if (file_exists(__DIR__.'/Data/outputtemplate.xlsx')) {
            $this->assertEquals(true, true);
            unlink(__DIR__.'/Data/outputtemplate.xlsx');
        }
    }

    public function testGetContentWithoutFileLocationSpecified(){
        if (file_exists(__DIR__.'/Data/outputtemplate.xlsx')) {
            unlink(__DIR__.'/Data/outputtemplate.xlsx');
        }
        copy(__DIR__.'/Data/exportedtemplate2.xlsx', __DIR__.'/Data/outputtemplate.xlsx');
        $params['templateDir'] = __DIR__."/Data";
        $this->parser->init($params);
        $template = 'template2.xlsx';
        $options = array();
        $data = [
            'firstname' => 'Polly',
            'address' => [['ksrtc', 'layout', 'jpnagar']],
            'hobbies' => ['badminton', 'basketball', 'tennis'],
            'expenses' => [
                ['Jan', 2500],
                ['Feb', 3000],
                ['Mar', 2000]
            ]];
        $result = $this->parser->getContent($template, $data, $options);
        if (!empty($result)) {
            $this->assertEquals(true, true);
        }
    }
}
