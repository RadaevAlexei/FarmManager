<?php

use \yii\bootstrap4\ActiveForm;
use \common\models\User;
use \common\models\Position;

/** @var User $model */

$this->title = Yii::t('app/user', 'USER_DETAIL');
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="box box-info">

    <?php $form = ActiveForm::begin(['id' => 'contact-form', 'class' => 'form-horizontal']); ?>
    <div class="box-body">

        <div class="form-group">
            <div class="col-sm-12">
                <?= $form->field($model, 'username')->textInput([
                    'class'    => 'form-control',
                    'disabled' => true,
                ]) ?>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-12">
                <?= $form->field($model, 'lastName')->textInput([
                    'class'    => 'form-control',
                    'disabled' => true,
                ]) ?>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-12">
                <?= $form->field($model, 'firstName')->textInput([
                    'class'    => 'form-control',
                    'disabled' => true,
                ]) ?>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-12">
                <?= $form->field($model, 'middleName')->textInput([
                    'class'    => 'form-control',
                    'disabled' => true,
                ]) ?>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-12">
                <?= $form->field($model, 'birthday')->input('date', [
                    'class'    => 'form-control',
                    'disabled' => true,
                ]) ?>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-12">
                <?= $form->field($model, 'gender')->dropDownList(User::getGenderList(), [
                    'class'    => 'form-control',
                    'disabled' => true,
                ]) ?>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-12">
                <?= $form->field($model, 'position_id')->dropDownList(Position::getAllPositions(), [
                    'class'    => 'form-control',
                    'disabled' => true,
                    'prompt'   => Yii::t('app/position', 'POSITION_CHOOSE'),
                ]) ?>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>