<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Class AnimalAsset
 * @package backend\assets
 */
class AnimalAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $css = [
        'css/animal/animal.css',
    ];

    public $js = [
        'js/animal/animal.js',
        'js/lodash.js',
    ];

    public $depends = [
        'yii\web\YiiAsset',
//        'yii\bootstrap\BootstrapAsset',
    ];
}
