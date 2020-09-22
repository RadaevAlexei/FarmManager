<?php

namespace backend\modules\pharmacy\assets;

use yii\web\AssetBundle;

/**
 * Class PreparationAsset
 * @package backend\modules\scheme\assets
 */
class PreparationAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $css = [];

    public $js = [
        'js/pharmacy/preparation.js'
    ];

    public $depends = [
        'yii\web\YiiAsset',
    ];
}
