<?php

use \yii\bootstrap\ActiveForm;
use \yii\helpers\Html;
use \yii\helpers\Url;
use \common\models\Color;

/** @var Color $model */

$this->title = Yii::t('app/color', 'COLOR_NEW');

?>

<div class="box box-info">

    <?php $form = ActiveForm::begin(['action' => Url::toRoute(['color/create']), 'id' => 'color-form', 'class' => 'form-horizontal']); ?>
        <div class="box-body">

            <div class="form-group">
                <div class="col-sm-12">
                    <?= $form->field($model, 'name')->textInput([
                        'autofocus' => true,
                        'class'     => 'form-control'
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
            <?= Html::submitButton(Yii::t('app/color', 'ADD'), ['class' => 'btn btn-info pull-right', 'name' => 'contact-button']) ?>
        </div>
    <?php ActiveForm::end(); ?>

</div>