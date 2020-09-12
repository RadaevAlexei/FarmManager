<?php

use \yii\bootstrap4\ActiveForm;
use \yii\helpers\Html;
use \yii\helpers\Url;
use \yii\jui\DatePicker;
use \common\models\Suspension;

/** @var Suspension $model */
/** @var array $animals */

$this->title = Yii::t('app/suspension', 'SUSPENSION_EDIT');
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="box box-info">

    <?php $form = ActiveForm::begin(['action' => Url::toRoute(['suspension/update', 'id' => $model->id]), 'id' => 'suspension-form', 'class' => 'form-horizontal']); ?>
    <div class="box-body">

        <div class="form-group">
            <div class="col-sm-12">
                <?= $form->field($model, 'animal')->dropDownList($animals, [
                    'class'    => 'form-control',
                    'disabled' => true,
                ]) ?>
            </div>
            <div class="col-sm-12">
                <?= $form->field($model, 'weight')->input('number', [
                    'class' => 'form-control'
                ]) ?>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-12">
                <?= $form->field($model, 'date')->widget(DatePicker::class, [
                    'language'   => 'ru',
                    'dateFormat' => 'yyyy-MM-dd',
                ]) ?>
            </div>
        </div>

    </div>

    <div class="box-footer">
        <?= Html::submitButton(Yii::t('app/suspension', 'EDIT'), ['class' => 'btn btn-info pull-right', 'name' => 'suspension-button']) ?>
    </div>
    <?php ActiveForm::end(); ?>

</div>