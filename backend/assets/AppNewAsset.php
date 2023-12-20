<?php

namespace backend\assets;

use yii\web\AssetBundle;
use yii\web\YiiAsset;

/**
 * Main backend application asset bundle.
 */
class AppNewAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/vendor.css?v=156',
        'css/main.css?v=156',
        'css/custom.css?v=156',
        'css/mobiscroll.javascript.min.css?v=156',
        'css/bootstrap-timepicker.min.css',
        'css/select2.min.css',
        'vendor/bower-asset/select2/dist/css/select2.min.css',
        'css/jquery-ui.min.css'
    ];
    public $js = [
        'js/main.js?v=156',
        'js/jquery-3.6.0.min.js',
        'js/jquery-ui.min.js',
        'js/custom.js?v=156',
        'js/mobiscroll.javascript.min.js?v=156',
        'js/multi-select.js',
        'js/file-upload.js?v=156',
        'js/qa.js?v=156',
        'js/bootstrap-timepicker.min.js',
        'js/select2.full.min.js',
        'vendor/bower-asset/select2/dist/js/select2.min.js',
        'vendor/bower-asset/clockpicker/dist/bootstrap-clockpicker.js',
        'js/jquery.dateandtime.js'
    ];

    public $depends = [
        YiiAsset::class,
    ];
}
