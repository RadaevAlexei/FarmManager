<?php

use \yii\bootstrap\ActiveForm;
use \yii\helpers\Html;
use \yii\helpers\Url;
use \common\models\Position;

/** @var Position $model */

$this->title = Yii::t('app/position', 'POSITION_NEW');

?>

<div class="box box-info">

    <!--<div class="box-header with-border">
        <h3 class="box-title"><? /*= Yii::t('app/position', 'POSITION_NEW') */ ?></h3>
    </div>-->

    <?php $form = ActiveForm::begin(['action' => Url::toRoute(['position/create']), 'id' => 'position-form', 'class' => 'form-horizontal']); ?>
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
            <?= Html::submitButton(Yii::t('app/position', 'ADD'), ['class' => 'btn btn-info pull-right', 'name' => 'contact-button']) ?>
        </div>
    <?php ActiveForm::end(); ?>

</div>