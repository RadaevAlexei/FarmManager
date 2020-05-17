<?php

use \backend\modules\livestock\assets\LivestockAsset;
use \yii\helpers\Html;
use \yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use \yii\widgets\ActiveForm;
use \yii\jui\DatePicker;
use yii\data\ArrayDataProvider;

/**
 * @var ArrayDataProvider $dataProvider
 * @var bool $disableReport
 * @var mixed $filterDateFrom
 * @var mixed $filterDateTo
 * @var array $reportTypes
 */

LivestockAsset::register($this);

$this->title = 'Формирование отчетов';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="box box-success">
    <?php $form = ActiveForm::begin([
        'action' => Url::toRoute(['index']),
        'class'  => 'form-horizontal'
    ]) ?>
    <div class="box-body">
        <div class="form-group">
            <label for="filter_date_from_id" class="col-sm-2 control-label">Начало периода</label>
            <div class="col-sm-10">
                <div class="form-group">
                    <?= DatePicker::widget([
                        'id'            => 'filter_date_from_id',
                        'name'          => 'filter_date_from',
                        'value'         => $filterDateFrom ? (new DateTime($filterDateFrom))->format('d.m.Y') : null,
                        'language'      => 'ru',
                        'dateFormat'    => 'dd.MM.yyyy',
                        'options'       => ['class' => 'form-control'],
                        'clientOptions' => [
                            'changeMonth' => true,
                            'changeYear'  => true,
                        ]
                    ]) ?>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="filter_date_to_id" class="col-sm-2 control-label">Конец периода</label>
            <div class="col-sm-10">
                <div class="form-group">
                    <?= DatePicker::widget([
                        'id'            => 'filter_date_to_id',
                        'name'          => 'filter_date_to',
                        'value'         => $filterDateTo ? (new DateTime($filterDateTo))->format('d.m.Y') : null,
                        'language'      => 'ru',
                        'dateFormat'    => 'dd.MM.yyyy',
                        'options'       => ['class' => 'form-control'],
                        'clientOptions' => [
                            'changeMonth' => true,
                            'changeYear'  => true
                        ]
                    ]) ?>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="report_id" class="col-sm-2 control-label">Тип отчета</label>
            <div class="col-sm-10">
                <div class="form-group">
                    <?= Html::dropDownList(
                        'report_type',
                        null,
                        $reportTypes,
                        ['class' => 'form-control']
                    ) ?>
                </div>
            </div>
        </div>
    </div>
    <div class="box-footer">

        <?= Html::button('Печать отчета', [
            'id'       => 'print-livestock-report-button',
            'class'    => 'btn btn-primary',
            'disabled' => $disableReport,
            'data'     => [
                'toggle' => 'modal',
                'url'    => Url::toRoute([
                    '/livestock/livestock-report/settings-report-form',
                    'dateFrom' => $filterDateFrom,
                    'dateTo'   => $filterDateTo
                ])
            ]
        ]); ?>

        <button type="submit" class="btn btn-primary pull-right">Показать</button>
    </div>
    <?php ActiveForm::end() ?>
</div>

<div id="grid_actions">
    <?php echo GridView::widget([
        "dataProvider" => $dataProvider,
        'formatter'    => [
            'class'       => 'yii\i18n\Formatter',
            'nullDisplay' => ''
        ],
        'tableOptions' => [
            'class' => 'table table-striped',
        ],
        'columns'      => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'label' => 'Дата рождения телёнка',
                'value' => function ($model) {
                    $birthday = new DateTime(
                        ArrayHelper::getValue($model, "child_birthday"),
                        new DateTimeZone('Europe/Samara')
                    );

                    if (empty($birthday)) {
                        return "";
                    }

                    return $birthday->format('d.m.Y');
                }
            ],
            [
                'label' => 'Номер телёнка',
                'value' => function ($model) {
                    return ArrayHelper::getValue($model, "child_label");
                }
            ],
            [
                'label' => 'Вес телёнка, кг',
                'value' => function ($model) {
                    return ArrayHelper::getValue($model, "birth_weight");
                }
            ],
            [
                'label' => 'Бирка матери',
                'value' => function ($model) {
                    return ArrayHelper::getValue($model, "mother_label");
                }
            ],
        ]
    ]); ?>
</div>

<!-- Модальное окно настройки отчета -->
<div class="modal fade" id="print-livestock-report-modal" tabindex="-1" role="dialog"
     aria-labelledby="printReportLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #0ead0e78">
                <h5 class="modal-title" id="printReportLabel">Настройка отчёта</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body"></div>
        </div>
    </div>
</div>