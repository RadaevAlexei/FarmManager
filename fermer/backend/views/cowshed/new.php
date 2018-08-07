<?php

use \yii\bootstrap\ActiveForm;
use \yii\helpers\Html;
use \yii\helpers\Url;
use \common\models\Cowshed;

/** @var Cowshed $model */

$this->title = Yii::t('app/cowshed', 'COWSHED_NEW');

?>

<div class="box box-info">

    <?php $form = ActiveForm::begin(['action' => Url::toRoute(['cowshed/create']), 'id' => 'cowshed-form', 'class' => 'form-horizontal']); ?>
        <div class="box-body">

            <div class="form-group">
                <div class="col-sm-12">
                    <?= $form->field($model, 'name')->textInput([
                        'autofocus' => true,
                        'class'     => 'form-control'
                    ]) ?>
                </div>
            </div>
        </div>

        <div class="box-footer">
            <?= Html::submitButton(Yii::t('app/cowshed', 'ADD'), ['class' => 'btn btn-info pull-right', 'name' => 'contact-button']) ?>
        </div>
    <?php ActiveForm::end(); ?>

</div>