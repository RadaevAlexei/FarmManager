<?php

use \yii\grid\GridView;
use \common\models\User;
use \yii\bootstrap\Html;
use \common\models\search\UserSearch;

$this->title = 'Список сотрудников';
$this->params['breadcrumbs'][] = $this->title;

/** @var $dataProvider \yii\data\ActiveDataProvider */
/** @var $searchModel UserSearch */

echo GridView::widget([
    "dataProvider" => $dataProvider,
    "filterModel"  => $searchModel,
    'columns'      => [
        ['class' => 'yii\grid\SerialColumn'],
        'username',
        [
            'attribute' => 'gender',
            'value'     => function (User $model) {
                return $model->getGenderLabel();
            },
            'filter'    => Html::activeDropDownList(
                $searchModel,
                "gender",
                User::getGenderList(),
                [
                    "prompt" => "Пол",
                    'class'  => 'form-control'
                ]
            )
        ],
        'posName',
        [
            'class'  => 'yii\grid\ActionColumn',
            'header' => 'Действия'
        ],
    ]
]);