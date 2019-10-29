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

        $this->logger->info("Template Name:".$templateName);
        $this->logger->info("Data context".print_r($data,true));

        $template = $this->setTemplateDir($templateName, $data);
        $this->logger->info("Template Directory:".print_r($template,true));
        if (!$template) {
            throw new Exception("Template not found!");
        }
        $this->client->assign($data);
        try{
            $content = $this->client->fetch($template);
            $this->logger->info("TEMPLATE CONTENT".print_r($content,true));
        }catch(Exception $e){
            print("Error - ".$e->getMessage()."\n");
            throw $e;
        }
        return $content;
    }

    private function setTemplateDir($templateName, $params = array()){
        $this->logger->info("in setTemplateDir");
        $template = $templateName.$this->templateExt;
        $templatePath = $this->getTemplatePath($template, $params);
       $this->logger->info("template - $templatePath/$template");
        
        if($templatePath){
            $this->client->setTemplateDir($templatePath);
            return $template;
        }

        return false;
        
    } 

    public function getTemplatePath($template, $params = array())
    {
        $this->logger->info("Params - ".print_r($params, true));
       if (!isset($params['orgUuid']) && isset($params['orgId'])) {
            $org = $this->getIdFromUuid('ox_organization', $params['orgId']);
            if ($org != 0) {
                $orgUuid = $params['orgId'];
                $params['orgUuid'] = $orgUuid;
            }
        } 
        $this->logger->debug("In getTemplatePath");
        return ArtifactUtils::getTemplatePath($this->config, $template, $params);
    }
}
