<?php

use \yii\bootstrap4\ActiveForm;
use \yii\helpers\Html;
use \yii\helpers\Url;
use backend\modules\scheme\models\Scheme;
use \backend\modules\scheme\models\Diagnosis;

/**
 * @var Scheme $model
 * @var Diagnosis[] $diagnosisList
 */

$this->title = Yii::t('app/scheme', 'SCHEME_NEW');

?>

<div class="box box-info">

	<?php $form = ActiveForm::begin(['action' => Url::toRoute(['scheme/create']), 'id' => 'scheme-form', 'class' => 'form-horizontal']); ?>
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
				<?= $form->field($model, 'diagnosis_id')->dropDownList(
					$diagnosisList,
					['class' => 'form-control', 'prompt' => 'Выберите диагноз'])
				?>
            </div>
        </div>

    </div>

    <div class="box-footer">
		<?= Html::submitButton(Yii::t('app/scheme', 'ADD'), ['class' => 'btn btn-info pull-right', 'name' => 'contact-button']) ?>
    </div>
	<?php ActiveForm::end(); ?>

</div>