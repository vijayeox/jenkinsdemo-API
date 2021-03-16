<?php
use Oxzion\Test\DelegateTest;
use Oxzion\AppDelegate\AppDelegateService;
use Oxzion\Auth\AuthContext;
use Oxzion\Auth\AuthConstants;
use PHPUnit\DbUnit\DataSet\YamlDataSet;
use Oxzion\Utils\FileUtils;
use PHPUnit\DbUnit\DataSet\DefaultDataSet;
use Oxzion\Utils\ArtifactUtils;
use Smalot\PdfParser\Parser;

class UnansweredTest extends DelegateTest
{
	public function setUp() : void
    {
        $this->loadConfig();
        $config = $this->getApplicationConfig();
        $this->data = array(
            "appName" => 'ox_client_app',
            'UUID' => 'da8f0152-b8d3-43bf-8090-40103bb98d5e',
            'description' => 'Arrowhead Client Application',
            'orgUuid' => '53012471-2863-4949-afb1-e69b0891c98a',
            'orgId' =>'1'
        );
        $migrationFolder = __DIR__  . "/../data/migrations/";
        $this->doMigration($this->data,$migrationFolder);
        $path = __DIR__.'/../../../api/data/delegate/'.$this->data['UUID'];
        if (!is_link($path)) {
            symlink(__DIR__.'/../data/delegate/',$path);
        }

        $this->tempFile = $config['TEMPLATE_FOLDER'].$this->data['orgUuid'];
        $templateLocation = __DIR__."/../data/template";
        $this->pdfParser = new Parser();

        if(FileUtils::fileExists($this->tempFile)){
                FileUtils::rmDir($this->tempFile);
        }
        FileUtils::symlink($templateLocation, $this->tempFile);


        parent::setUp();
    }

    public function getDataSet()
    {
        $dataset = new YamlDataSet(dirname(__FILE__) . "/Dataset/Unanswered.yml");
        return $dataset;
    }

    public function tearDown() : void
    {
        parent::tearDown();
        $path = __DIR__.'/../../../api/v1/data/delegate/'.$this->data['UUID'];
        if (is_link($path)) {
            unlink($path);
        }

        FileUtils::unlink($this->tempFile);
        $query = "DROP DATABASE " . $this->database;//comment
        $statement = $this->getDbAdapter()->query($query);
        $result = $statement->execute();

    }

    public function testUnansweredFields()
    {
        $org_id=AuthContext::put(AuthConstants::ORG_ID, $this->data['orgId']);
        $org_uuid = AuthContext::put(AuthConstants::ORG_UUID,$this->data['orgUuid']);
        $data = array();
        $config = $this->getApplicationConfig();
        $appId = $this->data['UUID'];
        $data['appId'] = $appId;
        $data['fileId'] = '39c3e9d1-146f-4ec0-a23c-9c651448ac05';
        $data['unansweredQuestions'] = array(
            0 => array(
                "api" => 'namedInsured'
            )
        );

        $delegateService = $this->getApplicationServiceLocator()->get(AppDelegateService::class);
        $delegateService->setPersistence($appId, $this->persistence);
        $content = $delegateService->execute($appId, 'Unanswered', $data);
        $this->assertEquals($content['unansweredQuestions'], $data['unansweredQuestions']);
        $this->assertEquals($content['appId'],$data['appId']);
        $this->assertEquals($content['fileId'],$data['fileId']);
        for($i=0;$i<2;$i++){
            if($i==0){
                $filepath = parse_url($content['unansweredQuestionsDocument']);
            }
            else{
                $filepath = parse_url($content['answeredQuestionsDocument']);
            }
            $filepath = explode('/',$filepath['path']);
            $filepath = join("/",array_slice($filepath,3));
            $pdfpath = "/app/api/data/file_docs/".$filepath;
            $this->assertTrue(is_file($pdfpath));
            $this->assertTrue(filesize($pdfpath)>0);
        }
    }

    public function testGetUnansweredFieldsWithDatagrid()
    {
        $org_id=AuthContext::put(AuthConstants::ORG_ID, $this->data['orgId']);
        $org_uuid = AuthContext::put(AuthConstants::ORG_UUID,$this->data['orgUuid']);
        $data = array();
        $config = $this->getApplicationConfig();
        $appId = $this->data['UUID'];
        $data['appId'] = $appId;
        $data['fileId'] = '8541d6ab-8abc-4be3-88e5-f0a714ded346';
        $data['unansweredQuestions'] = array(
            0 => array(
                "api"=> "dealershipPersonnel[0].name"
            ),
            1=> array(
                "api" =>"dealershipPersonnel[0].ownershipPercentageNumber"
            )
        );

        $delegateService = $this->getApplicationServiceLocator()->get(AppDelegateService::class);
        $delegateService->setPersistence($appId, $this->persistence);
        $content = $delegateService->execute($appId, 'Unanswered', $data);
        $this->assertEquals($content['unansweredQuestions'], $data['unansweredQuestions']);
        $this->assertEquals($content['appId'],$data['appId']);
        $this->assertEquals($content['fileId'],$data['fileId']);
        for($i=0;$i<2;$i++){
            if($i==0){
                $filepath = parse_url($content['unansweredQuestionsDocument']);
            }
            else{
                $filepath = parse_url($content['answeredQuestionsDocument']);
            }
            $filepath = explode('/',$filepath['path']);
            $filepath = join("/",array_slice($filepath,3));
            $pdfpath = "/app/api/data/file_docs/".$filepath;
            
            $this->assertTrue(is_file($pdfpath));
            $this->assertTrue(filesize($pdfpath)>0);
        }

    }

    public function testPdfGenerated()
    {
        $org_id=AuthContext::put(AuthConstants::ORG_ID, $this->data['orgId']);
        $org_uuid = AuthContext::put(AuthConstants::ORG_UUID,$this->data['orgUuid']);
        $data = array();
        $config = $this->getApplicationConfig();
        $appId = $this->data['UUID'];
        $data['appId'] = $appId;
        $data['fileId'] = '39c3e9d1-146f-4ec0-a23c-9c651448ac05';
        $data['unansweredQuestions'] = array(
            0 => array(
                "api" => 'namedInsured'
            )
        );

        $delegateService = $this->getApplicationServiceLocator()->get(AppDelegateService::class);
        $delegateService->setPersistence($appId, $this->persistence);
        $content = $delegateService->execute($appId, 'Unanswered', $data);
        for($i=0;$i<2;$i++){
            if($i==0){
                $filepath = parse_url($content['unansweredQuestionsDocument']);
                $actualPdfPath = "/app/clients/ArrowHead/test/files/Unanswered.pdf";
            }
            else{
                $filepath = parse_url($content['answeredQuestionsDocument']);
                $actualPdfPath = "/app/clients/ArrowHead/test/files/Answered.pdf";
            }
            $filepath = explode('/',$filepath['path']);
            $filepath = join("/",array_slice($filepath,3));
            $pdfpath = "/app/api/data/file_docs/".$filepath;
            
            $generatedPdfContent = $this->pdfParser->parseFile($pdfpath);
            $actualPdfContent = $this->pdfParser->parseFile($actualPdfPath);
            $this->assertEquals($generatedPdfContent->getText(),$actualPdfContent->getText());
        }

    }

    public function testPdfGeneratedWithDatagrid()
    {
        $org_id=AuthContext::put(AuthConstants::ORG_ID, $this->data['orgId']);
        $org_uuid = AuthContext::put(AuthConstants::ORG_UUID,$this->data['orgUuid']);
        $data = array();
        $config = $this->getApplicationConfig();
        $appId = $this->data['UUID'];
        $data['appId'] = $appId;
        $data['fileId'] = '8541d6ab-8abc-4be3-88e5-f0a714ded346';
        $data['unansweredQuestions'] = array(
            0 => array(
                "api"=> "dealershipPersonnel[0].name"
            ),
            1=> array(
                "api" =>"dealershipPersonnel[0].ownershipPercentageNumber"
            )
        );

        $delegateService = $this->getApplicationServiceLocator()->get(AppDelegateService::class);
        $delegateService->setPersistence($appId, $this->persistence);
        $content = $delegateService->execute($appId, 'Unanswered', $data);
        for($i=0;$i<2;$i++){
            if($i==0){
                $filepath = parse_url($content['unansweredQuestionsDocument']);
                $actualPdfPath = "/app/clients/ArrowHead/test/files/DataGridUnanswered.pdf";
            }
            else{
                $filepath = parse_url($content['answeredQuestionsDocument']);
                $actualPdfPath = "/app/clients/ArrowHead/test/files/DataGridAnswered.pdf";
            }
            $filepath = explode('/',$filepath['path']);
            $filepath = join("/",array_slice($filepath,3));
            $pdfpath = "/app/api/data/file_docs/".$filepath;
            
            $generatedPdfContent = $this->pdfParser->parseFile($pdfpath);
            $actualPdfContent = $this->pdfParser->parseFile($actualPdfPath);
            $this->assertEquals($generatedPdfContent->getText(),$actualPdfContent->getText());
        }

    }

}