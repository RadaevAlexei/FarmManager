<?php

use \yii\bootstrap\ActiveForm;
use \yii\helpers\Html;
use \yii\helpers\Url;
use \common\models\Position;

/** @var Position $model */

$this->title = Yii::t('app/position', 'POSITION_EDIT');
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="box box-info">

    <?php $form = ActiveForm::begin(['action' => Url::toRoute(['position/update', 'id' => $model->id]), 'id' => 'contact-form', 'class' => 'form-horizontal']); ?>
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
        <?php if (Yii::$app->user->can('userPositionEdit')) : ?>
            <?= Html::submitButton(
                Yii::t('app/position', 'EDIT'),
                ['class' => 'btn btn-info pull-right', 'name' => 'contact-button']
            ) ?>
        <?php endif; ?>
    </div>
    <?php ActiveForm::end(); ?>

</div>
