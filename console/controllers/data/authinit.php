<?php
return [
    'permissions' => [

        //***************************************************************** Общие роуты
        'menuAll'                          => 'Загальні пункти меню',

        //***************************************************************** Администрирование
        'menuAdminMain'          => 'Системне адміністрування (меню)',
        'systemAdminxx'             => 'Системне адміністрування (дії)',

    ],
    'roles' => [
        'superAdmin' => 'Головний системний адміністратор',
        'user' => 'User',
    ],
    'rolesPermissions' => [
        'superAdmin' => [
            'menuAdminMain',
            'systemAdminxx',
        ],
    ],
    'rolesChildren' => [
        'superAdmin' => [
            'user',
        ],
    ]
];