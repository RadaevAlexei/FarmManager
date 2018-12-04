<?php

namespace backend\modules\scheme\assets;

use yii\web\AssetBundle;

/**
 * Class ActionListAsset
 * @package backend\modules\scheme\assets
 */
class ActionListAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $css = [];

    public $js = [
        'js/scheme/action-list.js'
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];

    public $jsOptions = ['position' => \yii\web\View::POS_END];
}
