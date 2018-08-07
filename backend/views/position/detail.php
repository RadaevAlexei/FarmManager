<?php

use \yii\bootstrap\ActiveForm;
use \yii\helpers\Html;
use \yii\helpers\Url;
use \common\models\Position;

/** @var Position $model */

$this->title = Yii::t('app/position', 'POSITION_DETAIL');
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="box box-info">

    <?php $form = ActiveForm::begin(['id' => 'contact-form', 'class' => 'form-horizontal']); ?>
        <div class="box-body">
            <div class="form-group">
                <div class="col-sm-12">
                    <?= $form->field($model, 'name')->textInput([
                        'class'    => 'form-control',
                        'disabled' => true,
                    ]) ?>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-12">
                    <?= $form->field($model, 'short_name')->textInput([
                        'class'    => 'form-control',
                        'disabled' => true,
                    ]) ?>
                </div>
            </div>
        </div>
    <?php ActiveForm::end(); ?>

</div>