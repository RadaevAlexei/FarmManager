<?php


use \yii\bootstrap\ActiveForm;
use \yii\helpers\Html;
use \yii\helpers\Url;
use common\models\rectal\RectalSettings;
use \yii\helpers\ArrayHelper;

/** @var RectalSettings $model */

$this->title = 'Настройки РИ';

?>

<div class="box box-info">

    <?php $form = ActiveForm::begin([
        'action' => Url::toRoute([
            "/rectal/rectal-settings/update",
            'id' => ArrayHelper::getValue($model, "id")
        ]),
        'id' => 'rectal-settings-form',
        'class' => 'form-horizontal'
    ]); ?>
    <div class="box-body">

        <div class="form-group">
            <div class="col-sm-12">
                <?= $form->field($model, 'pregnancy_time')->input(
                    'number',
                    [
                        'class' => 'form-control',
                        'min' => 1,
                        'step' => 1,
                    ]
                ) ?>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-12">
                <?= $form->field($model, 'end_time')->input(
                    'number',
                    [
                        'class' => 'form-control',
                        'min' => 1,
                        'step' => 1,
                    ]
                ) ?>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-12">
                <?= $form->field($model, 'first_day')->input(
                    'number',
                    [
                        'class' => 'form-control',
                        'min' => 1,
                        'step' => 1,
                    ]
                ) ?>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-12">
                <?= $form->field($model, 'confirm_first')->input(
                    'number',
                    [
                        'class' => 'form-control',
                        'min' => 1,
                        'step' => 1,
                    ]
                ) ?>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-12">
                <?= $form->field($model, 'confirm_second')->input(
                    'number',
                    [
                        'class' => 'form-control',
                        'min' => 1,
                        'step' => 1,
                    ]
                ) ?>
            </div>
        </div>

    </div>

    <div class="box-footer">
        <?= Html::submitButton('Обновить', [
            'class' => 'btn btn-info pull-right'
        ]) ?>
    </div>
    <?php ActiveForm::end(); ?>

</div>