<?php
namespace Oxzion\Service;

use Smarty;
use Exception;
use Oxzion\Service\AbstractService;
use Oxzion\Utils\BosUtils;
use Oxzion\Auth\AuthContext;
use Oxzion\Auth\AuthConstants;
use Oxzion\Utils\ArtifactUtils;

class TemplateService extends AbstractService
{
    private $client;
    private $templateName;
    private $templateExt = ".tpl";
    private $templateDir;

    public function __construct($config, $dbAdapter)
    {
        parent::__construct($config, $dbAdapter);
        $this->init();
    }

    public function init()
    {
        $dataFolder = $this->config['DATA_FOLDER'];
        $templateDir = $this->config['TEMPLATE_FOLDER'];
        if (!file_exists($templateDir)) {
            mkdir($templateDir, 0777, true);
        }
        if (!file_exists($cacheDir = $templateDir.'/cache/')) {
            mkdir($cacheDir, 0777);
        }
        if (!file_exists($configsDir = $templateDir.'/configs/')) {
            mkdir($configsDir, 0777);
        }
        if (!file_exists($templatescDir = $templateDir.'/templates_c/')) {
            mkdir($templatescDir, 0777);
        }

        $this->templateDir = $templateDir;
        $this->client = new Smarty();
        // $this->client->debugging = true;
        $this->client->setCacheDir($cacheDir);
        $this->client->setConfigDir($configsDir);
        $this->client->setTemplateDir($templateDir);
        $this->client->setCompileDir($templatescDir);
        // $this->testInstall();
        // echo "<pre>";print_r($this->getContent('newAdminUser', array('company_name' => 'Test Organization', 'username' => 'testadmin', 'password' => 'welcome2oxzion')));exit();
        // echo "<pre>";print_r($this->getContent('test', array('name' => $this->adminUser)));exit();
    }

    /**
     * Gets the template content.
     *
     * @param      String     $templateName  The template name
     * @param      array      $data          The data element pathed in the template
     *
     * @throws     Exception  (If the template is not found)
     *
     * @return     String     The template content.
     */
    public function getContent($templateName, $data = array())
    {
        $template = $this->setTemplateDir($templateName, $data);
        if (!$template) {
            throw new Exception("Template not found!", 1);
        }
        $this->client->assign($data);

        return $this->client->fetch($template);
    }

    private function setTemplateDir($templateName, $params = array()){
        $template = $templateName.$this->templateExt;
        $templatePath = $this->getTemplatePath($template, $params);
        if($templatePath){
            $this->client->setTemplateDir($templatePath);
            return $template;
        }

        return false;
        
    } 

    public function getTemplatePath($template, $params = array())
    {
       if (!isset($params['orgUuid']) && isset($params['orgid'])) {
            $org = $this->getIdFromUuid('ox_organization', $params['orgid']);
            if ($org != 0) {
                $orgUuid = $params['orgid'];
                $params['orgUuid'] = $orgUuid;
            }
        } 
        
        return ArtifactUtils::getTemplatePath($this->config, $template, $params);
    }
}
