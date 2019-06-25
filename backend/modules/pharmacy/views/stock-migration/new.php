<?php

use \yii\bootstrap\ActiveForm;
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
$model->date = (new DateTime('now', new DateTimeZone('Europe/Samara')))->format('Y-m-d H:i:s');

?>

<div class="box box-info">

    <?php $form = ActiveForm::begin([
        'action' => Url::toRoute(['stock-migration/create-migration']),
        'id'     => 'stock-migration-form',
        'class'  => 'form-horizontal'
    ]); ?>
    <div class="box-body">
        <!--Дата перемещения-->
        <div class="form-group">
            <div class="col-sm-12">
                <?= $form->field($model, 'date')->widget(DatePicker::class, [
                    'language'   => 'ru',
                    'dateFormat' => 'dd.MM.yyyy',
                    'options'    => ['class' => 'form-control', 'autocomplete' => 'off']
                ]) ?>
            </div>
        </div>

        <!--Кто переместил?-->
        <div class="form-group">
            <div class="col-sm-12">
                <?= $form->field($model, 'user_id')->dropDownList(
                    $userList,
                    ['class' => 'form-control']
                ) ?>
            </div>
        </div>

        <!--Что переместил?-->
        <div class="form-group">
            <div class="col-sm-12">
                <?= $form->field($model, 'preparation_id')->dropDownList(
                    $preparationList,
                    ['class' => 'form-control', 'prompt' => 'Какой препарат хотите переместить?']
                ) ?>
            </div>
        </div>

        <!--Количество?-->
        <div class="form-group">
            <div class="col-sm-12">
                <?= $form->field($model, 'count')->input('number', [
                    ['class' => 'form-control']
                ]) ?>
            </div>
        </div>

        <!--Объём?-->
        <div class="form-group">
            <div class="col-sm-12">
                <?= $form->field($model, 'volume')->input('number', [
                    [
                        'class' => 'form-control',
                        'step'  => 0.1,
                        'min'   => 0.1,
                    ]
                ]) ?>
            </div>
        </div>

        <!--Откуда переместить?-->
        <div class="form-group">
            <div class="col-sm-12">
                <?= $form->field($model, 'stock_from_id')->dropDownList(
                    $stockList,
                    ['class' => 'form-control', 'prompt' => 'Откуда переместить?']
                ) ?>
            </div>
        </div>

        <!--Куда переместить?-->
        <div class="form-group">
            <div class="col-sm-12">
                <?= $form->field($model, 'stock_to_id')->dropDownList(
                    $stockList,
                    ['class' => 'form-control', 'prompt' => 'Куда переместить?']
                ) ?>
            </div>
        </div>
    </div>

    <div class="box-footer">
        <?= Html::submitButton('Переместить',
            ['class' => 'btn btn-info pull-right', 'name' => 'contact-button']) ?>
    </div>
    <?php ActiveForm::end(); ?>

</div>