<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-backend',
    'defaultRoute' => '/adminx',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'controllerMap' => [
        'wcontroller' => 'common\components\widgets\controllers\WidgetController',
    ],
    'modules' => [
        'adminx' => [
            'class' => 'backend\modules\adminx\Adminx',
        ],
        'kino' => [
            'class' => 'backend\modules\kino\Kino',
        ],
        'v1' => [
            'class' => 'backend\modules\v1\V1',
        ],
    ],
    'components' => [
        'configs' => [
            'class' => 'common\components\ConfigsComponent',
        ],
        'request' => [
          //  'csrfParam' => '_csrf-backend',
            'cookieValidationKey' => 'bBlVg2eb_z1rlmjkAO6losdfsd4otSDI3Smwa',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ],


        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'class' => 'backend\modules\adminx\components\User',
            'identityClass' => 'backend\modules\adminx\models\User',
            'loginUrl' => ['site/login'],
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
        ],
        'authManager' => [
            'class' => 'backend\modules\adminx\components\DbManager',
            'cache' => 'cache'
        ],

        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-backend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'trace', 'info'],
                    'categories' => ['dbg'],
                    'logFile' => '@runtime/dbg/dbg.log',
                    'logVars' => [],
                ],
            ],

        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
        'conservation' => [
            'class' => 'common\components\conservation\ConservationComponent',
        ],

    ],
    'params' => $params,
];
