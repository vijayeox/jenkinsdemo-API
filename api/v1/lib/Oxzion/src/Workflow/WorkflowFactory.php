<?php
namespace Oxzion\Workflow;

class WorkflowFactory
{
    private static $instance;
    private $config;
    private function __construct($config)
    {
        $this->config = $config;
    }

    public static function getInstance($config)
    {
        if (self::$instance === null) {
            self::$instance = new WorkflowFactory($config);
        }
        return self::$instance;
    }

    public function getActivity()
    {
        return new Camunda\ActivityImpl($this->config);
    }

    public function getProcessEngine()
    {
        return new Camunda\ProcessEngineImpl($this->config);
    }

    public function getProcessManager()
    {
        return new Camunda\ProcessManagerImpl($this->config);
    }

    public function getIncidentManager()
    {
        return new Camunda\IncidentManagerImpl($this->config);
    }
}
