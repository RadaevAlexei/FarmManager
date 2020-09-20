<?php

use \yii\helpers\Html;
use \yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\data\ArrayDataProvider;
use yii\helpers\Url;
use \yii\widgets\ActiveForm;
use \yii\jui\DatePicker;
use backend\modules\rectal\assets\RectalAsset;

/**
 * @var ArrayDataProvider $dataProvider
 * @var bool $disableReport
 * @var mixed $filterDateFrom
 * @var mixed $filterDateTo
 */

RectalAsset::register($this);

$this->title = 'Список животных находящиеся под ректальным исследованием';
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-header">Период осеменения коров</div>

                <?php $form = ActiveForm::begin([
                    'action' => Url::toRoute(['index']),
                    'class' => 'form-horizontal'
                ]) ?>

                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="filter_date_from_id" class="control-label">Начало периода</label>
                                <?= DatePicker::widget([
                                    'id' => 'filter_date_from_id',
                                    'name' => 'filter_date_from',
                                    'value' => $filterDateFrom ? (new DateTime($filterDateFrom))->format('d.m.Y') : null,
                                    'language' => 'ru',
                                    'dateFormat' => 'dd.MM.yyyy',
                                    'options' => ['class' => 'form-control form-control-sm'],
                                    'clientOptions' => [
                                        'changeMonth' => true,
                                        'changeYear' => true,
//                            'minDate'     => (new DateTime('now', new DateTimeZone('Europe/Samara')))->format('d.m.Y'),
                                    ]
                                ]) ?>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="filter_date_to_id" class="control-label">Конец периода</label>
                                <?= DatePicker::widget([
                                    'id' => 'filter_date_to_id',
                                    'name' => 'filter_date_to',
                                    'value' => $filterDateTo ? (new DateTime($filterDateTo))->format('d.m.Y') : null,
                                    'language' => 'ru',
                                    'dateFormat' => 'dd.MM.yyyy',
                                    'options' => ['class' => 'form-control form-control-sm'],
                                    'clientOptions' => [
                                        'changeMonth' => true,
                                        'changeYear' => true
                                    ]
                                ]) ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-sm btn-primary pull-right">Показать</button>

                    <?= Html::a(
                        'Список для гинеколога',
                        $dataProvider->getModels() ?
                            Url::toRoute([
                                '/rectal/rectal-list/download-rectal-list-gynecologist',
                                'dateFrom' => $filterDateFrom,
                                'dateTo' => $filterDateTo
                            ]) : "#",
                        [
                            'class' => 'btn btn-sm btn-success',
                            'disabled' => $dataProvider->getModels() ? false : true
                        ]
                    ) ?>
                    <?= Html::button('Отчет РИ', [
                        'id' => 'print-rectal-report-button',
                        'class' => 'btn btn-sm btn-primary',
                        'disabled' => $disableReport,
                        'data' => [
                            'toggle' => 'modal',
                            'url' => Url::toRoute([
                                '/rectal/rectal-list/settings-rectal-report-form',
                                'dateFrom' => $filterDateFrom,
                                'dateTo' => $filterDateTo
                            ])
                        ]
                    ]); ?>
                    <? /*= Html::a(
                            'Отчет РИ',
                            !$disableReport ?
                                Url::toRoute([
                                    '/rectal/rectal-list/download-rectal-list',
                                    'dateFrom' => $filterDateFrom,
                                    'dateTo'   => $filterDateTo
                                ]) : "#",
                            [
                                'class'    => 'btn btn-primary',
                                'disabled' => $disableReport ? true : false
                            ]
                        )*/ ?>
                </div>

                <?php ActiveForm::end() ?>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-success">
                <div class="card-header"><?= $this->title ?></div>
                <div class="card-body">
                    <?php echo GridView::widget([
                        "dataProvider" => $dataProvider,
                        'formatter' => ['class' => 'yii\i18n\Formatter', 'nullDisplay' => '',],
                        'tableOptions' => ['class' => 'table table-sm table-striped table-hover table-condensed'],
                        'columns' => [
                            [
                                'label' => 'Группа животного',
                                'value' => function ($model) {
                                    return ArrayHelper::getValue($model, 'animal_group_name');
                                }
                            ],
                            [
                                'label' => 'Номер ошейника',
                                'value' => function ($model) {
                                    return ArrayHelper::getValue($model, 'collar');
                                }
                            ],
                            [
                                'label' => 'Номер уха (Бирка)',
                                'value' => function ($model) {
                                    return ArrayHelper::getValue($model, 'label');
                                }
                            ],
                            [
                                'label' => 'Дата осеменения',
                                'value' => function ($model) {
                                    return (new DateTime(ArrayHelper::getValue($model,
                                        'insemination_date')))->format('d.m.Y');
                                }
                            ],
                            [
                                'label' => 'ФИО техника по ИО',
                                'value' => function ($model) {
                                    return ArrayHelper::getValue($model, 'lastName');
                                }
                            ],
                            [
                                'label' => 'Кратность осеменения',
                                'value' => function ($model) {
                                    return ArrayHelper::getValue($model, 'count_insemination');
                                }
                            ],
                            [
                                'label' => 'Сроки стельности, дн',
                                'value' => function ($model) {
                                    $days = ArrayHelper::getValue($model, 'days');
                                    return $days ? $days : 0;
                                }
                            ]
                        ]
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Модальное окно печати отчета по РИ -->
<div class="modal fade" id="print-rectal-report-modal" tabindex="-1" role="dialog"
     aria-labelledby="addCalvingLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #0ead0e78">
                <h5 class="modal-title" id="addCalvingLabel">Настройка отчёта</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body"></div>
        </div>
    </div>
</div>
