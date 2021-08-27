<?php
use Oxzion\Test\DelegateTest;
use Oxzion\AppDelegate\AppDelegateService;
use Oxzion\Auth\AuthContext;
use Oxzion\Auth\AuthConstants;
use PHPUnit\DbUnit\DataSet\YamlDataSet;
use Oxzion\Utils\FileUtils;
use PHPUnit\DbUnit\DataSet\DefaultDataSet;
use Oxzion\Utils\ArtifactUtils;
use Oxzion\Encryption\Crypto;
use Oxzion\Service\FileService;
use Oxzion\Db\Persistence\Persistence;

class UpdatePolicyStatusOnExpirationTest extends DelegateTest
{

    public function setUp() : void
    {
        $this->loadConfig();
        $this->data = array(
            "appName" => 'ox_client_app',
            'UUID' => 8765765,
            'description' => 'FirstAppOfTheClient',
            'orgUuid' => '53012471-2863-4949-afb1-e69b0891c98a'
        );
        $path = __DIR__ . '/../../../api/v1/data/delegate/' . $this->data['UUID'];
        if (!is_link($path)) {
            symlink(__DIR__ . '/../data/delegate/', $path);
        }
        $this->config = $this->getApplicationConfig();
        $this->persistence = new Persistence($this->config, $this->data['UUID'], $this->data['appName']);
        $this->delegateService = $this->getApplicationServiceLocator()->get(AppDelegateService::class);
        $this->fileService = $this->getApplicationServiceLocator()->get(FileService::class);
        parent::setUp();
    }

    public function getDataSet()
    {
        return new DefaultDataSet();
    }

    public function tearDown() : void
    {
        parent::tearDown();
        $path = __DIR__ . '/../../../api/v1/data/delegate/' . $this->data['UUID'];
        if (is_link($path)) {
            unlink($path);
        }
        $this->delegateService->setFileService($this->fileService);   
    } 

    private function setUpFileServiceMock()
    {
        $fileServiceMock = Mockery::mock('\Oxzion\Service\FileService');
        $this->delegateService->setFileService($fileServiceMock);
        return $fileServiceMock;
    }


    public function testStatusToExpiredNotEqualToDiveBoat()
    {
        $appId = $this->data['UUID'];
        $data = ['flag'=>'notEqualTo','orgId'=>$this->data['orgUuid'],'appId'=>$appId];
        $this->delegateService->setPersistence($appId, $this->persistence);
        $content = $this->delegateService->execute($appId, 'UpdatePolicyStatusOnExpiration', $data);
        $this->assertEquals($content, $data);
    }

    public function testStatusToExpiredEqualToDiveBoat()
    {
        $appId = $this->data['UUID'];
        $data = ['flag'=>'equalTo','orgId'=>$this->data['orgUuid'],'appId'=>$appId];
        $this->delegateService->setPersistence($appId, $this->persistence);
        $content = $this->delegateService->execute($appId, 'UpdatePolicyStatusOnExpiration', $data);
        $this->assertEquals($content, $data);
    }
}