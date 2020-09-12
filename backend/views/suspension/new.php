<?php

use \yii\bootstrap4\ActiveForm;
use \yii\helpers\Html;
use \yii\helpers\Url;
use \common\models\Suspension;
use \yii\jui\DatePicker;

/** @var Suspension $model */
/** @var array $animals */

$this->title = Yii::t('app/suspension', 'SUSPENSION_NEW');

?>

<div class="box box-info">

    <?php $form = ActiveForm::begin(['action' => Url::toRoute(['suspension/create']), 'id' => 'suspension-form', 'class' => 'form-horizontal']); ?>
        <div class="box-body">

            <div class="form-group">
                <div class="col-sm-12">
                    <?/*= echo $form->field($model, 'state_1')->widget(Select2::classname(), [
                        'data' => $data,
                        'language' => 'de',
                        'options' => ['placeholder' => 'Select a state ...'],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]);*/?>

                    <?= $form->field($model, 'animal')->dropDownList($animals, [
                        'class' => 'form-control'
                    ]) ?>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-12">
                    <?= $form->field($model, 'weight')->input('number', [
                        'class' => 'form-control'
                    ]) ?>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-12">
                    <?= $form->field($model, 'date')->widget(DatePicker::className(), [
                        'language'   => 'ru',
                        'dateFormat' => 'yyyy-MM-dd',
                    ]) ?>
                </div>
            </div>
        </div>

        <div class="box-footer">
            <?= Html::submitButton(Yii::t('app/suspension', 'ADD'), ['class' => 'btn btn-info pull-right', 'name' => 'contact-button']) ?>
        </div>
    <?php ActiveForm::end(); ?>

</div>