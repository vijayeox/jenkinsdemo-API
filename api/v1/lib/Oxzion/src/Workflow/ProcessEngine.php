<?php
namespace Oxzion\Workflow;

interface ProcessEngine
{

	public function getProcessDefinition($id);

    public function startProcess($id,  $processVariables = null);

    public function stopProcess($id);

	public function getProcessDefinitionsByParams($id,$paramsArray);
}
?>