<?php

return [
    'zuser' => [
        'userCollection' => 'PhalzonZ\ZUser\Models\User'
    ],

    'route' => [
        '/auth/login' => [
            'module' => 'ZUser',
            'controller' => 'auth',
            'action' => 'login'
        ],
        '/auth/logout' => [
            'module' => 'ZUser',
            'controller' => 'auth',
            'action' => 'logout'
        ],
        '/auth/register' => [
            'module' => 'ZUser',
            'controller' => 'auth',
            'action' => 'index'
        ]
    ],
];