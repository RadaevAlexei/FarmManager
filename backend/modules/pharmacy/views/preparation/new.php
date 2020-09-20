<?php

use \yii\bootstrap4\ActiveForm;
use \yii\helpers\Html;
use \yii\helpers\Url;
use \backend\modules\pharmacy\models\Preparation;
use \backend\modules\pharmacy\assets\PreparationAsset;
use \common\models\Measure;

/**
 * @var Preparation $model
 * @var array $packingList
 */

PreparationAsset::register($this);

$this->title = Yii::t('app/preparation', 'PREPARATION_NEW');
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">Заполните форму для создания</h3>
                </div>

                <?php $form = ActiveForm::begin(['action' => Url::toRoute(['preparation/create'])]); ?>

                <div class="card-body">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card card-primary">
                                    <div class="card-header">
                                        <h3 class="card-title">Основная информация</h3>
                                    </div>

                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <?= $form->field($model, 'name')->textInput([
                                                        'autofocus' => true,
                                                        'class' => 'form-control form-control-sm'
                                                    ]) ?>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <?= $form->field($model, 'category')->dropDownList(Preparation::getCategoryList(), [
                                                        'id' => 'select-category',
                                                        'class' => 'form-control form-control-sm',
                                                        'prompt' => 'Укажите формакологическую группу',
                                                    ]) ?>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="form-group select-classification-block <?= ($model->category === 1 ? "" : "hidden") ?>">
                                                    <?= $form->field($model, 'classification')->dropDownList(Preparation::getClassificationList(), [
                                                        'id' => 'select-classification',
                                                        'class' => 'form-control form-control-sm',
                                                        'prompt' => 'Классификация антибиотиков',
                                                    ]) ?>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="form-group select-beta-block <?= ($model->classification === 1 ? "" : "hidden") ?>">
                                                    <?= $form->field($model, 'beta')->dropDownList(Preparation::getBetaClassificationList(), [
                                                        'id' => 'select-beta',
                                                        'class' => 'form-control form-control-sm',
                                                        'prompt' => 'Выберите подтип бета-лактамных',
                                                    ]) ?>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <?= $form->field($model, 'danger_class')->dropDownList(Preparation::getDangerClass(), [
                                                        'class' => 'form-control form-control-sm',
                                                        'prompt' => 'Укажите класс опасности',
                                                    ]) ?>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <?= $form->field($model, 'measure')->dropDownList(Measure::getList(), [
                                                        'id' => 'select-measure',
                                                        'class' => 'form-control form-control-sm',
                                                        'prompt' => 'Выберите единицу измерения'
                                                    ]) ?>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <?= $form->field($model, 'volume')->input('number', [
                                                        'class' => 'form-control form-control-sm',
                                                        'min' => 0,
                                                        'step' => 0.1,
                                                    ]) ?>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <?= $form->field($model, 'price')->input('number', [
                                                        'class' => 'form-control form-control-sm',
                                                        'min' => 0,
                                                        'step' => 0.01,
                                                    ]) ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card card-primary">
                                    <div class="card-header">
                                        <h3 class="card-title">Период выведения</h3>
                                    </div>

                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <?= $form->field($model, 'period_milk_day')->input('number', [
                                                        'id' => 'period_milk_day',
                                                        'step' => '0.1',
                                                        'class' => 'form-control form-control-sm',
                                                    ]) ?>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <?= $form->field($model, 'period_milk_hour')->input('number', [
                                                        'id' => 'period_milk_hour',
                                                        'step' => '0.1',
                                                        'class' => 'form-control form-control-sm'
                                                    ]) ?>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <?= $form->field($model, 'period_meat_day')->input('number', [
                                                        'id' => 'period_meat_day',
                                                        'step' => '0.1',
                                                        'class' => 'form-control form-control-sm',
                                                    ]) ?>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <?= $form->field($model, 'period_meat_hour')->input('number', [
                                                        'id' => 'period_meat_hour',
                                                        'step' => '0.1',
                                                        'class' => 'form-control form-control-sm'
                                                    ]) ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <?php if (Yii::$app->user->can('managePharmacyEdit')) : ?>
                        <?= Html::submitButton(Yii::t('app/preparation', 'ADD'), ['class' => 'btn btn-sm btn-primary']) ?>
                    <?php endif; ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>