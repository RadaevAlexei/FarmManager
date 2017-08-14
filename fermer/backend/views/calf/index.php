<?php

use \yii\grid\GridView;
use \common\models\search\CalfSearch;

$this->title = 'Список поголовья';
$this->params['breadcrumbs'][] = $this->title;

/** @var $dataProvider \yii\data\ActiveDataProvider */
/** @var $searchModel CalfSearch */

echo GridView::widget([
    "dataProvider" => $dataProvider,
    "filterModel"  => $searchModel,
    'columns'      => [
        ['class' => 'yii\grid\SerialColumn'],
        'number',
        'nickname',
        [
            'class'  => 'yii\grid\ActionColumn',
            'header' => 'Действия'
        ],
    ]
]);