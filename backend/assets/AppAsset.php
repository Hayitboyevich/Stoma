<?php

namespace backend\assets;

use yii\web\AssetBundle;
use yii\web\YiiAsset;
use yii\bootstrap4\BootstrapAsset;

/**
 * Main backend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/site.css?v=1',
        'css/native-asset.css?v=1',
    ];
    public $js = [
    ];
    public $depends = [
        YiiAsset::class,
        BootstrapAsset::class,
    ];
}
