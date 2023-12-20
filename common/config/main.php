<?php

function pre($data)
{
    echo "<pre>";
    print_r($data);
    echo "</pre>";
    die();
}

return [
    'timeZone' => 'Asia/Tashkent',
    'name' => 'stomaservice.uz',
    'language' => 'ru_RU',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ]

    ],
];
