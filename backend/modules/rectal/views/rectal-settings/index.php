<?php


use \yii\bootstrap4\ActiveForm;
use \yii\helpers\Html;
use \yii\helpers\Url;
use common\models\rectal\RectalSettings;
use \yii\helpers\ArrayHelper;

/** @var RectalSettings $model */

$this->title = 'Настройки РИ';
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Какие настройки хотите изменить?</h3>
                </div>

                <?php $form = ActiveForm::begin([
                    'action' => Url::toRoute([
                        "/rectal/rectal-settings/update",
                        'id' => ArrayHelper::getValue($model, "id")
                    ])
                ]); ?>

                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
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
                        <div class="col-sm-6">
                            <div class="form-group">
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
                    </div>

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
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
                        <div class="col-sm-6">
                            <div class="form-group">
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

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
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
                    </div>
                </div>

                <div class="card-footer">
                    <?php if (Yii::$app->user->can('rectalSettingsEdit')) : ?>
                        <?= Html::submitButton('Обновить', ['class' => 'btn btn-sm btn-primary']) ?>
                    <?php endif; ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>