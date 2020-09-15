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
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Заполните форму для создания</h3>
                </div>

                <?php $form = ActiveForm::begin(['action' => Url::toRoute(['seed-cash-book/create'])]); ?>

                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <?= $form->field($model, 'type')->dropDownList(SeedCashBook::getTypeList(), [
                                    'id' => 'select-type',
                                    'class' => 'form-control form-control-sm',
                                    'disabled' => true,
                                    'prompt' => 'Выберите тип'
                                ]) ?>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <?= $form->field($model, 'container_duara_id')->dropDownList(ArrayHelper::map(ContainerDuara::getAllList(),
                                    "id", "name"), [
                                    'id' => 'select-container-duara',
                                    'class' => 'form-control form-control-sm',
                                    'prompt' => 'Выберите сосуд?'
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
                                    ->input('number', [
                                        'class' => 'form-control form-control-sm', 'min' => 1
                                    ])
                                ?>
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
                            <?php if ($model->type == SeedCashBook::TYPE_DEBIT) : ?>
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
                                <?= $form->field($model, 'seed_bull_id')->dropDownList(ArrayHelper::map(SeedBull::getAllList(),
                                    "id", "nickname"), [
                                    'id' => 'select-seed-bull',
                                    'class' => 'form-control form-control-sm',
                                    'prompt' => 'Выберите быка?'
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
                    <?php if (Yii::$app->user->can('seedCashBookEdit')) : ?>
                        <?= Html::submitButton('Добавить', ['class' => 'btn btn-sm btn-primary']) ?>
                    <?php endif; ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>