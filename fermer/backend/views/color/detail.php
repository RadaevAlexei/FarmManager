<?php

use \yii\helpers\ArrayHelper;
use \common\models\Color;

/** @var $color Color */

$this->title = 'Масть : ' . ArrayHelper::getValue($color, "name");

$this->params['breadcrumbs'][] = ['label' => 'Список мастей', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;