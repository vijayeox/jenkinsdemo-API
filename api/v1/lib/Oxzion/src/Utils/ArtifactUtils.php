<?php

namespace Oxzion\Utils;
use Oxzion\Auth\AuthContext;
use Oxzion\Auth\AuthConstants;

class ArtifactUtils
{
    public static function getTemplatePath($config, $template, $params = array())
    {
        $templateDir = $config['TEMPLATE_FOLDER'];
        $orgUuid = isset($params['orgUuid']) ? $params['orgUuid'] : AuthContext::get(AuthConstants::ORG_UUID);
        if (isset($orgUuid)) {
            $path = $orgUuid."/".$template;
        } else {
            $path = $template;
        }
        
        if (is_file($templateDir.$path)) {
            return $templateDir.$orgUuid;
        } else if (is_file($templateDir.$template)) {
            return $templateDir;
        }
        return false;
    }
}