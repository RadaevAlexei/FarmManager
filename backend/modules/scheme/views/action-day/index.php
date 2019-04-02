<?php

use \yii\helpers\Html;
use \yii\helpers\Url;
use \yii\grid\GridView;
use \backend\modules\scheme\assets\ActionDayAsset;
use \yii\helpers\ArrayHelper;
use \yii\data\ArrayDataProvider;
use \backend\modules\scheme\models\search\ActionHistorySearch;

$this->title = 'Список дел';
$this->params['breadcrumbs'][] = $this->title;

ActionDayAsset::register($this);

/**
 * /** @var $dataProvider ArrayDataProvider
 * /** @var $searchModel ActionHistorySearch
 */
?>

<div class="form-group">
    <?= Html::a(
        'Скачать список дел на сегодня',
        $dataProvider->getModels() ? Url::toRoute(['action-day/download-action-list']) : "#",
        [
            'class'    => 'btn btn-success',
            'disabled' => $dataProvider->getModels() ? false : true
        ]
    ) ?>
</div>

<?php echo GridView::widget([
    "dataProvider" => $dataProvider,
    'columns'      => [
        ['class' => 'yii\grid\SerialColumn'],
        [
            'label' => 'Схема лечения',
            'value' => function ($model) {
                return ArrayHelper::getValue($model, "scheme_name");
            }
        ],
        [
            'label' => 'Количество голов на схеме',
            'value' => function ($model) {
                return count(ArrayHelper::getValue($model, "animals"));
            }
        ],
        [
            'class'    => 'yii\grid\ActionColumn',
            'header'   => 'Просмотр',
            'template' => '<div class="btn-group">{detail}</div>',
            'buttons'  => [
                'detail' => function ($url, $model) {
                    return Html::a(
                        '<span class="glyphicon glyphicon-eye-open"></span>',
                        Url::toRoute(['action-day/details', 'scheme_id' => ArrayHelper::getValue($model, "scheme_id")]),
                        ['class' => 'btn btn-primary']
                    );
                }
            ],
        ]
    ]
]);