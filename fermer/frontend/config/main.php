<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'language' => 'ru-RU',
    'sourceLanguage' => 'ru-RU',
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-frontend',
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'advanced-frontend',
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
                '/' => 'calf/index',
                'country' => 'country/index',
                'list' => 'calf/index',
                'suspension' => 'suspension/index',

                'employees' => 'admin/employees-list',
                'employee/detail' => 'admin/employee-detail',
                'employee/add' => 'admin/employee-add',
                'employee/save' => 'admin/employee-save',
                'employee/delete' => 'admin/employee-delete',

                'groups' => 'admin/groups-list',
                'group/detail' => 'admin/group-detail',
                'group/add' => 'admin/group-add',
                'group/edit' => 'admin/group-edit',
                'group/save' => 'admin/group-save',
                'group/delete' => 'admin/group-delete',

                'functions' => 'admin/functions-list',
                'function/add' => 'admin/function-add',
                'function/edit' => 'admin/function-edit',
                'function/save' => 'admin/function-save',
                'function/delete' => 'admin/function-delete',

                'detail/<id:[0-9]+>' => 'calf/detail',
                '<controller:[-_0-9a-zA-Z]+>/<action:[-_0-9a-zA-Z]+>' => '<controller>/<action>',
                '<module:[-_0-9a-zA-Z]+>/<controller:[-_0-9a-zA-Z]+>/<action:[-_0-9a-zA-Z]+>' => '<module>/<controller>/<action>'
            ],
        ],

        'i18n' => [
            'translations' => [
                'app*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'forceTranslation' => true,
                    'basePath' => '@frontend/messages',
                    'fileMap' => [
                        'app/front' => 'front.php',
                        'app/back' => 'back.php'
                    ],
                ]
            ]
        ]

    ],
    'params' => $params,
];
