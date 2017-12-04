<?php

use \yii\bootstrap\ActiveForm;
use \yii\helpers\Html;
use \yii\helpers\Url;
use \common\models\User;
use \common\models\Position;

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
                    'class'     => 'form-control'
                ]) ?>
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
                <?= $form->field($model, 'birthday')->input('date', [
                    'class' => 'form-control'
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
                    'class'  => 'form-control',
                    'prompt' => Yii::t('app/position', 'POSITION_CHOOSE'),
                ]) ?>
            </div>
        </div>
    </div>

    <div class="box-footer">
        <?= Html::submitButton(Yii::t('app/user', 'EDIT'), ['class' => 'btn btn-info pull-right', 'name' => 'contact-button']) ?>
    </div>
    <?php ActiveForm::end(); ?>

</div>