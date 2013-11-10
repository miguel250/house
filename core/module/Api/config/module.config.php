<?php

return array(
    'router' => array(
        'routes' => array(
            'user' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/api/user[/:id]',
                    'defaults' => array(
                        'controller' => 'Api\Controller\User',
                    ),
                ),
            ),
            'item' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/api/item[/:id]',
                    'defaults' => array(
                        'controller' => 'Api\Controller\Item',
                    ),
                ),
            ),
            'ping' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/api/ping[/:id]',
                    'defaults' => array(
                        'controller' => 'Api\Controller\Ping',
                    ),
                ),
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Api\Controller\User' => 'Api\Controller\UserController',
            'Api\Controller\Item' => 'Api\Controller\ItemController',
             'Api\Controller\Ping' => 'Api\Controller\PingController',
        ),
    ),
    'view_manager' => array(
        'strategies' => array(
            'ViewJsonStrategy',
        ),
    ),
);