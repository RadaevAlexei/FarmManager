<?php

namespace backend\modules\scheme\assets;

use yii\web\AssetBundle;

/**
 * Class ActionAsset
 * @package backend\modules\scheme\assets
 */
class ActionAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $css = [
//        'css/scheme/action-list.css'
    ];

    public $js = [
        'js/scheme/action.js'
    ];

    public $depends = [
        'yii\web\YiiAsset',
    ];
}
