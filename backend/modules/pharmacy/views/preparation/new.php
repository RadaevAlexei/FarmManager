<?php

use \yii\bootstrap\ActiveForm;
use \yii\helpers\Html;
use \yii\helpers\Url;
use \backend\modules\pharmacy\models\Preparation;
use \backend\modules\pharmacy\assets\PreparationAsset;

/**
 * @var Preparation $model
 * @var array $packingList
 */

PreparationAsset::register($this);

$this->title = Yii::t('app/preparation', 'PREPARATION_NEW');

?>

<div class="box box-info">

    <?php $form = ActiveForm::begin([
        'action' => Url::toRoute(['preparation/create']),
        'id'     => 'preparation-form',
        'class'  => 'form-horizontal'
    ]); ?>
    <div class="box-body">

        <!--Название препарата-->
        <div class="form-group">
            <div class="col-sm-12">
                <?= $form->field($model, 'name')->textInput([
                    'autofocus' => true,
                    'class'     => 'form-control'
                ]) ?>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-12">
                <?= $form->field($model, 'category')->dropDownList(Preparation::getCategoryList(), [
                    'id'     => 'select-category',
                    'class'  => 'form-control',
                    'prompt' => 'Укажите формакологическую группу',
                ]) ?>
            </div>
        </div>

        <div class="form-group select-classification-block <?=($model->category === 1 ? "" : "hidden")?>">
            <div class="col-sm-12">
                <?= $form->field($model, 'classification')->dropDownList(Preparation::getClassificationList(), [
                    'id'     => 'select-classification',
                    'class'  => 'form-control',
                    'prompt' => 'Классификация антибиотиков',
                ]) ?>
            </div>
        </div>

        <div class="form-group select-beta-block <?=($model->classification === 1 ? "" : "hidden")?>">
            <div class="col-sm-12">
                <?= $form->field($model, 'beta')->dropDownList(Preparation::getBetaClassificationList(), [
                    'id'     => 'select-beta',
                    'class'  => 'form-control',
                    'prompt' => 'Выберите подтип бета-лактамных',
                ]) ?>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-12">
                <?= $form->field($model, 'danger_class')->dropDownList(Preparation::getDangerClass(), [
                    'class'  => 'form-control',
                    'prompt' => 'Укажите класс опасности',
                ]) ?>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-12">
                <?= $form->field($model, 'period_milk')->input('number', [
                    'class' => 'form-control'
                ]) ?>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-12">
                <?= $form->field($model, 'period_meat')->input('number', [
                    'class' => 'form-control'
                ]) ?>
            </div>
        </div>

    </div>

    <div class="box-footer">
        <?= Html::submitButton(Yii::t('app/preparation', 'ADD'),
            ['class' => 'btn btn-info pull-right', 'name' => 'contact-button']) ?>
    </div>
    <?php ActiveForm::end(); ?>

</div>