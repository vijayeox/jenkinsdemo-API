<?php
namespace Oxzion\Document\Template;

interface TemplateParser
{

    public function init(array $params);
    
    public function getContent($template, array $data = array(), array $options = array());
}
