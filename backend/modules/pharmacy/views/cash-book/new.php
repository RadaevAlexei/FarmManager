<?php

use \yii\bootstrap4\ActiveForm;
use \yii\helpers\Html;
use \yii\helpers\Url;
use \backend\modules\pharmacy\models\CashBook;
use \common\models\User;
use \yii\helpers\ArrayHelper;
use \yii\jui\DatePicker;
use \backend\modules\pharmacy\models\Preparation;
use backend\modules\pharmacy\models\Stock;

/**
 * @var CashBook $model
 */

$this->title = 'Добавление';

?>

<div class="box box-info">

    <?php $form = ActiveForm::begin([
        'action' => Url::toRoute(['cash-book/create']),
        'id'     => 'cash-book-form',
        'class'  => 'form-horizontal'
    ]); ?>
    <div class="box-body">

        <div class="form-group">
            <div class="col-sm-12">
                <?= $form->field($model, 'user_id')->dropDownList(ArrayHelper::map(User::getAllList(),
                    "id", "lastName"), [
                    'id'     => 'select-user',
                    'class'  => 'form-control',
                    'prompt' => 'Кто проводит?'
                ]) ?>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-12">
                <?= $form->field($model, 'type')->hiddenInput(['value' => $model->type])->label(false) ?>
                <?= $form->field($model, 'type')->dropDownList(CashBook::getTypeList(), [
                    'id'       => 'select-type',
                    'class'    => 'form-control',
                    'disabled' => true,
                    'prompt'   => 'Выберите тип'
                ]) ?>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-12">
                <?= $form->field($model, 'date')->widget(DatePicker::class, [
                    'language'   => 'ru',
                    'dateFormat' => 'dd.MM.yyyy',
                    'options'    => ['class' => 'form-control', 'autocomplete' => 'off']
                ]) ?>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-12">
                <?= $form->field($model, 'preparation_id')->dropDownList(ArrayHelper::map(Preparation::getAllList(),
                    "id", "name"), [
                    'id'     => 'select-preparation',
                    'class'  => 'form-control',
                    'prompt' => 'Выберите препарат?'
                ]) ?>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-12">
                <?= $form->field($model, 'stock_id')->dropDownList(ArrayHelper::map(Stock::getAllList(),
                    "id", "name"), [
                    'id'     => 'select-stock',
                    'class'  => 'form-control',
                    'prompt' => 'Выберите склад?'
                ]) ?>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-12">
                <?= $form->field($model, 'count')->input('number', ['class' => 'form-control', 'min' => 0]) ?>
            </div>
        </div>

        <?php if ($model->type == CashBook::TYPE_DEBIT) : ?>
            <!--<div class="form-group">
                <div class="col-sm-12">
                    <? /*= $form->field($model, 'total_price')->input('number', [
                        'class' => 'form-control',
                        'min'   => 0,
                        'step'  => 0.01,
                    ]) */ ?>
                </div>
            </div>-->

            <div class="form-group">
                <div class="col-sm-12">
                    <?= $form->field($model, 'vat_percent')->input('number', [
                        'class' => 'form-control',
                        'min'   => 0,
                        'max'   => 100,
                        'step'  => 0.01,
                    ]) ?>
                </div>
            </div>
        <?php endif; ?>

    </div>

    <div class="box-footer">
        <?= Html::submitButton('Добавить',
            ['class' => 'btn btn-info pull-right', 'name' => 'contact-button']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>