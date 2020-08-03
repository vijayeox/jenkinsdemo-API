<?php

namespace Oxzion\Utils;
use Oxzion\Auth\AuthContext;
use Oxzion\Auth\AuthConstants;
use Logger;

class ArtifactUtils
{
    public static $logger;
    public static function getTemplatePath($config, $template, $params = array())
    {
        $templateDir = $config['TEMPLATE_FOLDER']; 
        $orgUuid = isset($params['orgUuid']) ? $params['orgUuid'] : isset($params['orgId']) : $params['orgId'] : AuthContext::get(AuthConstants::ORG_UUID);
        self::$logger->info("Org Uuid - $orgUuid");
        if (isset($orgUuid)) {
            $path = $orgUuid."/".$template;
        } else {
            $path = $template;
        }
        self::$logger->info("Path - $path, template directory - $templateDir,template - $template");
        if (is_file($templateDir.$path)) {
            return $templateDir.$orgUuid;
        }else if (is_file($templateDir.$template)) {
            return $templateDir;
        }
        return false;
    }

    public static function getDocumentFilePath($templateDir,$fileUuid,$params = array())
    { 
        $orgUuid = isset($params['orgUuid']) ? $params['orgUuid'] : AuthContext::get(AuthConstants::ORG_UUID);        
        if (isset($orgUuid)) {
            $path = $orgUuid."/".$fileUuid."/";
        }else{
            $path = $fileUuid."/";
        }
        if(!is_file($templateDir.$path)){
            FileUtils::createDirectory($templateDir.$path);
        }
        return array('absolutePath' => $templateDir.$path, 'relativePath' => $path);
    }

    public static function getMimeType($fileName){
        $pathInfo = pathinfo($fileName);
        $fileExtension = $pathInfo['extension'];
        switch ($fileExtension) {
            case 'png':
                $mimeType = 'image/png';
                break;
            case 'pdf':
                $mimeType = 'application/pdf';
                break;
            case 'jpeg':
            case 'jpg':
                $mimeType = 'image/jpeg';
                break;
            case 'mp4':
                $mimeType = 'video/mp4';
                break;
            case 'gif':
                $mimeType = 'image/gif';
                break;
            case 'xls':
                $mimeType = 'application/vnd.ms-excel';
                break;
            case 'xlsx':
                $mimeType = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
                break;
            case 'docx':
                $mimeType = 'application/vnd.openxmlformats-officedocument.wordprocessingml.document';
                break;
            case 'doc':
                $mimeType = 'application/msword';
                break;
            case 'odt':
                $mimeType = 'application/vnd.oasis.opendocument.text';
                break;
            case 'zip':
                $mimeType = 'application/zip';
                break;
            default:
                $mimeType = 'application/octet-stream';
                break;
        }
        return $mimeType;

    }
}

ArtifactUtils::$logger  = Logger::getLogger(__CLASS__);