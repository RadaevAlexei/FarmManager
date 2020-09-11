<?php

namespace backend\modules\rectal\assets;

use yii\web\AssetBundle;

/**
 * Class RectalAsset
 * @package backend\modules\rectal\assets
 */
class RectalAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $css = [];

    public $js = [
        'js/rectal/rectal.js',
    ];

    public $depends = [
        'yii\web\YiiAsset',
    ];
}
