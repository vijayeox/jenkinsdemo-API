<?php
namespace User\Controller;

use Zend\Log\Logger;
use Oxzion\Controller\AbstractApiController;
use Oxzion\Service\ProfilePictureService;
use Zend\Db\Adapter\AdapterInterface;
use Exception;
use Zend\InputFilter\Input;
use Oxzion\Utils\FileUtils;



class ProfilePictureController extends AbstractApiController { 
    /**
    * @var ProfilepictureService Instance of Projectpicture Service
    */
    private $profilepictureService;
    /**
    * @ignore __construct
    */
    public function __construct(ProfilePictureService $profilepictureService, Logger $log, AdapterInterface $dbAdapter)
    {
        parent::__construct(null, $log, __class__, User::class);
        $this->setIdentifierName('profileId');
        $this->profilepictureService = $profilepictureService;
    }

    // public function base64_to_png($base64_string, $output_file) {
    //    // print_r($base64_string);
    //     $ifp = fopen( $output_file, 'w' ); 
    //     $data = explode( ',', $base64_string );
    //     fwrite( $ifp, base64_decode( $data[ 1 ] ) );
    //     fclose( $ifp ); 
    //     return $output_file; 
    // }



     //   public function LoadPNG($imgname)
     //   {
     //    /* Attempt to open */
     //        $im =imagecreatefrompng($imgname);

     //    /* See if it failed */
     //         if(!$im)
     //        {
     //        /* Create a blank image */
     //            $im  = imagecreatetruecolor(150, 30);
     //            $bgc = imagecolorallocate($im, 255, 255, 255);
     //            $tc  = imagecolorallocate($im, 0, 0, 0);

     //            imagefilledrectangle($im, 0, 0, 150, 30, $bgc);

     //            /* Output an error message */
     //            imagestring($im, 1, 5, 5, 'Error loading ' . $imgname, $tc);
     //         }

     //    return $im;
     // }




     /**
    * Update Profilepicture API
    * @api
    * @link /profilepicture[/:profileId]
    * @method POST
    * @param $id ID of Profilepicture to update 
    * @param $data 
    * @return array Returns a JSON Response with Status Code and Created Profilepicture.
    */
    public function updateAction() {

        $params=$this->params()->fromPost();
    // print_r($params['file']);
        $files=substr($params['file'],strpos($params['file'],",")+1);
        // // print $uri;
        $files=base64_decode($files);
        // // print_r($files);
        // $image=base64_to_jpeg($files,'tmp.png');
        // $image= $this->base64_to_png( $params['file'], 'tmp.png');
        // print_r($image);
        // header('Content-Type: image/png');

        // $img=$this->LoadPNG($image);
        try {
            $count = $this->profilepictureService->uploadProfilepicture($files);

        } catch (Exception $e) {
            //print_r($e);
            $this->log->err("Failed to upload profile picture", [$e]);
            return $this->getErrorResponse("Profile picture upload failed",500);
        }
        return $this->getSuccessResponse("Upload successfull",200);


    }
    
}