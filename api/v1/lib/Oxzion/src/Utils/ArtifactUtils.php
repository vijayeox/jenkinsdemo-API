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
        print("in gettemplatePath");
        $templateDir = $config['TEMPLATE_FOLDER']; 
        $orgUuid = isset($params['orgUuid']) ? $params['orgUuid'] : AuthContext::get(AuthConstants::ORG_UUID);
        if (isset($orgUuid)) {
            $path = $orgUuid."/".$template;
        } else {
            $path = $template;
        }
        self::$logger->info("path - $path");
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
}

ArtifactUtils::$logger = Logger::getLogger('Oxzion\Utils\ArtifactUtils');