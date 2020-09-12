<?php

use \yii\bootstrap4\ActiveForm;
use \yii\helpers\Html;
use \yii\helpers\Url;
use \backend\modules\scheme\models\Diagnosis;

/** @var Diagnosis $model */

$this->title = Yii::t('app/diagnosis', 'DIAGNOSIS_NEW');

?>

<div class="box box-info">

	<?php $form = ActiveForm::begin(['action' => Url::toRoute(['diagnosis/create']), 'id' => 'diagnosis-form', 'class' => 'form-horizontal']); ?>
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
		<?= Html::submitButton(Yii::t('app/diagnosis', 'ADD'), ['class' => 'btn btn-info pull-right', 'name' => 'contact-button']) ?>
    </div>
	<?php ActiveForm::end(); ?>

</div>