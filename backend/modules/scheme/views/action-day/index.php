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

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-header"> На какую дату посмотреть список дел?</div>
                <div class="card-body">

                    <?php $form = ActiveForm::begin([
                        'action' => Url::toRoute(['index'])
                    ]) ?>

                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <button type="submit" class="btn btn-sm btn-primary">Показать</button>
                            </div>
                            <?= DatePicker::widget([
                                'name' => 'filter_date',
                                'value' => (new DateTime($filterDate))->format('d.m.Y'),
                                'language' => 'ru',
                                'dateFormat' => 'dd.MM.yyyy',
                                'options' => ['class' => 'form-control form-control-sm'],
                                'clientOptions' => [
                                    'changeMonth' => true,
                                    'changeYear' => true,
                                    'minDate' => (new DateTime('now',
                                        new DateTimeZone('Europe/Samara')))->format('d.m.Y'),
                                ]
                            ]) ?>
                        </div>
                    </div>
                    <?php ActiveForm::end() ?>

                    <div class="form-group">
                        <?= Html::a(
                            'Скачать список дел на выбранную дату',
                            $dataProvider->getModels() ? Url::toRoute([
                                'action-day/download-action-list',
                                'filterDate' => $filterDate
                            ]) : "#",
                            [
                                'class' => 'btn btn-sm btn-success',
                                'disabled' => $dataProvider->getModels() ? false : true
                            ]
                        ) ?>
                    </div>

                    <div id="grid_actions">
                        <?= GridView::widget([
                            "dataProvider" => $dataProvider,
                            'formatter' => ['class' => 'yii\i18n\Formatter', 'nullDisplay' => '',],
                            'tableOptions' => ['class' => 'table table-sm table-striped table-hover table-condensed'],
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
                                                '<span class="fas fa-sm fa-eye"></span>',
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
                </div>
            </div>
        </div>
    </div>
</div>
