<?php

    return [ 
        'logger' => [
                'rootLogger' => [
                    'level' => 'INFO',
                    'appenders' => ['default']
                ],
                'loggers' => [
                    'ControllerLogger' => [
                        'level' => 'INFO',
                        'appenders' => ['default']
                    ],
                    'OxzionLogger' => [
                        'level' => 'INFO',
                        'appenders' => ['default']
                    ]
                ],
                'appenders' => [
                    'default' => [
                        'class' => 'LoggerAppenderDailyFile',
                        'layout' => [
                            'class' => 'LoggerLayoutPattern',
                            'params' => [
                                'conversionPattern' => '%date{d/m/Y H:i:s,u} %C->%M %-5level [%pid] %msg%n%ex'
                            ]
                        ],
                        'params' => [
                            'file' => __DIR__."/../../logs/application-%s.log",
                            'append' => true,
                            'datePattern' => 'Y-m-d'
                        ]
                    // ],
                    // 'request' => [
                    //     'class' => 'LoggerAppenderDailyFile',
                    //     'layout' => [
                    //         'class' => 'LoggerLayoutPattern',
                    //         'params' => [
                    //             'conversionPattern' => '%date(d:m:Y H:i:s,u) [%pid] From:%server{REMOTE_ADDR}:%server{REMOTE_PORT} Request:[%request]%n%ex'
                    //         ]
                    //     ],
                    //     'params' => [
                    //         'file' => __DIR__."/../../logs/request.log",
                    //         'append' => true
                    //     ]
                    ]
                ]
            ]
        ];
?>