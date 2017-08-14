<?php

use \yii\helpers\ArrayHelper;
use \common\models\Suspension;

/** @var $suspension Suspension */

$this->title = 'Взвешивание : ' . ArrayHelper::getValue($suspension, "weight");

$this->params['breadcrumbs'][] = ['label' => 'Список взвешиваний', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;