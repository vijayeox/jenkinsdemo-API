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
            $userid="1";
            $tempFolder = $config['DATA_FOLDER']."user/".$userid."/";
            FileUtils::createDirectory($tempFolder);
            copy(__DIR__."/../files/oxzionlogo.png", $tempFolder."profile.png");       
            $this->dispatch('/user/profile/'.$userid, 'GET');
            $this->assertResponseStatusCode(200);
            $this->assertModuleName('User');
            $this->assertControllerName(ProfilePictureController::class); // as specified in router's controller name alias
            $this->assertControllerClass('ProfilePictureController');
            $this->assertMatchedRouteName('profilePicture');
            $img="profile.png";
            FileUtils::deleteFile($img,$tempFolder);
        }

        public function testProfilePictureNotFound()
        {
            $this->initAuthToken($this->adminUser);
            $config = $this->getApplicationConfig();
            $userid="1";
            $this->dispatch('/user/profile/'.$userid, 'GET');
            $this->assertResponseStatusCode(200);
            $this->assertModuleName('User');
            $this->assertControllerName(ProfilePictureController::class); // as specified in router's controller name alias
            $this->assertControllerClass('ProfilePictureController');
            $this->assertMatchedRouteName('profilePicture');
            
        }

        public function testCreateprofilepicture()
        {
            $this->initAuthToken($this->adminUser);
            $file=__DIR__."/../files/";
            $tmp = '/tmp/';
            $picture = 'oxzionlogo.png';
            $tmpFile = "phpJCV57I";
            FileUtils::copy($file.$picture, $tmpFile, $tmp);
            $_FILES = [
                'file' => [
                    'name' => $picture,
                    'type' => 'image/png',
                    'tmp_name' => $tmp.$tmpFile,
                    'error' => 0
                ]
            ];

            $this->dispatch('/user/profile', 'POST');
            $this->assertResponseStatusCode(200);
            $this->assertModuleName('User');
            $this->assertControllerName(ProfilePictureController::class); // as specified in router's controller name alias
            $this->assertControllerClass('ProfilePictureController');
            $this->assertMatchedRouteName('updateProfile');
            $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
            $content = (array)json_decode($this->getResponse()->getContent(), true);
            $this->assertEquals($content['status'], 'success');
            $config = $this->getApplicationConfig();
            $userid="1";
            $img="profile.png";
            $destFile= $config['DATA_FOLDER']."user/".$userid."/";
            $size=FileUtils::getFileSize($img,$destFile);
            // print_r(file_get_contents($destFile));
            $size2=FileUtils::getFileSize($picture,$file);
            $this->assertEquals($size,$size2);
            FileUtils::deleteFile($img,$destFile);


        }



    }
}