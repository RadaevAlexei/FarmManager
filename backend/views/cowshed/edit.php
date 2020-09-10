<?php

use \yii\bootstrap\ActiveForm;
use \yii\helpers\Html;
use \yii\helpers\Url;
use \common\models\Cowshed;

/** @var Cowshed $model */

$this->title = Yii::t('app/cowshed', 'COWSHED_EDIT');
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="box box-info">

    <?php $form = ActiveForm::begin(['action' => Url::toRoute(['cowshed/update', 'id' => $model->id]), 'id' => 'cowshed-form', 'class' => 'form-horizontal']); ?>
    <div class="box-body">

        <div class="form-group">
            <div class="col-sm-12">
                <?= $form->field($model, 'name')->textInput([
                    'autofocus' => true,
                    'class' => 'form-control'
                ]) ?>
            </div>
        </div>
    </div>

    <div class="box-footer">
        <?php if (Yii::$app->user->can('cowshedEdit')) : ?>
            <?= Html::submitButton(Yii::t('app/cowshed', 'EDIT'), ['class' => 'btn btn-info pull-right', 'name' => 'contact-button']) ?>
        <?php endif; ?>
    </div>
    <?php ActiveForm::end(); ?>

</div>
