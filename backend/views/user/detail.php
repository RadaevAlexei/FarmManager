<?php

use \yii\grid\GridView;
use \yii\helpers\ArrayHelper;

/** @var $user \common\models\User */

$this->title = 'Карточка сотрудника';

$this->params['breadcrumbs'][] = ['label' => 'Список пользователей', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Пользователь : ' . ArrayHelper::getValue($user, "username");