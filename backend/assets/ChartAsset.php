<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class ChartAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/site.css',
        'js/bootstrap-datepicker-1.6.4-dist\css\bootstrap-datepicker.min.css',
        'js/jquery-ui-1.12.1.custom/jquery-ui.min.css'
    ];
    public $js = [
        'js/suspension.js',
        'js/jquery-ui-1.12.1.custom/jquery-ui.min.js',
        'js/bootstrap-datepicker-1.6.4-dist/js/bootstrap-datepicker.min.js',
        'js/Chart.bundle.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];

    public $jsOptions = ['position' => \yii\web\View::POS_END];
}
