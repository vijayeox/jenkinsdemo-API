<?php
namespace Oxzion\Utils;

class ImageUtils
{
    public static function createPNGImage($file, $targetFile)
    {
        $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
        if($file){
            switch ($extension) {
                case 'jpg':
                case 'jpeg':
                case 'JPG':
                case 'JPEG':
                    $image = imagecreatefromjpeg($file);
                    break;
                case 'gif':
                case 'GIF':
                    $image = imagecreatefromgif($file);
                    break;
                case 'png':
                case 'PNG':
                    $image = imagecreatefrompng($file);
                break;
            }
            if($image){
                return imagepng($image, $targetFile);
            } else {
                return null;
            }
        } else {
            return null;
        }
    }
}