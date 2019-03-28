<?php

use \yii\grid\GridView;
use \yii\helpers\Html;
use \yii\helpers\Url;
use \yii\data\ActiveDataProvider;
use backend\modules\scheme\models\search\ActionSearch;
use backend\modules\scheme\models\Action;
use \common\models\TypeField;
use \yii\helpers\ArrayHelper;

$this->title = 'Список дел';
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="form-group">
    <?= Html::a(
        'Скачать список дел на сегодня',
        Url::toRoute(['action-day/download-action-list']),
        [
            'class' => 'btn btn-success'
        ]
    ) ?>
</div>

<?php /*echo GridView::widget([
    "dataProvider" => $dataProvider,
    "filterModel"  => $searchModel,
    'formatter'    => ['class' => 'yii\i18n\Formatter', 'nullDisplay' => ''],
    'tableOptions' => [
        'style' => 'display:block; width:100%; overflow-x:auto',
        'class' => 'table table-striped',
    ],
    'columns'      => [
        ['class' => 'yii\grid\SerialColumn'],
        'name',
        [
            'attribute' => 'type',
            'value'     => function (Action $model) {
                return TypeField::getType($model->type);
            }
        ],
        [
            'attribute' => 'action_list_id',
            'format'    => 'raw',
            'value'     => function (Action $model) {
                return Html::a(ArrayHelper::getValue($model, "actionList.name"),
                    Url::to(['action-list/edit', 'id' => $model->action_list_id]));
            }
        ],
        [
            'class'    => 'yii\grid\ActionColumn',
            'header'   => Yii::t('app/action', 'ACTIONS'),
            'template' => '<div class="btn-group">{update} {delete} </div>',
            'buttons'  => [
                'update' => function ($url, $model) {
                    return Html::a(
                        '<span class="glyphicon glyphicon-edit"></span>',
                        Url::toRoute(['action/edit', 'id' => $model->id]),
                        ['class' => 'btn btn-warning']
                    );
                },
                'delete' => function ($url, $model) {
                    return Html::a(
                        '<span class="glyphicon glyphicon-trash"></span>',
                        Url::toRoute(['action/delete', 'id' => $model->id]),
                        ['class' => 'btn btn-danger']
                    );
                },
            ],
        ],
    ]
]);*/