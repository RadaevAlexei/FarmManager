<?php

use \yii\bootstrap4\ActiveForm;
use \yii\helpers\Html;
use \yii\helpers\Url;
use backend\modules\reproduction\models\SeedBull;
use yii\jui\DatePicker;

/**
 * @var SeedBull $model
 * @var array $seedSupplierList
 * @var array $breedList
 * @var array $colorList
 */

$this->title = 'Изменение данных о быке';
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Какие данные хотите изменить?</h3>
                </div>

                <?php $form = ActiveForm::begin(['action' => Url::toRoute(['seed-bull/update', 'id' => $model->id])]); ?>

                <div class="card-body">

                    <div class="row">
                        <div class="col-sm-6">
                            <!--Кличка-->
                            <div class="form-group">
                                <?= $form->field($model, 'nickname')->textInput([
                                    'autofocus' => true,
                                    'class' => 'form-control form-control-sm'
                                ]) ?>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <!--Номер 1-->
                            <div class="form-group">
                                <?= $form->field($model, 'number_1')->textInput([
                                    'class' => 'form-control form-control-sm'
                                ]) ?>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-6">
                            <!--Дата рождения-->
                            <div class="form-group">
                                <?= $form->field($model, 'birthday')->widget(DatePicker::class, [
                                    'language' => 'ru',
                                    'dateFormat' => 'dd.MM.yyyy',
                                    'options' => ['class' => 'form-control form-control-sm', 'autocomplete' => 'off']
                                ]) ?>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <!--Номер 2-->
                            <div class="form-group">
                                <?= $form->field($model, 'number_2')->textInput([
                                    'class' => 'form-control form-control-sm'
                                ]) ?>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-6">
                            <!--Поставщик семени-->
                            <div class="form-group">
                                <?= $form->field($model, 'contractor')->dropDownList($seedSupplierList, [
                                    'id' => 'select-contractor-seed',
                                    'class' => 'form-control form-control-sm',
                                    'prompt' => 'Выберите поставщика семени',
                                ]) ?>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <!--Номер 3-->
                            <div class="form-group">
                                <?= $form->field($model, 'number_3')->textInput([
                                    'class' => 'form-control form-control-sm'
                                ]) ?>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-6">
                            <!--Порода-->
                            <div class="form-group">
                                <?= $form->field($model, 'breed')->dropDownList($breedList, [
                                    'id' => 'select-breed',
                                    'class' => 'form-control form-control-sm',
                                    'prompt' => 'Выберите породу',
                                ]) ?>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <!--Цена за единицу-->
                            <div class="form-group">
                                <?= $form->field($model, 'price')->input('number', [
                                    'class' => 'form-control form-control-sm',
                                    'min' => 0.01,
                                    'step' => 0.01,
                                ]) ?>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-6">
                            <!--Масть-->
                            <div class="form-group">
                                <?= $form->field($model, 'color_id')->dropDownList($colorList, [
                                    'id' => 'select-color',
                                    'class' => 'form-control form-control-sm',
                                    'prompt' => 'Выберите масть',
                                ]) ?>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="card-footer">
                    <?php if (Yii::$app->user->can('seedBullEdit')) : ?>
                        <?= Html::submitButton('Редактировать', ['class' => 'btn btn-sm btn-primary']) ?>
                    <?php endif; ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>