<?php

use \yii\bootstrap\ActiveForm;
use \yii\helpers\Html;
use \yii\helpers\Url;
use backend\modules\reproduction\models\SeedBull;
use yii\jui\DatePicker;

/**
 * @var SeedBull $model
 * @var array $contractorSeedList
 * @var array $breedList
 * @var array $colorList
 */

$this->title = 'Изменение данных о быке';
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="box box-info">

    <?php $form = ActiveForm::begin([
        'action' => Url::toRoute(['seed-bull/update', 'id' => $model->id]),
        'id' => 'seed-bull-form',
        'class' => 'form-horizontal'
    ]); ?>
    <div class="box-body">

        <!--Кличка-->
        <div class="form-group">
            <div class="col-sm-12">
                <?= $form->field($model, 'nickname')->textInput([
                    'autofocus' => true,
                    'class' => 'form-control'
                ]) ?>
            </div>
        </div>

        <!--Дата рождения-->
        <div class="form-group">
            <div class="col-sm-12">
                <?= $form->field($model, 'birthday')->widget(DatePicker::class, [
                    'language'   => 'ru',
                    'dateFormat' => 'dd.MM.yyyy',
                    'options'    => ['class' => 'form-control', 'autocomplete' => 'off']
                ]) ?>
            </div>
        </div>

        <!--Номер 1-->
        <div class="form-group">
            <div class="col-sm-12">
                <?= $form->field($model, 'number_1')->textInput([
                    'class' => 'form-control'
                ]) ?>
            </div>
        </div>

        <!--Номер 2-->
        <div class="form-group">
            <div class="col-sm-12">
                <?= $form->field($model, 'number_2')->textInput([
                    'class' => 'form-control'
                ]) ?>
            </div>
        </div>

        <!--Номер 3-->
        <div class="form-group">
            <div class="col-sm-12">
                <?= $form->field($model, 'number_3')->textInput([
                    'class' => 'form-control'
                ]) ?>
            </div>
        </div>

        <!--Поставщик семени-->
        <div class="form-group">
            <div class="col-sm-12">
                <?= $form->field($model, 'contractor')->dropDownList($contractorSeedList, [
                    'id' => 'select-contractor-seed',
                    'class' => 'form-control',
                    'prompt' => 'Выберите поставщика семени',
                ]) ?>
            </div>
        </div>

        <!--Порода-->
        <div class="form-group">
            <div class="col-sm-12">
                <?= $form->field($model, 'breed')->dropDownList($breedList, [
                    'id' => 'select-breed',
                    'class' => 'form-control',
                    'prompt' => 'Выберите породу',
                ]) ?>
            </div>
        </div>

        <!--Масть-->
        <div class="form-group">
            <div class="col-sm-12">
                <?= $form->field($model, 'color_id')->dropDownList($colorList, [
                    'id' => 'select-color',
                    'class' => 'form-control',
                    'prompt' => 'Выберите масть',
                ]) ?>
            </div>
        </div>

        <!--Цена за единицу-->
        <div class="form-group">
            <div class="col-sm-12">
                <?= $form->field($model, 'price')->input('number', [
                    'class' => 'form-control',
                    'min'   => 0.01,
                    'step'  => 0.01,
                ]) ?>
            </div>
        </div>

    </div>

    <div class="box-footer">
        <?= Html::submitButton('Редактировать',
            ['class' => 'btn btn-info pull-right', 'name' => 'edit-button']) ?>
    </div>
    <?php ActiveForm::end(); ?>

</div>