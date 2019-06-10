<?php
namespace Oxzion\Service;

use Smarty;
use Exception;
use Bos\Service\AbstractService;
use Oxzion\Utils\BosUtils;

class EmailTemplateService extends AbstractService {

	private $client;
	private $templateName;
	private $templateExt = ".tpl";

    public function __construct($config, $dbAdapter){
        parent::__construct($config, $dbAdapter);
        $this->init();
    }

    public function init() {
    	$dataFolder = $this->config['DATA_FOLDER'];
    	$templateDir = $this->config['TEMPLATE_FOLDER'];
    	if (!file_exists($templateDir)) mkdir($templateDir, 0777, true);
    	if (!file_exists($cacheDir = $dataFolder.'/cache/')) mkdir($cacheDir, 0777);
    	if (!file_exists($configsDir = $dataFolder.'/configs/')) mkdir($configsDir, 0777);
    	if (!file_exists($templatescDir = $dataFolder.'/templates_c/')) mkdir($templatescDir, 0777);

		$this->client = new Smarty();
		// $this->client->debugging = true;
		$this->client->setCacheDir($cacheDir);
		$this->client->setConfigDir($configsDir);
		$this->client->setTemplateDir($templateDir);
		$this->client->setCompileDir($templatescDir);
		// $this->testInstall();
        // echo "<pre>";print_r($this->getContent('newAdminUser', array('company_name' => 'Test Organization', 'username' => 'testadmin', 'password' => 'welcome2oxzion')));exit();
        // echo "<pre>";print_r($this->getContent('test', array('name' => 'Karan')));exit();
	}

	/**
	 * Gets the templates.
	 *
	 * @return     array  The templates name and the template.
	 */
	public function getTemplates() {
		$templates = array();
		foreach (glob($this->client->getTemplateDir()[0]."*") as $file) {
			if (is_file($file)) {
				if (strpos($file, $this->templateExt) != false) {
					$templates[basename($file)] = file_get_contents($file);
				}
			}
		}
		return $templates;
	}

	/**
	 * Gets the template content.
	 *
	 * @param      String     $templatePath  The template path or the template name
	 * @param      array      $data          The data element pathed in the template
	 *
	 * @throws     Exception  (If the template is not found)
	 *
	 * @return     String     The template content.
	 */
	public function getContent($templatePath, $data = array()) {
		if (!$this->checkTemplateExists($templatePath))
			throw new Exception("Email Template not found!", 1);

		$this->client->assign($data);
		return $this->client->fetch($this->templateName);
	}

	public function setTemplatePath($templatePath) {
		if (is_dir($templatePath)) {
			$this->client->setTemplateDir($templateDir);
		} else {
			$pathInfo = pathinfo($templatePath);
			if ($pathInfo['dirname'] != '.') {
				$this->client->setTemplateDir($pathInfo['dirname']);
				$this->templateName = $pathInfo['basename'].$this->templateExt;
			} else {
				$this->templateName = $pathInfo['basename'].$this->templateExt;
			}
		}
	}

	public function checkTemplateExists($templatePath) {
		$this->setTemplatePath($templatePath);
		if (is_file($this->client->getTemplateDir()[0].$this->templateName)) {
			return true;
		}
		return false;
	}

}
?>