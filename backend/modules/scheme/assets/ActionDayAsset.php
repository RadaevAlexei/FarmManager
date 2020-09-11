<?php

namespace backend\modules\scheme\assets;

use yii\web\AssetBundle;

/**
 * Class ActionDayAsset
 * @package backend\modules\scheme\assets
 */
class ActionDayAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $css = [
//        'css/scheme/scheme.css'
    ];

    public $js = [
        'js/scheme/action-day.js'
    ];

    public $depends = [
        'yii\web\YiiAsset',
    ];
}
