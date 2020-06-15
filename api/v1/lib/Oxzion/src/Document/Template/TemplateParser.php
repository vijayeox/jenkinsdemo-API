<?php
namespace Oxzion\Document\Template;

interface TemplateParser
{

    public function init(array $params);

      /**
     * Merges the data provided with the template
     * @method getContent
     * @param $template - templatePath (absolute Path without file name),
     *   templateNameWithExt (example: file.ext)
     * @param array $data
     * @param array $options Specific to the implementation
     * @return mixed generated content of the template
     */    
    public function getContent(array $template, array $data = array(), array $options = array());
}
