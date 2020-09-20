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
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Заполните форму для создания</h3>
                </div>

                <?php $form = ActiveForm::begin(['action' => Url::toRoute(['cash-book/create'])]); ?>

                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <?= $form->field($model, 'type')->dropDownList(CashBook::getTypeList(), [
                                    'id' => 'select-type',
                                    'class' => 'form-control form-control-sm',
                                    'disabled' => true,
                                    'prompt' => 'Выберите тип'
                                ]) ?>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <?= $form->field($model, 'preparation_id')->dropDownList(ArrayHelper::map(Preparation::getAllList(),
                                    "id", "name"), [
                                    'id' => 'select-preparation',
                                    'class' => 'form-control form-control-sm',
                                    'prompt' => 'Выберите препарат?'
                                ]) ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <?= $form->field($model, 'user_id')->dropDownList(ArrayHelper::map(User::getAllList(),
                                    "id", "lastName"), [
                                    'id' => 'select-user',
                                    'class' => 'form-control form-control-sm',
                                    'prompt' => 'Кто проводит?'
                                ]) ?>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <?= $form->field($model, 'count')
                                    ->input('number', ['class' => 'form-control form-control-sm', 'min' => 0]) ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <?= $form->field($model, 'date')->widget(DatePicker::class, [
                                    'language' => 'ru',
                                    'dateFormat' => 'dd.MM.yyyy',
                                    'options' => ['class' => 'form-control form-control-sm', 'autocomplete' => 'off']
                                ]) ?>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <?php if ($model->type == CashBook::TYPE_DEBIT) : ?>
                                <!--<div class="form-group">
                                    <div class="col-sm-12">
                                        <? /*= $form->field($model, 'total_price')->input('number', [
                                            'class' => 'form-control form-control-sm',
                                            'min'   => 0,
                                            'step'  => 0.01,
                                        ]) */ ?>
                                    </div>
                                </div>-->

                                <div class="form-group">
                                    <?= $form->field($model, 'vat_percent')->input('number', [
                                        'class' => 'form-control form-control-sm',
                                        'min' => 0,
                                        'max' => 100,
                                        'step' => 0.01,
                                    ]) ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <?= $form->field($model, 'stock_id')->dropDownList(ArrayHelper::map(Stock::getAllList(),
                                    "id", "name"), [
                                    'id' => 'select-stock',
                                    'class' => 'form-control form-control-sm',
                                    'prompt' => 'Выберите склад?'
                                ]) ?>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <?= $form->field($model, 'type')
                                ->hiddenInput(['value' => $model->type])
                                ->label(false)
                            ?>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <?php if (Yii::$app->user->can('managePharmacyEdit')) : ?>
                        <?= Html::submitButton('Добавить', ['class' => 'btn btn-sm btn-primary']) ?>
                    <?php endif; ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>