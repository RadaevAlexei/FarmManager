<?php

use \yii\grid\GridView;
use \common\models\PositionSearch;

$this->title = 'Список должностей';
$this->params['breadcrumbs'][] = $this->title;

/** @var $dataProvider \yii\data\ActiveDataProvider */
/** @var $searchModel PositionSearch */

echo GridView::widget([
    "dataProvider" => $dataProvider,
    "filterModel"  => $searchModel,
    'columns'      => [
        ['class' => 'yii\grid\SerialColumn'],
        'name',
        'short_name',
        [
            'class'  => 'yii\grid\ActionColumn',
            'header' => 'Действия'
        ],
    ]
]);