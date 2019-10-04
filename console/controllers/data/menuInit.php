<?php
$t = [
    [
        'name' => 'Адміністрування',
        'route' => '',
        'role' => 'menuAdminxMain',
        'access_level' => 2,
        'children' => [
            [
                'name'       => 'Користувачі',
                'route'      => '/adminx/user',
                'role'       => 'menuAdminMain',
                'access_level' => 2,
                'children' => []
            ],
            [
                'name'       => 'Правила',
                'route'      => '/adminx/rule',
                'role'       => 'menuAdminMain',
                'access_level' => 2,
                'children' => []
            ],
            [
                'name'       => 'Дозвіли, ролі',
                'route'      => '/adminx/auth-item',
                'role'       => 'menuAdminMain',
                'access_level' => 2,
                'children' => []
            ],
            [
                'name'       => 'Редактор меню',
                'route'      => '/adminx/menux/menu',
                'role'       => 'menuAdminMain',
                'access_level' => 2,
                'children' => []
            ],
            [
                'name'       => 'Системні налаштування',
                'route'      => '/adminx/configs/update',
                'role'        => 'menuAdminMain',
                'access_level' => 2,
                'children' => []
            ],
            [
                'name'       => 'Відвідування сайту',
                'route'      => '/adminx/check/guest-control',
                'role'       => 'menuAdminMain',
                'access_level' => 2,
                'children' => []
            ],
            [
                'name'       => 'PHP-info',
                'route'      => 'adminx/user/php-info',
                'role'       => 'menuAdminMain',
                'access_level' => 2,
                'children' => [],
            ],
        ]
    ],

    //********************************************************************************************************** КАБИНЕТ
    [
        'name' => 'Кабінет',
        'route' => '',
        'role' => 'menuAll',
        'access_level' => 0,
        'children' => [
            [
                'name'       => 'Зміна паролю',
                'route'      => '/adminx/user/change-password',
                'role' => 'menuAll',
                'access_level' => 0,
                'children' => [],
            ],
            [
                'name'       => 'Вихід',
                'route'      => '/adminx/user/logout ',
                'role' => 'menuAll',
                'access_level' => 0,
                'children' => [],
            ],
        ]
    ],
    [
        'name'       => 'Вхід',
        'route'      => '/adminx/user/login',
        'role' => '',
        'access_level' => 0,
        'children' => [],
    ],
];

return $t;