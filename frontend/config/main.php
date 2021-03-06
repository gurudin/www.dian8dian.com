<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'components' => [
        /**
         * Menu 组件.
         */
        'menu' => [
            'class' => 'frontend\components\menu',
        ],
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
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '/nav/<menu>'  => 'nav/index',
                '/sitemap.xml' => 'site/sitemap',
                '/google-sitemap.xml' => 'site/google-sitemap',
                [
                    'route'    => 'nav/search',
                    'pattern'  => '/search/<keywords>',
                    'defaults' => ['keywords' => '']
                ],
                [
                    'route'    => 'article/detail',
                    'pattern'  => '/article/<id>/<title>',
                    'defaults' => ['title' => ''],
                ]
            ],
        ],
    ],
    'params' => $params,
];
