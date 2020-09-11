<?php

namespace backend\modules\scheme\assets;

use yii\web\AssetBundle;

/**
 * Class SchemeAsset
 * @package backend\modules\scheme\assets
 */
class SchemeAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $css = [
//        'css/scheme/scheme.css'
    ];

    public $js = [
        'js/scheme/scheme.js'
    ];

    public $depends = [
        'yii\web\YiiAsset',
    ];
}
