<?php

use \yii\bootstrap4\ActiveForm;
use \yii\helpers\Html;
use \yii\helpers\Url;
use \common\models\User;
use \yii\helpers\ArrayHelper;
use \yii\jui\DatePicker;
use backend\modules\reproduction\models\SeedCashBook;
use backend\modules\reproduction\models\SeedBull;
use backend\modules\reproduction\models\ContainerDuara;

/**
 * @var SeedCashBook $model
 */

$this->title = 'Добавление';

?>

<div class="box box-info">

    <?php $form = ActiveForm::begin([
        'action' => Url::toRoute(['seed-cash-book/create']),
        'id'     => 'seed-cash-book-form',
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
                <?= $form->field($model, 'type')->dropDownList(SeedCashBook::getTypeList(), [
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
                <?= $form->field($model, 'seed_bull_id')->dropDownList(ArrayHelper::map(SeedBull::getAllList(),
                    "id", "nickname"), [
                    'id'     => 'select-seed-bull',
                    'class'  => 'form-control',
                    'prompt' => 'Выберите быка?'
                ]) ?>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-12">
                <?= $form->field($model, 'container_duara_id')->dropDownList(ArrayHelper::map(ContainerDuara::getAllList(),
                    "id", "name"), [
                    'id'     => 'select-container-duara',
                    'class'  => 'form-control',
                    'prompt' => 'Выберите сосуд?'
                ]) ?>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-12">
                <?= $form->field($model, 'count')->input('number', ['class' => 'form-control', 'min' => 1]) ?>
            </div>
        </div>

        <?php if ($model->type == SeedCashBook::TYPE_DEBIT) : ?>

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
            ['class' => 'btn btn-info pull-right', 'name' => 'add-button']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>