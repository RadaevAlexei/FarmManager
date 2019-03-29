<?php

use \yii\helpers\Html;
use \yii\helpers\Url;
use \yii\grid\GridView;
use \backend\modules\scheme\models\ActionHistory;
use \yii\helpers\ArrayHelper;

$this->title = 'Список дел';
$this->params['breadcrumbs'][] = $this->title;

/**
 * /** @var $dataProvider ActiveDataProvider
 * /** @var $searchModel ActionHistorySearch
 */
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
    'columns'      => [
        ['class' => 'yii\grid\SerialColumn'],
        [
            'label' => 'Схема лечения',
            'value' => function (ActionHistory $model) {
                return ArrayHelper::getValue($model, "appropriationScheme.scheme.name");
            }
        ],
        [
            'label' => 'Диагноз',
            'value' => function (ActionHistory $model) {
                return ArrayHelper::getValue($model, "appropriationScheme.scheme.diagnosis.name");
            }
        ],
        [
            'class'    => 'yii\grid\ActionColumn',
            'header'   => 'Просмотр',
            'template' => '<div class="btn-group">{detail}</div>',
            'buttons'  => [
                'detail' => function ($url, ActionHistory $model) {
                    return Html::a(
                        '<span class="glyphicon glyphicon-eye-open"></span>',
                        Url::toRoute(['action-day/details', 'scheme_id' => ArrayHelper::getValue($model, "appropriationScheme.scheme.id")]),
                        ['class' => 'btn btn-primary']
                    );
                }
            ],
        ]
    ]
]);*/