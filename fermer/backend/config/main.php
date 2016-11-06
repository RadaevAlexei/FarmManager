<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'language' => 'ru-RU',
    'sourceLanguage' => 'ru-RU',
    'bootstrap' => ['log'],
    'modules' => [],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-backend',
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
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
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'class' => '\yii\web\UrlManager',
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => true,
            'suffix' => '/',
            'rules' => [
                '/' => 'site/index',

                'employees' => 'employees/list',
                'employee/detail/<id:\d+>' => 'employees/detail',
                'employee/<action:(new)>' => 'employees/actions',
                'employee/<action:(new|edit|delete)>/<id:\d+>' => 'employees/actions',
                'employee/<action:(save)>' => 'employees/save-update',
                'employee/<action:(save|update)>/<id:\d+>' => 'employees/save-update',

                'functions' => 'functions/list',
                'function/<action:(new)>' => 'functions/actions',
                'function/<action:(new|edit|delete)>/<id:\d+>' => 'functions/actions',
                'function/<action:(save)>' => 'functions/save-update',
                'function/<action:(save|update)>/<id:\d+>' => 'functions/save-update',

                'groups' => 'groups/list',
                'group/detail/<id:\d+>' => 'groups/detail',
                'group/<action:(new)>' => 'groups/actions',
                'group/<action:(new|edit|delete)>/<id:\d+>' => 'groups/actions',
                'group/<action:(save)>' => 'groups/save-update',
                'group/<action:(save|update)>/<id:\d+>' => 'groups/save-update',

                'suspension/add' => 'admin/suspension-add',
                'suspension/save' => 'admin/suspension-save',

                '<controller:[-_0-9a-zA-Z]+>/<action:[-_0-9a-zA-Z]+>' => '<controller>/<action>',
                '<module:[-_0-9a-zA-Z]+>/<controller:[-_0-9a-zA-Z]+>/<action:[-_0-9a-zA-Z]+>' => '<module>/<controller>/<action>'
            ]
        ],

        'i18n' => [
            'translations' => [
                'app*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'forceTranslation' => true,
                    'basePath' => '@backend/messages',
                    'fileMap' => [
                        'app/back' => 'backend.php'
                    ],
                ]
            ]
        ]
    ],
    'params' => $params,
];
