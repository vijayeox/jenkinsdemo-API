<?php
// move_uploaded_file function uploads the file via.HTTP POST hence override the function with the following code.
namespace Oxzion\Service{
    function move_uploaded_file($filename, $destination)
    {
        //Copy file
        return copy($filename, $destination);
    }
}

namespace User{

    use User\Controller\ProfilePictureController;
    use User\Controller\ProfilePictureDownloadController;
    use Oxzion\Test\MainControllerTest;
    use PHPUnit\DbUnit\TestCaseTrait;
    use PHPUnit\DbUnit\DataSet\YamlDataSet;
    use Zend\Db\Sql\Sql;
    use Zend\Db\Adapter\Adapter;
    use Oxzion\Utils\FileUtils;


    class ProfilePictureControllerTest extends MainControllerTest
    {

        public function setUp() : void
        {
            $this->loadConfig();
            parent::setUp();
        }
      
        public function testProfilePicture()
        {
            $this->initAuthToken($this->adminUser);
            $config = $this->getApplicationConfig();
            $userid="3a866d46-3fa0-11e9-a814-68ecc57cde45";
            $tempFolder = $config['DATA_FOLDER']."user/".$userid."/";
            FileUtils::createDirectory($tempFolder);
            copy(__DIR__."/../files/oxzionlogo.png", $tempFolder."profile.png");       
            $this->dispatch('/user/profile/'.$userid, 'GET');
            $this->assertResponseStatusCode(200);
            $this->assertModuleName('User');
            $this->assertControllerName(ProfilePictureDownloadController::class); // as specified in router's controller name alias
            $this->assertControllerClass('ProfilePictureDownloadController');
            $this->assertMatchedRouteName('profilePicture');
            $img="profile.png";
            FileUtils::deleteFile($img,$tempFolder);
        }

        public function testProfilePictureNotFound()
        {
            $this->initAuthToken($this->adminUser);
            $config = $this->getApplicationConfig();
            $userid="3a866d46-3fa0-11e9-a814-68ecc57cde45";
            $this->dispatch('/user/profile/'.$userid, 'GET');
            $this->assertResponseStatusCode(200);
            $this->assertModuleName('User');
            $this->assertControllerName(ProfilePictureDownloadController::class); // as specified in router's controller name alias
            $this->assertControllerClass('ProfilePictureDownloadController');
            $this->assertMatchedRouteName('profilePicture');            
        }

        public function testCreateprofilepicture()
        {
            $this->initAuthToken($this->adminUser);
            $files=__DIR__."/../files/";
            $tmp = '/tmp/';
            $picture = 'oxzionlogo.png';
            $tmpFile = "phpJCV57I";
            FileUtils::copy($files.$picture, $tmpFile, $tmp);
            $data=['file' => 'data:image/png;base64,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'
            ];
            $this->setJsonContent(json_encode($data));
            $this->dispatch('/user/profile', 'POST',$data);
            $this->assertResponseStatusCode(200);
            $this->assertModuleName('User');
            $this->assertControllerName(ProfilePictureController::class); // as specified in router's controller name alias
            $this->assertControllerClass('ProfilePictureController');
            $this->assertMatchedRouteName('updateProfile');
            $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
            $content = (array)json_decode($this->getResponse()->getContent(), true);
            $this->assertEquals($content['status'], 'success');          
        }

        public function testProfilePictureWithUsername()
        {
            $this->initAuthToken($this->adminUser);
            $config = $this->getApplicationConfig();
            $username = "bharatg";
            $userid="b0cb0d3c-496e-11e9-a876-b88198a956ff";
            $tempFolder = $config['DATA_FOLDER']."user/".$userid."/";
            FileUtils::createDirectory($tempFolder);
            copy(__DIR__."/../files/oxzionlogo.png", $tempFolder."profile.png");       
            $this->dispatch('/user/profile/username'.$username, 'GET');
            $this->assertResponseStatusCode(200);
            $this->assertModuleName('User');
            $this->assertControllerName(ProfilePictureDownloadController::class); // as specified in router's controller name alias
            $this->assertControllerClass('ProfilePictureDownloadController');
            $this->assertMatchedRouteName('profilePicture');
            $img="profile.png";
            FileUtils::deleteFile($img,$tempFolder);
        }
    }
}