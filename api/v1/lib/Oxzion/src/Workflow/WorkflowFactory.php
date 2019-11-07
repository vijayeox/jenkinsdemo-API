<?php
namespace Oxzion\Workflow;

class WorkflowFactory
{
    private static $instance;

    private function __construct()
    {
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new WorkflowFactory();
        }
        return self::$instance;
    }

    public function getActivity()
    {
        return new Camunda\ActivityImpl();
    }

    public function getEventManager()
    {
        return new Camunda\EventManagerImpl();
    }

    public function getGroup()
    {
        return new Camunda\GroupImpl();
    }

    public function getProcessEngine()
    {
        return new Camunda\ProcessEngineImpl();
    }

    public function getProcessManager()
    {
        return new Camunda\ProcessManagerImpl();
    }
    public function getUser()
    {
        return new Camunda\UserImpl();
    }
}
