<?php
$params = array_merge(
	require(__DIR__ . '/../../common/config/params.php'),
	require(__DIR__ . '/../../common/config/params-local.php'),
	require(__DIR__ . '/params.php'),
	require(__DIR__ . '/params-local.php')
);

return [
	'id'                  => 'app-backend',
	'basePath'            => dirname(__DIR__),
	'controllerNamespace' => 'backend\controllers',
	'language'            => 'ru-RU',
	'sourceLanguage'      => 'ru-RU',
	'bootstrap'           => ['log'],
	'components'          => [
		'request'      => [
			'csrfParam' => '_csrf-backend',
		],
		'user'         => [
			'identityClass'   => 'common\models\User',
			'enableAutoLogin' => true,
			'identityCookie'  => ['name' => '_identity-backend', 'httpOnly' => true],
		],
		'session'      => [
			// this is the name of the session cookie used for login on the backend
			'name' => 'advanced-backend',
		],
		'log'          => [
			'traceLevel' => YII_DEBUG ? 3 : 0,
			'targets'    => [
				[
					'class'  => 'yii\log\FileTarget',
					'levels' => ['error', 'warning'],
				],
			],
		],
		'errorHandler' => [
			'errorAction' => 'site/error',
		],
		'urlManager'   => [
			'class'               => '\yii\web\UrlManager',
			'enablePrettyUrl'     => true,
			'showScriptName'      => false,
			'enableStrictParsing' => true,
			'suffix'              => '/',
			'rules'               => [
				'/' => 'site/index',

				'<controller:\w+>/<id:\d+>'                                                  => '<controller>/view',
				'<controller:[-_0-9a-zA-Z]+>/<action:[-_0-9a-zA-Z]+>/<id:\d+>'               => '<controller>/<action>',
				'<controller:[-_0-9a-zA-Z]+>/<action:[-_0-9a-zA-Z]+>'                        => '<controller>/<action>',
				'/<module:\w+>/<controller:[-_0-9a-zA-Z]+>/<action:[-_0-9a-zA-Z]+>/<id:\d+>' => '<module>/<controller>/<action>',
				'/<module:\w+>/<controller:[-_0-9a-zA-Z]+>/<action:[-_0-9a-zA-Z]+>'          => '<module>/<controller>/<action>',
			]
		],

		'i18n' => [
			'translations' => [
				'app*' => [
					'class'            => 'yii\i18n\PhpMessageSource',
					'forceTranslation' => true,
					'basePath'         => '@backend/messages',
					'fileMap'          => [
						'app/back'       => 'backend.php',
						'app/position'   => 'position.php',
						'app/color'      => 'color.php',
						'app/user'       => 'user.php',
						'app/animal'     => 'animal.php',
						'app/cowshed'    => 'cowshed.php',
						'app/farm'       => 'farm.php',
						'app/suspension' => 'suspension.php',
						'app/diagnosis'  => 'diagnosis.php',
					],
				]
			]
		]
	],
	'params'              => $params,
	'modules'             => [
		'gii'    => [
			'class'      => 'yii\gii\Module',
			'allowedIPs' => ['*']
		],
		'scheme' => [
			'class' => 'backend\modules\scheme\Module'
		]
	],
];
