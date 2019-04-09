<?php

use \yii\helpers\Html;
use \yii\helpers\Url;
use \yii\grid\GridView;
use \backend\modules\scheme\assets\ActionDayAsset;
use \yii\helpers\ArrayHelper;
use \yii\data\ArrayDataProvider;
use \backend\modules\scheme\models\search\ActionHistorySearch;
use \yii\widgets\ActiveForm;
use \yii\jui\DatePicker;

$this->title = 'Текущие и предстоящие дела';
$this->params['breadcrumbs'][] = $this->title;

ActionDayAsset::register($this);

/**
 * @var $dataProvider ArrayDataProvider
 * @var $searchModel ActionHistorySearch
 * @var $filterDate string
 * @var $disableExecuteAction bool
 */
?>

<div class="box box-success">
    <div class="box-header with-border">
        <h3 class="box-title">На какую дату посмотреть список дел?</h3>
    </div>
    <?php $form = ActiveForm::begin([
        'action' => Url::toRoute(['index'])
    ]) ?>
    <div class="box-body">
        <div class="form-group">
            <div class="col-sm-3">
                <?= DatePicker::widget([
                    'name' => 'filter_date',
                    'value' => (new DateTime($filterDate))->format('d.m.Y'),
                    'language' => 'ru',
                    'dateFormat' => 'dd.MM.yyyy',
                    'options' => ['class' => 'form-control']
                ]) ?>
            </div>
            <div class="col-sm-3">
                <button type="submit" class="btn btn-primary">Показать</button>
            </div>
        </div>
    </div>
    <?php ActiveForm::end() ?>
</div>

<div class="form-group">
    <?= Html::a(
        'Скачать список дел на выбранную дату',
        $dataProvider->getModels() ? Url::toRoute(['action-day/download-action-list', 'filterDate' => $filterDate]) : "#",
        [
            'class' => 'btn btn-success',
            'disabled' => $dataProvider->getModels() ? false : true
        ]
    ) ?>
</div>

<div id="grid_actions">
    <?php echo GridView::widget([
        "dataProvider" => $dataProvider,
        'columns' => [
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
                'class' => 'yii\grid\ActionColumn',
                'header' => 'Просмотр',
                'template' => '<div class="btn-group">{detail}</div>',
                'buttons' => [
                    'detail' => function ($url, $model) use ($disableExecuteAction) {
                        return Html::a(
                            '<span class="glyphicon glyphicon-eye-open"></span>',
                            Url::toRoute([
                                'action-day/details',
                                'scheme_id' => ArrayHelper::getValue($model, "scheme_id"),
                                'disable' => $disableExecuteAction
                            ]),
                            ['class' => 'btn btn-primary']
                        );
                    }
                ],
            ]
        ]
    ]); ?>
</div>