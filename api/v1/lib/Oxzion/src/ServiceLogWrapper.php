<?php
namespace Oxzion;

class ServiceLogWrapper {
    private $instance;

    function __construct($instance) {
        $this->instance = $instance;
        $this->logger = $logger;
    }

    function __call($method, $args) {
        if (in_array($method, get_class_methods($this->instance) ) ) {
            $this->logger->debug('Method entry:' . get_class($this->instance) . '->' . $method);
            try {
                return call_user_func_array(array($this->instance, $method), $args);
            }
            catch(\Throwable $e) {
                $this->logger->err($e);
                $this->logger->err('Input parameters:' . json_encode($args));
                throw($e);
            }
            finally {
                $this->logger->debug('Method exit:' . get_class($this->instance) . '->' . $method);
            }
        } 
        else {
            $this->logger->err("Method ${method} not found in class:" . get_class($this->instance));
            $this->logger->err('Input parameters:' . json_encode($args));
            throw new BadMethodCallException("Method ${method} not found in class:" . get_class($this->instance));
        }
    }
}


