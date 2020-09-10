<?php

use \yii\bootstrap\ActiveForm;
use \yii\helpers\Html;
use \yii\helpers\Url;
use \backend\modules\scheme\models\Diagnosis;

/** @var Diagnosis $model */

$this->title = Yii::t('app/diagnosis', 'DIAGNOSIS_EDIT');
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="box box-info">

	<?php $form = ActiveForm::begin(['action' => Url::toRoute(['diagnosis/update', 'id' => $model->id]), 'id' => 'diagnosis-form', 'class' => 'form-horizontal']); ?>
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
        <?php if (Yii::$app->user->can('diagnosisEdit')) : ?>
            <?= Html::submitButton(Yii::t('app/diagnosis', 'EDIT'), ['class' => 'btn btn-info pull-right', 'name' => 'contact-button']) ?>
        <?php endif; ?>
    </div>
	<?php ActiveForm::end(); ?>

</div>
