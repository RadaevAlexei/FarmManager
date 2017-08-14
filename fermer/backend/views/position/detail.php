<?php

use \yii\grid\GridView;
use \yii\helpers\ArrayHelper;

/** @var $position \common\models\Position */

$this->title = 'Должность';

$this->params['breadcrumbs'][] = ['label' => 'Список должностей', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Должность : ' . ArrayHelper::getValue($position, "name");