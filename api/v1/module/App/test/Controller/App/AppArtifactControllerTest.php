<?php
namespace App;

use App\Service\AppService;
use Oxzion\Test\ControllerTest;
use Oxzion\Utils\FileUtils;
use Oxzion\App\AppArtifactNamingStrategy;
use PHPUnit\DbUnit\DataSet\YamlDataSet;
use Exception;
use AppTest\AppTestSetUpTearDownHelper;

class AppArtifactControllerTest extends ControllerTest {
    private $setUpTearDownHelper = NULL;
    private $config = NULL;

    function __construct() {
        parent::__construct();
        $this->loadConfig();
        $this->config = $this->getApplicationConfig();
        $this->setUpTearDownHelper = new AppTestSetUpTearDownHelper($this->config['db']);
    }

    public function setUp(): void {
        parent::setUp();
        $this->setUpTearDownHelper->cleanAll();
    }

    public function tearDown(): void {
        parent::tearDown();
        $this->setUpTearDownHelper->cleanAll();
    }

    public function getDataSet() {
        return new YamlDataSet(dirname(__FILE__) . "/../../Dataset/Workflow.yml");
    }

    protected function runDefaultAsserts() {
        $this->assertModuleName('App');
        $this->assertControllerClass('AppArtifactController');
        $this->assertControllerName('App\Controller\AppArtifactController');
        $contentTypeHeader = $this->getResponseHeader('content-type')->toString();
        $contentTypeRegex = '/application\/json(;? *?charset=utf-8)?/i';
        $this->assertTrue(preg_match($contentTypeRegex, $contentTypeHeader) ? true : false);
    }

    private function setupAppSourceDir($ymlData) {
        $appService = $this->getApplicationServiceLocator()->get(AppService::class);
        $appService->setupOrUpdateApplicationDirectoryStructure($ymlData);
    }

    private function createTemporaryFile($sourceFilePath) {
        $tempDir = sys_get_temp_dir();
        $tempFilePath = tempnam($tempDir, '');
        if (!copy($sourceFilePath, $tempFilePath)) {
            throw new Exception("Failed to copy ${sourceFilePath} to ${tempFilePath}");
        }
        return $tempFilePath;
    }

    public function testArtifactAddForm() {
        $uuid = '1c0f0bc6-df6a-11e9-8a34-2a2ae2dbcce4';
        //Setup data and application source directory.
        $data = [
            'app' => [
                'name' => 'Admin App',
                'uuid' => $uuid,
                'type' => 2, 
                'category' => 'Admin', 
                'logo' => 'app.png'
            ]
        ];
        $this->setupAppSourceDir($data);
        $fileName = 'AddFormTest.json';
        $fileSize = 74665;
        $filePath = __DIR__ . '/../../Dataset/' . $fileName;
        $_FILES = [
            'artifactFile' => [
                'name' => $fileName,
                'type' => 'application/json',
                'tmp_name' => $this->createTemporaryFile($filePath),
                'error' => UPLOAD_ERR_OK,
                'size' => $fileSize
            ]
        ];
        $this->initAuthToken($this->adminUser);
        $this->dispatch("/app/${uuid}/artifact/form/add", 'POST');
        $this->runDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals('success', $content['status']);
        //Ensure file is found in the correct location
        $appSourceDir = AppArtifactNamingStrategy::getSourceAppDirectory($this->config, $data['app']);
        $artifactFile = $appSourceDir . '/content/forms/' . $fileName;
        $this->assertTrue(file_exists($artifactFile));
        $this->assertEquals($fileSize, filesize($artifactFile));
    }

    public function testArtifactAddFormWrongUuid() {
        $uuid = '11111111-1111-1111-1111-111111111111';
        $this->initAuthToken($this->adminUser);
        $this->dispatch("/app/${uuid}/artifact/form/add", 'POST');
        $this->runDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals('error', $content['status']);
        $this->assertEquals(404, $content['errorCode']);
        $this->assertEquals('ox_app', $content['data']['entity']);
        $this->assertEquals($uuid, $content['data']['uuid']);
    }

    public function testArtifactAddFormWithoutAppSourceDir() {
        $uuid = '1c0f0bc6-df6a-11e9-8a34-2a2ae2dbcce4';
        //Setup data.
        $data = [
            'app' => [
                'name' => 'Admin App',
                'uuid' => $uuid,
                'type' => 2, 
                'category' => 'Admin', 
                'logo' => 'app.png'
            ]
        ];
        //Ensure app source dir does not exist.
        $appSourceDir = AppArtifactNamingStrategy::getSourceAppDirectory($this->config, $data['app']);
        try {
            FileUtils::rmDir($appSourceDir);
        }
        catch(Exception $ignored) {}
        $this->initAuthToken($this->adminUser);
        $this->dispatch("/app/${uuid}/artifact/form/add", 'POST');
        $this->runDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals('error', $content['status']);
        $this->assertEquals(404, $content['errorCode']);
        $this->assertEquals('Application source directory is not found.', $content['message']);
        $this->assertEquals($appSourceDir, $content['data']['directory']);
    }

    public function testArtifactAddFormWithDuplicateFileName() {
        $uuid = '1c0f0bc6-df6a-11e9-8a34-2a2ae2dbcce4';
        //Setup data and application source directory.
        $data = [
            'app' => [
                'name' => 'Admin App',
                'uuid' => $uuid,
                'type' => 2, 
                'category' => 'Admin', 
                'logo' => 'app.png'
            ]
        ];
        $this->setupAppSourceDir($data);
        $fileName = 'AddFormTest.json';
        $fileSize = 74665;
        $filePath = __DIR__ . '/../../Dataset/' . $fileName;
        $_FILES = [
            'artifactFile' => [
                'name' => $fileName,
                'type' => 'application/json',
                'tmp_name' => $this->createTemporaryFile($filePath),
                'error' => UPLOAD_ERR_OK,
                'size' => $fileSize
            ]
        ];
        //Ensure file already exists in the destination directory.
        $appSourceDir = AppArtifactNamingStrategy::getSourceAppDirectory($this->config, $data['app']);
        $artifactFile = $appSourceDir . '/content/forms/' . $fileName;
        if (!copy($filePath, $artifactFile)) {
            throw new Exception("Failed to copy file ${filePath} to ${artifactFile}.");
        }
        $this->initAuthToken($this->adminUser);
        $this->dispatch("/app/${uuid}/artifact/form/add", 'POST');
        $this->runDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals('error', $content['status']);
    }

    public function testArtifactAddFormWithWrongArtifactType() {
        $uuid = '1c0f0bc6-df6a-11e9-8a34-2a2ae2dbcce4';
        //Setup data and application source directory.
        $data = [
            'app' => [
                'name' => 'Admin App',
                'uuid' => $uuid,
                'type' => 2, 
                'category' => 'Admin', 
                'logo' => 'app.png'
            ]
        ];
        $this->setupAppSourceDir($data);
        $fileName = 'AddFormTest.json';
        $fileSize = 74665;
        $filePath = __DIR__ . '/../../Dataset/' . $fileName;
        $_FILES = [
            'artifactFile' => [
                'name' => $fileName,
                'type' => 'application/json',
                'tmp_name' => $this->createTemporaryFile($filePath),
                'error' => UPLOAD_ERR_OK,
                'size' => $fileSize
            ]
        ];
        $this->initAuthToken($this->adminUser);
        $this->dispatch("/app/${uuid}/artifact/wrongArtifact/add", 'POST');
        $this->runDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals('error', $content['status']);
        $this->assertEquals(500, $content['errorCode']);
        $this->assertEquals('Unexpected error.', $content['message']);
    }

    public function testArtifactAddWorkflow() {
        $uuid = '1c0f0bc6-df6a-11e9-8a34-2a2ae2dbcce4';
        //Setup data and application source directory.
        $data = [
            'app' => [
                'name' => 'Admin App',
                'uuid' => $uuid,
                'type' => 2, 
                'category' => 'Admin', 
                'logo' => 'app.png'
            ]
        ];
        $this->setupAppSourceDir($data);
        $fileName = 'AddWorkflowTest.bpmn';
        $fileSize = 546495;
        $filePath = __DIR__ . '/../../Dataset/' . $fileName;
        $_FILES = [
            'artifactFile' => [
                'name' => $fileName,
                'type' => 'application/octet-stream',
                'tmp_name' => $this->createTemporaryFile($filePath),
                'error' => UPLOAD_ERR_OK,
                'size' => $fileSize
            ]
        ];
        $this->initAuthToken($this->adminUser);
        $this->dispatch("/app/${uuid}/artifact/workflow/add", 'POST');
        $this->runDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals('success', $content['status']);
        //Ensure file is found in the correct location.
        $appSourceDir = AppArtifactNamingStrategy::getSourceAppDirectory($this->config, $data['app']);
        $artifactFile = $appSourceDir . '/content/workflows/' . $fileName;
        $this->assertTrue(file_exists($artifactFile));
        $this->assertEquals($fileSize, filesize($artifactFile));
    }

    public function testArtifactAddWorkflowWrongUuid() {
        $uuid = '11111111-1111-1111-1111-111111111111';
        $this->initAuthToken($this->adminUser);
        $this->dispatch("/app/${uuid}/artifact/workflow/add", 'POST');
        $this->runDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals('error', $content['status']);
        $this->assertEquals(404, $content['errorCode']);
        $this->assertEquals('ox_app', $content['data']['entity']);
        $this->assertEquals($uuid, $content['data']['uuid']);
    }

    public function testArtifactAddWorkflowWithoutAppSourceDir() {
        $uuid = '1c0f0bc6-df6a-11e9-8a34-2a2ae2dbcce4';
        //Setup data.
        $data = [
            'app' => [
                'name' => 'Admin App',
                'uuid' => $uuid,
                'type' => 2, 
                'category' => 'Admin', 
                'logo' => 'app.png'
            ]
        ];
        //Ensure app source dir does not exist.
        $appSourceDir = AppArtifactNamingStrategy::getSourceAppDirectory($this->config, $data['app']);
        try {
            FileUtils::rmDir($appSourceDir);
        }
        catch(Exception $ignored) {}
        $this->initAuthToken($this->adminUser);
        $this->dispatch("/app/${uuid}/artifact/workflow/add", 'POST');
        $this->runDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals('error', $content['status']);
        $this->assertEquals(404, $content['errorCode']);
        $this->assertEquals('Application source directory is not found.', $content['message']);
        $this->assertEquals($appSourceDir, $content['data']['directory']);
    }

    public function testArtifactAddWorkflowWithDuplicateFileName() {
        $uuid = '1c0f0bc6-df6a-11e9-8a34-2a2ae2dbcce4';
        //Setup data and application source directory.
        $data = [
            'app' => [
                'name' => 'Admin App',
                'uuid' => $uuid,
                'type' => 2, 
                'category' => 'Admin', 
                'logo' => 'app.png'
            ]
        ];
        $this->setupAppSourceDir($data);
        $fileName = 'AddWorkflowTest.bpmn';
        $filePath = __DIR__ . '/../../Dataset/' . $fileName;
        $_FILES = [
            'artifactFile' => [
                'name' => $fileName,
                'type' => 'application/octet-stream',
                'tmp_name' => $this->createTemporaryFile($filePath),
                'error' => UPLOAD_ERR_OK,
                'size' => 546495
            ]
        ];
        //Ensure file already exists in the destination directory.
        $appSourceDir = AppArtifactNamingStrategy::getSourceAppDirectory($this->config, $data['app']);
        $artifactFile = $appSourceDir . '/content/workflows/' . $fileName;
        if (!copy($filePath, $artifactFile)) {
            throw new Exception("Failed to copy file ${filePath} to ${artifactFile}.");
        }
        $this->initAuthToken($this->adminUser);
        $this->dispatch("/app/${uuid}/artifact/workflow/add", 'POST');
        $this->runDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals('error', $content['status']);
    }

    public function testArtifactAddWorkflowWithWrongArtifactType() {
        $uuid = '1c0f0bc6-df6a-11e9-8a34-2a2ae2dbcce4';
        //Setup data and application source directory.
        $data = [
            'app' => [
                'name' => 'Admin App',
                'uuid' => $uuid,
                'type' => 2, 
                'category' => 'Admin', 
                'logo' => 'app.png'
            ]
        ];
        $this->setupAppSourceDir($data);
        $fileName = 'AddWorkflowTest.bpmn';
        $filePath = __DIR__ . '/../../Dataset/' . $fileName;
        $_FILES = [
            'artifactFile' => [
                'name' => $fileName,
                'type' => 'application/octet-stream',
                'tmp_name' => $this->createTemporaryFile($filePath),
                'error' => UPLOAD_ERR_OK,
                'size' => 546495
            ]
        ];
        $this->initAuthToken($this->adminUser);
        $this->dispatch("/app/${uuid}/artifact/wrongArtifact/add", 'POST');
        $this->runDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals('error', $content['status']);
        $this->assertEquals(500, $content['errorCode']);
        $this->assertEquals('Unexpected error.', $content['message']);
    }

    public function testArtifactDeleteForm() {
        $uuid = '1c0f0bc6-df6a-11e9-8a34-2a2ae2dbcce4';
        //Setup data and application source directory.
        $data = [
            'app' => [
                'name' => 'Admin App',
                'uuid' => $uuid,
                'type' => 2, 
                'category' => 'Admin', 
                'logo' => 'app.png'
            ]
        ];
        $this->setupAppSourceDir($data);
        $appSourceDir = AppArtifactNamingStrategy::getSourceAppDirectory($this->config, $data['app']);
        $fileName = 'AddFormTest.json';
        $filePath = __DIR__ . '/../../Dataset/' . $fileName;
        $targetPath = $appSourceDir . '/content/forms/' . $fileName;
        if (!copy($filePath, $targetPath)) {
            throw new Exception("Failed to copy file ${filePath} to ${targetPath}.");
        }

        $this->initAuthToken($this->adminUser);
        $this->dispatch("/app/${uuid}/artifact/form/delete/${fileName}", 'DELETE');
        $this->runDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals('success', $content['status']);
        //Ensure file is NOT found in the location.
        $this->assertFalse(file_exists($targetPath));
    }

    public function testArtifactDeleteFormWrongUuid() {
        $uuid = '11111111-1111-1111-1111-111111111111';
        $this->initAuthToken($this->adminUser);
        $this->dispatch("/app/${uuid}/artifact/form/delete/AnyFileName.json", 'DELETE');
        $this->runDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals('error', $content['status']);
        $this->assertEquals(404, $content['errorCode']);
        $this->assertEquals('ox_app', $content['data']['entity']);
        $this->assertEquals($uuid, $content['data']['uuid']);
    }

    public function testArtifactDeleteFormWithoutAppSourceDir() {
        $uuid = '1c0f0bc6-df6a-11e9-8a34-2a2ae2dbcce4';
        //Setup data.
        $data = [
            'app' => [
                'name' => 'Admin App',
                'uuid' => $uuid,
                'type' => 2, 
                'category' => 'Admin', 
                'logo' => 'app.png'
            ]
        ];
        //Ensure app source dir does not exist.
        $appSourceDir = AppArtifactNamingStrategy::getSourceAppDirectory($this->config, $data['app']);
        try {
            FileUtils::rmDir($appSourceDir);
        }
        catch(Exception $ignored) {}
        $this->initAuthToken($this->adminUser);
        $this->dispatch("/app/${uuid}/artifact/form/delete/AnyFileName.json", 'DELETE');
        $this->runDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals('error', $content['status']);
        $this->assertEquals(404, $content['errorCode']);
        $this->assertEquals('Application source directory is not found.', $content['message']);
        $this->assertEquals($appSourceDir, $content['data']['directory']);
    }

    public function testArtifactDeleteFormFileNotFound() {
        $uuid = '1c0f0bc6-df6a-11e9-8a34-2a2ae2dbcce4';
        //Setup data and application source directory.
        $data = [
            'app' => [
                'name' => 'Admin App',
                'uuid' => $uuid,
                'type' => 2, 
                'category' => 'Admin', 
                'logo' => 'app.png'
            ]
        ];
        $this->setupAppSourceDir($data);
        $fileName = 'AddFormTest.json';
        //Ensure artifact file does not exist.
        $appSourceDir = AppArtifactNamingStrategy::getSourceAppDirectory($this->config, $data['app']);
        $artifactFile = $appSourceDir . '/content/forms/' . $fileName;
        if (file_exists($artifactFile) && !unlink($artifactFile)) {
            throw new Exception("Failed to delete file ${artifactFile}.");
        }
        $this->initAuthToken($this->adminUser);
        $this->dispatch("/app/${uuid}/artifact/form/delete/${fileName}", 'DELETE');
        $this->runDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals('error', $content['status']);
        $this->assertEquals(404, $content['errorCode']);
        $this->assertEquals('Artifact file is not found.', $content['message']);
        $this->assertEquals($artifactFile, $content['data']['file']);
    }

    public function testArtifactDeleteFormWithWrongArtifactType() {
        $uuid = '1c0f0bc6-df6a-11e9-8a34-2a2ae2dbcce4';
        //Setup data and application source directory.
        $data = [
            'app' => [
                'name' => 'Admin App',
                'uuid' => $uuid,
                'type' => 2, 
                'category' => 'Admin', 
                'logo' => 'app.png'
            ]
        ];
        $this->setupAppSourceDir($data);
        $fileName = 'AddFormTest.json';

        $this->initAuthToken($this->adminUser);
        $this->dispatch("/app/${uuid}/artifact/wrongArtifact/delete/${fileName}", 'DELETE');
        $this->runDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals('error', $content['status']);
        $this->assertEquals(500, $content['errorCode']);
        $this->assertEquals('Unexpected error.', $content['message']);
    }

    public function testArtifactDeleteWorkflow() {
        $uuid = '1c0f0bc6-df6a-11e9-8a34-2a2ae2dbcce4';
        //Setup data and application source directory.
        $data = [
            'app' => [
                'name' => 'Admin App',
                'uuid' => $uuid,
                'type' => 2, 
                'category' => 'Admin', 
                'logo' => 'app.png'
            ]
        ];
        $this->setupAppSourceDir($data);
        $appSourceDir = AppArtifactNamingStrategy::getSourceAppDirectory($this->config, $data['app']);
        $fileName = 'AddWorkflowTest.bpmn';
        $filePath = __DIR__ . '/../../Dataset/' . $fileName;
        $targetPath = $appSourceDir . '/content/workflows/' . $fileName;
        if (!copy($filePath, $targetPath)) {
            throw new Exception("Failed to copy file ${filePath} to ${targetPath}.");
        }

        $this->initAuthToken($this->adminUser);
        $this->dispatch("/app/${uuid}/artifact/workflow/delete/${fileName}", 'DELETE');
        $this->runDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals('success', $content['status']);
        //Ensure file is NOT found in the location.
        $this->assertFalse(file_exists($targetPath));
    }

    public function testArtifactDeleteWorkflowWrongUuid() {
        $uuid = '11111111-1111-1111-1111-111111111111';
        $this->initAuthToken($this->adminUser);
        $this->dispatch("/app/${uuid}/artifact/workflow/delete/AnyFileName.bpmn", 'DELETE');
        $this->runDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals('error', $content['status']);
        $this->assertEquals(404, $content['errorCode']);
        $this->assertEquals('ox_app', $content['data']['entity']);
        $this->assertEquals($uuid, $content['data']['uuid']);
    }

    public function testArtifactDeleteWorkflowWithoutAppSourceDir() {
        $uuid = '1c0f0bc6-df6a-11e9-8a34-2a2ae2dbcce4';
        //Setup data.
        $data = [
            'app' => [
                'name' => 'Admin App',
                'uuid' => $uuid,
                'type' => 2, 
                'category' => 'Admin', 
                'logo' => 'app.png'
            ]
        ];
        //Ensure app source dir does not exist.
        $appSourceDir = AppArtifactNamingStrategy::getSourceAppDirectory($this->config, $data['app']);
        try {
            FileUtils::rmDir($appSourceDir);
        }
        catch(Exception $ignored) {}
        $this->initAuthToken($this->adminUser);
        $this->dispatch("/app/${uuid}/artifact/workflow/delete/AnyFileName.bpmn", 'DELETE');
        $this->runDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals('error', $content['status']);
        $this->assertEquals(404, $content['errorCode']);
        $this->assertEquals('Application source directory is not found.', $content['message']);
        $this->assertEquals($appSourceDir, $content['data']['directory']);
    }

    public function testArtifactDeleteWorkflowFileNotFound() {
        $uuid = '1c0f0bc6-df6a-11e9-8a34-2a2ae2dbcce4';
        //Setup data and application source directory.
        $data = [
            'app' => [
                'name' => 'Admin App',
                'uuid' => $uuid,
                'type' => 2, 
                'category' => 'Admin', 
                'logo' => 'app.png'
            ]
        ];
        $this->setupAppSourceDir($data);
        $fileName = 'AddWorkflowTest.bpmn';
        //Ensure artifact file does not exist.
        $appSourceDir = AppArtifactNamingStrategy::getSourceAppDirectory($this->config, $data['app']);
        $artifactFile = $appSourceDir . '/content/workflows/' . $fileName;
        if (file_exists($artifactFile) && !unlink($artifactFile)) {
            throw new Exception("Failed to delete file ${artifactFile}.");
        }
        $this->initAuthToken($this->adminUser);
        $this->dispatch("/app/${uuid}/artifact/workflow/delete/${fileName}", 'DELETE');
        $this->runDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals('error', $content['status']);
        $this->assertEquals(404, $content['errorCode']);
        $this->assertEquals('Artifact file is not found.', $content['message']);
        $this->assertEquals($artifactFile, $content['data']['file']);
    }

    public function testArtifactDeleteWorkflowWithWrongArtifactType() {
        $uuid = '1c0f0bc6-df6a-11e9-8a34-2a2ae2dbcce4';
        //Setup data and application source directory.
        $data = [
            'app' => [
                'name' => 'Admin App',
                'uuid' => $uuid,
                'type' => 2, 
                'category' => 'Admin', 
                'logo' => 'app.png'
            ]
        ];
        $this->setupAppSourceDir($data);
        $fileName = 'AddWorkflowTest.bpmn';

        $this->initAuthToken($this->adminUser);
        $this->dispatch("/app/${uuid}/artifact/wrongArtifact/delete/${fileName}", 'DELETE');
        $this->runDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals('error', $content['status']);
        $this->assertEquals(500, $content['errorCode']);
        $this->assertEquals('Unexpected error.', $content['message']);
    }
}
