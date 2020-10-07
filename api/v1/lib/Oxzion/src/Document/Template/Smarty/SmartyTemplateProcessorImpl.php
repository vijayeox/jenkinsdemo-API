<?php
namespace Oxzion\Document\Template\Smarty;

use Oxzion\Utils\FileUtils;
use Exception;
use Smarty;
use Oxzion\Service\AbstractService;
use Oxzion\Utils\BosUtils;
use Oxzion\Auth\AuthContext;
use Oxzion\Auth\AuthConstants;
use Oxzion\Utils\ArtifactUtils;
use Oxzion\Document\Template\TemplateParser;
use Oxzion\Service\TemplateService;

class SmartyTemplateProcessorImpl implements TemplateParser  
{
    private $client;
    private $templateExt = ".tpl";
    
    /**
     * Initializes the template processor
     * @method init
     * @param array $params - cacheDir, configsDir, templateDir, compileDir
     * @return none
     */
    public function init($params){
        $this->client = new Smarty();
        $this->client->setCacheDir($params['cacheDir']);
        $this->client->setConfigDir($params['configsDir']);
        $this->client->setTemplateDir($params['templateDir']);
        $this->client->setCompileDir($params['compileDir']);
    }
    
    /**
     * Merges the data provided with the template
     * @method getContent
     * @param $template - templatePath (absolute Path without file name),
     *   templateNameWithExt (example: file.ext)
     * @param array $data
     * @param array $options N/A
     * @return the generated content of the template
     */
    public function getContent($template, $data = array(), $options = array()){
        $this->client->setTemplateDir($template['templatePath']);
        $template = $template['templateNameWithExt'];
        $this->client->assign($data);
        $content = $this->client->fetch($template);
        return $content;
    }
}
