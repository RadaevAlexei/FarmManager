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
                    ],
                ]
            ]
        ]

    ],
    'params' => $params,
];
