<?php

use \yii\helpers\ArrayHelper;
use \common\models\Group;

/** @var $group Group */

$this->title = 'Группа : ' . ArrayHelper::getValue($group, "name");

$this->params['breadcrumbs'][] = ['label' => 'Список групп', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;