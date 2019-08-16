<?php
namespace Oxzion\Workflow;

class WorkflowFactory
{
    private static $instance;

    protected function __construct()
    {
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new WorkflowFactory();
        }
        return self::$instance;
    }

    public static function getActivity()
    {
        return new Camunda\ActivityImpl();
    }

    public static function getEventManager()
    {
        return new Camunda\EventManagerImpl();
    }

    public static function getGroup()
    {
        return new Camunda\GroupImpl();
    }

    public static function getProcessEngine()
    {
        return new Camunda\ProcessEngineImpl();
    }

    public function getProcessManager()
    {
        return new Camunda\ProcessManagerImpl();
    }
    public static function getUser()
    {
        return new Camunda\UserImpl();
    }
}
