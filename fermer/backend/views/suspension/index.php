<?php

use \yii\grid\GridView;
use \common\models\search\SuspensionSearch;

$this->title = 'Список перевесок';
$this->params['breadcrumbs'][] = $this->title;

/** @var $dataProvider \yii\data\ActiveDataProvider */
/** @var $searchModel SuspensionSearch */

echo GridView::widget([
    "dataProvider" => $dataProvider,
    "filterModel"  => $searchModel,
    'columns'      => [
        ['class' => 'yii\grid\SerialColumn'],
        'calf',
        'weight',
        'date',
        [
            'class'  => 'yii\grid\ActionColumn',
            'header' => 'Действия'
        ],
    ]
]);