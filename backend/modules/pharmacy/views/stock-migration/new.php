<?php

use \yii\bootstrap4\ActiveForm;
use \yii\helpers\Html;
use \yii\helpers\Url;
use \backend\modules\pharmacy\models\StockMigration;
use yii\jui\DatePicker;

/**
 * @var StockMigration $model
 * @var array $userList
 * @var array $preparationList
 * @var array $stockList
 */

$this->title = 'Перемещение препарата';
$this->params['breadcrumbs'][] = $this->title;

$model->date = (new DateTime('now', new DateTimeZone('Europe/Samara')))->format('Y-m-d H:i:s');

?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Заполните форму для создания</h3>
                </div>

                <?php $form = ActiveForm::begin(['action' => Url::toRoute(['stock-migration/create-migration'])]); ?>

                <div class="card-body">

                    <div class="row">
                        <div class="col-sm-6">
                            <!--Дата перемещения-->
                            <div class="form-group">
                                <?= $form->field($model, 'date')->widget(DatePicker::class, [
                                    'language' => 'ru',
                                    'dateFormat' => 'dd.MM.yyyy',
                                    'options' => ['class' => 'form-control', 'autocomplete' => 'off']
                                ]) ?>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <!--Откуда переместить?-->
                            <div class="form-group">
                                <?= $form->field($model, 'stock_from_id')->dropDownList(
                                    $stockList,
                                    ['class' => 'form-control', 'prompt' => 'Откуда переместить?']
                                ) ?>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-6">
                            <!--Кто переместил?-->
                            <div class="form-group">
                                <?= $form->field($model, 'user_id')->dropDownList(
                                    $userList,
                                    ['class' => 'form-control']
                                ) ?>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <!--Куда переместить?-->
                            <div class="form-group">
                                <?= $form->field($model, 'stock_to_id')->dropDownList(
                                    $stockList,
                                    ['class' => 'form-control', 'prompt' => 'Куда переместить?']
                                ) ?>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-6">
                            <!--Что переместил?-->
                            <div class="form-group">
                                <?= $form->field($model, 'preparation_id')->dropDownList(
                                    $preparationList,
                                    ['class' => 'form-control', 'prompt' => 'Какой препарат хотите переместить?']
                                ) ?>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-6">
                            <!--Количество?-->
                            <div class="form-group">
                                <?= $form->field($model, 'count')->input('number', [
                                    ['class' => 'form-control']
                                ]) ?>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-6">
                            <!--Объём?-->
                            <div class="form-group">
                                <?= $form->field($model, 'volume')->input('number', [
                                    [
                                        'class' => 'form-control',
                                        'step' => 0.1,
                                        'min' => 0.1,
                                    ]
                                ]) ?>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="card-footer">
                    <?php if (Yii::$app->user->can('managePharmacyEdit')) : ?>
                        <?= Html::submitButton('Переместить', ['class' => 'btn btn-sm btn-primary']) ?>
                    <?php endif; ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>