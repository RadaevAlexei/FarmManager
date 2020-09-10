<?php

use \yii\bootstrap\ActiveForm;
use \yii\helpers\Html;
use \yii\helpers\Url;
use \common\models\User;
use \common\models\Position;
use yii\jui\DatePicker;

/** @var User $model */

$this->title = Yii::t('app/user', 'USER_EDIT');

?>

<div class="box box-info">

    <?php $form = ActiveForm::begin(['action' => Url::toRoute(['user/update', 'id' => $model->id]), 'id' => 'contact-form', 'class' => 'form-horizontal']); ?>
    <div class="box-body">

        <div class="form-group">
            <div class="col-sm-12">
                <?= $form->field($model, 'username')->textInput([
                    'autofocus' => true,
                    'class' => 'form-control'
                ]) ?>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-12">
                <?= $form->field($model, 'email')->input(
                    'email',
                    [
                        'class' => 'form-control'
                    ]
                ) ?>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-12">
                <?= $form->field($model, 'lastName')->textInput([
                    'class' => 'form-control'
                ]) ?>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-12">
                <?= $form->field($model, 'firstName')->textInput([
                    'class' => 'form-control'
                ]) ?>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-12">
                <?= $form->field($model, 'middleName')->textInput([
                    'class' => 'form-control'
                ]) ?>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-12">
                <?= $form->field($model, 'birthday')->widget(DatePicker::class, [
                    'language' => 'ru',
                    'dateFormat' => 'dd.MM.yyyy',
                    'options' => ['class' => 'form-control', 'autocomplete' => 'off']
                ]) ?>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-12">
                <?= $form->field($model, 'gender')->dropDownList(User::getGenderList(), [
                    'class' => 'form-control'
                ]) ?>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-12">
                <?= $form->field($model, 'position_id')->dropDownList(Position::getAllPositions(), [
                    'class' => 'form-control',
                    'prompt' => Yii::t('app/position', 'POSITION_CHOOSE'),
                ]) ?>
            </div>
        </div>
    </div>

    <div class="box-footer">
        <?php if (Yii::$app->user->can('userEdit')) : ?>
            <?= Html::submitButton(
                Yii::t('app/user', 'EDIT'),
                ['class' => 'btn btn-info pull-right', 'name' => 'contact-button']
            ) ?>

        <?php endif; ?>
    </div>
    <?php ActiveForm::end(); ?>

</div>
