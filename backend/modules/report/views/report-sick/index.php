<?php

use \yii\helpers\Url;
use \yii\widgets\ActiveForm;
use \kartik\date\DatePicker;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use backend\modules\report\models\forms\ReportSickForm;

/**
 * @var ReportSickForm $model
 */

$this->title = 'Отчеты о заболеваемости';
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="box box-info">

    <div class="box-header with-border">
        <h3 class="box-title"><?= $this->title ?></h3>
    </div>

    <?php $form = ActiveForm::begin([
        'action' => Url::toRoute([
            "download",
        ]),
        'class' => 'form-horizontal'
    ]); ?>
    <div class="box-body">

        <div class="form-group">
            <div class="col-sm-12">
                <?= $form->field($model, 'type')->dropDownList(
                    ReportSickForm::getListType(),
                    [
                        'class' => 'form-control',
                        'prompt' => 'Выберите тип отчета',
                    ])
                ?>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-12">
                <?= $form->field($model, 'diagnosis_id')->dropDownList(
                    ReportSickForm::getListDiagnosis(),
                    [
                        'class' => 'form-control',
                        'prompt' => 'Выберите диагноз',
                    ])
                ?>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-12">
                <?= $form->field($model, 'display')->dropDownList(
                    ReportSickForm::getListDisplay(),
                    [
                        'class' => 'form-control',
                        'prompt' => 'Выберите что нужно отображать в графике',
                    ])
                ?>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-6">
                <?= $form->field($model, 'dateFrom')->widget(
                    DatePicker::class,
                    [
                        'class' => 'form-control',
                        'type' => DatePicker::TYPE_INPUT,
                        'pluginOptions' => [
                            'autoclose' => true,
                            'format' => 'dd.mm.yyyy',
                            'todayHighlight' => true,
                        ]
                    ]
                );
                ?>
            </div>
            <div class="col-sm-6">
                <?= $form->field($model, 'dateTo')->widget(
                    DatePicker::class,
                    [
                        'class' => 'form-control',
                        'type' => DatePicker::TYPE_INPUT,
                        'pluginOptions' => [
                            'autoclose' => true,
                            'format' => 'dd.mm.yyyy',
                            'todayHighlight' => true,
                        ]
                    ]
                );
                ?>
            </div>
        </div>

        <!--<div class="form-group">
            <div class="col-sm-12">
                <?/*= $form->field($model, 'comparativeAnalysis')->dropDownList(
                    [
                        0 => 'Нет',
                        1 => 'Да',
                    ],
                    [
                        'class' => 'form-control',
                        'prompt' => 'Вам нужен сравнительный анализ?',
                    ])
                */?>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-12">
                <?/*= $form->field($model, 'compareWith')->widget(
                    DatePicker::class,
                    [
                        'class' => 'form-control',
                        'type' => DatePicker::TYPE_INPUT,
                        'pluginOptions' => [
                            'autoclose' => true,
                            'format' => 'yyyy.mm.dd',

                        ]
                    ]
                );
                */?>
            </div>
        </div>-->

    </div>

    <div class="box-footer">
        <?= Html::submitButton('Скачать', [
            'class' => 'btn btn-info pull-right'
        ]) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
