<?php
namespace Oxzion\FormEngine;

class FormFactory
{
    private static $instance;

    protected function __construct() {
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new FormFactory();
        }
        return self::$instance;
    }
    public static function getFormEngine() {
        return new Formio\EngineImpl();
    }
}
