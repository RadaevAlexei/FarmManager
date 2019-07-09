<?php

use \yii\bootstrap\ActiveForm;
use \yii\helpers\Html;
use \yii\helpers\Url;
use backend\modules\reproduction\models\SeedBull;

/**
 * @var SeedBull $model
 * @var array $contractorSeedList
 * @var array $breedList
 * @var array $colorList
 */

$this->title = 'Добавление быка';

?>

<div class="box box-info">

    <?php $form = ActiveForm::begin([
        'action' => Url::toRoute(['seed-bull/create']),
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
                <?= $form->field($model, 'color')->dropDownList($colorList, [
                    'id' => 'select-color',
                    'class' => 'form-control',
                    'prompt' => 'Выберите масть',
                ]) ?>
            </div>
        </div>
    </div>

    <div class="box-footer">
        <?= Html::submitButton('Создать',
            ['class' => 'btn btn-info pull-right', 'name' => 'contact-button']) ?>
    </div>
    <?php ActiveForm::end(); ?>

</div>