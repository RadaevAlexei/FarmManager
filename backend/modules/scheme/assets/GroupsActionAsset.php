<?php

namespace backend\modules\scheme\assets;

use yii\web\AssetBundle;

/**
 * Class GroupsActionAsset
 * @package backend\modules\scheme\assets
 */
class GroupsActionAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $css = [
        'css/scheme/groups-action.css'
    ];

    public $js = [
        'js/scheme/groups-action.js'
    ];

    public $depends = [
        'yii\web\YiiAsset',
    ];
}
