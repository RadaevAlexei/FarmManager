<?php

use \yii\bootstrap4\ActiveForm;
use \yii\helpers\Html;
use \yii\helpers\Url;
use \common\models\Color;

/** @var Color $model */

$this->title = Yii::t('app/color', 'COLOR_EDIT');
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="box box-info">

    <?php $form = ActiveForm::begin(['action' => Url::toRoute(['color/update', 'id' => $model->id]), 'id' => 'contact-form', 'class' => 'form-horizontal']); ?>
    <div class="box-body">

        <div class="form-group">
            <div class="col-sm-12">
                <?= $form->field($model, 'name')->textInput([
                    'autofocus' => true,
                    'class' => 'form-control'
                ]) ?>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-12">
                <?= $form->field($model, 'short_name')->textInput([
                    'class' => 'form-control'
                ]) ?>
            </div>
        </div>
    </div>

    <div class="box-footer">
        <?php if (Yii::$app->user->can('animalColorEdit')) : ?>
            <?= Html::submitButton(Yii::t('app/color', 'EDIT'), ['class' => 'btn btn-info pull-right', 'name' => 'contact-button']) ?>
        <?php endif; ?>
    </div>
    <?php ActiveForm::end(); ?>

</div>
