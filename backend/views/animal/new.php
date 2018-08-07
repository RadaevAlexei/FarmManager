<?php

use \yii\bootstrap\ActiveForm;
use \yii\helpers\Html;
use \yii\helpers\Url;
use common\models\Animal;
use \yii\jui\DatePicker;

/** @var Animal $model */

$this->title = Yii::t('app/animal', 'ANIMAL_NEW');

?>

<div class="box box-info">

    <?php $form = ActiveForm::begin(['action' => Url::toRoute(['animal/create']), 'id' => 'animal-form', 'class' => 'form-horizontal']); ?>
    <div class="box-body">

        <div class="form-group">
            <div class="col-sm-12">
                <?= $form->field($model, 'sex')->dropDownList(Animal::getListSexTypes(), [
                    'class' => 'form-control'
                ]) ?>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-12">
                <?= $form->field($model, 'physical_state')->dropDownList(Animal::getListPhysicalState(), [
                    'class' => 'form-control'
                ]) ?>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-12">
                <?= $form->field($model, 'nickname')->textInput([
                    'class' => 'form-control'
                ]) ?>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-12">
                <?= $form->field($model, 'label')->textInput([
                    'class' => 'form-control'
                ]) ?>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-12">
                <?= $form->field($model, 'birthday')->widget(DatePicker::className(), [
                    'language'   => 'ru',
                    'dateFormat' => 'yyyy-MM-dd',
                ]) ?>
            </div>
        </div>

        <!--<div class="form-group">
            <div class="col-sm-12">
                <? /*= $form->field($model, 'сowshed_id')->dropDownList([1=>1], [
                    'class' => 'form-control'
                ]) */ ?>
            </div>
        </div>-->

    </div>

    <div class="box-footer">
        <?= Html::submitButton(Yii::t('app/animal', 'ADD'), ['class' => 'btn btn-info pull-right', 'name' => 'contact-button']) ?>
    </div>
    <?php ActiveForm::end(); ?>

</div>