<?php

namespace Oxzion;

use Zend\Db\Adapter\AdapterInterface;

class Module {

    public function getServiceConfig(){
        return [
            'factories' => [
                Auth\AuthContext::class => function($container) {
                    return new Auth\AuthContext();
                },
                Auth\AuthSuccessListener::class => function($container){
                    return new Auth\AuthSuccessListener($container->get(Service\UserService::class));

                },
                Service\UserService::class => function($container) {
                    $config = $container->get('config');
                    $dbAdapter = $container->get(AdapterInterface::class);
                    return new Service\UserService($config, $dbAdapter);
                },
            ],
            
        ];
    }
    /**
     * Retrieve default zend-db configuration for zend-mvc context.
     *
     * @return array
     */
    public function getConfig()
    {
        return [
        ];
    }

    
}