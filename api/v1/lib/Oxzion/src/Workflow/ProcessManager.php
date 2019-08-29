<?php
namespace Oxzion\Workflow;

interface ProcessManager
{

    /**
   * Retrieves the BPMN 2.0 XML of this process definition.
   *
   * @param String $id id of the process definition
   * @return mixed returns the server response
   */

    public function deploy($name, $filesArray);

    public function remove($id);

    public function get($id);
}
