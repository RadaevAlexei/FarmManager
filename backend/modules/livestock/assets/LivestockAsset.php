<?php

namespace backend\modules\livestock\assets;

use yii\web\AssetBundle;

/**
 * Class LivestockAsset
 * @package backend\modules\livestock\assets
 */
class LivestockAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $css = [];

    public $js = [
        'js/livestock/livestock.js',
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
