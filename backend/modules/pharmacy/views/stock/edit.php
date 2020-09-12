<?php

use \yii\bootstrap4\ActiveForm;
use \yii\helpers\Html;
use \yii\helpers\Url;
use \backend\modules\pharmacy\models\Stock;

/**
 * @var Stock $model
 */

$this->title = 'Редактирование склада';
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="box box-info">

    <?php $form = ActiveForm::begin([
        'action' => Url::toRoute(['stock/update', 'id' => $model->id]),
        'id' => 'stock-form',
        'class' => 'form-horizontal'
    ]); ?>
    <div class="box-body">

        <!--Название склада-->
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
        <?php if (Yii::$app->user->can('managePharmacyEdit')) : ?>
            <?= Html::submitButton('Редактировать',
                ['class' => 'btn btn-info pull-right', 'name' => 'contact-button']) ?>
        <?php endif; ?>
    </div>
    <?php ActiveForm::end(); ?>

</div>
